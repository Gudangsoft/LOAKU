<?php

// Simple script to update existing publishers with validation tokens

use App\Models\Publisher;

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Update publishers
$publishers = Publisher::whereNull('validation_token')->get();

echo "Found " . $publishers->count() . " publishers without tokens.\n";

foreach ($publishers as $publisher) {
    $publisher->generateValidationToken();
    $publisher->save();
    echo "Generated token for {$publisher->name}: {$publisher->validation_token}\n";
}

echo "Done!\n";
