<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\User;
use App\Models\Publisher;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Administrator Account
        Account::create([
            'username' => 'admin',
            'email' => 'admin@loaku.com',
            'password' => Hash::make('admin123'),
            'full_name' => 'System Administrator',
            'phone' => '+628123456789',
            'role' => 'administrator',
            'status' => 'active',
            'permissions' => Account::getDefaultPermissions('administrator'),
        ]);

        // Create Super Administrator Account
        Account::create([
            'username' => 'superadmin',
            'email' => 'superadmin@loaku.com',
            'password' => Hash::make('superadmin123'),
            'full_name' => 'Super Administrator',
            'phone' => '+628123456790',
            'role' => 'administrator',
            'status' => 'active',
            'permissions' => Account::getDefaultPermissions('administrator'),
        ]);

        // Get existing publishers
        $publishers = Publisher::all();

        // Create Publisher Accounts for existing publishers
        if ($publishers->count() > 0) {
            foreach ($publishers as $index => $publisher) {
                Account::create([
                    'username' => 'publisher_' . $publisher->id,
                    'email' => 'publisher' . $publisher->id . '@' . strtolower(str_replace(' ', '', $publisher->name)) . '.com',
                    'password' => Hash::make('publisher123'),
                    'full_name' => 'Publisher Admin - ' . $publisher->name,
                    'phone' => '+62812345' . str_pad($publisher->id, 4, '0', STR_PAD_LEFT),
                    'role' => 'publisher',
                    'status' => 'active',
                    'publisher_id' => $publisher->id,
                    'permissions' => Account::getDefaultPermissions('publisher'),
                ]);
            }
        } else {
            // Create sample publisher and account
            $samplePublisher = Publisher::create([
                'name' => 'Sample Academic Publisher',
                'address' => 'Jl. Sample No. 123, Jakarta',
                'email' => 'contact@sampleacademic.com',
                'phone' => '+628123456999',
                'website' => 'https://sampleacademic.com',
            ]);

            Account::create([
                'username' => 'publisher_sample',
                'email' => 'publisher@sampleacademic.com',
                'password' => Hash::make('publisher123'),
                'full_name' => 'Publisher Admin - Sample Academic',
                'phone' => '+628123456999',
                'role' => 'publisher',
                'status' => 'active',
                'publisher_id' => $samplePublisher->id,
                'permissions' => Account::getDefaultPermissions('publisher'),
            ]);
        }

        // Update existing users with roles if they exist
        $this->updateExistingUsers();

        $this->command->info('Account seeder completed successfully!');
        $this->command->info('Administrator accounts created:');
        $this->command->info('- Username: admin, Password: admin123');
        $this->command->info('- Username: superadmin, Password: superadmin123');
        $this->command->info('Publisher accounts created with default password: publisher123');
    }

    private function updateExistingUsers()
    {
        // Update existing admin users
        User::where('is_admin', true)->update([
            'role' => 'administrator',
            'status' => 'active',
            'permissions' => json_encode(Account::getDefaultPermissions('administrator'))
        ]);

        // Update regular users
        User::where('is_admin', false)->orWhereNull('is_admin')->update([
            'role' => 'user',
            'status' => 'active',
            'permissions' => json_encode([])
        ]);
    }
}
