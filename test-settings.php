<?php

// Test file to check website settings functionality
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->booted(function () {
    // Test 1: Check if WebsiteSetting model exists and has data
    try {
        $settings = App\Models\WebsiteSetting::all();
        echo "✅ WebsiteSetting model works, found " . $settings->count() . " records\n";
        
        // Test 2: Check keyBy method
        $settingsByKey = $settings->keyBy('key');
        echo "✅ KeyBy method works\n";
        
        // Test 3: Test controller
        $controller = new App\Http\Controllers\Admin\WebsiteSettingController();
        echo "✅ Controller instantiated successfully\n";
        
        // Test 4: Check if view exists
        if (view()->exists('admin.website-settings.index')) {
            echo "✅ View admin.website-settings.index exists\n";
        } else {
            echo "❌ View admin.website-settings.index NOT found\n";
        }
        
        // Test 5: Check if layout exists
        if (view()->exists('admin.layouts.app')) {
            echo "✅ Layout admin.layouts.app exists\n";
        } else {
            echo "❌ Layout admin.layouts.app NOT found\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
});

$app->boot();
