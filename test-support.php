<?php

// Test create support record manually
use App\Models\Support;

$support = Support::create([
    'name' => 'Test Support',
    'logo' => 'test-logo.png',
    'website' => 'https://example.com',
    'description' => 'Test support description',
    'order' => 1,
    'is_active' => true
]);

echo "Support created with ID: " . $support->id . "\n";
echo "Logo URL: " . $support->logo_url . "\n";
