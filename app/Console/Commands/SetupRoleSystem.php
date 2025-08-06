<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Account;
use App\Models\Publisher;
use Illuminate\Support\Facades\Hash;

class SetupRoleSystem extends Command
{
    protected $signature = 'setup:roles';
    protected $description = 'Setup role-based access control system';

    public function handle()
    {
        $this->info('🚀 Setting up Role-Based Access Control System...');

        try {
            // Check if accounts table exists
            if (!Schema::hasTable('accounts')) {
                $this->info('📊 Creating accounts table...');
                
                Schema::create('accounts', function (Blueprint $table) {
                    $table->id();
                    $table->string('username')->unique();
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->string('full_name');
                    $table->string('phone', 20)->nullable();
                    $table->enum('role', ['administrator', 'publisher'])->default('publisher');
                    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                    $table->foreignId('publisher_id')->nullable()->constrained()->cascadeOnDelete();
                    $table->json('permissions')->nullable();
                    $table->string('avatar')->nullable();
                    $table->rememberToken();
                    $table->timestamps();
                });

                $this->info('✅ Accounts table created successfully!');
            } else {
                $this->info('📊 Accounts table already exists.');
            }

            // Add columns to users table if not exist
            if (!Schema::hasColumn('users', 'role')) {
                $this->info('👤 Adding role columns to users table...');
                
                Schema::table('users', function (Blueprint $table) {
                    $table->string('role', 50)->default('user');
                    $table->boolean('is_admin')->default(false);
                });

                $this->info('✅ Users table updated successfully!');
            }

            // Create test publisher
            $this->info('🏢 Creating test publisher...');
            $publisher = Publisher::updateOrCreate(
                ['name' => 'Test Publisher Corp'],
                [
                    'email' => 'publisher@test.com',
                    'phone' => '+62 812-3456-7890',
                    'address' => 'Jakarta, Indonesia',
                    'website' => 'https://testpublisher.com'
                ]
            );

            // Create admin account
            $this->info('👑 Creating administrator account...');
            $adminAccount = Account::updateOrCreate(
                ['email' => 'admin@loaku.com'],
                [
                    'username' => 'admin',
                    'full_name' => 'Super Administrator',
                    'password' => Hash::make('admin123'),
                    'role' => 'administrator',
                    'status' => 'active',
                    'permissions' => [
                        'manage_users',
                        'manage_accounts',
                        'manage_publishers',
                        'manage_journals',
                        'manage_loa_requests',
                        'manage_templates',
                        'view_analytics',
                        'system_settings'
                    ]
                ]
            );

            // Create publisher account
            $this->info('📚 Creating publisher account...');
            $publisherAccount = Account::updateOrCreate(
                ['email' => 'publisher@test.com'],
                [
                    'username' => 'publisher1',
                    'full_name' => 'Test Publisher',
                    'password' => Hash::make('publisher123'),
                    'role' => 'publisher',
                    'status' => 'active',
                    'publisher_id' => $publisher->id,
                    'permissions' => [
                        'manage_journals',
                        'manage_loa_requests',
                        'view_publisher_analytics'
                    ]
                ]
            );

            $this->info('');
            $this->info('🎉 Role system setup completed successfully!');
            $this->info('');
            $this->info('📋 Default Accounts Created:');
            $this->info('');
            $this->info('👑 Administrator Account:');
            $this->info('   Email: admin@loaku.com');
            $this->info('   Password: admin123');
            $this->info('   Role: Administrator');
            $this->info('');
            $this->info('📚 Publisher Account:');
            $this->info('   Email: publisher@test.com');
            $this->info('   Password: publisher123');
            $this->info('   Role: Publisher');
            $this->info('');
            $this->info('🌐 You can now login at: /admin/login');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error during setup: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            return 1;
        }
    }
}
