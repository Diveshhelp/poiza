<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class DatabaseAutoBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup {--upload : Upload backup to Google Drive} 
                                          {--keep=7 : Number of days to keep local backups}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database backup and optionally upload to Google Drive';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting database backup process...');
        
        try {
            // Create backup
            $backupPath = $this->createDatabaseBackup();
            
            if (!$backupPath) {
                $this->error('Database backup failed!');
                return;
            }
            
            $this->info("Database backup created successfully: " . basename($backupPath));
            
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
            
            $this->info('Database backup process completed successfully.');
        } catch (\Exception $e) {
            $this->error("Database backup failed: " . $e->getMessage());
            Log::error("Database backup exception: " . $e->getMessage());
        }
    }

    /**
     * Create a database backup
     * 
     * @return string|bool The path to the backup file or false on failure
     */
    protected function createDatabaseBackup()
    {
        try {
            // Create backups directory if it doesn't exist
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            // Set filename with timestamp
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            $filePath = $backupPath . '/' . $filename;
            
            // Get database configuration
            $connection = config('database.default');
            $driver = config("database.connections.{$connection}.driver");
            
            if ($driver !== 'mysql') {
                throw new \Exception("Only MySQL databases are supported for backup");
            }
            
            // Database configuration
            $host = config("database.connections.{$connection}.host");
            $port = config("database.connections.{$connection}.port");
            $database = config("database.connections.{$connection}.database");
            $username = config("database.connections.{$connection}.username");
            $password = config("database.connections.{$connection}.password");
            
            // Try mysqldump method first (faster and more reliable)
            $mysqldumpPath = env('MYSQLDUMP_PATH', 'mysqldump');
            $command = "\"{$mysqldumpPath}\" --user={$username} --password={$password} --host={$host} --port={$port} --add-drop-table --skip-lock-tables {$database} > \"{$filePath}\"";
            
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0) {
                $this->comment('Using mysqldump command method');
                Log::info("Database backup created successfully using mysqldump: {$filePath}");
                return $filePath;
            }
            
            // If mysqldump fails, fall back to PHP backup
            $this->comment('Mysqldump failed, using PHP backup method');
            Log::warning("mysqldump failed, falling back to PHP backup method");
            
            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $tableField = "Tables_in_" . $database;
            
            // Start SQL output
            $output = "-- Laravel Database Backup\n";
            $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            
            foreach ($tables as $table) {
                $tableName = $table->$tableField;
                
                // Get create table syntax
                $createTableSql = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableField = "Create Table";
                $output .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $output .= $createTableSql[0]->$createTableField . ";\n\n";
                
                // Get data
                $rows = DB::table($tableName)->get();
                
                if (count($rows) > 0) {
                    $output .= "INSERT INTO `{$tableName}` VALUES ";
                    $first = true;
                    
                    // Process in chunks for large tables
                    $chunks = array_chunk($rows->toArray(), 100);
                    
                    foreach ($chunks as $chunk) {
                        if (!$first) {
                            $output .= ";\nINSERT INTO `{$tableName}` VALUES ";
                        }
                        
                        $rowFirst = true;
                        foreach ($chunk as $row) {
                            if (!$rowFirst) {
                                $output .= ",\n";
                            } else {
                                $rowFirst = false;
                                $first = false;
                            }
                            
                            $values = array_map(function ($value) {
                                if (is_null($value)) {
                                    return 'NULL';
                                } elseif (is_numeric($value)) {
                                    return $value;
                                } else {
                                    return "'" . addslashes($value) . "'";
                                }
                            }, (array) $row);
                            
                            $output .= "(" . implode(", ", $values) . ")";
                        }
                    }
                    
                    $output .= ";\n";
                }
            }
            
            // Save the SQL to file
            File::put($filePath, $output);
            Log::info("Database backup created successfully using PHP: {$filePath}");
            
            return $filePath;
        } catch (\Exception $e) {
            Log::error("Failed to create database backup: " . $e->getMessage());
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
            $folderId = env('GOOGLE_DRIVE_BACKUP_FOLDER_ID');
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
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);
            
            Log::info("Database backup uploaded to Google Drive with ID: " . $file->id);
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
                $fileDate = Carbon::createFromTimestamp(File::lastModified($file));
                
                if ($fileDate->lt($cutoffDate)) {
                    File::delete($file);
                    $deletedCount++;
                }
            }
            
            $this->info("Deleted {$deletedCount} old backup files.");
        } catch (\Exception $e) {
            Log::error("Failed to clean old backups: " . $e->getMessage());
        }
    }
}