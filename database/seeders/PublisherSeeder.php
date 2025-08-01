<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Publisher;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publishers = [
            [
                'name' => 'Universitas Teknologi Indonesia',
                'address' => 'Jl. Teknologi No. 123, Jakarta Selatan, Indonesia',
                'phone' => '+62-21-12345678',
                'whatsapp' => '+62-812-3456-7890',
                'email' => 'publisher@teknologi.ac.id',
            ],
            [
                'name' => 'Institut Penelitian dan Pengembangan Sains',
                'address' => 'Jl. Penelitian Raya No. 456, Bandung, Jawa Barat',
                'phone' => '+62-22-87654321',
                'whatsapp' => '+62-813-9876-5432',
                'email' => 'editor@ipps.ac.id',
            ],
            [
                'name' => 'Yayasan Ilmu Pengetahuan Indonesia',
                'address' => 'Jl. Ilmu Pengetahuan No. 789, Yogyakarta, DIY',
                'phone' => '+62-274-56789012',
                'whatsapp' => '+62-814-5678-9012',
                'email' => 'info@yipi.org',
            ],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }
    }
}
