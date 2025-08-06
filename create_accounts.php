<?php

// Simple script to create admin account
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Account;
use App\Models\Publisher;
use Illuminate\Support\Facades\Hash;

try {
    // Create admin account
    $adminAccount = Account::updateOrCreate(
        ['email' => 'admin@loaku.com'],
        [
            'username' => 'admin',
            'full_name' => 'Super Administrator',
            'password' => Hash::make('admin123'),
            'role' => 'administrator',
            'status' => 'active',
            'permissions' => [
                'manage_users',
                'manage_accounts',
                'manage_publishers',
                'manage_journals',
                'manage_loa_requests',
                'manage_templates',
                'view_analytics',
                'system_settings'
            ]
        ]
    );

    echo "✅ Admin account created successfully!\n";
    echo "Username: admin\n";
    echo "Email: admin@loaku.com\n";
    echo "Password: admin123\n";
    echo "Role: Administrator\n\n";

    // Create a test publisher
    $publisher = Publisher::updateOrCreate(
        ['name' => 'Test Publisher Corp'],
        [
            'email' => 'publisher@test.com',
            'phone' => '+62 812-3456-7890',
            'address' => 'Jakarta, Indonesia',
            'website' => 'https://testpublisher.com'
        ]
    );

    // Create publisher account
    $publisherAccount = Account::updateOrCreate(
        ['email' => 'publisher@test.com'],
        [
            'username' => 'publisher1',
            'full_name' => 'Test Publisher',
            'password' => Hash::make('publisher123'),
            'role' => 'publisher',
            'status' => 'active',
            'publisher_id' => $publisher->id,
            'permissions' => [
                'manage_journals',
                'manage_loa_requests',
                'view_publisher_analytics'
            ]
        ]
    );

    echo "✅ Publisher account created successfully!\n";
    echo "Username: publisher1\n";
    echo "Email: publisher@test.com\n";
    echo "Password: publisher123\n";
    echo "Role: Publisher\n";
    echo "Publisher: {$publisher->name}\n\n";

    echo "🚀 Role system setup complete!\n";
    echo "You can now login at: /admin/login\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
