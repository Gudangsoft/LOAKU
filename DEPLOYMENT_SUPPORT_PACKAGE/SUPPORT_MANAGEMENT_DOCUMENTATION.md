# 🎯 SUPPORT/SPONSOR MANAGEMENT SYSTEM
## Fitur Manajemen Logo Support/Sponsor untuk Website LOA SIPTENAN

### 📋 OVERVIEW
Fitur ini memungkinkan admin untuk mengelola logo-logo support/sponsor yang akan ditampilkan di footer website. Admin dapat menambah, edit, hapus, dan mengatur urutan tampilan logo.

### 🗂️ STRUKTUR FILE

#### 1. Database & Model
```
database/migrations/2025_08_16_040204_create_supports_table.php
app/Models/Support.php
```

#### 2. Controller & Routes
```
app/Http/Controllers/Admin/SupportController.php
routes/web.php (admin routes group)
```

#### 3. Views
```
resources/views/admin/supports/
├── index.blade.php      # Daftar support
├── create.blade.php     # Form tambah support
├── edit.blade.php       # Form edit support
└── show.blade.php       # Detail support
```

#### 4. Frontend Display
```
resources/views/layouts/app.blade.php (footer section)
```

---

### 🚀 FITUR YANG TERSEDIA

#### Admin Panel Features:
1. **📋 Daftar Support** - Tabel dengan DataTables, search, pagination
2. **➕ Tambah Support** - Form upload logo, nama, website, deskripsi
3. **✏️ Edit Support** - Update data dan ganti logo
4. **👁️ Detail Support** - Lihat informasi lengkap dan preview logo
5. **🔄 Toggle Status** - Aktif/nonaktif support dengan AJAX
6. **🗑️ Hapus Support** - Delete dengan konfirmasi SweetAlert
7. **📊 Urutan Tampil** - Atur urutan tampilan logo di frontend

#### Frontend Features:
1. **🏠 Display Footer** - Logo support tampil di footer website
2. **🔗 Link Support** - Logo bisa diklik ke website support
3. **📱 Responsive Design** - Tampilan mobile-friendly
4. **✨ Hover Effects** - Animasi saat mouse hover

---

### 📊 STRUKTUR DATABASE

```sql
CREATE TABLE supports (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,           -- Nama support/sponsor
    logo VARCHAR(255) NULL,               -- Nama file logo
    website VARCHAR(255) NULL,            -- URL website support
    description TEXT NULL,                -- Deskripsi support
    `order` INT NOT NULL DEFAULT 0,       -- Urutan tampil
    is_active TINYINT(1) NOT NULL DEFAULT 1, -- Status aktif
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

### 🎛️ CARA PENGGUNAAN

#### A. Menambah Support Baru:
1. Login sebagai admin
2. Masuk ke menu **Admin > Support Management**
3. Klik tombol **"Tambah Support"**
4. Isi form:
   - **Nama**: Nama organisasi/perusahaan
   - **Logo**: Upload gambar (JPG, PNG, GIF max 2MB)
   - **Website**: URL website (opsional)
   - **Deskripsi**: Keterangan singkat (opsional)
   - **Urutan**: Angka untuk urutan tampil (0 = paling awal)
5. Klik **"Simpan"**

#### B. Mengedit Support:
1. Di daftar support, klik tombol **"Edit"** (ikon pensil)
2. Update data yang diinginkan
3. Untuk ganti logo, upload file baru (logo lama akan terhapus otomatis)
4. Klik **"Update"**

#### C. Mengatur Status:
- Klik toggle switch **Aktif/Nonaktif** untuk show/hide logo di frontend
- Logo yang nonaktif tidak akan tampil di website

#### D. Mengatur Urutan:
- Edit support dan ubah nilai **"Urutan"**
- Angka kecil akan tampil lebih awal
- Contoh: Order 1, 2, 3 → tampil berurutan

---

### 🔧 KONFIGURASI TEKNIS

#### A. Routes yang Tersedia:
```php
Route::prefix('admin')->name('admin.')->group(function() {
    Route::resource('supports', SupportController::class);
    Route::patch('supports/{support}/toggle', [SupportController::class, 'toggle'])
          ->name('supports.toggle');
});
```

#### B. Model Scopes:
```php
// Hanya support yang aktif
Support::active()->get();

// Support terurut berdasarkan order
Support::ordered()->get();

// Kombinasi
Support::active()->ordered()->get();
```

#### C. Storage Configuration:
- Logo disimpan di: `storage/app/public/supports/`
- Akses public via: `public/storage/supports/`
- URL accessor: `$support->logo_url`

---

### 🎨 TAMPILAN FRONTEND

Logo support akan muncul di **footer website** dengan karakteristik:
- **Grid responsive**: 6 kolom di mobile, 3 di tablet, 2 di desktop
- **Ukuran logo**: Maksimal tinggi 60px, width auto
- **Hover effect**: Slight zoom dan opacity change
- **Link support**: Logo bisa diklik ke website jika ada
- **Conditional display**: Hanya muncul jika ada support aktif

### 📱 RESPONSIVE DESIGN
- **Mobile (xs)**: 2 logo per baris
- **Tablet (md)**: 4 logo per baris  
- **Desktop (lg+)**: 6 logo per baris

---

### 🔒 SECURITY FEATURES

1. **File Upload Validation**:
   - Hanya menerima: JPG, JPEG, PNG, GIF
   - Maksimal ukuran: 2MB
   - Filename di-randomize untuk keamanan

2. **Access Control**:
   - Hanya admin yang bisa akses
   - CSRF protection pada semua form
   - Model binding untuk parameter validation

3. **File Management**:
   - Logo lama otomatis terhapus saat update
   - Storage link untuk akses public file

---

### 🚨 TROUBLESHOOTING

#### Error "Storage link not found":
```bash
php artisan storage:link
```

#### Error "Class 'Support' not found":
- Pastikan model Support sudah dibuat
- Check namespace di controller: `use App\Models\Support;`

#### Logo tidak tampil di frontend:
- Cek storage link sudah dibuat
- Pastikan file ada di `storage/app/public/supports/`
- Verifikasi permission folder storage

#### Error 404 saat akses admin/supports:
- Pastikan routes sudah ditambahkan
- Clear route cache: `php artisan route:clear`

---

### 📋 TESTING CHECKLIST

#### ✅ Admin Panel:
- [ ] Bisa akses halaman daftar support
- [ ] Form tambah support working
- [ ] Upload logo berhasil  
- [ ] Edit support dan ganti logo
- [ ] Toggle status aktif/nonaktif
- [ ] Hapus support dengan konfirmasi
- [ ] DataTables search dan pagination
- [ ] Validation error handling

#### ✅ Frontend:
- [ ] Logo tampil di footer website
- [ ] Logo responsive di berbagai device
- [ ] Link ke website support working
- [ ] Hover effects smooth
- [ ] Urutan logo sesuai setting admin

---

### 🎯 DEPLOYMENT CHECKLIST

#### File yang harus di-upload ke production:
1. **Database**:
   - `database/migrations/2025_08_16_040204_create_supports_table.php`

2. **Models**:
   - `app/Models/Support.php`

3. **Controllers**:
   - `app/Http/Controllers/Admin/SupportController.php`

4. **Views**:
   - `resources/views/admin/supports/` (semua file)

5. **Routes**:
   - Update `routes/web.php` (bagian admin routes)

6. **Layout**:
   - `resources/views/layouts/app.blade.php` (updated footer)

#### Commands untuk production:
```bash
# Upload file, kemudian:
php artisan migrate
php artisan storage:link
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

### 🎉 FITUR SUPPORT/SPONSOR SIAP DIGUNAKAN!

**Admin bisa:**
- ✅ Mengelola logo support/sponsor
- ✅ Upload, edit, hapus logo
- ✅ Mengatur urutan dan status tampil
- ✅ Preview logo sebelum publish

**Visitor melihat:**
- ✅ Logo support di footer website
- ✅ Link ke website support
- ✅ Tampilan responsive dan menarik

**Next Step**: Upload ke production server **loa.siptenan.org** 🚀
