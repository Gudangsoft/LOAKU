<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Publisher;
use App\Models\Journal;
use App\Models\LoaRequest;
use Illuminate\Support\Facades\Hash;

class PublisherUserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Create publisher users
        $publishers = [
            [
                'name' => 'John Publisher',
                'email' => 'publisher@test.com',
                'password' => Hash::make('publisher123'),
                'role' => 'publisher',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Academic Press',
                'email' => 'sarah@test.com', 
                'password' => Hash::make('publisher123'),
                'role' => 'publisher',
                'email_verified_at' => now(),
            ]
        ];

        foreach ($publishers as $publisherData) {
            $user = User::firstOrCreate(
                ['email' => $publisherData['email']],
                $publisherData
            );
            
            $this->command->info("Created publisher user: {$user->email}");
            
            // Create publisher company for this user
            if ($user->email === 'publisher@test.com') {
                $publisherCompany = Publisher::firstOrCreate(
                    ['user_id' => $user->id, 'name' => 'Scientific Publications Ltd'],
                    [
                        'user_id' => $user->id,
                        'name' => 'Scientific Publications Ltd',
                        'address' => 'Jl. Akademik Raya No. 123, Jakarta 12345',
                        'phone' => '+62-21-7890123',
                        'email' => 'info@scipub.com',
                        'website' => 'https://scipub.com'
                    ]
                );
                
                // Create journals for this publisher
                $journals = [
                    [
                        'user_id' => $user->id,
                        'publisher_id' => $publisherCompany->id,
                        'name' => 'International Journal of Computer Science',
                        'e_issn' => '2580-1234',
                        'p_issn' => '1234-5678',
                        'chief_editor' => 'Dr. John Smith',
                        'website' => 'https://ijcs.scipub.com'
                    ],
                    [
                        'user_id' => $user->id,
                        'publisher_id' => $publisherCompany->id,
                        'name' => 'Journal of Applied Mathematics',
                        'e_issn' => '2580-5678',
                        'p_issn' => '5678-1234',
                        'chief_editor' => 'Prof. Jane Doe',
                        'website' => 'https://jam.scipub.com'
                    ]
                ];
                
                foreach ($journals as $journalData) {
                    $journal = Journal::firstOrCreate(
                        ['user_id' => $user->id, 'name' => $journalData['name']],
                        $journalData
                    );
                    
                    // Create sample LOA requests for this journal
                    $this->createSampleLoaRequests($journal);
                }
            }
            
            if ($user->email === 'sarah@test.com') {
                $publisherCompany = Publisher::firstOrCreate(
                    ['user_id' => $user->id, 'name' => 'Academic Research Press'],
                    [
                        'user_id' => $user->id,
                        'name' => 'Academic Research Press',
                        'address' => 'Jl. Pendidikan No. 456, Bandung 40123',
                        'phone' => '+62-22-3456789',
                        'email' => 'info@academicpress.edu',
                        'website' => 'https://academicpress.edu'
                    ]
                );
                
                $journal = Journal::firstOrCreate(
                    ['user_id' => $user->id, 'name' => 'Educational Technology Review'],
                    [
                        'user_id' => $user->id,
                        'publisher_id' => $publisherCompany->id,
                        'name' => 'Educational Technology Review',
                        'e_issn' => '2580-9012',
                        'p_issn' => '9012-3456',
                        'chief_editor' => 'Dr. Sarah Wilson',
                        'website' => 'https://etr.academicpress.edu'
                    ]
                );
                
                $this->createSampleLoaRequests($journal);
            }
        }
    }
    
    private function createSampleLoaRequests(Journal $journal)
    {
        $requests = [
            [
                'journal_id' => $journal->id,
                'article_title' => 'Advanced Machine Learning Techniques for Data Analysis',
                'authors' => 'Dr. Michael Chen, Prof. Lisa Wang',
                'corresponding_author' => 'Dr. Michael Chen',
                'corresponding_email' => 'michael.chen@university.edu',
                'article_type' => 'research',
                'submission_date' => now()->subDays(15),
                'status' => 'pending',
                'article_id' => 'ART' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'registration_number' => 'LOASIP.' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT) . '.001'
            ],
            [
                'journal_id' => $journal->id,
                'article_title' => 'Sustainable Development in Urban Planning',
                'authors' => 'Prof. Ahmad Rahman, Dr. Siti Nurhaliza',
                'corresponding_author' => 'Prof. Ahmad Rahman',
                'corresponding_email' => 'ahmad.rahman@institut.ac.id',
                'article_type' => 'research',
                'submission_date' => now()->subDays(10),
                'status' => 'approved',
                'approved_at' => now()->subDays(2),
                'article_id' => 'ART' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'registration_number' => 'LOASIP.' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT) . '.002'
            ],
            [
                'journal_id' => $journal->id,
                'article_title' => 'Innovation in Digital Marketing Strategies',
                'authors' => 'Dr. Robert Johnson',
                'corresponding_author' => 'Dr. Robert Johnson',
                'corresponding_email' => 'robert.j@business.edu',
                'article_type' => 'review',
                'submission_date' => now()->subDays(5),
                'status' => 'pending',
                'article_id' => 'ART' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'registration_number' => 'LOASIP.' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT) . '.003'
            ]
        ];
        
        foreach ($requests as $requestData) {
            LoaRequest::firstOrCreate(
                [
                    'journal_id' => $requestData['journal_id'],
                    'registration_number' => $requestData['registration_number']
                ],
                $requestData
            );
        }
    }
}
