<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Journal;

// Get all journals
$journals = Journal::all();

echo "Found " . $journals->count() . " journals\n";

foreach ($journals as $journal) {
    if (empty($journal->website)) {
        // Generate sample website URL based on journal name
        $website = 'https://journal.' . strtolower(str_replace([' ', ':', '-', '&'], '', $journal->name)) . '.com';
        
        $journal->update(['website' => $website]);
        echo "Updated '{$journal->name}' with website: {$website}\n";
    } else {
        echo "'{$journal->name}' already has website: {$journal->website}\n";
    }
}

echo "Journal website update completed!\n";
