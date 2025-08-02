-- SQL Commands untuk menambahkan kolom website ke tabel publishers

-- 1. Cek struktur tabel publishers saat ini
DESCRIBE publishers;

-- 2. Tambahkan kolom website setelah kolom email
ALTER TABLE publishers 
ADD COLUMN website VARCHAR(255) NULL 
AFTER email;

-- 3. Verifikasi kolom sudah ditambahkan
DESCRIBE publishers;

-- 4. (Opsional) Update existing records jika diperlukan
-- UPDATE publishers SET website = 'https://example.com' WHERE id = 1;

-- 5. Cek data dalam tabel
SELECT id, name, email, website FROM publishers LIMIT 5;
