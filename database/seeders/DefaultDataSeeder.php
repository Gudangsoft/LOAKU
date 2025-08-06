<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Publisher;
use App\Models\Journal;
use App\Models\LoaTemplate;
use App\Models\User;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating default publishers...');
        
        // Create default publishers
        $publishers = [
            [
                'name' => 'LP2M Universitas Siber Malang',
                'email' => 'lp2m@unisma.ac.id',
                'phone' => '0341-582171',
                'address' => 'Jl. MT. Haryono No. 193, Malang, Jawa Timur',
                'website' => 'https://unisma.ac.id',
                'description' => 'Lembaga Penelitian dan Pengabdian Masyarakat Universitas Siber Malang',
                'is_active' => true
            ],
            [
                'name' => 'LPPM Universitas Teknologi Indonesia',
                'email' => 'lppm@uti.ac.id',
                'phone' => '021-5123456',
                'address' => 'Jakarta, Indonesia',
                'website' => 'https://uti.ac.id',
                'description' => 'Lembaga Penelitian dan Pengabdian Masyarakat UTI',
                'is_active' => true
            ]
        ];

        foreach ($publishers as $publisherData) {
            Publisher::firstOrCreate(
                ['email' => $publisherData['email']],
                $publisherData
            );
        }

        $this->command->info('Created ' . count($publishers) . ' publishers.');

        $this->command->info('Creating default journals...');

        // Create default journals
        $journals = [
            [
                'name' => 'Menulis: Jurnal Ilmu Komputer dan Teknologi',
                'issn' => '2798-5903',
                'e_issn' => '2798-5911',
                'description' => 'Jurnal ilmiah yang memfokuskan pada bidang ilmu komputer, teknologi informasi, dan topik terkait',
                'publisher_id' => 1, // Will be set to actual publisher ID
                'website' => 'https://ejournal.unisma.ac.id/index.php/menulis',
                'is_active' => true,
                'submission_guidelines' => 'Artikel harus original, belum pernah dipublikasikan, dan sesuai dengan scope jurnal.',
                'review_process' => 'Peer review dengan sistem double blind review',
                'publication_frequency' => 'Bulanan'
            ],
            [
                'name' => 'Jurnal Teknologi dan Sistem Informasi',
                'issn' => '2621-2579',
                'e_issn' => '2621-2587',
                'description' => 'Jurnal yang memfokuskan pada teknologi informasi dan sistem informasi',
                'publisher_id' => 2,
                'website' => 'https://jtsi.uti.ac.id',
                'is_active' => true,
                'submission_guidelines' => 'Manuscript harus dalam format IEEE template.',
                'review_process' => 'Single blind peer review',
                'publication_frequency' => '3 kali per tahun'
            ]
        ];

        $publisherIds = Publisher::pluck('id', 'name')->toArray();

        foreach ($journals as $journalData) {
            // Set proper publisher ID
            if ($journalData['publisher_id'] == 1) {
                $journalData['publisher_id'] = $publisherIds['LP2M Universitas Siber Malang'] ?? 1;
            } else {
                $journalData['publisher_id'] = $publisherIds['LPPM Universitas Teknologi Indonesia'] ?? 2;
            }

            Journal::firstOrCreate(
                ['name' => $journalData['name']],
                $journalData
            );
        }

        $this->command->info('Created ' . count($journals) . ' journals.');

        $this->command->info('Creating default LOA template...');

        // Create default LOA template
        $templateHtml = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Letter of Acceptance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 40px; }
        .content { margin: 20px 0; }
        .signature { margin-top: 50px; text-align: right; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #666; }
        .qr-code { text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LETTER OF ACCEPTANCE</h1>
        <h2>{{publisher_name}}</h2>
    </div>
    
    <div class="content">
        <p>To: {{author_name}}<br>
        Email: {{author_email}}</p>
        
        <p>Dear {{author_name}},</p>
        
        <p>We are pleased to inform you that your manuscript entitled "<strong>{{title}}</strong>" has been accepted for publication in our journal.</p>
        
        <p><strong>Publication Details:</strong></p>
        <ul>
            <li>Journal: {{journal_name}}</li>
            <li>Volume: {{volume}}</li>
            <li>Issue: {{issue}}</li>
            <li>Publication Date: {{publication_date}}</li>
        </ul>
        
        <p>Verification Code: <strong>{{verification_code}}</strong></p>
        
        <div class="qr-code">
            <p>Scan QR Code to verify this document:</p>
            {{qr_code_image}}
        </div>
    </div>
    
    <div class="signature">
        <p>Best regards,</p>
        <br><br>
        <p><strong>Editorial Board</strong><br>
        {{publisher_name}}<br>
        Date: {{current_date}}</p>
    </div>
    
    <div class="footer">
        <p>This document is digitally verified. For verification, visit: {{verification_url}}</p>
    </div>
</body>
</html>';

        LoaTemplate::firstOrCreate(
            ['name' => 'Default LOA Template'],
            [
                'name' => 'Default LOA Template',
                'description' => 'Template LOA standar untuk semua jurnal',
                'template_html' => $templateHtml,
                'is_active' => true,
                'language' => 'en',
                'template_variables' => json_encode([
                    'publisher_name', 'author_name', 'author_email', 'title',
                    'journal_name', 'volume', 'issue', 'publication_date',
                    'verification_code', 'qr_code_image', 'current_date',
                    'verification_url'
                ])
            ]
        );

        $this->command->info('Created default LOA template.');

        $this->command->info('Creating default admin user...');

        // Create default admin user
        $defaultAdmin = [
            'name' => 'Super Administrator',
            'email' => 'admin@loasiptenan.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'role' => 'super_admin',
            'email_verified_at' => now()
        ];

        $user = User::firstOrCreate(
            ['email' => $defaultAdmin['email']],
            $defaultAdmin
        );

        if ($user->wasRecentlyCreated) {
            $this->command->info('Created default admin: admin@loasiptenan.com / admin123');
        } else {
            $this->command->info('Default admin already exists.');
        }

        $this->command->info('Default data seeding completed!');
    }
}
