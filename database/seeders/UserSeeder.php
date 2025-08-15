<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@loaku.test'
        ], [
            'name' => 'Admin LOAKU',
            'email' => 'admin@loaku.test',
            'password' => Hash::make('password123'),
            'is_admin' => true
        ]);

        User::updateOrCreate([
            'email' => 'user@loaku.test'
        ], [
            'name' => 'User LOAKU',
            'email' => 'user@loaku.test',
            'password' => Hash::make('password123'),
            'is_admin' => false
        ]);
    }
}
