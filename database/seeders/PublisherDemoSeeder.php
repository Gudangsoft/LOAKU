<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publisher;

class PublisherDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Publisher::updateOrCreate([
            'name' => 'Penerbit Satu'
        ], [
            'name' => 'Penerbit Satu',
            'email' => 'publisher1@loaku.test',
            'website' => 'https://penerbitsatu.com',
            'user_id' => 2,
            'address' => 'Jl. Mawar No. 1, Jakarta'
        ]);

        Publisher::updateOrCreate([
            'name' => 'Penerbit Dua'
        ], [
            'name' => 'Penerbit Dua',
            'email' => 'publisher2@loaku.test',
            'website' => 'https://penerbitdua.com',
            'user_id' => 3,
            'address' => 'Jl. Melati No. 2, Bandung'
        ]);
    }
}
