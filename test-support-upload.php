<?php

// Test script untuk debug upload support
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SUPPORT UPLOAD TEST ===\n";

// Test 1: Check directories
echo "\n1. Checking directories:\n";
$storageDir = storage_path('app/public/supports');
echo "Storage dir: $storageDir\n";
echo "Exists: " . (file_exists($storageDir) ? 'YES' : 'NO') . "\n";
echo "Writable: " . (is_writable($storageDir) ? 'YES' : 'NO') . "\n";

$publicDir = public_path('storage/supports');
echo "Public dir: $publicDir\n";
echo "Exists: " . (file_exists($publicDir) ? 'YES' : 'NO') . "\n";
echo "Is link: " . (is_link($publicDir) ? 'YES' : 'NO') . "\n";

// Test 2: List current files
echo "\n2. Current files in storage:\n";
if (file_exists($storageDir)) {
    $files = scandir($storageDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $fullPath = $storageDir . '/' . $file;
            echo "- $file (" . filesize($fullPath) . " bytes)\n";
        }
    }
}

// Test 3: Check database
echo "\n3. Current support records:\n";
$supports = App\Models\Support::all(['id', 'name', 'logo']);
foreach ($supports as $support) {
    echo "- ID {$support->id}: {$support->name} -> {$support->logo}\n";
    if ($support->logo) {
        $filePath = $storageDir . '/' . $support->logo;
        echo "  File exists: " . (file_exists($filePath) ? 'YES' : 'NO') . "\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";
