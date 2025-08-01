<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoaRequest;

class LoaRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleRequests = [
            [
                'no_reg' => 'LOASIP.ART001.01',
                'article_id' => 'ART001',
                'volume' => 5,
                'number' => 2,
                'month' => 'Agustus',
                'year' => 2025,
                'article_title' => 'Implementation of Machine Learning Algorithms for Predictive Analytics in E-Commerce',
                'author' => 'Dr. Ahmad Teknologi, S.Kom., M.Kom.',
                'author_email' => 'ahmad.tekno@example.com',
                'journal_id' => 1, // Jurnal Teknologi Informasi dan Komunikasi
                'status' => 'pending',
            ],
            [
                'no_reg' => 'LOASIP.ART002.01',
                'article_id' => 'ART002',
                'volume' => 3,
                'number' => 1,
                'month' => 'Juli',
                'year' => 2025,
                'article_title' => 'A Comprehensive Study on Blockchain Technology Applications in Healthcare Systems',
                'author' => 'Prof. Dr. Sari Komputer, M.T.',
                'author_email' => 'sari.komputer@example.com',
                'journal_id' => 2, // Indonesian Journal of Computer Science
                'status' => 'approved',
                'approved_at' => now()->subDays(2),
            ],
            [
                'no_reg' => 'LOASIP.ART003.01',
                'article_id' => 'ART003',
                'volume' => 1,
                'number' => 1,
                'month' => 'Juni',
                'year' => 2025,
                'article_title' => 'Renewable Energy Integration in Smart Grid Systems: Challenges and Solutions',
                'author' => 'Dr. Budi Sains, S.T., M.Eng.',
                'author_email' => 'budi.sains@example.com',
                'journal_id' => 3, // Jurnal Penelitian Sains dan Teknologi
                'status' => 'rejected',
                'admin_notes' => 'Artikel memerlukan revisi lebih lanjut pada metodologi penelitian dan analisis data.',
            ],
        ];

        foreach ($sampleRequests as $request) {
            LoaRequest::create($request);
        }
        
        echo "LOA requests seeded successfully!\n";
    }
}
