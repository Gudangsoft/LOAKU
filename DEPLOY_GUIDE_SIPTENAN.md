# Panduan Deploy ke loa.siptenan.org

## File yang Diperbaiki dan Perlu Di-Update

### 1. File Utama yang Berubah:
- `resources/views/publisher/loa-requests/show.blade.php` - View LOA detail yang sudah diperbaiki
- `app/Http/Controllers/PublisherController.php` - Error handling diperbaiki  
- `routes/web.php` - Tambahan route debug
- `app/Http/Middleware/PublisherMiddleware.php` - Akses publisher diperbaiki

### 2. Cara Deploy ke Server:

#### Opsi A: Via Git Pull (Jika server sudah setup git)
```bash
# Login ke server via SSH/cPanel Terminal
cd /path/to/your/website/root

# Pull perubahan terbaru
git pull origin main

# Clear cache Laravel
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

#### Opsi B: Upload Manual via cPanel/FTP
Upload file-file berikut ke server:

1. **Upload file view yang baru:**
   - Upload `resources/views/publisher/loa-requests/show.blade.php` ke folder yang sama di server

2. **Upload controller yang diperbaiki:**
   - Upload `app/Http/Controllers/PublisherController.php` ke folder yang sama di server

3. **Upload middleware yang diperbaiki:**
   - Upload `app/Http/Middleware/PublisherMiddleware.php` ke folder yang sama di server

4. **Upload routes (opsional - untuk debug routes):**
   - Upload `routes/web.php` ke folder yang sama di server

#### Opsi C: Via cPanel File Manager
1. Login ke cPanel hosting Anda
2. Buka File Manager
3. Navigate ke folder website
4. Upload file-file yang sudah diperbaiki
5. Extract/replace file yang ada

### 3. Setelah Upload, Jalankan:
```bash
# Clear cache Laravel (via terminal/SSH)
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Atau via cPanel terminal jika tersedia
```

### 4. Test Setelah Deploy:
- Akses: http://loa.siptenan.org/publisher/loa-requests/2
- Pastikan halaman tidak blank lagi
- Test login sebagai publisher dan akses halaman LOA detail

### 5. Troubleshooting Jika Masih Blank:
1. Clear browser cache (Ctrl+F5)
2. Check file permissions di server (755 untuk folder, 644 untuk file)
3. Pastikan folder `storage/framework/views` writable (777)
4. Check error log di cPanel untuk error PHP

## File-File Penting yang Sudah Diperbaiki:

### show.blade.php (View LOA Detail)
- File: `resources/views/publisher/loa-requests/show.blade.php`
- Sudah dibuat ulang dengan HTML standar
- Tidak depend pada layout external yang bermasalah
- Styling built-in yang menarik

### PublisherController.php
- File: `app/Http/Controllers/PublisherController.php`  
- Error handling diperbaiki (tidak return JSON lagi)
- Debug logging ditambahkan

### PublisherMiddleware.php
- File: `app/Http/Middleware/PublisherMiddleware.php`
- Akses publisher diperbaiki untuk user dengan role tidak standar

## Kontak untuk Bantuan:
Jika ada masalah setelah deploy, hubungi tim teknis atau provide akses cPanel/SSH untuk bantuan lebih lanjut.

---
*Deploy guide generated: {{ date('d M Y H:i') }}*
