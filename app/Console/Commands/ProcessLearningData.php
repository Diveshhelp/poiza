<?php

namespace App\Console\Commands;

use App\Models\Leave;
use App\Models\User;
use DB;
use Illuminate\Console\Command;
use Log;
use Carbon\Carbon;
use Str;

class ProcessLearningData extends Command
{
    protected $signature = 'process-learning:job {--chunk=1000 : Number of records to process at once}';
    protected $description = 'Migrate leave data from old table to new table (Optimized)';

    public function handle()
    {
        $chunkSize = $this->option('chunk');
        $this->info("Starting leave data migration with chunk size: {$chunkSize}");

        try {
            DB::beginTransaction();

            // Step 1: Pre-load user email mappings to avoid repeated queries
            $this->info('Loading user mappings...');
            $oldUsers = DB::table('raj_main_users')->pluck('email', 'id')->toArray();
            $newUsers = User::pluck('id', 'email')->toArray();
            
            // Create mapping array for faster lookups
            $userMapping = [];
            foreach ($oldUsers as $oldId => $email) {
                if (isset($newUsers[$email])) {
                    $userMapping[$oldId] = $newUsers[$email];
                }
            }

            $this->info('User mappings loaded: ' . count($userMapping) . ' users found');

            // Step 2: Process data in chunks for memory efficiency
            $totalProcessed = 0;
            $batchData = [];

            DB::table('raj_main_learning')->orderBy('id')->chunk($chunkSize, function ($oldLeaves) use (&$totalProcessed, &$batchData, $userMapping) {
                
                foreach ($oldLeaves as $oldLeave) {
                    
                    // Prepare data for batch insert
                    $batchData[] = [
                        'uuid'=> (string) Str::uuid(),
                        'learning_title' => $oldLeave->raj_main_learning_title,
                        'learning_description' => $oldLeave->raj_main_learning_description,
                        'status' => $oldLeave->status,
                        'sort_order' =>$oldLeave->sort_order,
                        'created_at' => $oldLeave->created_at,
                        'updated_at' => $oldLeave->updated_at,
                        'deleted_at' => $oldLeave->deleted_at,
                        'team_id'=>32
                    ];
                }

                // Batch insert when we have enough records or this is the last chunk
                if (count($batchData) >= 500) { // Insert in batches of 500
                    $this->insertBatch($batchData);
                    $totalProcessed += count($batchData);
                    $this->info("Processed: {$totalProcessed} records");
                    $batchData = []; // Reset batch
                }
            });

            // Insert remaining records
            if (!empty($batchData)) {
                $this->insertBatch($batchData);
                $totalProcessed += count($batchData);
            }

            DB::commit();
            
            $this->info("Migration completed successfully!");
            $this->info("Total records processed: {$totalProcessed}");
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Migration failed: " . $e->getMessage());
            Log::error("Leave migration failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }

    /**
     * Insert batch data into leaves table
     */
    private function insertBatch(array $data)
    {
        try {
            DB::table('learning')->insert($data);
        } catch (\Exception $e) {
            Log::error("Batch insert failed", [
                'error' => $e->getMessage(),
                'data_count' => count($data)
            ]);
            throw $e;
        }
    }
}