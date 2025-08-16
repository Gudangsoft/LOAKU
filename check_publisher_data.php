<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Publisher;

$publishers = Publisher::all();

echo "Total publishers: " . $publishers->count() . "\n";

foreach ($publishers as $pub) {
    echo "Publisher: {$pub->name}\n";
    echo "  Status: {$pub->status}\n";
    echo "  validated_at: " . ($pub->validated_at === null ? 'NULL' : $pub->validated_at) . "\n";
    echo "  validated_at type: " . gettype($pub->validated_at) . "\n";
    echo "  Token: {$pub->validation_token}\n";
    echo "---\n";
}
