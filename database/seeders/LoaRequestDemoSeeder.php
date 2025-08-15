<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoaRequest;

class LoaRequestDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoaRequest::updateOrCreate([
            'article_id' => 'ART001'
        ], [
            'no_reg' => 'LOASIP.ART001.01',
            'article_id' => 'ART001',
            'volume' => 1,
            'number' => 1,
            'month' => 'Januari',
            'year' => 2025,
            'article_title' => 'Pengaruh Teknologi AI di Indonesia',
            'author' => 'Andi Sains',
            'author_email' => 'andi@contoh.com',
            'journal_id' => 1,
            'status' => 'pending',
            'admin_notes' => null,
            'approved_at' => null,
            'user_id' => 2
        ]);

        LoaRequest::updateOrCreate([
            'article_id' => 'ART002'
        ], [
            'no_reg' => 'LOASIP.ART002.01',
            'article_id' => 'ART002',
            'volume' => 2,
            'number' => 1,
            'month' => 'Februari',
            'year' => 2025,
            'article_title' => 'Inovasi Energi Terbarukan',
            'author' => 'Budi Teknologi',
            'author_email' => 'budi@contoh.com',
            'journal_id' => 2,
            'status' => 'pending',
            'admin_notes' => null,
            'approved_at' => null,
            'user_id' => 3
        ]);
    }
}
