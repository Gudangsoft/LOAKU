<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:files {--include-uploads : Include uploaded files} {--include-logs : Include log files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create backup of important files (uploads, configs, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Starting files backup...");
            
            $includeUploads = $this->option('include-uploads');
            $includeLogs = $this->option('include-logs');
            
            // Create backup directory if not exists
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            // Generate backup folder name
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $folderName = "files_backup_{$timestamp}";
            $folderPath = $backupPath . '/' . $folderName;
            
            $this->info("Creating backup folder: {$folderName}");
            $this->createFolderBackup($folderPath, $includeUploads, $includeLogs);
            
            // Clean old backups (keep last 7 days)
            $this->cleanOldBackups($backupPath);
            
            $this->info("Files backup process completed successfully!");
            $this->info("Backup saved to: storage/app/backups/{$folderName}");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Files backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Create folder backup
     */
    private function createFolderBackup($folderPath, $includeUploads, $includeLogs)
    {
        File::makeDirectory($folderPath, 0755, true);
        
        $filesCount = 0;
        
        // Copy essential files
        $this->info('Backing up essential configuration files...');
        $filesCount += $this->copyEssentialFiles($folderPath);
        
        // Copy uploaded files if requested
        if ($includeUploads) {
            $this->info('Backing up uploaded files...');
            $filesCount += $this->copyUploadedFiles($folderPath);
        }
        
        // Copy log files if requested
        if ($includeLogs) {
            $this->info('Backing up log files...');
            $filesCount += $this->copyLogFiles($folderPath);
        }
        
        $this->info("Backup created successfully!");
        $this->line("Files included: {$filesCount}");
    }
    
    /**
     * Copy essential Laravel files
     */
    private function copyEssentialFiles($backupPath)
    {
        $count = 0;
        $essentialPath = $backupPath . '/essential';
        File::makeDirectory($essentialPath, 0755, true);
        
        // Config files
        if (File::exists(base_path('config'))) {
            File::copyDirectory(base_path('config'), $essentialPath . '/config');
            $count += count(File::allFiles($essentialPath . '/config'));
        }
        
        // Environment file
        if (File::exists(base_path('.env'))) {
            File::copy(base_path('.env'), $essentialPath . '/.env');
            $count++;
        }
        
        // Composer files
        if (File::exists(base_path('composer.json'))) {
            File::copy(base_path('composer.json'), $essentialPath . '/composer.json');
            $count++;
        }
        
        if (File::exists(base_path('composer.lock'))) {
            File::copy(base_path('composer.lock'), $essentialPath . '/composer.lock');
            $count++;
        }
        
        // Package.json if exists
        if (File::exists(base_path('package.json'))) {
            File::copy(base_path('package.json'), $essentialPath . '/package.json');
            $count++;
        }
        
        return $count;
    }
    
    /**
     * Copy uploaded files
     */
    private function copyUploadedFiles($backupPath)
    {
        $count = 0;
        $uploadsPath = $backupPath . '/uploads';
        
        // Public storage (logos, uploads)
        if (File::exists(public_path('storage'))) {
            File::copyDirectory(public_path('storage'), $uploadsPath . '/public');
            $count += count(File::allFiles($uploadsPath . '/public'));
        }
        
        // Storage app public
        if (File::exists(storage_path('app/public'))) {
            File::copyDirectory(storage_path('app/public'), $uploadsPath . '/app_public');
            $count += count(File::allFiles($uploadsPath . '/app_public'));
        }
        
        return $count;
    }
    
    /**
     * Copy log files (last 30 days)
     */
    private function copyLogFiles($backupPath)
    {
        $count = 0;
        $logsPath = $backupPath . '/logs';
        File::makeDirectory($logsPath, 0755, true);
        
        if (File::exists(storage_path('logs'))) {
            $logFiles = File::files(storage_path('logs'));
            $cutoffDate = Carbon::now()->subDays(30);
            
            foreach ($logFiles as $file) {
                if (Carbon::createFromTimestamp($file->getMTime())->gt($cutoffDate)) {
                    File::copy($file->getPathname(), $logsPath . '/' . $file->getFilename());
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Clean old backup files and folders
     */
    private function cleanOldBackups($backupPath)
    {
        $items = array_merge(
            File::files($backupPath),
            File::directories($backupPath)
        );
        
        $cutoffDate = Carbon::now()->subDays(7);
        
        foreach ($items as $item) {
            $itemPath = is_string($item) ? $item : $item->getPathname();
            $modTime = is_string($item) ? filemtime($item) : $item->getMTime();
            
            if (Carbon::createFromTimestamp($modTime)->lt($cutoffDate)) {
                if (is_dir($itemPath)) {
                    File::deleteDirectory($itemPath);
                    $this->line("Deleted old backup folder: " . basename($itemPath));
                } else {
                    File::delete($itemPath);
                    $this->line("Deleted old backup file: " . basename($itemPath));
                }
            }
        }
    }
}
