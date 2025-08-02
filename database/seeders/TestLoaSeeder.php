<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publisher;
use App\Models\Journal;
use App\Models\LoaRequest;
use App\Models\LoaValidated;
use Carbon\Carbon;

class TestLoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test publisher
        $publisher = Publisher::firstOrCreate([
            'name' => 'Test Scientific Publisher',
            'address' => 'Jl. Ilmu Pengetahuan No. 123, Jakarta',
            'email' => 'info@testpublisher.com',
            'phone' => '+62-21-12345678'
        ]);

        // Create a test journal
        $journal = Journal::firstOrCreate([
            'name' => 'Test Journal of Science',
            'publisher_id' => $publisher->id,
            'e_issn' => '2345-6789',
            'p_issn' => '1234-5678',
            'chief_editor' => 'Dr. Test Editor'
        ]);

        // Create a test LOA request
        $loaRequest = LoaRequest::firstOrCreate([
            'no_reg' => 'REG001',
            'article_id' => 'ART001',
            'article_title' => 'Test Article: Advanced Studies in Testing',
            'author' => 'John Doe, Jane Smith',
            'author_email' => 'john.doe@example.com',
            'journal_id' => $journal->id,
            'volume' => 10,
            'number' => 2,
            'month' => 'August',
            'year' => 2025,
            'status' => 'approved',
            'approved_at' => Carbon::now()
        ]);

        // Create the test LOA validated record
        LoaValidated::firstOrCreate([
            'loa_code' => 'LOA20250801030918',
            'loa_request_id' => $loaRequest->id,
            'verification_url' => url('/verify-loa')
        ]);

        $this->command->info('Test LOA data created successfully!');
    }
}
