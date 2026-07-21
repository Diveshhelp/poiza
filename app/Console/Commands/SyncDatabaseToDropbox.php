<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use File;
use Illuminate\Console\Command;
use App\Services\DropboxBackupService;
use Illuminate\Support\Facades\Log;

class SyncDatabaseToDropbox extends Command
{
    protected $signature = 'db-backup:dropbox';

    protected $description = 'Sync Database to Dropbox for backup';

    protected $dropboxService;

    public function __construct(DropboxBackupService $dropboxService)
    {
        parent::__construct();
        $this->dropboxService = $dropboxService;
    }

    
    public function handle()
    {
        // Get arguments
        $localPath =  'database';
        $dropboxPath ='/backup/' . $localPath;
        $recursive = true;
        $userId = '';
       
        $this->info("Starting sync from {$localPath} to Dropbox:{$dropboxPath}");
        $start = microtime(true);
        
        try {
            $filePath=$this->createDatabaseBackup();
            // Run the sync
            $results = $this->dropboxService->syncDirectory($localPath, $dropboxPath, $recursive);
            


              // Remove local file after successful upload
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                
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

    protected function createDatabaseBackup()
    {
        try {
            // Create backups directory if it doesn't exist
            $backupPath = storage_path('app/public/database');
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
}