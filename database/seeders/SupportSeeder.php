<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Support;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Support::truncate();
        
        Support::create([
            'name' => 'Support Organization 1',
            'logo' => 'valid-logo-1.png',
            'website' => 'https://support1.com',
            'description' => 'Primary support organization',
            'order' => 1,
            'is_active' => true
        ]);

        Support::create([
            'name' => 'Support Organization 2',
            'logo' => 'valid-logo-2.png',
            'website' => 'https://support2.com',
            'description' => 'Secondary support organization',
            'order' => 2,
            'is_active' => true
        ]);
        
        Support::create([
            'name' => 'Support Organization 3',
            'logo' => null,
            'website' => 'https://support3.com',
            'description' => 'Support without logo',
            'order' => 3,
            'is_active' => true
        ]);
    }
}
