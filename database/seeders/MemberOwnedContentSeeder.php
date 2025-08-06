<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Publisher;
use App\Models\Journal;
use App\Models\LoaRequest;
use App\Models\LoaValidated;

class MemberOwnedContentSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get member users
        $member1 = User::where('email', 'member@test.com')->first();
        $member2 = User::where('email', 'member2@test.com')->first();

        if (!$member1 || !$member2) {
            $this->command->error('Member users not found. Please run MemberUserSeeder first.');
            return;
        }

        // Create publishers for member1
        $publisher1 = Publisher::create([
            'user_id' => $member1->id,
            'name' => 'Tech Innovation Publishers',
            'address' => 'Jl. Teknologi No. 123, Jakarta',
            'phone' => '021-1234567',
            'whatsapp' => '081234567890',
            'email' => 'info@techinnovation.com',
            'website' => 'https://techinnovation.com',
            'logo' => 'publisher_logos/tech_innovation.png',
        ]);

        // Create journals for member1
        $journal1 = Journal::create([
            'user_id' => $member1->id,
            'publisher_id' => $publisher1->id,
            'name' => 'International Journal of Computer Science and Technology',
            'e_issn' => '2456-1234',
            'p_issn' => '1234-5678',
            'chief_editor' => 'Prof. Dr. Ahmad Teknologi',
            'logo' => 'journal_logos/ijcst.png',
            'ttd_stample' => 'stamps/ijcst_stamp.png',
            'website' => 'https://ijcst.techinnovation.com',
        ]);

        $journal2 = Journal::create([
            'user_id' => $member1->id,
            'publisher_id' => $publisher1->id,
            'name' => 'Journal of Artificial Intelligence Research',
            'e_issn' => '2456-5678',
            'p_issn' => '5678-1234',
            'chief_editor' => 'Dr. Sarah AI Expert',
            'logo' => 'journal_logos/jair.png',
            'ttd_stample' => 'stamps/jair_stamp.png',
            'website' => 'https://jair.techinnovation.com',
        ]);

        // Create publishers for member2
        $publisher2 = Publisher::create([
            'user_id' => $member2->id,
            'name' => 'Green Energy Academic Press',
            'address' => 'Jl. Hijau Energi No. 456, Bandung',
            'phone' => '022-7654321',
            'whatsapp' => '082345678901',
            'email' => 'contact@greenenergypress.org',
            'website' => 'https://greenenergypress.org',
            'logo' => 'publisher_logos/green_energy.png',
        ]);

        // Create journals for member2
        $journal3 = Journal::create([
            'user_id' => $member2->id,
            'publisher_id' => $publisher2->id,
            'name' => 'Renewable Energy and Sustainability Journal',
            'e_issn' => '2789-1234',
            'p_issn' => '3456-7890',
            'chief_editor' => 'Prof. Dr. Budi Renewable',
            'logo' => 'journal_logos/resj.png',
            'ttd_stample' => 'stamps/resj_stamp.png',
            'website' => 'https://resj.greenenergypress.org',
        ]);

        // Create LOA requests for member1's journals
        $requests = [
            [
                'journal_id' => $journal1->id,
                'article_title' => 'Machine Learning Applications in Healthcare Systems',
                'author' => 'Dr. John Smith',
                'author_email' => 'john.smith@university.edu',
                'status' => 'approved',
                'admin_notes' => 'Excellent research. LOA approved.',
                'approved_at' => now()->subDays(3),
            ],
            [
                'journal_id' => $journal1->id,
                'article_title' => 'Blockchain Technology in Supply Chain Management',
                'author' => 'Prof. Maria Garcia',
                'author_email' => 'maria.garcia@institute.org',
                'status' => 'pending',
                'admin_notes' => null,
                'approved_at' => null,
            ],
            [
                'journal_id' => $journal2->id,
                'article_title' => 'Deep Learning for Natural Language Processing',
                'author' => 'Dr. Ahmed Hassan',
                'author_email' => 'ahmed.hassan@tech.edu',
                'status' => 'approved',
                'admin_notes' => 'Outstanding contribution to AI research.',
                'approved_at' => now()->subDays(1),
            ],
            [
                'journal_id' => $journal3->id,
                'article_title' => 'Solar Panel Efficiency in Tropical Climate',
                'author' => 'Dr. Lisa Sustainability',
                'author_email' => 'lisa.sustainability@green.edu',
                'status' => 'pending',
                'admin_notes' => null,
                'approved_at' => null,
            ],
            [
                'journal_id' => $journal3->id,
                'article_title' => 'Wind Energy Potential in Coastal Areas',
                'author' => 'Prof. David Windpower',
                'author_email' => 'david.wind@renewable.org',
                'status' => 'rejected',
                'admin_notes' => 'Insufficient peer review documentation.',
                'approved_at' => null,
            ],
        ];

        foreach ($requests as $index => $requestData) {
            $sequential = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $articleId = 'ART' . ($index + 100);
            $no_reg = "LOASIP.{$articleId}.{$sequential}";

            $request = LoaRequest::create([
                'no_reg' => $no_reg,
                'article_id' => $articleId,
                'volume' => rand(10, 25),
                'number' => rand(1, 4),
                'month' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'][rand(0, 5)],
                'year' => 2024,
                'article_title' => $requestData['article_title'],
                'author' => $requestData['author'],
                'author_email' => $requestData['author_email'],
                'journal_id' => $requestData['journal_id'],
                'status' => $requestData['status'],
                'admin_notes' => $requestData['admin_notes'],
                'approved_at' => $requestData['approved_at'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 5)),
            ]);

            // Create validated LOA for approved requests
            if ($request->status === 'approved') {
                $loaCode = 'LOA' . date('Y') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                
                LoaValidated::create([
                    'loa_request_id' => $request->id,
                    'loa_code' => $loaCode,
                    'pdf_path_id' => 'loa_pdfs/' . $loaCode . '_id.pdf',
                    'pdf_path_en' => 'loa_pdfs/' . $loaCode . '_en.pdf',
                    'verification_url' => url('/loa/verify/' . $loaCode),
                    'created_at' => $request->approved_at,
                    'updated_at' => $request->approved_at,
                ]);
            }
        }

        $this->command->info('✅ Created publishers and journals for members');
        $this->command->info('✅ Created LOA requests for member-owned journals');
        $this->command->line('');
        $this->command->info('Member 1 (member@test.com) owns:');
        $this->command->line('- Tech Innovation Publishers');
        $this->command->line('- International Journal of Computer Science and Technology');
        $this->command->line('- Journal of Artificial Intelligence Research');
        $this->command->line('');
        $this->command->info('Member 2 (member2@test.com) owns:');
        $this->command->line('- Green Energy Academic Press');
        $this->command->line('- Renewable Energy and Sustainability Journal');
    }
}
