<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WebsiteSetting;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'LOA Management System',
                'type' => 'text',
                'description' => 'Nama Website'
            ],
            [
                'key' => 'site_description',
                'value' => 'Sistem Manajemen Letter of Acceptance untuk jurnal ilmiah',
                'type' => 'text',
                'description' => 'Deskripsi Website'
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@loasystem.com',
                'type' => 'text',
                'description' => 'Email Admin'
            ],
            [
                'key' => 'phone',
                'value' => '+62 812 3456 7890',
                'type' => 'text',
                'description' => 'Nomor Telepon'
            ],
            [
                'key' => 'address',
                'value' => 'Jl. Contoh No. 123, Jakarta, Indonesia',
                'type' => 'text',
                'description' => 'Alamat'
            ],
            [
                'key' => 'logo',
                'value' => null,
                'type' => 'image',
                'description' => 'Logo Website'
            ],
            [
                'key' => 'favicon',
                'value' => null,
                'type' => 'image',
                'description' => 'Favicon Website'
            ]
        ];

        foreach ($settings as $setting) {
            WebsiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
