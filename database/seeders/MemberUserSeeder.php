<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MemberUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create test member user
        User::firstOrCreate(
            ['email' => 'member@test.com'],
            [
                'name' => 'Test Member',
                'password' => Hash::make('member123'),
                'role' => 'member',
                'is_admin' => false,
                'phone' => '08123456789',
                'organization' => 'Test University',
                'email_verified_at' => now()
            ]
        );

        // Create another member for testing
        User::firstOrCreate(
            ['email' => 'member2@test.com'],
            [
                'name' => 'Member Dua',
                'password' => Hash::make('member123'),
                'role' => 'member',
                'is_admin' => false,
                'phone' => '08987654321',
                'organization' => 'ABC Institute',
                'email_verified_at' => now()
            ]
        );
    }
}
