# URGENT FIX untuk loa.siptenan.org - Error 419 PAGE EXPIRED

## 🚨 Masalah: Error 419 PAGE EXPIRED

Error ini terjadi karena:
- CSRF token expired/invalid
- Session storage bermasalah
- Cache Laravel corrupt
- Application key missing/berubah

## 🔧 SOLUSI CEPAT:

### Via SSH/Terminal Server:
```bash
# Login ke server
ssh username@loa.siptenan.org

# Navigate ke folder Laravel
cd /home/wwwroot/LOAKU

# Jalankan fix commands:
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan session:flush
php artisan key:generate --force

# Set permissions
chmod -R 777 storage/
chmod -R 755 bootstrap/cache/

# Cache ulang
php artisan config:cache
php artisan route:cache
```

### Via cPanel File Manager:
1. **Login cPanel** hosting loa.siptenan.org
2. **File Manager** → Navigate ke folder LOAKU
3. **Terminal** (jika ada) → jalankan commands di atas
4. **Atau manual:**
   - Delete folder `storage/framework/cache/*`
   - Delete folder `storage/framework/sessions/*` 
   - Delete folder `storage/framework/views/*`
   - Set permissions folder `storage/` ke 777

### Via Upload Script:
1. **Upload** file `fix_419_error.sh` ke server
2. **Set executable:** `chmod +x fix_419_error.sh`
3. **Run:** `./fix_419_error.sh`

## 🧪 Test Setelah Fix:
- https://loa.siptenan.org/admin/login
- https://loa.siptenan.org/
- Clear browser cache (Ctrl+F5)
- Try incognito window

## 📱 Alternatif Darurat:
Jika masih error, coba akses:
- https://loa.siptenan.org/login (general login)
- https://loa.siptenan.org/home

## 🔍 Debug Info:
Jika masih bermasalah, check:
- Error logs di `storage/logs/laravel.log`
- Web server error logs
- PHP error logs
- .env file ada dan valid

---
**Priority:** URGENT - Website down
**ETA:** 5-10 minutes jika ada akses server
