<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@loasiptenan.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now()
            ]
        );

        // Create additional test admin if needed
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now()
            ]
        );
    }
}
