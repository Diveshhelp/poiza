<?php

namespace App\Jobs;

use App\Models\DropboxToken;
use App\Services\DropboxTokenService;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Services\DropboxBackupService;
use Exception;
use Storage;

class DropboxBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3600; // 1 hour

    /**
     * The destination path in Dropbox
     *
     * @var string
     */
    protected $document_id;

    /**
     * User ID associated with the file
     *
     * @var int|null
     */
    protected $teamUUID;

    /**
     * Whether to sync recursively
     *
     * @var bool
     */
    protected $recursive;
    public $localDisk;

   
    public function __construct(string $teamUUID, int $document_id ) {
        $this->teamUUID = $teamUUID;
        $this->document_id = $document_id;
    }

    /**
     * Execute the job.
     *
     * @param DropboxBackupService $dropboxService
     * @return void
     */

    public function handle(DropboxBackupService $dropboxService)
    {

        
        $this->localDisk = Storage::disk('public');

        try {
     

            $localPath = 'documents/'.$this->teamUUID.'/'.$this->document_id;
            $files = $this->localDisk->files($localPath);
            $dropboxPath = '/backup/' . $localPath;

            
            Log::info("Starting Dropbox backup job");
                
            
            $start = microtime(true);
            
            // Use the existing DropboxBackupService to perform the sync
            $results = $dropboxService->syncDirectory($localPath, $dropboxPath, $this->recursive);
            
            $duration = round(microtime(true) - $start, 2);
            
            // Log results
            Log::info("Dropbox backup job completed", [
                'duration' => $duration,
                'total_files' => $results['total_files'],
                'synced_files' => $results['synced_files'],
                'failed_files' => $results['failed_files'],
                'skipped_files' => $results['skipped_files'],
                'total_size' => $results['total_size'],
                'synced_size' => $results['synced_size']
            ]);
            
            // If there were failures, log details
            if ($results['failed_files'] > 0) {
                foreach ($results['errors'] as $error) {
                    Log::error("Failed to sync file", [
                        'file' => $error['file'],
                        'error' => $error['error']
                    ]);
                }
                
                if ($results['failed_files'] > count($results['errors'])) {
                    Log::warning("Some file sync errors were not logged in detail");
                }
            }
            
        } catch (Exception $e) {
            Log::error("Dropbox backup job failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error("DropboxBackupJob failed", [
            'error' => $exception->getMessage()
        ]);
    }
}