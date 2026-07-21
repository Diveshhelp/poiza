<?php

namespace App\Console\Commands;

use App\Models\User;
use DB;
use Illuminate\Console\Command;
use Log;
use Carbon\Carbon;
use Str;
use Illuminate\Support\Facades\Schema;

class ProcessTicketData extends Command
{
    protected $signature = 'process-ticket:job {--chunk=500 : Number of records to process at once} {--batch=200 : Batch insert size} {--dry-run : Run without inserting data}';
    protected $description = 'Migrate ticket data from old table to new table (MySQL Connection Safe)';

    private $startTime;
    private $stats = [
        'total_processed' => 0,
        'successful_inserts' => 0,
        'skipped_records' => 0,
        'errors' => 0,
        'connection_resets' => 0,
    ];

    public function handle()
    {
        $this->startTime = microtime(true);
        $chunkSize = $this->option('chunk');
        $batchSize = $this->option('batch');
        $isDryRun = $this->option('dry-run');
        
        $this->displayHeader($chunkSize, $batchSize, $isDryRun);

        try {
            // Set MySQL connection parameters for long-running operations
            $this->configureMySQLConnection();

            // Get total count for progress tracking
            $totalRecords = DB::table('raj_main_ticket')->count();
            $this->info("📊 Total records to process: " . number_format($totalRecords));

            // Pre-load user mappings
            $userMapping = $this->loadUserMappings();

            // Process data with connection management
            $this->processTicketData($chunkSize, $batchSize, $userMapping, $totalRecords, $isDryRun);

            $this->displayResults();
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Migration failed: " . $e->getMessage());
            Log::error("Ticket migration failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'stats' => $this->stats
            ]);
            
            $this->displayResults();
            return Command::FAILURE;
        }
    }

    /**
     * Configure MySQL connection for long-running operations
     */
    private function configureMySQLConnection()
    {
        try {
            // Set MySQL timeout and packet size configurations
            DB::statement("SET SESSION wait_timeout = 3600");
            DB::statement("SET SESSION interactive_timeout = 3600"); 
            DB::statement("SET SESSION max_allowed_packet = 1073741824"); // 1GB
            DB::statement("SET SESSION innodb_lock_wait_timeout = 300");
            
            $this->info("✅ MySQL connection configured for long operations");
        } catch (\Exception $e) {
            $this->warn("⚠️  Could not configure MySQL settings: " . $e->getMessage());
        }
    }

    /**
     * Check and refresh database connection if needed
     */
    private function ensureConnection()
    {
        try {
            // Test connection with a simple query
            DB::select('SELECT 1');
        } catch (\Exception $e) {
            $this->warn("🔄 Connection lost, reconnecting...");
            $this->stats['connection_resets']++;
            
            // Reconnect
            DB::reconnect();
            $this->configureMySQLConnection();
            
            $this->info("✅ Database connection restored");
        }
    }

    /**
     * Display command header
     */
    private function displayHeader($chunkSize, $batchSize, $isDryRun)
    {
        $this->info("🎫 Starting Ticket Data Migration");
        $this->table(
            ['Setting', 'Value'],
            [
                ['Chunk Size', number_format($chunkSize)],
                ['Batch Insert Size', number_format($batchSize)],
                ['Dry Run', $isDryRun ? 'Yes' : 'No'],
                ['Memory Limit', ini_get('memory_limit')],
                ['Start Time', Carbon::now()->format('Y-m-d H:i:s')]
            ]
        );
    }

    /**
     * Load user mappings with error handling
     */
    private function loadUserMappings(): array
    {
        $this->info('📋 Loading user mappings...');
        $mappingStart = microtime(true);

        try {
            $this->ensureConnection();
            
            $oldUsers = DB::table('raj_main_users')->pluck('email', 'id')->toArray();
            $newUsers = User::pluck('id', 'email')->toArray();
            
            $userMapping = [];
            $missingCount = 0;
            
            foreach ($oldUsers as $oldId => $email) {
                if (isset($newUsers[$email])) {
                    $userMapping[$oldId] = $newUsers[$email];
                } else {
                    $missingCount++;
                }
            }

            $mappingTime = round(microtime(true) - $mappingStart, 2);
            
            $this->table(
                ['Metric', 'Count'],
                [
                    ['Old Users', number_format(count($oldUsers))],
                    ['New Users', number_format(count($newUsers))],
                    ['Successful Mappings', number_format(count($userMapping))],
                    ['Missing Mappings', number_format($missingCount)],
                    ['Load Time', $mappingTime . 's']
                ]
            );

            return $userMapping;

        } catch (\Exception $e) {
            $this->error("Failed to load user mappings: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process ticket data with connection management
     */
    private function processTicketData($chunkSize, $batchSize, $userMapping, $totalRecords, $isDryRun)
    {
        $this->info('🎫 Processing ticket data...');
        
        $progressBar = $this->output->createProgressBar($totalRecords);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% - %elapsed:6s%/%estimated:-6s% - %memory:6s%');
        
        $batchData = [];
        $processedCount = 0;
        $lastConnectionCheck = time();

        try {
            DB::table('raj_main_ticket')
                ->orderBy('id')
                ->chunk($chunkSize, function ($oldTickets) use (
                    &$batchData, 
                    $userMapping, 
                    $progressBar, 
                    &$processedCount,
                    $batchSize,
                    $isDryRun,
                    &$lastConnectionCheck
                ) {
                    // Check connection every 5 minutes
                    if (time() - $lastConnectionCheck > 300) {
                        $this->ensureConnection();
                        $lastConnectionCheck = time();
                    }

                    foreach ($oldTickets as $oldTicket) {
                        $processedCount++;
                        $progressBar->advance();

                        // Validate and prepare record
                        $ticketData = $this->prepareTicketData($oldTicket, $userMapping);
                        
                        if ($ticketData) {
                            $batchData[] = $ticketData;
                        } else {
                            $this->stats['skipped_records']++;
                        }

                        // Insert when batch is full
                        if (count($batchData) >= $batchSize) {
                            if (!$isDryRun) {
                                $this->insertBatchSafely($batchData);
                            }
                            
                            $this->stats['successful_inserts'] += count($batchData);
                            $this->stats['total_processed'] += count($batchData);
                            $batchData = [];
                        }
                    }
                });

            // Insert remaining records
            if (!empty($batchData)) {
                if (!$isDryRun) {
                    $this->insertBatchSafely($batchData);
                }
                
                $this->stats['successful_inserts'] += count($batchData);
                $this->stats['total_processed'] += count($batchData);
            }

            $progressBar->finish();
            $this->newLine(2);

        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine();
            throw $e;
        }
    }

    /**
     * Prepare ticket data with validation
     */
    private function prepareTicketData($oldTicket, $userMapping): ?array
    {
        try {
            // Helper function to safely map user IDs
            $mapUser = function($userId) use ($userMapping) {
                return ($userId && isset($userMapping[$userId])) ? $userMapping[$userId] : null;
            };

            return [
                'uuid' => (string) Str::uuid(),
                'establish_name' => $oldTicket->establish_name ?? null,
                'nature_of_work_id' => $oldTicket->nature_of_work_id ?? null,
                'branch_id' => $oldTicket->branch_id ?? null,
                'ticket_unique_no' => $oldTicket->ticket_unique_no ?? null,
                'generated_by' => $mapUser($oldTicket->generated_by),
                'approve_by' => $mapUser($oldTicket->approve_by),
                'ticket_approve_date' => $oldTicket->ticket_approve_date,
                'work_alloted_to' => $mapUser($oldTicket->work_alloted_to),
                'ticket_close_by' => $mapUser($oldTicket->ticket_close_by),
                'tocket_close_approve_by' => $mapUser($oldTicket->tocket_close_approve_by),
                'ticket_close_approve_date' => $oldTicket->ticket_close_approve_date,
                'ticket_transfered_to' => $mapUser($oldTicket->ticket_transfered_to),
                'ticket_transfered_date' => $oldTicket->ticket_transfered_date,
                'status' => $oldTicket->status ?? 'pending',
                'json_data' => $oldTicket->json_data,
                'created_at' => $oldTicket->created_at,
                'updated_at' => $oldTicket->updated_at,
                'deleted_at' => $oldTicket->deleted_at,
                'team_id' => 32,
                'user_id' => $mapUser($oldTicket->work_alloted_to),
            ];

        } catch (\Exception $e) {
            Log::warning("Failed to prepare ticket data for ID: {$oldTicket->id}", [
                'error' => $e->getMessage(),
                'ticket_id' => $oldTicket->id ?? 'unknown'
            ]);
            return null;
        }
    }

    /**
     * Insert batch with connection safety and retry logic
     */
    private function insertBatchSafely(array $data, $maxRetries = 3)
    {
        $attempt = 0;
        
        while ($attempt < $maxRetries) {
            try {
                $this->ensureConnection();
                
                // Use transaction for each batch to avoid long-running transactions
                DB::transaction(function() use ($data) {
                    DB::table('ticket')->insert($data);
                }, 3); // 3 deadlock retries
                
                return; // Success, exit retry loop
                
            } catch (\Exception $e) {
                $attempt++;
                $this->stats['errors']++;
                
                if ($attempt >= $maxRetries) {
                    Log::error("Batch insert failed after {$maxRetries} attempts", [
                        'error' => $e->getMessage(),
                        'data_count' => count($data),
                        'attempt' => $attempt
                    ]);
                    throw $e;
                }
                
                $this->warn("⚠️  Batch insert failed (attempt {$attempt}/{$maxRetries}), retrying...");
                
                // Progressive backoff: 1s, 2s, 3s
                sleep($attempt);
                
                // Ensure fresh connection for retry
                DB::reconnect();
                $this->configureMySQLConnection();
                $this->stats['connection_resets']++;
            }
        }
    }

    /**
     * Display final results
     */
    private function displayResults()
    {
        $totalTime = round(microtime(true) - $this->startTime, 2);
        $recordsPerSecond = $this->stats['total_processed'] > 0 ? 
            round($this->stats['total_processed'] / $totalTime, 2) : 0;
        
        $this->newLine();
        $this->info("🎉 Ticket Migration Completed!");
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['⏱️  Total Time', $totalTime . 's'],
                ['📊 Records Processed', number_format($this->stats['total_processed'])],
                ['✅ Successful Inserts', number_format($this->stats['successful_inserts'])],
                ['⏭️  Skipped Records', number_format($this->stats['skipped_records'])],
                ['❌ Errors', number_format($this->stats['errors'])],
                ['🔄 Connection Resets', number_format($this->stats['connection_resets'])],
                ['🚀 Records/Second', number_format($recordsPerSecond)],
                ['💾 Peak Memory', $this->formatBytes(memory_get_peak_usage(true))],
                ['🏁 End Time', Carbon::now()->format('Y-m-d H:i:s')]
            ]
        );

        // Performance rating
        if ($recordsPerSecond >= 500) {
            $this->info("🏆 Performance: EXCELLENT");
        } elseif ($recordsPerSecond >= 200) {
            $this->info("🥇 Performance: GOOD");
        } elseif ($recordsPerSecond >= 50) {
            $this->info("🥈 Performance: AVERAGE");
        } else {
            $this->warn("🥉 Performance: SLOW");
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
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
// Basic usage (safe defaults)
php artisan process-ticket:job

// Optimized for large datasets
php artisan process-ticket:job --chunk=1000 --batch=100

// Conservative approach for unstable connections
php artisan process-ticket:job --chunk=200 --batch=50

// Test run
php artisan process-ticket:job --dry-run

// Memory-efficient for very large datasets
php artisan process-ticket:job --chunk=100 --batch=25
*/