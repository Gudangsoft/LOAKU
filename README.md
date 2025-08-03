# LOA-SIPTENAN Management System
(EKO SISWANTO)

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-orange.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)


## 📋 Deskripsi Project

**LOA-SIPTENAN** adalah sistem manajemen Letter of Acceptance (LOA) untuk artikel jurnal ilmiah berbasis web. Sistem ini memungkinkan pengelolaan permohonan LOA secara digital dengan fitur verifikasi QR Code dan dashboard admin yang komprehensif.

## ✨ Fitur Utama

### 🌐 **Fitur Publik:**
- **Request LOA** - Form permohonan LOA dengan validasi lengkap
- **Search & Download LOA** - Pencarian dan unduh sertifikat LOA yang telah disetujui
- **QR Code Verification** - Verifikasi keaslian LOA menggunakan QR Code
- **Responsive Design** - Tampilan modern yang responsif di semua perangkat

### 👨‍💼 **Fitur Admin:**
- **Dashboard** - Statistik dan overview sistem
- **LOA Request Management** - Approve/reject permohonan LOA
- **Journal Management** - CRUD data jurnal
- **Publisher Management** - CRUD data penerbit
- **PDF Generator** - Generate sertifikat LOA bilingual (Indonesia & English)
- **QR Code Generator** - Generate QR Code untuk verifikasi

### 🔐 **Authentication:**
- **Admin Login System** - Sistem login khusus admin
- **Role-based Access** - Akses berdasarkan role pengguna
- **Session Management** - Pengelolaan sesi yang aman

## 🛠️ Tech Stack

- **Backend:** Laravel 12.x (PHP)
- **Database:** MySQL 8.0+
- **Frontend:** Blade Templates + Bootstrap 5.3
- **PDF Generation:** DomPDF
- **QR Code:** Google Charts API + QR Server API
- **Icons:** Font Awesome 6

## 🚀 Instalasi di Localhost

### **Prasyarat:**
- XAMPP/LARAGON (Apache + MySQL + PHP 8.1+)
- Composer
- Git (optional)

### **Langkah Instalasi:**

#### 1. **Clone Repository**
```bash
git clone https://github.com/Gudangsoft/LOAKU.git
cd LOAKU
```

#### 2. **Install Dependencies**
```bash
composer install
```

#### 3. **Konfigurasi Environment**
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. **Konfigurasi Database**
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loa_management
DB_USERNAME=root
DB_PASSWORD=
```

#### 5. **Buat Database**
- Buka phpMyAdmin (http://localhost/phpmyadmin)
- Buat database baru: `loa_management`

#### 6. **Migrasi Database**
```bash
php artisan migrate --seed
```

#### 7. **Jalankan Server**
```bash
php artisan serve
```

#### 8. **Akses Aplikasi**
- **Home:** http://127.0.0.1:8000
- **Admin:** http://127.0.0.1:8000/admin

## 👤 Default Admin Account

```
Email: admin@example.com
Password: password
```

**Atau buat admin baru:** http://127.0.0.1:8000/admin/create-admin

## 📖 Panduan Penggunaan

### **Untuk User:**
1. **Request LOA:** Klik "Request LOA" → Isi form → Submit
2. **Search LOA:** Klik "Search LOA" → Masukkan kriteria pencarian
3. **Verify LOA:** Gunakan QR Scanner atau input manual

### **For Admin:**
1. **Login Admin:** Klik dropdown "Admin" → Admin Login
2. **Kelola Request:** Dashboard → LOA Requests → Approve/Reject
3. **Kelola Master Data:** Journals & Publishers management
4. **Generate PDF:** Otomatis saat approve request

## 🔧 Troubleshooting

### **Error Database:**
```bash
# Reset database
php artisan migrate:fresh --seed

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### **Permission Error:**
```bash
# Set permission (Linux/Mac)
chmod -R 775 storage bootstrap/cache

# Windows - pastikan folder writable
```

## 👨‍💻 Developer

**Gudangsoft Team**
- GitHub: [@Gudangsoft](https://github.com/Gudangsoft)

---

⭐ **Jangan lupa beri bintang jika project ini membantu!** ⭐

## 🚀 Fitur Utama

### 👥 User Flow
- **Home Page**: Informasi singkat sistem dan statistik
- **Request LOA**: Form permintaan LOA dengan validasi lengkap
- **Cari LOA**: Pencarian LOA berdasarkan kode atau email
- **Verifikasi LOA**: Verifikasi keaslian dokumen LOA

### 🧑‍💼 Admin Flow
- **Dashboard Admin**: Statistik dan overview sistem
- **Manajemen Permintaan**: Approve/reject permintaan LOA
- **Data Jurnal**: CRUD manajemen jurnal ilmiah
- **Data Penerbit**: CRUD manajemen institusi penerbit

## 🛠️ Tech Stack

- **Framework**: Laravel 12.x
- **Database**: MySQL 8.0+
- **Frontend**: Bootstrap 5.3 + Font Awesome
- **PDF Generation**: DomPDF (bilingual support)
- **Image Processing**: Intervention Image
- **Language**: PHP 8.2+

## 📋 Requirements

- PHP 8.2 atau lebih tinggi
- MySQL 8.0 atau lebih tinggi  
- Composer
- Node.js & NPM (optional untuk asset compilation)

## ⚡ Quick Start

### 1. Clone & Install Dependencies
```bash
git clone <repository-url>
cd loa-management-system
composer install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Configuration
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loa_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Database Migration
```bash
php artisan migrate
```

### 5. Storage Setup
```bash
php artisan storage:link
```

### 6. Run Application
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 📊 Database Schema

### Tabel Utama
- `publishers` - Data penerbit/institusi
- `journals` - Data jurnal ilmiah  
- `loa_requests` - Permintaan LOA dari user
- `loa_validated` - LOA yang sudah divalidasi admin

### Relasi Database
- Publisher → hasMany → Journals
- Journal → hasMany → LoaRequests  
- LoaRequest → hasOne → LoaValidated

## 🎯 User Flow Detail

### 1. Request LOA
- Input: ID Artikel, Volume, Number, Bulan, Tahun, Judul, Author, Email, Jurnal
- Auto-generate No. Registrasi: `LOASIP.{ArticleID}.{Sequential}`
- Status: Pending → Admin Review

### 2. Admin Approval
- Review permintaan di dashboard admin
- Approve → Generate kode LOA otomatis
- Reject → Berikan alasan penolakan

### 3. Pencarian & Download
- Cari menggunakan kode LOA atau email
- Download PDF dalam 2 bahasa (Indonesia & English)
- Link ke website jurnal (jika tersedia)

### 4. Verifikasi
- Input kode LOA untuk verifikasi
- Tampilkan status: "Terverifikasi" atau "Tidak ditemukan"

## 🔧 Konfigurasi

### PDF Configuration
Untuk mengoptimalkan generasi PDF, edit `config/dompdf.php`:
```php
'options' => [
    'font_dir' => storage_path('fonts/'),
    'font_cache' => storage_path('fonts/'),
    'temp_dir' => sys_get_temp_dir(),
    'chroot' => realpath(base_path()),
    'enable_php' => false,
    'enable_javascript' => true,
    'enable_remote' => true,
]
```

### Storage Configuration
Pastikan folder storage memiliki permission yang benar:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 📝 API Endpoints

### Public Routes
- `GET /` - Home page
- `GET /request-loa` - Form request LOA
- `POST /request-loa` - Submit permintaan
- `GET /search-loa` - Pencarian LOA
- `POST /search-loa` - Proses pencarian
- `GET /verify-loa` - Form verifikasi
- `POST /verify-loa` - Proses verifikasi
- `GET /loa/print/{code}/{lang}` - Download PDF
- `GET /loa/view/{code}/{lang}` - View PDF

### Admin Routes (Prefix: `/admin`)
- `GET /` - Dashboard
- `GET /loa-requests` - List permintaan
- `POST /loa-requests/{id}/approve` - Approve LOA
- `POST /loa-requests/{id}/reject` - Reject LOA
- Resource routes untuk `journals` dan `publishers`

## 🎨 UI Components

### Styling
- **Bootstrap 5.3**: Framework CSS modern
- **Font Awesome 6**: Icon set lengkap
- **Custom CSS**: Gradient dan animasi modern
- **Responsive Design**: Mobile-first approach

### Tema Warna
- Primary: `#667eea` (Gradient blue)
- Secondary: `#764ba2` (Gradient purple)  
- Success: `#28a745`
- Warning: `#ffc107`
- Danger: `#dc3545`

## 🔒 Security Features

- **CSRF Protection**: Laravel built-in CSRF
- **Input Validation**: Comprehensive form validation
- **File Upload Security**: Image validation & storage
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Blade template escaping

## 📱 Mobile Responsive

Aplikasi fully responsive dengan breakpoints:
- Mobile: `< 576px`
- Tablet: `576px - 768px`  
- Desktop: `> 768px`

## 🧪 Testing

```bash
# Run unit tests
php artisan test

# Run specific test
php artisan test --filter=LoaRequestTest
```

## 🚀 Deployment

### Production Setup
1. Set environment ke production di `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize aplikasi:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Setup web server (Apache/Nginx) dan SSL certificate

## 🤝 Contributing

1. Fork repository
2. Buat feature branch: `git checkout -b feature/AmazingFeature`
3. Commit changes: `git commit -m 'Add AmazingFeature'`
4. Push branch: `git push origin feature/AmazingFeature`
5. Open Pull Request

## 📄 License

Proyek ini menggunakan [MIT License](LICENSE).

## 🆘 Support

Jika menemukan bug atau membutuhkan bantuan:
- Buat [Issue](../../issues) di GitHub
- Email: upport@loa.siptenan.org
- Dokumentasi: [Wiki](../../wiki)

## 📈 Roadmap

- [ ] Email notification system
- [ ] Multi-language support
- [ ] Advanced reporting & analytics  
- [ ] API untuk integrasi eksternal
- [ ] Mobile app (React Native)

---

**Dibuat dengan ❤️ menggunakan Laravel**

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
