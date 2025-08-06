<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Publisher;

// Get all publishers
$publishers = Publisher::all();

echo "Found " . $publishers->count() . " publishers\n";

foreach ($publishers as $publisher) {
    if (empty($publisher->website)) {
        // Generate sample website URL based on publisher name
        $website = 'https://www.' . strtolower(str_replace(' ', '', $publisher->name)) . '.com';
        
        $publisher->update(['website' => $website]);
        echo "Updated {$publisher->name} with website: {$website}\n";
    } else {
        echo "{$publisher->name} already has website: {$publisher->website}\n";
    }
}

echo "Website update completed!\n";
