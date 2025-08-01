<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoaRequest;
use App\Models\LoaValidated;

class LoaValidatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test LOA validated records for verification testing
        $journal = \App\Models\Journal::first();
        
        if ($journal) {
            // Test data dengan kode LOA yang mudah diingat
            $testData = [
                [
                    'article_title' => 'Test Article: Machine Learning in Healthcare',
                    'author' => 'Dr. John Smith',
                    'email' => 'john.smith@university.edu',
                    'loa_code' => 'LOA20250801001',
                ],
                [
                    'article_title' => 'Test Article: Artificial Intelligence Applications', 
                    'author' => 'Prof. Jane Doe',
                    'email' => 'jane.doe@institute.org',
                    'loa_code' => 'LOA20250801002',
                ],
                [
                    'article_title' => 'Test Article: Sustainable Technology Development',
                    'author' => 'Dr. Ahmed Hassan',
                    'email' => 'ahmed.hassan@tech.com',
                    'loa_code' => 'LOA20250801003',
                ]
            ];

            foreach ($testData as $data) {
                // Create LOA request
                $loaRequest = LoaRequest::firstOrCreate(
                    ['email' => $data['email']],
                    [
                        'article_title' => $data['article_title'],
                        'author' => $data['author'],
                        'affiliation' => 'Test University',
                        'journal_id' => $journal->id,
                        'manuscript_file' => 'test-manuscript.pdf',
                        'status' => 'approved',
                        'no_reg' => 'REG' . now()->format('Ymd') . rand(1000, 9999)
                    ]
                );

                // Create validated LOA
                LoaValidated::firstOrCreate(
                    ['loa_code' => $data['loa_code']],
                    [
                        'loa_request_id' => $loaRequest->id,
                        'verification_url' => 'http://localhost:8000/verify-loa'
                    ]
                );
            }
            
            echo "Test LOA validation records created successfully!\n";
        }
    }
}
