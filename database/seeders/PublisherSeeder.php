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
                'website' => 'https://teknologi.ac.id',
            ],
            [
                'name' => 'Institut Penelitian dan Pengembangan Sains',
                'address' => 'Jl. Penelitian Raya No. 456, Bandung, Jawa Barat',
                'phone' => '+62-22-87654321',
                'whatsapp' => '+62-813-9876-5432',
                'email' => 'editor@ipps.ac.id',
                'website' => 'https://ipps.ac.id',
            ],
            [
                'name' => 'Yayasan Ilmu Pengetahuan Indonesia',
                'address' => 'Jl. Ilmu Pengetahuan No. 789, Yogyakarta, DIY',
                'phone' => '+62-274-56789012',
                'whatsapp' => '+62-814-5678-9012',
                'email' => 'info@yipi.org',
                'website' => 'https://yipi.org',
            ],
            [
                'name' => 'Institut Teknologi Bandung',
                'address' => 'Jl. Ganesha No.10, Lb. Siliwangi, Kecamatan Coblong, Kota Bandung, Jawa Barat 40132',
                'phone' => '022-2500935',
                'whatsapp' => '0822500935',
                'email' => 'info@itb.ac.id',
                'website' => 'https://itb.ac.id',
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'address' => 'Bulaksumur, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281',
                'phone' => '0274-588688',
                'whatsapp' => '08274588688',
                'email' => 'info@ugm.ac.id',
                'website' => 'https://ugm.ac.id',
            ],
            [
                'name' => 'Universitas Indonesia',
                'address' => 'Kampus UI Depok, Pondok Cina, Kecamatan Beji, Kota Depok, Jawa Barat 16424',
                'phone' => '021-7270011',
                'whatsapp' => '08217270011',
                'email' => 'info@ui.ac.id',
                'website' => 'https://ui.ac.id',
            ],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }
    }
}
