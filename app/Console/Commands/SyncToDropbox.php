<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DropboxBackupService;
use Illuminate\Support\Facades\Log;

class SyncToDropbox extends Command
{
    protected $signature = 'backup:dropbox 
                            {path? : Local path to sync (default: documents)}
                            {--destination= : Destination path in Dropbox}
                            {--user-id= : Only sync files for a specific user ID}
                            {--no-recursive : Don\'t sync subdirectories}';

    protected $description = 'Sync files to Dropbox for backup';

    protected $dropboxService;

    public function __construct(DropboxBackupService $dropboxService)
    {
        parent::__construct();
        $this->dropboxService = $dropboxService;
    }

    
    public function handle()
    {
        // Get arguments
        $localPath = $this->argument('path') ?? 'documents';
        $dropboxPath = $this->option('destination') ?? '/backup/' . $localPath;
        $recursive = !$this->option('no-recursive');
        $userId = $this->option('user-id');
        
        // If user ID is specified, modify the path
        if ($userId) {
            $localPath = $localPath . '/user_' . $userId;
            $dropboxPath = $dropboxPath . '/user_' . $userId;
        }
        
        $this->info("Starting sync from {$localPath} to Dropbox:{$dropboxPath}");
        $start = microtime(true);
        
        try {
            // Run the sync
            $results = $this->dropboxService->syncDirectory($localPath, $dropboxPath, $recursive);
            
            // Output results
            $duration = round(microtime(true) - $start, 2);
            $this->info("Sync completed in {$duration} seconds.");
            $this->info("Total files: {$results['total_files']}");
            $this->info("Synced files: {$results['synced_files']}");
            $this->info("Skipped files: {$results['skipped_files']} (already up to date)");
            
            if ($results['failed_files'] > 0) {
                $this->error("Failed files: {$results['failed_files']}");
                
                // Show first 10 errors
                foreach (array_slice($results['errors'], 0, 10) as $index => $error) {
                    $this->error(" - {$error['file']}: {$error['error']}");
                }
                
                // If there are more errors, indicate that
                if (count($results['errors']) > 10) {
                    $remaining = count($results['errors']) - 10;
                    $this->error("... and {$remaining} more errors");
                }
            }
            
            // Show size information
            $totalSizeMB = round($results['total_size'] / 1024 / 1024, 2);
            $syncedSizeMB = round($results['synced_size'] / 1024 / 1024, 2);
            $this->info("Total size: {$totalSizeMB} MB");
            $this->info("Synced size: {$syncedSizeMB} MB");
            
            // Log the summary
            Log::info("Dropbox sync completed", [
                'local_path' => $localPath,
                'dropbox_path' => $dropboxPath,
                'duration' => $duration,
                'total_files' => $results['total_files'],
                'synced_files' => $results['synced_files'],
                'failed_files' => $results['failed_files'],
                'skipped_files' => $results['skipped_files'],
                'total_size' => $results['total_size'],
                'synced_size' => $results['synced_size']
            ]);
            
            return ($results['failed_files'] > 0) ? 1 : 0;
            
        } catch (\Exception $e) {
            $this->error("Error during sync: " . $e->getMessage());
            Log::error("Dropbox sync error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 1;
        }
    }
}