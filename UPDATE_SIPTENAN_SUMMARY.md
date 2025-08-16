# 🚀 PANDUAN UPDATE WEBSITE LOA.SIPTENAN.ORG

## 📦 File yang Sudah Disiapkan:
- ✅ `loa_siptenan_update.zip` - File ZIP untuk upload ke server
- ✅ `DEPLOY_GUIDE_SIPTENAN.md` - Panduan lengkap deployment
- ✅ `deploy_siptenan/` - Folder berisi semua file yang diperbaiki

## 🔧 Yang Sudah Diperbaiki:
1. **Publisher LOA Detail Page** - Tidak blank lagi
2. **Controller Error Handling** - Diperbaiki
3. **Middleware Publisher** - Akses diperbaiki
4. **View Template** - Dibuat ulang dari nol

## 📋 Langkah Update ke Server:

### Cara 1: Upload Manual (Termudah)
1. **Download file:** `loa_siptenan_update.zip`
2. **Login ke cPanel** hosting loa.siptenan.org
3. **Buka File Manager**
4. **Navigate** ke folder root website Laravel
5. **Upload dan extract** file ZIP
6. **Jalankan** script `quick_update.sh` (atau manual clear cache)

### Cara 2: Via SSH (Jika Ada Akses)
```bash
# Upload file loa_siptenan_update.zip ke server
# Extract di folder Laravel root
unzip loa_siptenan_update.zip

# Jalankan update script
chmod +x quick_update.sh
./quick_update.sh
```

### Cara 3: Manual File Copy
Copy file-file ini ke server:
- `resources/views/publisher/loa-requests/show.blade.php`
- `app/Http/Controllers/PublisherController.php`
- `app/Http/Middleware/PublisherMiddleware.php`
- `routes/web.php`

Kemudian clear cache:
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

## 🧪 Test Setelah Update:
- **URL Test:** http://loa.siptenan.org/publisher/loa-requests/2
- **Expected:** Halaman menampilkan detail LOA dengan styling menarik
- **Not Expected:** Halaman blank

## 🆘 Troubleshooting:
Jika masih blank:
1. **Clear browser cache** (Ctrl+F5)
2. **Check file permissions** di server
3. **Check error logs** di storage/logs/
4. **Try incognito/private window**

## 📞 Need Help?
Jika butuh bantuan upload atau ada masalah, provide:
- cPanel access atau SSH access
- Error messages dari browser console
- Error logs dari server

---
**Status:** ✅ Ready for deployment
**Generated:** {{ date('d M Y H:i') }}
