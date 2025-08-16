# 📦 DEPLOYMENT PACKAGE: SUPPORT MANAGEMENT SYSTEM
## File-file yang harus di-upload ke production loa.siptenan.org

### 🗂️ STRUKTUR DEPLOYMENT

#### 1. Database Migration
```
database/migrations/2025_08_16_040204_create_supports_table.php
```

#### 2. Model
```  
app/Models/Support.php
```

#### 3. Controller
```
app/Http/Controllers/Admin/SupportController.php
```

#### 4. Views (Folder Lengkap)
```
resources/views/admin/supports/
├── index.blade.php
├── create.blade.php  
├── edit.blade.php
└── show.blade.php
```

#### 5. Layout Update
```
resources/views/layouts/app.blade.php (updated footer section)
```

#### 6. Routes Update
```
routes/web.php (tambahan admin support routes)
```

---

### 🚀 LANGKAH DEPLOYMENT KE PRODUCTION

#### A. Upload Files via cPanel File Manager:
1. **Login ke cPanel loa.siptenan.org**
2. **Buka File Manager**
3. **Masuk ke folder website** (biasanya public_html atau domain folder)
4. **Upload file sesuai struktur folder**:

```
app/
├── Models/Support.php
└── Http/Controllers/Admin/SupportController.php

database/migrations/2025_08_16_040204_create_supports_table.php

resources/views/
├── layouts/app.blade.php
└── admin/supports/ (buat folder supports, upload semua file view)

routes/web.php (replace atau edit manual)
```

#### B. Jalankan Commands di Terminal cPanel:
```bash
# 1. Masuk ke folder website
cd /home/username/public_html

# 2. Jalankan migration
php artisan migrate

# 3. Buat storage link
php artisan storage:link

# 4. Clear cache
php artisan config:clear
php artisan route:clear  
php artisan view:clear

# 5. Set permission storage (jika perlu)
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

#### C. Buat Folder Storage untuk Logo:
```bash
# Pastikan folder ada
mkdir -p storage/app/public/supports
chmod 755 storage/app/public/supports
```

---

### 📋 VERIFICATION CHECKLIST

Setelah upload, test hal berikut:

#### ✅ Admin Panel:
- [ ] Akses: `https://loa.siptenan.org/admin/supports`
- [ ] Form tambah support working  
- [ ] Upload logo berhasil tersimpan
- [ ] Edit dan hapus support working
- [ ] Toggle status working

#### ✅ Frontend:
- [ ] Buka homepage: `https://loa.siptenan.org`
- [ ] Scroll ke footer, pastikan section "Supported By" ada
- [ ] Test tambah support di admin, cek muncul di footer
- [ ] Test link logo ke website support

---

### 🔧 TROUBLESHOOTING PRODUCTION

#### Error "Class Support not found":
```bash
composer dump-autoload
php artisan config:clear
```

#### Error "Table supports doesn't exist":
```bash
php artisan migrate
# Atau manual run SQL di phpMyAdmin
```

#### Logo tidak tampil:
```bash
php artisan storage:link
chmod -R 755 storage
```

#### Error 500 saat akses admin/supports:
- Cek log error di cPanel Error Logs
- Pastikan semua file controller dan view sudah ter-upload
- Cek permission file dan folder

---

### 📝 MANUAL ROUTES UPDATE

Jika tidak bisa replace routes/web.php, tambahkan manual di bagian admin routes:

```php
// Di dalam Route::prefix('admin')->name('admin.')->group(function() {
    Route::resource('supports', SupportController::class);
    Route::patch('supports/{support}/toggle', [SupportController::class, 'toggle'])
          ->name('supports.toggle');
```

---

### 🎯 QUICK DEPLOYMENT SCRIPT

Jika ada akses SSH, bisa gunakan script ini:

```bash
#!/bin/bash
# deployment-support.sh

echo "🚀 Deploying Support Management System..."

# Backup current files
cp routes/web.php routes/web.php.backup
cp resources/views/layouts/app.blade.php resources/views/layouts/app.blade.php.backup

# Run migration
php artisan migrate --force

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create storage link
php artisan storage:link

echo "✅ Deployment completed!"
echo "📝 Test URL: https://loa.siptenan.org/admin/supports"
```

---

### 🎉 DEPLOYMENT READY!

Semua file sudah siap untuk di-upload ke **loa.siptenan.org**. 

**Next Steps:**
1. 📤 Upload files via cPanel File Manager
2. 🔄 Run migration commands
3. ✅ Test admin panel dan frontend
4. 🎯 Add support logos via admin

**Production URL setelah deploy:**
- Admin: `https://loa.siptenan.org/admin/supports`
- Frontend: `https://loa.siptenan.org` (logo tampil di footer)
