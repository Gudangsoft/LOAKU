<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SystemLogController extends Controller
{
    /**
     * Display system logs
     */
    public function index()
    {
        $logFiles = $this->getLogFiles();
        $currentLog = request('file', 'laravel.log');
        $logs = $this->readLogFile($currentLog);
        
        return view('admin.system-logs.index', compact('logFiles', 'currentLog', 'logs'));
    }

    /**
     * Get all log files
     */
    private function getLogFiles()
    {
        $logPath = storage_path('logs');
        
        if (!File::exists($logPath)) {
            return [];
        }

        $files = File::files($logPath);
        $logFiles = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                $logFiles[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime())
                ];
            }
        }

        // Sort by modification time, newest first
        usort($logFiles, function($a, $b) {
            return strcmp($b['modified'], $a['modified']);
        });

        return $logFiles;
    }

    /**
     * Read log file content
     */
    private function readLogFile($filename)
    {
        $logPath = storage_path('logs/' . $filename);
        
        if (!File::exists($logPath)) {
            return [];
        }

        $content = File::get($logPath);
        $lines = array_reverse(explode("\n", $content));
        $logs = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;
            
            // Parse Laravel log format
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)/', $line, $matches)) {
                $logs[] = [
                    'timestamp' => $matches[1],
                    'environment' => $matches[2],
                    'level' => strtoupper($matches[3]),
                    'message' => $matches[4],
                    'raw' => $line
                ];
            } else {
                // If line doesn't match pattern, add as continuation of previous log
                if (!empty($logs)) {
                    $logs[count($logs) - 1]['message'] .= "\n" . $line;
                    $logs[count($logs) - 1]['raw'] .= "\n" . $line;
                }
            }
        }

        return array_slice($logs, 0, 1000); // Limit to 1000 entries
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

    /**
     * Clear log file
     */
    public function clear($filename)
    {
        $logPath = storage_path('logs/' . $filename);
        
        if (File::exists($logPath)) {
            File::put($logPath, '');
            return redirect()->route('admin.system-logs.index', ['file' => $filename])
                ->with('success', "Log file {$filename} berhasil dibersihkan.");
        }

        return redirect()->route('admin.system-logs.index')
            ->with('error', 'Log file tidak ditemukan.');
    }

    /**
     * Download log file
     */
    public function download($filename)
    {
        $logPath = storage_path('logs/' . $filename);
        
        if (File::exists($logPath)) {
            return response()->download($logPath);
        }

        return redirect()->route('admin.system-logs.index')
            ->with('error', 'Log file tidak ditemukan.');
    }
}
