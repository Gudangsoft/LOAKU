<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberTestingSeeder extends Seeder
{
    /**
     * Run the database seeder for complete member testing environment.
     */
    public function run(): void
    {
        $this->command->info('🚀 Setting up complete member testing environment...');
        
        // 1. Create admin users
        $this->call(AdminUserSeeder::class);
        $this->command->info('✅ Admin users created');
        
        // 2. Create member users
        $this->call(MemberUserSeeder::class);
        $this->command->info('✅ Member users created');
        
        // 3. Create publishers
        $this->call(PublisherSeeder::class);
        $this->command->info('✅ Publishers created');
        
        // 4. Create journals
        $this->call(JournalSeeder::class);
        $this->command->info('✅ Journals created');
        
        // 5. Create member LOA requests
        $this->call(MemberLoaRequestSeeder::class);
        $this->command->info('✅ Member LOA requests created');
        
        // 6. Create validated LOAs for approved requests
        $this->call(MemberLoaValidatedSeeder::class);
        $this->command->info('✅ Validated LOAs created');
        
        $this->command->info('🎉 Member testing environment setup complete!');
        $this->command->line('');
        $this->command->info('Test accounts:');
        $this->command->line('📧 Admin: admin@loasiptenan.com / admin123');
        $this->command->line('📧 Member 1: member@test.com / member123');
        $this->command->line('📧 Member 2: member2@test.com / member123');
        $this->command->line('');
        $this->command->info('🌐 Access URLs:');
        $this->command->line('👨‍💼 Admin: http://localhost:8000/admin/login');
        $this->command->line('👤 Member: http://localhost:8000/admin/login (same login, redirects to member dashboard)');
    }
}
