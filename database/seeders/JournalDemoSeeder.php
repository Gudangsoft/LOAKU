<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Journal;

class JournalDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Journal::updateOrCreate([
            'name' => 'Jurnal Sains Indonesia'
        ], [
            'name' => 'Jurnal Sains Indonesia',
            'e_issn' => '1234-5678',
            'p_issn' => '8765-4321',
            'chief_editor' => 'Dr. Siti Mawar',
            'logo' => null,
            'ttd_stample' => null,
            'website' => 'https://jurnalsains.id',
            'publisher_id' => 1,
            'user_id' => 2
        ]);

        Journal::updateOrCreate([
            'name' => 'Jurnal Teknologi Nusantara'
        ], [
            'name' => 'Jurnal Teknologi Nusantara',
            'e_issn' => '2345-6789',
            'p_issn' => '9876-5432',
            'chief_editor' => 'Dr. Budi Melati',
            'logo' => null,
            'ttd_stample' => null,
            'website' => 'https://jurnalteknologi.id',
            'publisher_id' => 2,
            'user_id' => 3
        ]);
    }
}
