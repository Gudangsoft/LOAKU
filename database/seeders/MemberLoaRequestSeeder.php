<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoaRequest;
use App\Models\User;
use App\Models\Journal;

class MemberLoaRequestSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get member users
        $members = User::where('role', 'member')->take(2)->get();
        
        if ($members->count() === 0) {
            $this->command->warn('No member users found. Please run MemberUserSeeder first.');
            return;
        }

        // Get some journals
        $journals = Journal::take(3)->get();
        
        if ($journals->count() === 0) {
            $this->command->warn('No journals found. Please run JournalSeeder first.');
            return;
        }

        $requestData = [
            [
                'article_title' => 'Machine Learning Applications in Healthcare',
                'author' => 'Dr. John Smith',
                'author_email' => 'john.smith@university.edu',
                'article_id' => 'ART001',
                'volume' => 15,
                'number' => 3,
                'month' => 'Maret',
                'year' => 2024,
                'status' => 'approved',
                'admin_notes' => 'Request approved. LOA generated successfully.',
                'approved_at' => now()->subDays(5),
            ],
            [
                'article_title' => 'Sustainable Energy Solutions for Rural Areas',
                'author' => 'Prof. Maria Garcia',
                'author_email' => 'maria.garcia@institute.org',
                'article_id' => 'ART002',
                'volume' => 12,
                'number' => 2,
                'month' => 'Februari',
                'year' => 2024,
                'status' => 'pending',
                'admin_notes' => null,
                'approved_at' => null,
            ],
            [
                'article_title' => 'Blockchain Technology in Supply Chain Management',
                'author' => 'Dr. Ahmed Hassan',
                'author_email' => 'ahmed.hassan@tech.edu',
                'article_id' => 'ART003',
                'volume' => 8,
                'number' => 1,
                'month' => 'Januari',
                'year' => 2024,
                'status' => 'rejected',
                'admin_notes' => 'Insufficient documentation provided. Please resubmit with complete author verification.',
                'approved_at' => null,
            ],
            [
                'article_title' => 'Artificial Intelligence in Education: Trends and Challenges',
                'author' => 'Dr. Sarah Johnson',
                'author_email' => 'sarah.johnson@edu.ac.id',
                'article_id' => 'ART004',
                'volume' => 20,
                'number' => 4,
                'month' => 'April',
                'year' => 2024,
                'status' => 'approved',
                'admin_notes' => 'Excellent submission. LOA approved.',
                'approved_at' => now()->subDays(2),
            ],
            [
                'article_title' => 'Climate Change Impact on Agricultural Productivity',
                'author' => 'Prof. David Wilson',
                'author_email' => 'david.wilson@agri.edu',
                'article_id' => 'ART005',
                'volume' => 18,
                'number' => 2,
                'month' => 'Juni',
                'year' => 2024,
                'status' => 'pending',
                'admin_notes' => null,
                'approved_at' => null,
            ],
        ];

        foreach ($requestData as $index => $data) {
            // Alternate between members
            $member = $members[$index % $members->count()];
            $journal = $journals[$index % $journals->count()];
            
            // Generate registration number
            $sequential = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $no_reg = "LOASIP.{$data['article_id']}.{$sequential}";

            LoaRequest::create([
                'user_id' => $member->id,
                'no_reg' => $no_reg,
                'article_id' => $data['article_id'],
                'volume' => $data['volume'],
                'number' => $data['number'],
                'month' => $data['month'],
                'year' => $data['year'],
                'article_title' => $data['article_title'],
                'author' => $data['author'],
                'author_email' => $data['author_email'],
                'journal_id' => $journal->id,
                'status' => $data['status'],
                'admin_notes' => $data['admin_notes'],
                'approved_at' => $data['approved_at'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 5)),
            ]);
        }

        $this->command->info('Created ' . count($requestData) . ' member LOA requests.');
    }
}
