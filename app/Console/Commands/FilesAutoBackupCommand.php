<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use ZipArchive;

class FilesAutoBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:backup-public {--upload : Upload backup to Google Drive} 
                                               {--keep=7 : Number of days to keep local backups}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a zip backup of the public folder and optionally upload to Google Drive';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting public folder backup process...');
        
        try {
            // Create backup
            $backupPath = $this->createPublicFolderBackup();
            
            if (!$backupPath) {
                $this->error('Public folder backup failed!');
                return;
            }
            
            $this->info("Public folder backup created successfully: " . basename($backupPath));
            
            // Upload to Google Drive if option is set
            if ($this->option('upload')) {
                $this->info('Uploading backup to Google Drive...');
                $uploaded = $this->uploadToGoogleDrive($backupPath, basename($backupPath));
                
                if ($uploaded) {
                    $this->info('Backup uploaded to Google Drive successfully.');
                } else {
                    $this->error('Failed to upload backup to Google Drive.');
                }
            }
            
            // Clean old backups
            $this->cleanOldBackups();
            
            $this->info('Public folder backup process completed successfully.');
        } catch (\Exception $e) {
            $this->error("Public folder backup failed: " . $e->getMessage());
            Log::error("Public folder backup exception: " . $e->getMessage());
        }
    }

    /**
     * Create a zip backup of the public folder
     * 
     * @return string|bool The path to the backup file or false on failure
     */
    protected function createPublicFolderBackup()
    {
        try {
            // Create backups directory if it doesn't exist
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            // Set filename with timestamp
            $filename = 'public-backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.zip';
            $filePath = $backupPath . '/' . $filename;
            
            // Source directory to backup
            $sourceDir = storage_path('app/public');
            if (!File::exists($sourceDir)) {
                throw new \Exception("Source directory does not exist: {$sourceDir}");
            }
            
            // Create zip archive
            $zip = new ZipArchive();
            if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \Exception("Cannot create zip file: {$filePath}");
            }
            
            // Helper function to add files and subdirectories recursively
            $addFilesToZip = function ($dir, $zipBasePath = '') use (&$addFilesToZip, $zip, $sourceDir) {
                $files = File::files($dir);
                $directories = File::directories($dir);
                
                // Add files
                foreach ($files as $file) {
                    $relativePath = $zipBasePath . basename($file);
                    $zip->addFile($file, $relativePath);
                }
                
                // Add subdirectories recursively
                foreach ($directories as $directory) {
                    $dirName = basename($directory);
                    $relativePath = $zipBasePath . $dirName . '/';
                    $zip->addEmptyDir($relativePath);
                    $addFilesToZip($directory, $relativePath);
                }
            };
            
            // Add all files from the source directory
            $addFilesToZip($sourceDir);
            
            // Close the zip file
            $zip->close();
            
            Log::info("Public folder backup created successfully: {$filePath}");
            return $filePath;
        } catch (\Exception $e) {
            Log::error("Failed to create public folder backup: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload the backup file to Google Drive
     * 
     * @param string $filePath Path to the backup file
     * @param string $fileName Name of the file to use in Google Drive
     * @return bool Success or failure
     */
    protected function uploadToGoogleDrive($filePath, $fileName)
    {
        try {
            // Check if credentials file exists
            $credentialsPath = storage_path('app/google-service-account.json');
            if (!File::exists($credentialsPath)) {
                throw new \Exception("Google Drive credentials file not found at: {$credentialsPath}");
            }
            
            // Get folder ID from config or environment
            $folderId = env('GOOGLE_DRIVE_BACKUP_FILES_FOLDER_ID');
            if (empty($folderId)) {
                throw new \Exception("Google Drive folder ID not configured. Set GOOGLE_DRIVE_BACKUP_FOLDER_ID in .env");
            }
            
            // Initialize the Google Client
            $client = new Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Drive::DRIVE);
            
            // Create Google Drive service
            $service = new Drive($client);
            
            // File metadata
            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [$folderId]
            ]);
            
            // Upload file
            $content = file_get_contents($filePath);
            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => 'application/zip',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);
            
            Log::info("Public folder backup uploaded to Google Drive with ID: " . $file->id);
            return true;
        } catch (\Exception $e) {
            Log::error("Google Drive upload failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Clean old backup files
     */
    protected function cleanOldBackups()
    {
        try {
            $backupPath = storage_path('app/backups');
            $daysToKeep = (int) $this->option('keep');
            
            if ($daysToKeep <= 0) {
                return;
            }
            
            $this->info("Cleaning backups older than {$daysToKeep} days...");
            
            $cutoffDate = Carbon::now()->subDays($daysToKeep);
            $files = File::files($backupPath);
            $deletedCount = 0;
            
            foreach ($files as $file) {
                if (strpos(basename($file), 'public-backup-') === 0) {
                    $fileDate = Carbon::createFromTimestamp(File::lastModified($file));
                    
                    if ($fileDate->lt($cutoffDate)) {
                        File::delete($file);
                        $deletedCount++;
                    }
                }
            }
            
            $this->info("Deleted {$deletedCount} old public folder backup files.");
        } catch (\Exception $e) {
            Log::error("Failed to clean old backups: " . $e->getMessage());
        }
    }
}