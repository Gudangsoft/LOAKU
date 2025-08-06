-- Create accounts table for role-based access control
CREATE TABLE IF NOT EXISTS `accounts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password` varchar(255) NOT NULL,
    `full_name` varchar(255) NOT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `role` enum('administrator','publisher') NOT NULL DEFAULT 'publisher',
    `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
    `publisher_id` bigint(20) unsigned DEFAULT NULL,
    `permissions` json DEFAULT NULL,
    `avatar` varchar(255) DEFAULT NULL,
    `remember_token` varchar(100) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `accounts_username_unique` (`username`),
    UNIQUE KEY `accounts_email_unique` (`email`),
    KEY `accounts_publisher_id_foreign` (`publisher_id`),
    CONSTRAINT `accounts_publisher_id_foreign` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add role column to users table if not exists
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `role` varchar(50) DEFAULT 'user';
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `is_admin` tinyint(1) NOT NULL DEFAULT 0;

-- Insert default admin account
INSERT IGNORE INTO `accounts` (
    `username`, 
    `email`, 
    `password`, 
    `full_name`, 
    `role`, 
    `status`, 
    `permissions`,
    `created_at`, 
    `updated_at`
) VALUES (
    'admin',
    'admin@loaku.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Super Administrator',
    'administrator',
    'active',
    JSON_ARRAY('manage_users', 'manage_accounts', 'manage_publishers', 'manage_journals', 'manage_loa_requests', 'manage_templates', 'view_analytics', 'system_settings'),
    NOW(),
    NOW()
);

-- Insert default publisher account (if publisher exists)
INSERT IGNORE INTO `accounts` (
    `username`, 
    `email`, 
    `password`, 
    `full_name`, 
    `role`, 
    `status`, 
    `publisher_id`,
    `permissions`,
    `created_at`, 
    `updated_at`
) SELECT 
    'publisher1',
    'publisher@test.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Test Publisher',
    'publisher',
    'active',
    p.id,
    JSON_ARRAY('manage_journals', 'manage_loa_requests', 'view_publisher_analytics'),
    NOW(),
    NOW()
FROM publishers p 
WHERE p.name = 'Test Publisher Corp' 
LIMIT 1;
