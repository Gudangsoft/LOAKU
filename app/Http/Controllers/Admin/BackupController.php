<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * Display backup management page
     */
    public function index()
    {
        $backupPath = storage_path('app/backups');
        $backups = $this->getBackupFiles($backupPath);
        
        // Check if this is a test route
        if (request()->routeIs('admin.backups-test.*')) {
            return view('backup-test', compact('backups'));
        }
        
        return view('admin.backups.index', compact('backups'));
    }
    
    /**
     * Create database backup
     */
    public function createDatabase(Request $request)
    {
        return $this->createDatabaseBackup($request);
    }

    /**
     * Create database backup
     */
    public function createDatabaseBackup(Request $request)
    {
        $request->validate([
            'type' => 'required|in:full,schema,data',
            'compress' => 'boolean'
        ]);
        
        try {
            $command = 'backup:database';
            $command .= ' --type=' . $request->type;
            
            if ($request->compress) {
                $command .= ' --compress';
            }
            
            $exitCode = Artisan::call($command);
            
            if ($exitCode === 0) {
                $output = Artisan::output();
                return response()->json([
                    'success' => true,
                    'message' => 'Database backup created successfully!',
                    'output' => $output
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup failed with exit code: ' . $exitCode,
                    'output' => Artisan::output()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Create files backup
     */
    public function createFiles(Request $request)
    {
        return $this->createFilesBackup($request);
    }

    /**
     * Create files backup
     */
    public function createFilesBackup(Request $request)
    {
        $request->validate([
            'include_uploads' => 'boolean',
            'include_logs' => 'boolean'
        ]);
        
        try {
            $command = 'backup:files';
            
            if ($request->include_uploads) {
                $command .= ' --include-uploads';
            }
            
            if ($request->include_logs) {
                $command .= ' --include-logs';
            }
            
            $exitCode = Artisan::call($command);
            
            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Files backup created successfully!',
                    'output' => Artisan::output()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Files backup failed with exit code: ' . $exitCode,
                    'output' => Artisan::output()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Files backup failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download backup file
     */
    public function download($filename)
    {
        $backupPath = storage_path('app/backups');
        $filePath = $backupPath . '/' . $filename;
        
        if (!File::exists($filePath)) {
            abort(404, 'Backup file not found');
        }
        
        return response()->download($filePath);
    }
    
    /**
     * Delete backup file or directory
     */
    public function delete($filename)
    {
        $backupPath = storage_path('app/backups');
        $filePath = $backupPath . '/' . $filename;
        
        if (!File::exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Backup file not found'
            ], 404);
        }
        
        try {
            if (is_dir($filePath)) {
                File::deleteDirectory($filePath);
                $message = 'Backup directory deleted successfully';
            } else {
                File::delete($filePath);
                $message = 'Backup file deleted successfully';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete backup: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get backup statistics for AJAX
     */
    public function stats()
    {
        return $this->getStats();
    }

    /**
     * Get backup statistics
     */
    public function getStats()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            return response()->json([
                'total_backups' => 0,
                'total_size' => 0,
                'database_backups' => 0,
                'files_backups' => 0,
                'oldest_backup' => null,
                'newest_backup' => null
            ]);
        }
        
        $files = File::files($backupPath);
        $totalSize = 0;
        $dbBackups = 0;
        $fileBackups = 0;
        $dates = [];
        
        foreach ($files as $file) {
            $totalSize += File::size($file->getPathname());
            $dates[] = File::lastModified($file->getPathname());
            
            if (str_contains($file->getFilename(), 'backup_')) {
                $dbBackups++;
            } elseif (str_contains($file->getFilename(), 'files_backup_')) {
                $fileBackups++;
            }
        }
        
        return response()->json([
            'total_backups' => count($files),
            'total_size' => $this->formatBytes($totalSize),
            'database_backups' => $dbBackups,
            'files_backups' => $fileBackups,
            'oldest_backup' => !empty($dates) ? date('Y-m-d H:i:s', min($dates)) : null,
            'newest_backup' => !empty($dates) ? date('Y-m-d H:i:s', max($dates)) : null
        ]);
    }
    
    /**
     * Get backup files with metadata
     */
    private function getBackupFiles($backupPath)
    {
        if (!File::exists($backupPath)) {
            return [];
        }
        
        $items = array_merge(File::files($backupPath), File::directories($backupPath));
        $backups = [];
        
        foreach ($items as $item) {
            if (is_string($item)) {
                // It's a directory path
                $filename = basename($item);
                $isDir = true;
                $size = $this->getDirectorySize($item);
                $lastModified = File::lastModified($item);
            } else {
                // It's a file object
                $filename = $item->getFilename();
                $isDir = false;
                $size = File::size($item->getPathname());
                $lastModified = File::lastModified($item->getPathname());
            }
            
            $backups[] = [
                'filename' => $filename,
                'type' => $this->getBackupType($filename),
                'size' => $this->formatBytes($size),
                'size_bytes' => $size,
                'created_at' => Carbon::createFromTimestamp($lastModified),
                'age' => Carbon::createFromTimestamp($lastModified)->diffForHumans(),
                'is_directory' => $isDir
            ];
        }
        
        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });
        
        return $backups;
    }
    
    /**
     * Determine backup type from filename
     */
    private function getBackupType($filename)
    {
        if (str_contains($filename, 'backup_')) {
            if (str_contains($filename, '_full_')) return 'Database (Full)';
            if (str_contains($filename, '_schema_')) return 'Database (Schema)';
            if (str_contains($filename, '_data_')) return 'Database (Data)';
            return 'Database';
        } elseif (str_contains($filename, 'files_backup_')) {
            return 'Files';
        }
        
        return 'Unknown';
    }
    
    /**
     * Get directory size recursively
     */
    private function getDirectorySize($directory)
    {
        $size = 0;
        foreach (File::allFiles($directory) as $file) {
            $size += $file->getSize();
        }
        return $size;
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
