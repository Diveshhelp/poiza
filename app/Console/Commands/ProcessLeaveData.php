<?php

namespace App\Console\Commands;

use App\Models\Leave;
use App\Models\User;
use DB;
use Illuminate\Console\Command;
use Log;
use Carbon\Carbon;
use Str;

class ProcessLeaveData extends Command
{
    protected $signature = 'process-leave:job {--chunk=1000 : Number of records to process at once}';
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

            DB::table('raj_main_leaves')->orderBy('id')->chunk($chunkSize, function ($oldLeaves) use (&$totalProcessed, &$batchData, $userMapping) {
                
                foreach ($oldLeaves as $oldLeave) {
                    // Skip if user mapping not found
                    if (!isset($userMapping[$oldLeave->employee_id])) {
                        Log::warning("User mapping not found for employee_id: {$oldLeave->employee_id}");
                        continue;
                    }

                    // Calculate total days
                    $startDate = Carbon::parse($oldLeave->start_date);
                    $endDate = Carbon::parse($oldLeave->end_date);
                    $totalDays = $startDate->diffInDays($endDate) + 1;

                    // Prepare data for batch insert
                    $batchData[] = [
                        'uuid' => (string) Str::uuid(),
                        'user_id' => $userMapping[$oldLeave->employee_id],
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                        'total_days' => $totalDays,
                        'is_full_day' => $oldLeave->half_day === 'yes' ? 'no' : 'yes', // Reverse logic
                        'leave_half' => $oldLeave->half_day === 'yes' ? 'first_half' : null,
                        'reason' => $this->combineReasonDescription($oldLeave),
                        'status' => $this->mapStatus($oldLeave->status),
                        'team_id' => 32,
                        'created_at' => $oldLeave->created_at,
                        'updated_at' => $oldLeave->updated_at,
                        'deleted_at' => $oldLeave->deleted_at,
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
            DB::table('leaves')->insert($data);
        } catch (\Exception $e) {
            Log::error("Batch insert failed", [
                'error' => $e->getMessage(),
                'data_count' => count($data)
            ]);
            throw $e;
        }
    }

    /**
     * Combine reason and description
     */
    private function combineReasonDescription($oldLeave)
    {
        $reason = $oldLeave->reason ?? '';
        $description = $oldLeave->description ?? '';
        
        if (empty($description) || $description === $reason) {
            return $reason;
        }
        
        return $reason . ' - ' . $description;
    }

    /**
     * Map old status to new status
     */
    private function mapStatus($oldStatus)
    {
        $statusMap = [
            'approved' => 'approved',
            'rejected' => 'rejected',
            'pending' => 'pending',
        ];

        return $statusMap[$oldStatus] ?? 'pending';
    }
}

// ==========================================
// ALTERNATIVE ULTRA-FAST VERSION (Raw SQL)
// ==========================================

/*
class ProcessDataUltraFast extends Command
{
    protected $signature = 'process:job-ultra-fast';
    protected $description = 'Ultra-fast migration using raw SQL';

    public function handle()
    {
        try {
            $this->info('Starting ultra-fast migration...');
            
            // Single SQL query to migrate all data at once
            $sql = "
                INSERT INTO leaves (
                    uuid, user_id, start_date, end_date, total_days, 
                    is_full_day, leave_half, reason, status, team_id,
                    created_at, updated_at, deleted_at
                )
                SELECT 
                    UUID() as uuid,
                    u.id as user_id,
                    DATE(ol.start_date) as start_date,
                    DATE(ol.end_date) as end_date,
                    GREATEST(1, DATEDIFF(DATE(ol.end_date), DATE(ol.start_date)) + 1) as total_days,
                    CASE WHEN ol.half_day = 'yes' THEN 'no' ELSE 'yes' END as is_full_day,
                    CASE WHEN ol.half_day = 'yes' THEN 'first_half' ELSE NULL END as leave_half,
                    CONCAT(
                        ol.reason, 
                        CASE 
                            WHEN ol.description IS NOT NULL 
                                AND ol.description != '' 
                                AND ol.description != ol.reason 
                            THEN CONCAT(' - ', ol.description)
                            ELSE ''
                        END
                    ) as reason,
                    CASE 
                        WHEN ol.status = 'approved' THEN 'approved'
                        WHEN ol.status = 'rejected' THEN 'rejected'
                        WHEN ol.status = 'pending' THEN 'pending'
                        ELSE 'pending'
                    END as status,
                    32 as team_id,
                    ol.created_at,
                    ol.updated_at,
                    ol.deleted_at
                FROM raj_main_leaves ol
                INNER JOIN raj_main_users ou ON ol.employee_id = ou.id
                INNER JOIN users u ON ou.email = u.email
            ";

            $affected = DB::statement($sql);
            
            $this->info('Ultra-fast migration completed!');
            $this->info("Records migrated: " . DB::table('leaves')->count());
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Ultra-fast migration failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
*/