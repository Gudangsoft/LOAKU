-- Add user_id column to publishers table if it doesn't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'publishers' 
     AND column_name = 'user_id' 
     AND table_schema = DATABASE()) > 0,
    'SELECT "Column publishers.user_id already exists" as msg',
    'ALTER TABLE `publishers` 
     ADD COLUMN `user_id` BIGINT UNSIGNED NULL AFTER `id`,
     ADD INDEX `publishers_user_id_index` (`user_id`),
     ADD CONSTRAINT `publishers_user_id_foreign` 
     FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add user_id column to journals table if it doesn't exist  
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE table_name = 'journals' 
     AND column_name = 'user_id' 
     AND table_schema = DATABASE()) > 0,
    'SELECT "Column journals.user_id already exists" as msg',
    'ALTER TABLE `journals` 
     ADD COLUMN `user_id` BIGINT UNSIGNED NULL AFTER `id`,
     ADD INDEX `journals_user_id_index` (`user_id`),
     ADD CONSTRAINT `journals_user_id_foreign` 
     FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update existing publishers to assign them to member users
UPDATE `publishers` p
JOIN `users` u ON u.role = 'member' AND u.email = 'member@test.com'
SET p.`user_id` = u.`id`
WHERE p.`name` LIKE '%Tech Innovation%';

UPDATE `publishers` p  
JOIN `users` u ON u.role = 'member' AND u.email = 'member2@test.com'
SET p.`user_id` = u.`id`
WHERE p.`name` LIKE '%Green Energy%';

-- Update existing journals to match their publishers
UPDATE `journals` j
JOIN `publishers` p ON j.`publisher_id` = p.`id`
SET j.`user_id` = p.`user_id`
WHERE p.`user_id` IS NOT NULL;
