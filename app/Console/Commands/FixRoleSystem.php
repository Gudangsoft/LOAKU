<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class FixRoleSystem extends Command
{
    protected $signature = 'fix:role-system';
    protected $description = 'Fix and create role system tables if missing';

    public function handle()
    {
        $this->info('🔧 Fixing Role System Tables...');

        try {
            // Create roles table
            if (!Schema::hasTable('roles')) {
                $this->info('Creating roles table...');
                DB::statement("
                    CREATE TABLE `roles` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `name` varchar(255) NOT NULL UNIQUE,
                        `display_name` varchar(255) NOT NULL,
                        `description` text,
                        `permissions` json,
                        `is_active` tinyint(1) NOT NULL DEFAULT 1,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
                $this->info('✅ Roles table created');
            } else {
                $this->info('✅ Roles table already exists');
            }

            // Create role_users table
            if (!Schema::hasTable('role_users')) {
                $this->info('Creating role_users table...');
                DB::statement("
                    CREATE TABLE `role_users` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `user_id` bigint(20) unsigned NOT NULL,
                        `role_id` bigint(20) unsigned NOT NULL,
                        `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `expires_at` timestamp NULL DEFAULT NULL,
                        `additional_permissions` json,
                        `is_active` tinyint(1) NOT NULL DEFAULT 1,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `role_users_user_id_role_id_unique` (`user_id`,`role_id`),
                        KEY `role_users_user_id_foreign` (`user_id`),
                        KEY `role_users_role_id_foreign` (`role_id`),
                        CONSTRAINT `role_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
                        CONSTRAINT `role_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
                $this->info('✅ Role_users table created');
            } else {
                $this->info('✅ Role_users table already exists');
            }

            // Create permissions table
            if (!Schema::hasTable('permissions')) {
                $this->info('Creating permissions table...');
                DB::statement("
                    CREATE TABLE `permissions` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `name` varchar(255) NOT NULL UNIQUE,
                        `display_name` varchar(255) NOT NULL,
                        `description` text,
                        `category` varchar(255) NOT NULL DEFAULT 'general',
                        `is_system` tinyint(1) NOT NULL DEFAULT 0,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
                $this->info('✅ Permissions table created');
            } else {
                $this->info('✅ Permissions table already exists');
            }

            // Create role_permissions table
            if (!Schema::hasTable('role_permissions')) {
                $this->info('Creating role_permissions table...');
                DB::statement("
                    CREATE TABLE `role_permissions` (
                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `role_id` bigint(20) unsigned NOT NULL,
                        `permission_id` bigint(20) unsigned NOT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
                        KEY `role_permissions_role_id_foreign` (`role_id`),
                        KEY `role_permissions_permission_id_foreign` (`permission_id`),
                        CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
                        CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                ");
                $this->info('✅ Role_permissions table created');
            } else {
                $this->info('✅ Role_permissions table already exists');
            }

            $this->info('🎉 Role system tables fixed successfully!');

            // Now seed the data
            $this->info('🌱 Seeding role system data...');
            $this->call('db:seed', ['--class' => 'RolePermissionSeeder']);

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error fixing role system: ' . $e->getMessage());
            return 1;
        }
    }
}
