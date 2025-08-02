# 📋 PANDUAN LENGKAP MEMBUAT LOA & GENERATE NOMOR LOA

## 🎯 **Flow Pembuatan LOA:**

### 1. **Author Mengajukan LOA Request**
```
URL: http://127.0.0.1:8000/request-loa
```
- Author mengisi form LOA request
- Data tersimpan dengan status "pending"
- Nomor registrasi otomatis dibuat (LOASIP.ArticleID.Sequential)

### 2. **Admin Approval Process**
```
URL: http://127.0.0.1:8000/admin/loa-requests
Login: admin@admin.com / admin
```

**Langkah-langkah:**
1. Login sebagai admin
2. Buka "Daftar Permintaan LOA"
3. Review LOA request yang status "pending"
4. Klik "Approve" untuk menyetujui
5. **Nomor LOA otomatis dibuat** saat approve!

### 3. **Format Nomor LOA:**
```
Format: LOA + YYYYMMDD + ArticleID + Sequential
Contoh: LOA2025080212101
        LOA20250802ART00101
```

## 🔧 **Cara Generate Nomor LOA untuk Request yang Sudah Ada:**

### Method 1: Via Route Generate
```
URL: http://127.0.0.1:8000/admin/generate-missing-loa
```
- Otomatis generate nomor LOA untuk semua request yang approved tapi belum ada nomor LOA
- Response JSON dengan daftar LOA yang dibuat

### Method 2: Via Admin Panel
1. Login admin: http://127.0.0.1:8000/admin/test-login
2. Buka LOA Requests: http://127.0.0.1:8000/admin/loa-requests
3. Untuk request yang "Belum dibuat", klik "Approve" atau "Generate LOA"

### Method 3: Manual via Code
```php
// Generate LOA untuk request tertentu
$request = LoaRequest::find($id);
$loaCode = LoaValidated::generateLoaCodeWithArticleId($request->article_id);

LoaValidated::create([
    'loa_request_id' => $request->id,
    'loa_code' => $loaCode,
    'verification_url' => route('loa.verify')
]);
```

## 📊 **Status LOA Request:**

| Status | Keterangan | Nomor LOA |
|--------|------------|-----------|
| `pending` | Menunggu approval admin | ❌ Belum ada |
| `approved` | Disetujui admin | ✅ **Auto generate** |
| `rejected` | Ditolak admin | ❌ Tidak ada |

## 🎨 **Cara Melihat LOA yang Sudah Dibuat:**

### For Admin:
- **All LOA Requests:** http://127.0.0.1:8000/admin/loa-requests
- **Validated LOAs:** http://127.0.0.1:8000/validated-loas

### For Public:
- **Search LOA:** http://127.0.0.1:8000/validated-loas
- **View LOA:** http://127.0.0.1:8000/loa/view/{loaCode}

## 🚀 **Quick Actions:**

### Generate LOA untuk Request yang Belum Ada Nomor:
```
http://127.0.0.1:8000/admin/generate-missing-loa
```

### Test LOA System:
```
http://127.0.0.1:8000/test-loa-create
```

### Admin Login:
```
URL: http://127.0.0.1:8000/admin/test-login
Email: admin@admin.com
Password: admin
```

## 📋 **Troubleshooting:**

### Problem: Nomor LOA Tidak Muncul
**Solution:**
1. Pastikan LOA request sudah di-approve admin
2. Jalankan: http://127.0.0.1:8000/admin/generate-missing-loa
3. Check di admin panel: http://127.0.0.1:8000/admin/loa-requests

### Problem: Cannot Access Admin
**Solution:**
1. Create admin user: http://127.0.0.1:8000/admin/create-admin
2. Login: http://127.0.0.1:8000/admin/test-login

### Problem: LOA Template Error  
**Solution:**
1. Run template seeder: `php artisan db:seed --class=LoaTemplateSeeder`
2. Check templates: http://127.0.0.1:8000/admin/loa-templates

---

**🎉 Summary: Nomor LOA otomatis dibuat saat admin approve request LOA!**
