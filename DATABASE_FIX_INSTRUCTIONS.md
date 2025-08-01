## Database Fix Instructions

The admin error is caused by a missing database table `loa_validated`. Follow these steps to fix it:

### Option 1: Manual Database Fix (Recommended)

1. **Open MySQL/phpMyAdmin or MySQL Command Line**
2. **Select the `loa_management` database**
3. **Run this SQL command:**

```sql
USE loa_management;

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
  KEY `loa_validated_loa_request_id_foreign` (`loa_request_id`),
  CONSTRAINT `loa_validated_loa_request_id_foreign` FOREIGN KEY (`loa_request_id`) REFERENCES `loa_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mark migration as completed
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES 
('2025_01_01_000004_create_loa_validated_table', 1);
```

### Option 2: Using Laravel Artisan (if working)

1. Open terminal/command prompt
2. Navigate to project folder: `cd "c:\Users\Admin\Desktop\LOA-SIPTENAN-PROJECT"`
3. Run: `php artisan migrate`
4. If that fails, try: `php artisan migrate:fresh --seed`

### Option 3: Using the Fix Script

1. Run the fix script: `php fix_database.php`

## After fixing the database:

1. **Start Laravel server:**
   ```bash
   php artisan serve --port=8000
   ```

2. **Access the application:**
   - Main site: http://127.0.0.1:8000/
   - Admin login: http://127.0.0.1:8000/admin/login
   - Create admin: http://127.0.0.1:8000/admin/create-admin

## The Error Details:

The error message shows:
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'loa_management.loa_validateds' doesn't exist
```

This happens when the LOA system tries to create approved LOA records but the required table is missing from the database.

## Verification:

After applying the fix, you should be able to:
- Access admin dashboard without errors
- Approve LOA requests
- Generate LOA certificates
- Use all admin functions properly
