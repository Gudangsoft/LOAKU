<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--type=full : Type of backup (full, schema, data)} {--compress : Compress the backup file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database backup with optional compression';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Starting database backup...");
            
            $type = $this->option('type');
            $compress = $this->option('compress');
            
            // Create backup directory if not exists
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            // Generate backup filename
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $database = config('database.connections.mysql.database');
            $filename = "backup_{$database}_{$type}_{$timestamp}.sql";
            $fullPath = $backupPath . '/' . $filename;
            
            $this->info("Backup type: {$type}");
            $this->line("Target file: {$filename}");
            
            // Create backup using Laravel DB
            $this->createLaravelBackup($fullPath, $type);
            $this->info("Created backup using Laravel DB");
            
            // Compress if requested
            if ($compress) {
                $this->info("Compressing backup file...");
                $compressedFile = $this->compressBackup($fullPath);
                if ($compressedFile) {
                    $this->info("Backup compressed successfully");
                    
                    // Remove uncompressed file
                    File::delete($fullPath);
                    $filename = basename($compressedFile);
                }
            }
            
            // Clean old backups (keep last 7 days)
            $this->cleanOldBackups($backupPath);
            
            $this->info("Backup process completed successfully!");
            $this->info("Backup saved to: storage/app/backups/{$filename}");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Create backup using Laravel DB facade
     */
    private function createLaravelBackup($fullPath, $type)
    {
        $database = config('database.connections.mysql.database');
        $sql = "-- Database backup created on " . Carbon::now()->toDateTimeString() . "\n";
        $sql .= "-- Database: {$database}\n";
        $sql .= "-- Backup type: {$type}\n\n";
        
        // Get all tables
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $database;
        
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            if ($type === 'full' || $type === 'schema') {
                // Get table structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= "-- Structure for table `{$tableName}`\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            }
            
            if ($type === 'full' || $type === 'data') {
                // Get table data
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "-- Data for table `{$tableName}`\n";
                    $sql .= "INSERT INTO `{$tableName}` VALUES\n";
                    
                    $values = [];
                    foreach ($rows as $row) {
                        $rowData = [];
                        foreach ((array)$row as $value) {
                            if ($value === null) {
                                $rowData[] = 'NULL';
                            } else {
                                $rowData[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $values[] = "(" . implode(', ', $rowData) . ")";
                    }
                    
                    $sql .= implode(",\n", $values) . ";\n\n";
                }
            }
        }
        
        File::put($fullPath, $sql);
    }
    
    /**
     * Compress backup file using gzip
     */
    private function compressBackup($filePath)
    {
        if (!function_exists('gzencode')) {
            $this->warn('gzip extension not available, skipping compression');
            return false;
        }
        
        $data = File::get($filePath);
        $compressed = gzencode($data, 9);
        
        $compressedPath = $filePath . '.gz';
        File::put($compressedPath, $compressed);
        
        return $compressedPath;
    }
    
    /**
     * Clean old backup files
     */
    private function cleanOldBackups($backupPath)
    {
        $files = File::files($backupPath);
        $cutoffDate = Carbon::now()->subDays(7);
        
        foreach ($files as $file) {
            if (Carbon::createFromTimestamp($file->getMTime())->lt($cutoffDate)) {
                File::delete($file->getPathname());
                $this->line("Deleted old backup: " . $file->getFilename());
            }
        }
    }
}
