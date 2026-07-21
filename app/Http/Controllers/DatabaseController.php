<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB,Auth;
use File;
use Log;
use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Schema;


class DatabaseController extends Controller
{
    public function download(): BinaryFileResponse
    {

        $myTeamId= Auth::user()->currentTeam->id;
        $backupPath = storage_path('app/backups');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }
        
        $zipPath = storage_path('app/database-backup.zip');


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
             Log::info("Database backup created successfully using mysqldump: {$filePath}");
         }
         
         // If mysqldump fails, fall back to PHP backup
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
             

            // Check if team_id column exists in this table
            $columns = Schema::getColumnListing($tableName);
            $hasTeamId = in_array('team_id', $columns);

            // Get data - filter by team_id only if the column exists
            if ($hasTeamId) {
                $rows = DB::table($tableName)->where("team_id", $myTeamId)->get();
            } else {
                $rows = DB::table($tableName)->get();
            }
             
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
         
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($filePath, 'database.mysql');
        $zip->close();

        return response()->download($zipPath, 'database-backup.zip', [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="database-backup.zip"'
        ])->deleteFileAfterSend(true);
        
    }

    public function downloadDocuments(): BinaryFileResponse
    {
        $companyName=Auth::user()->name;
        $teamuuid=Auth::user()->currentTeam->uuid??'';
        $documentsPath = storage_path('app/public/documents/'.$teamuuid);
        $zipPath = storage_path('app/documents-backup.zip');

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($documentsPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($documentsPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        $downFileNAme=$companyName.'-documents-backup.zip';
        return response()->download($zipPath, $downFileNAme, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename=$downFileNAme'
        ])->deleteFileAfterSend(true);
    }
}