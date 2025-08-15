<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate([
            'email' => 'admin@loaku.test'
        ], [
            'name' => 'Admin LOAKU',
            'email' => 'admin@loaku.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_admin' => true
        ]);

        // Publisher 1
        User::updateOrCreate([
            'email' => 'publisher1@loaku.test'
        ], [
            'name' => 'Publisher Satu',
            'email' => 'publisher1@loaku.test',
            'password' => Hash::make('publisher123'),
            'role' => 'publisher',
            'is_admin' => false
        ]);

        // Publisher 2
        User::updateOrCreate([
            'email' => 'publisher2@loaku.test'
        ], [
            'name' => 'Publisher Dua',
            'email' => 'publisher2@loaku.test',
            'password' => Hash::make('publisher123'),
            'role' => 'publisher',
            'is_admin' => false
        ]);
    }
}
