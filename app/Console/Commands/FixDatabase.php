<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixDatabase extends Command
{
    protected $signature = 'db:fix';
    protected $description = 'Fix missing database tables';

    public function handle()
    {
        $this->info('Fixing database issues...');
        
        try {
            // Check if loa_validated table exists
            if (!Schema::hasTable('loa_validated')) {
                $this->info('Creating loa_validated table...');
                
                DB::statement("
                    CREATE TABLE `loa_validated` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `loa_request_id` bigint(20) unsigned NOT NULL,
                        `loa_code` varchar(255) NOT NULL,
                        `pdf_path_id` varchar(255) DEFAULT NULL,
                        `pdf_path_en` varchar(255) DEFAULT NULL,
                        `verification_url` varchar(255) DEFAULT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `loa_validated_loa_code_unique` (`loa_code`),
                        KEY `loa_validated_loa_request_id_foreign` (`loa_request_id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
                
                $this->info('✓ loa_validated table created successfully');
            } else {
                $this->info('✓ loa_validated table already exists');
            }
            
            // Update migrations table if needed
            if (!DB::table('migrations')->where('migration', '2025_01_01_000004_create_loa_validated_table')->exists()) {
                DB::table('migrations')->insert([
                    'migration' => '2025_01_01_000004_create_loa_validated_table',
                    'batch' => 1
                ]);
                $this->info('✓ Migration record added');
            }
            
            $this->info('Database fix completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error fixing database: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
