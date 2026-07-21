<?php

namespace App\Services;

use App\Models\DropboxToken;
use Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Dropbox\Client as DropboxClient;
use App\Models\BackupLog;

class DropboxBackupService
{
    protected $dropbox;
    protected $localDisk;
    protected $chunkSize;
    
    public function __construct()
    {
        // Get token record from database
        $tokenRecord = DropboxToken::latest()->first();
        
        // Check if token exists and if it's expired or about to expire
        if ($tokenRecord) {
            $needsRefresh = false;
            
            // Check if token has an expiration time and if it's expired or about to expire (within 5 minutes)
            if ($tokenRecord->expires_at && now()->addMinutes(5)->isAfter($tokenRecord->expires_at)) {
                $needsRefresh = true;
                Log::info('Dropbox token is expired or about to expire, refreshing');
            }
            
            // Refresh token if needed
            if ($needsRefresh && $tokenRecord->refresh_token) {
                try {
                    $response = Http::timeout(15)->asForm()->post('https://api.dropboxapi.com/oauth2/token', [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $tokenRecord->refresh_token,
                        'client_id' => config('services.dropbox.client_id'),
                        'client_secret' => config('services.dropbox.app_secret'),
                    ]);
                    
                    if ($response->successful()) {
                        $tokenData = $response->json();
                        
                        // Update access token in database
                        $tokenRecord->access_token = $tokenData['access_token'];
                        $tokenRecord->expires_at = isset($tokenData['expires_in'])
                            ? now()->addSeconds($tokenData['expires_in'] - 30)  // 30 second buffer
                            : null;
                            
                        // Update refresh token if provided
                        if (isset($tokenData['refresh_token'])) {
                            $tokenRecord->refresh_token = $tokenData['refresh_token'];
                        }
                        
                        $tokenRecord->save();
                        
                        Log::info('Dropbox token refreshed successfully', [
                            'expires_at' => $tokenRecord->expires_at
                        ]);
                    } else {
                        $errorDetails = $response->json();
                        $errorMessage = $errorDetails['error_description'] ?? $errorDetails['error'] ?? 'Unknown error';
                        Log::error("Failed to refresh Dropbox token: {$errorMessage}", [
                            'status' => $response->status(),
                            'error' => $errorDetails
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception while refreshing Dropbox token: ' . $e->getMessage());
                }
            }
        }
        
        // Get the latest access token (either original or newly refreshed)
        $token = $tokenRecord->access_token ?? config('services.dropbox.token');
        
        // Create Dropbox client with the token
        $this->dropbox = new DropboxClient($token);
        
        // Set local disk - we'll use public disk to access documents folder
        $this->localDisk = Storage::disk('public');
        
        // Set chunk size for large files (50MB by default)
        $this->chunkSize = config('backup.documents.chunk_size', 50 * 1024 * 1024);
    }
    
    public function syncDirectory($localPath = 'documents', $dropboxPath = 'backup', $recursive = true)
    {
        $results = [
            'total_files' => 0,
            'synced_files' => 0,
            'failed_files' => 0,
            'skipped_files' => 0,
            'total_size' => 0,
            'synced_size' => 0,
            'errors' => []
        ];
        
        try {
            // Ensure Dropbox path starts with a slash
            if ($dropboxPath !== '' && !Str::startsWith($dropboxPath, '/')) {
                $dropboxPath = '/' . $dropboxPath;
            }
            
            // Check if directory exists
            if (!$this->localDisk->exists($localPath)) {
                throw new \Exception("Local path does not exist: {$localPath}. Full path: " . storage_path('app/public/' . $localPath));
            }
            
            // Get all files in the directory (not recursive)
            $files = $this->localDisk->files($localPath);
            $results['total_files'] += count($files);
            
            Log::info("Found {$results['total_files']} files in {$localPath}");
            
            // Sync each file
            foreach ($files as $file) {
                $fileSize = $this->localDisk->size($file);
                $results['total_size'] += $fileSize;
                
                $relativePath = $localPath === '' ? $file : Str::substr($file, Str::length($localPath) + 1);
                $targetPath = $dropboxPath . '/' . $relativePath;
                
                Log::info("Processing file: {$file} -> {$targetPath}");
                
                try {
                    // Check if file needs syncing
                    if (!$this->fileNeedsSync($file, $targetPath)) {
                        $results['skipped_files']++;
                        Log::info("Skipping file (already up to date): {$file}");
                        continue;
                    }
                    
                    Log::info("Uploading file: {$file} to Dropbox: {$targetPath}");
                    
                    // Upload the file
                    if ($fileSize > $this->chunkSize) {
                        // Use chunked upload for large files
                        $this->uploadLargeFile($file, $targetPath);
                    } else {
                        // Use simple upload for smaller files
                        $this->uploadFile($file, $targetPath);
                    }
                    
                    $results['synced_files']++;
                    $results['synced_size'] += $fileSize;
                    
                    // Log the successful sync
                    BackupLog::create([
                        'file_path' => $file,
                        'destination' => 'dropbox:' . $targetPath,
                        'status' => 'success',
                        'size' => $fileSize
                    ]);
                    
                    Log::info("Successfully uploaded file: {$file}");
                    
                } catch (\Exception $e) {
                    $results['failed_files']++;
                    $results['errors'][] = [
                        'file' => $file,
                        'error' => $e->getMessage()
                    ];
                    
                    // Log the error
                    Log::error("Failed to sync file to Dropbox: {$file}", [
                        'error' => $e->getMessage(),
                        'path' => $targetPath
                    ]);
                    
                    BackupLog::create([
                        'file_path' => $file,
                        'destination' => 'dropbox:' . $targetPath,
                        'status' => 'failed',
                        'size' => $fileSize,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Process subdirectories if recursive
            if ($recursive) {
                $directories = $this->localDisk->directories($localPath);
                Log::info("Found " . count($directories) . " directories in {$localPath}");
                
                foreach ($directories as $directory) {
                    $relativePath = $localPath === '' ? $directory : Str::substr($directory, Str::length($localPath) + 1);
                    $targetPath = $dropboxPath . '/' . $relativePath;
                    
                    Log::info("Processing directory: {$directory} -> {$targetPath}");
                    
                    // Ensure directory exists in Dropbox
                    try {
                        $this->dropbox->createFolder($targetPath);
                        Log::info("Created directory in Dropbox: {$targetPath}");
                    } catch (\Exception $e) {
                        // Directory may already exist, that's fine
                        Log::info("Directory may already exist in Dropbox: {$targetPath}");
                    }
                    
                    // Recursively sync the subdirectory
                    $subResults = $this->syncDirectory($directory, $targetPath, true);
                    
                    // Merge results
                    $results['total_files'] += $subResults['total_files'];
                    $results['synced_files'] += $subResults['synced_files'];
                    $results['failed_files'] += $subResults['failed_files'];
                    $results['skipped_files'] += $subResults['skipped_files'];
                    $results['total_size'] += $subResults['total_size'];
                    $results['synced_size'] += $subResults['synced_size'];
                    $results['errors'] = array_merge($results['errors'], $subResults['errors']);
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Error syncing directory to Dropbox: {$localPath}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $results['errors'][] = [
                'directory' => $localPath,
                'error' => $e->getMessage()
            ];
        }
        
        return $results;
    }
    
    protected function fileNeedsSync($localPath, $dropboxPath)
    {
        try {
            // Get Dropbox file metadata
            $metadata = $this->dropbox->getMetadata($dropboxPath);
            
            // If file doesn't exist on Dropbox, it needs sync
            if (!$metadata) {
                Log::info("File doesn't exist on Dropbox, needs sync: {$dropboxPath}");
                return true;
            }
            
            // Compare file sizes
            $localSize = $this->localDisk->size($localPath);
            if ($localSize != $metadata['size']) {
                Log::info("File size mismatch, needs sync. Local: {$localSize}, Dropbox: {$metadata['size']}");
                return true;
            }
            
            // Compare modification times
            $localModified = $this->localDisk->lastModified($localPath);
            $dropboxModified = strtotime($metadata['server_modified']);
            
            // If local file is newer, it needs sync
            $needsSync = $localModified > $dropboxModified;
            
            if ($needsSync) {
                Log::info("File is newer locally, needs sync. Local: " . date('Y-m-d H:i:s', $localModified) . 
                          ", Dropbox: " . date('Y-m-d H:i:s', $dropboxModified));
            } else {
                Log::info("File is up to date, no sync needed: {$localPath}");
            }
            
            return $needsSync;
            
        } catch (\Exception $e) {
            // If there's an error (like file not found), sync the file
            Log::info("File not found on Dropbox or other error, marking for sync: {$dropboxPath}", [
                'error' => $e->getMessage()
            ]);
            return true;
        }
    }
    
    protected function uploadFile($localPath, $dropboxPath)
    {
        $content = $this->localDisk->get($localPath);
        $this->dropbox->upload($dropboxPath, $content);
    }
    
    protected function uploadLargeFile($localPath, $dropboxPath)
    {
        $fileStream = $this->localDisk->readStream($localPath);
        $fileSize = $this->localDisk->size($localPath);
        
        $sessionId = null;
        $offset = 0;
        
        // Upload file in chunks
        while (!feof($fileStream)) {
            // Read a chunk
            $chunk = fread($fileStream, $this->chunkSize);
            
            if ($sessionId === null) {
                // Start a new upload session
                $sessionId = $this->dropbox->startUploadSession($chunk);
            } else {
                // Append to existing session
                $this->dropbox->appendContentToUploadSession(
                    $sessionId,
                    $chunk,
                    $offset
                );
            }
            
            $offset += strlen($chunk);
        }
        
        // Complete the session and move file to final location
        $this->dropbox->finishUploadSession(
            $sessionId,
            $dropboxPath,
            $fileSize
        );
        
        fclose($fileStream);
    }
    
    public function listDropboxFiles($path = '')
    {
        // Ensure path starts with a slash
        if ($path !== '' && !Str::startsWith($path, '/')) {
            $path = '/' . $path;
        }
        
        return $this->dropbox->listFolder($path);
    }
    
    public function downloadFile($dropboxPath, $localPath)
    {
        $file = $this->dropbox->download($dropboxPath);
        
        // Ensure directory exists
        $directory = dirname($localPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        File::put($localPath, $file);
        
        return true;
    }
}