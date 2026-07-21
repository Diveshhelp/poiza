<?php

namespace App\Console\Commands;

use App\Models\User;
use DB;
use Illuminate\Console\Command;
use Log;
use Carbon\Carbon;

class ProcessLearningUserData extends Command
{
    protected $signature = 'process-learning-user:job {--chunk=1000 : Number of records to process at once} {--dry-run : Run without actually inserting data}';
    protected $description = 'Migrate learning user data from old table to new table (Optimized with time tracking)';

    private $startTime;
    private $stats = [
        'total_processed' => 0,
        'successful_inserts' => 0,
        'skipped_records' => 0,
        'errors' => 0,
    ];

    public function handle()
    {
        $this->startTime = microtime(true);
        $chunkSize = $this->option('chunk');
        $isDryRun = $this->option('dry-run');
        
        $this->displayHeader($chunkSize, $isDryRun);

        try {
            if (!$isDryRun) {
                DB::beginTransaction();
            }

            // Step 1: Get total count for progress tracking
            $totalRecords = DB::table('raj_main_learning_user')->count();
            $this->info("Total records to process: {$totalRecords}");

            // Step 2: Pre-load user mappings
            $userMapping = $this->loadUserMappings();

            // Step 3: Process data with progress tracking
            $this->processLearningUserData($chunkSize, $userMapping, $totalRecords, $isDryRun);

            if (!$isDryRun) {
                DB::commit();
                $this->info("✅ Transaction committed successfully!");
            }

            $this->displayResults();
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            if (!$isDryRun) {
                DB::rollBack();
                $this->error("❌ Transaction rolled back due to error");
            }
            
            $this->error("Migration failed: " . $e->getMessage());
            Log::error("Learning user migration failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'stats' => $this->stats
            ]);
            
            $this->displayResults();
            return Command::FAILURE;
        }
    }

    /**
     * Display command header with configuration
     */
    private function displayHeader($chunkSize, $isDryRun)
    {
        $this->info("🚀 Starting Learning User Data Migration");
        $this->info("⚙️  Configuration:");
        $this->table(
            ['Setting', 'Value'],
            [
                ['Chunk Size', $chunkSize],
                ['Dry Run', $isDryRun ? 'Yes' : 'No'],
                ['Memory Limit', ini_get('memory_limit')],
                ['Start Time', Carbon::now()->format('Y-m-d H:i:s')]
            ]
        );
    }

    /**
     * Load and optimize user mappings
     */
    private function loadUserMappings(): array
    {
        $this->info('📋 Loading user mappings...');
        $mappingStart = microtime(true);

        $oldUsers = DB::table('raj_main_users')->pluck('email', 'id')->toArray();
        $newUsers = User::pluck('id', 'email')->toArray();
        
        // Create optimized mapping array
        $userMapping = [];
        $missingEmails = [];
        
        foreach ($oldUsers as $oldId => $email) {
            if (isset($newUsers[$email])) {
                $userMapping[$oldId] = $newUsers[$email];
            } else {
                $missingEmails[] = $email;
            }
        }

        $mappingTime = round(microtime(true) - $mappingStart, 2);
        
        $this->info("✅ User mappings loaded in {$mappingTime}s");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Old Users Found', count($oldUsers)],
                ['New Users Found', count($newUsers)],
                ['Successful Mappings', count($userMapping)],
                ['Missing Mappings', count($missingEmails)]
            ]
        );

        if (!empty($missingEmails)) {
            $this->warn("⚠️  Missing email mappings for: " . implode(', ', array_slice($missingEmails, 0, 5)) . 
                       (count($missingEmails) > 5 ? " and " . (count($missingEmails) - 5) . " more..." : ""));
        }

        return $userMapping;
    }

    /**
     * Process learning user data in chunks
     */
    private function processLearningUserData($chunkSize, $userMapping, $totalRecords, $isDryRun)
    {
        $this->info('📊 Processing learning user data...');
        
        $progressBar = $this->output->createProgressBar($totalRecords);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% - %elapsed:6s%/%estimated:-6s% - %memory:6s%');
        
        $batchData = [];
        $processedInCurrentChunk = 0;

        DB::table('raj_main_learning_user')
            ->orderBy('id')
            ->chunk($chunkSize, function ($oldRecords) use (
                &$batchData, 
                $userMapping, 
                $progressBar, 
                &$processedInCurrentChunk,
                $isDryRun
            ) {
                foreach ($oldRecords as $oldRecord) {
                    $processedInCurrentChunk++;
                    $progressBar->advance();

                    // Validate user mappings
                    $validationResult = $this->validateUserMappings($oldRecord, $userMapping);
                    
                    if (!$validationResult['valid']) {
                        $this->stats['skipped_records']++;
                        Log::warning("Skipped record ID {$oldRecord->id}: " . $validationResult['reason']);
                        continue;
                    }

                    // Prepare data for batch insert
                    $batchData[] = [
                        'learning_id' => $oldRecord->learning_id,
                        'user_id' => $userMapping[$oldRecord->user_id],
                        'admin_id' => $userMapping[$oldRecord->admin_id] ?? null,
                        'comment' => $oldRecord->status ?? null,
                        'created_by' => $userMapping[$oldRecord->created_by] ?? null,
                        'created_at' => $oldRecord->created_at,
                        'updated_at' => $oldRecord->updated_at,
                        'deleted_at' => $oldRecord->deleted_at,
                    ];

                    // Batch insert when we have enough records
                    if (count($batchData) >= 500) {
                        if (!$isDryRun) {
                            $this->insertBatch($batchData);
                        }
                        $this->stats['successful_inserts'] += count($batchData);
                        $this->stats['total_processed'] += count($batchData);
                        $batchData = []; // Reset batch
                    }
                }
            });

        // Insert remaining records
        if (!empty($batchData)) {
            if (!$isDryRun) {
                $this->insertBatch($batchData);
            }
            $this->stats['successful_inserts'] += count($batchData);
            $this->stats['total_processed'] += count($batchData);
        }

        $progressBar->finish();
        $this->newLine(2);
    }

    /**
     * Validate user mappings for a record
     */
    private function validateUserMappings($record, $userMapping): array
    {
        $required = ['user_id'];
        $optional = ['admin_id', 'created_by'];

        // Check required mappings
        foreach ($required as $field) {
            if (empty($record->$field)) {
                return [
                    'valid' => false,
                    'reason' => "Missing required field: {$field}"
                ];
            }
            
            if (!isset($userMapping[$record->$field])) {
                return [
                    'valid' => false,
                    'reason' => "User mapping not found for {$field}: {$record->$field}"
                ];
            }
        }

        // Log warnings for missing optional mappings
        foreach ($optional as $field) {
            if (!empty($record->$field) && !isset($userMapping[$record->$field])) {
                Log::warning("Optional user mapping not found for {$field}: {$record->$field} (Record ID: {$record->id})");
            }
        }

        return ['valid' => true, 'reason' => null];
    }

    /**
     * Insert batch data with error handling
     */
    private function insertBatch(array $data)
    {
        try {
            $insertStart = microtime(true);
            DB::table('learning_user')->insert($data);
            $insertTime = round((microtime(true) - $insertStart) * 1000, 2);
            
            // Log slow inserts
            if ($insertTime > 1000) { // More than 1 second
                Log::warning("Slow batch insert detected", [
                    'records' => count($data),
                    'time_ms' => $insertTime
                ]);
            }
            
        } catch (\Exception $e) {
            $this->stats['errors']++;
            Log::error("Batch insert failed", [
                'error' => $e->getMessage(),
                'data_count' => count($data),
                'sample_data' => array_slice($data, 0, 2) // Log first 2 records for debugging
            ]);
            throw $e;
        }
    }

    /**
     * Display final results and statistics
     */
    private function displayResults()
    {
        $totalTime = round(microtime(true) - $this->startTime, 2);
        $recordsPerSecond = $this->stats['total_processed'] > 0 ? 
            round($this->stats['total_processed'] / $totalTime, 2) : 0;
        
        $this->newLine();
        $this->info("🎉 Migration Process Completed!");
        
        // Performance Statistics
        $this->table(
            ['Metric', 'Value'],
            [
                ['⏱️  Total Time', $totalTime . 's'],
                ['📊 Records Processed', number_format($this->stats['total_processed'])],
                ['✅ Successful Inserts', number_format($this->stats['successful_inserts'])],
                ['⏭️  Skipped Records', number_format($this->stats['skipped_records'])],
                ['❌ Errors', number_format($this->stats['errors'])],
                ['🚀 Records/Second', number_format($recordsPerSecond)],
                ['💾 Peak Memory Usage', $this->formatBytes(memory_get_peak_usage(true))],
                ['🏁 End Time', Carbon::now()->format('Y-m-d H:i:s')]
            ]
        );

        // Performance Rating
        $this->displayPerformanceRating($recordsPerSecond);
        
        // Log final statistics
        Log::info("Learning user migration completed", [
            'total_time_seconds' => $totalTime,
            'records_per_second' => $recordsPerSecond,
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'stats' => $this->stats
        ]);
    }

    /**
     * Display performance rating based on records per second
     */
    private function displayPerformanceRating($recordsPerSecond)
    {
        if ($recordsPerSecond >= 1000) {
            $this->info("🏆 Performance Rating: EXCELLENT (Very Fast)");
        } elseif ($recordsPerSecond >= 500) {
            $this->info("🥇 Performance Rating: GOOD (Fast)");
        } elseif ($recordsPerSecond >= 100) {
            $this->info("🥈 Performance Rating: AVERAGE (Moderate)");
        } else {
            $this->warn("🥉 Performance Rating: SLOW (Consider optimization)");
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

// ==========================================
// USAGE EXAMPLES
// ==========================================

/*
// Basic usage
php artisan process-learning-user:job

// With custom chunk size
php artisan process-learning-user:job --chunk=2000

// Dry run to test without inserting
php artisan process-learning-user:job --dry-run

// Large dataset optimization
php artisan process-learning-user:job --chunk=5000
*/