<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Journal;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $journals = [
            [
                'name' => 'Jurnal Teknologi Informasi dan Komunikasi',
                'e_issn' => '2654-7823',
                'p_issn' => '1234-5678',
                'chief_editor' => 'Prof. Dr. Ahmad Teknologi, M.T.',
                'website' => 'https://jurnal-tik.teknologi.ac.id',
                'publisher_id' => 1,
            ],
            [
                'name' => 'Indonesian Journal of Computer Science',
                'e_issn' => '2765-9834',
                'p_issn' => '2345-6789',
                'chief_editor' => 'Dr. Sari Komputer, S.Kom., M.Kom.',
                'website' => 'https://ijcs.teknologi.ac.id',
                'publisher_id' => 1,
            ],
            [
                'name' => 'Jurnal Penelitian Sains dan Teknologi',
                'e_issn' => '2876-1095',
                'p_issn' => '3456-7890',
                'chief_editor' => 'Prof. Dr. Budi Sains, M.Sc.',
                'website' => 'https://jpst.ipps.ac.id',
                'publisher_id' => 2,
            ],
            [
                'name' => 'International Journal of Applied Sciences',
                'e_issn' => '2987-2106',
                'p_issn' => '4567-8901',
                'chief_editor' => 'Dr. Maya Applied, Ph.D.',
                'website' => 'https://ijas.ipps.ac.id',
                'publisher_id' => 2,
            ],
            [
                'name' => 'Jurnal Ilmu Pengetahuan Indonesia',
                'e_issn' => '3098-3217',
                'p_issn' => '5678-9012',
                'chief_editor' => 'Prof. Dr. Rudi Pengetahuan, M.A.',
                'website' => 'https://jipi.yipi.org',
                'publisher_id' => 3,
            ],
        ];

        foreach ($journals as $journal) {
            Journal::create($journal);
        }
    }
}
