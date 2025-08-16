<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:restore {file : Backup file name} {--force : Force restore without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database from backup file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->argument('file');
        $backupPath = storage_path('app/backups');
        $fullPath = $backupPath . '/' . $filename;
        
        // Check if backup file exists
        if (!File::exists($fullPath)) {
            $this->error("Backup file not found: {$filename}");
            $this->info("Available backups:");
            $this->listBackups();
            return Command::FAILURE;
        }
        
        // Handle compressed files
        $isCompressed = str_ends_with($filename, '.gz');
        $sqlFile = $fullPath;
        
        if ($isCompressed) {
            $this->info('Decompressing backup file...');
            $sqlFile = $this->decompressBackup($fullPath);
            if (!$sqlFile) {
                $this->error('Failed to decompress backup file!');
                return Command::FAILURE;
            }
        }
        
        // Get database configuration
        $database = config('database.connections.mysql.database');
        
        // Confirmation
        if (!$this->option('force')) {
            $this->warn("WARNING: This will completely replace the current database '{$database}'!");
            $this->warn("All existing data will be lost!");
            
            if (!$this->confirm('Are you sure you want to continue?')) {
                $this->info('Restore cancelled.');
                return Command::SUCCESS;
            }
        }
        
        try {
            $this->info('Starting database restore...');
            
            // Get database configuration
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            
            // Build mysql command
            $command = "mysql --host={$host} --port={$port} --user={$username}";
            
            if ($password) {
                $command .= " --password={$password}";
            }
            
            $command .= " {$database} < \"{$sqlFile}\"";
            
            $this->info('Executing restore command...');
            
            // Execute restore
            $output = [];
            $resultCode = 0;
            exec($command . ' 2>&1', $output, $resultCode);
            
            if ($resultCode !== 0) {
                $this->error('Restore failed!');
                $this->error('Error output: ' . implode("\n", $output));
                return Command::FAILURE;
            }
            
            // Clean up decompressed file if it was created
            if ($isCompressed && $sqlFile !== $fullPath) {
                File::delete($sqlFile);
            }
            
            $this->info('Database restored successfully!');
            $this->info("Restored from: {$filename}");
            
            // Verify restore by checking some basic tables
            $this->verifyRestore();
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Restore failed: " . $e->getMessage());
            
            // Clean up decompressed file if it was created
            if ($isCompressed && $sqlFile !== $fullPath && File::exists($sqlFile)) {
                File::delete($sqlFile);
            }
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Decompress backup file
     */
    private function decompressBackup($compressedPath)
    {
        try {
            $decompressedPath = str_replace('.gz', '', $compressedPath);
            $command = "gzip -dc \"{$compressedPath}\" > \"{$decompressedPath}\"";
            
            $output = [];
            $resultCode = 0;
            exec($command . ' 2>&1', $output, $resultCode);
            
            if ($resultCode === 0 && File::exists($decompressedPath)) {
                return $decompressedPath;
            }
            
            return false;
        } catch (\Exception $e) {
            $this->error("Decompression failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * List available backup files
     */
    private function listBackups()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            $this->line('No backup directory found.');
            return;
        }
        
        $files = File::files($backupPath);
        $backups = array_filter($files, function($file) {
            return str_contains($file->getFilename(), 'backup_') && 
                   (str_ends_with($file->getFilename(), '.sql') || str_ends_with($file->getFilename(), '.sql.gz'));
        });
        
        if (empty($backups)) {
            $this->line('No database backup files found.');
            return;
        }
        
        $this->table(['Filename', 'Size', 'Modified'], array_map(function($file) {
            return [
                $file->getFilename(),
                $this->formatBytes(File::size($file->getPathname())),
                date('Y-m-d H:i:s', File::lastModified($file->getPathname()))
            ];
        }, $backups));
    }
    
    /**
     * Verify restore by checking tables
     */
    private function verifyRestore()
    {
        try {
            $this->info('Verifying restore...');
            
            // Check if main tables exist and have data
            $tables = ['users', 'loa_requests', 'publishers', 'journals'];
            $results = [];
            
            foreach ($tables as $table) {
                try {
                    $count = DB::table($table)->count();
                    $results[] = [$table, $count, '✓'];
                } catch (\Exception $e) {
                    $results[] = [$table, 'N/A', '✗ ' . $e->getMessage()];
                }
            }
            
            $this->table(['Table', 'Records', 'Status'], $results);
            
        } catch (\Exception $e) {
            $this->warn("Could not verify restore: " . $e->getMessage());
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
