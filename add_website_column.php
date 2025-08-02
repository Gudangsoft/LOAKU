<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Check if column exists
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('publishers');
    echo "Current columns: " . implode(', ', $columns) . PHP_EOL;
    
    if (!in_array('website', $columns)) {
        echo "Website column does not exist. Adding it now..." . PHP_EOL;
        
        // Add the column
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE publishers ADD COLUMN website VARCHAR(255) NULL AFTER email');
        
        echo "Website column added successfully!" . PHP_EOL;
    } else {
        echo "Website column already exists!" . PHP_EOL;
    }
    
    // Verify the column was added
    $newColumns = \Illuminate\Support\Facades\Schema::getColumnListing('publishers');
    echo "Updated columns: " . implode(', ', $newColumns) . PHP_EOL;
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
