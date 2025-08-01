-- Script SQL untuk memperbaiki database LOA Management
-- Jalankan script ini di phpMyAdmin atau MySQL command line

USE loa_management;

-- Buat tabel loa_validated jika belum ada
CREATE TABLE IF NOT EXISTS `loa_validated` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `loa_request_id` bigint(20) unsigned NOT NULL,
  `loa_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_path_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_path_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loa_validated_loa_code_unique` (`loa_code`),
  KEY `loa_validated_loa_request_id_foreign` (`loa_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Buat tabel migrations jika belum ada
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tambahkan record migration
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES 
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2025_01_01_000001_create_publishers_table', 1),
('2025_01_01_000002_create_journals_table', 1),
('2025_01_01_000003_create_loa_requests_table', 1),
('2025_01_01_000004_create_loa_validated_table', 1),
('2025_08_01_000001_add_is_admin_to_users_table', 1);

-- Tambahkan foreign key constraint jika belum ada
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = 'loa_management' 
    AND TABLE_NAME = 'loa_validated' 
    AND CONSTRAINT_NAME = 'loa_validated_loa_request_id_foreign');

SET @sql = IF(@constraint_exists = 0, 
    'ALTER TABLE `loa_validated` ADD CONSTRAINT `loa_validated_loa_request_id_foreign` FOREIGN KEY (`loa_request_id`) REFERENCES `loa_requests` (`id`) ON DELETE CASCADE', 
    'SELECT "Foreign key constraint already exists" as status');

PREPARE statement FROM @sql;
EXECUTE statement;
DEALLOCATE PREPARE statement;

-- Tampilkan status tabel
SELECT 'Database repair completed successfully!' as status;
SHOW TABLES;
