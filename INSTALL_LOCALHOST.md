# 🏠 CARA INSTALL LOA-KU DI LOCALHOST

## 🎯 Quick Start Guide

### 1. Download Project
```bash
# Clone dari GitHub
git clone https://github.com/Gudangsoft/LOAKU.git
cd LOAKU

# ATAU download ZIP backup
# Extract LOAKU-Backup-[tanggal].zip ke folder LOAKU
```

### 2. Install Dependencies
```bash
# Install Composer dependencies
composer install

# Install NPM dependencies  
npm install

# Build assets
npm run build
```

### 3. Environment Setup
```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loa_ku_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Database Setup
```bash
# Create database (di phpMyAdmin atau MySQL)
CREATE DATABASE loa_ku_db;

# Run migrations
php artisan migrate

# Run seeders (data demo)
php artisan db:seed
```

### 6. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set permissions (jika perlu)
chmod -R 775 storage bootstrap/cache
```

### 7. Start Development Server
```bash
php artisan serve
```

## 🌐 Akses Aplikasi

- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

### Default Admin Login:
- **Email**: admin@admin.com  
- **Password**: password

## 🛠️ Requirements

- **PHP**: >= 8.2
- **Composer**: Latest version
- **Node.js**: >= 18.x
- **MySQL**: >= 8.0
- **Web Server**: Apache/Nginx (untuk production)

## 🔧 Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Error: Database connection
1. Pastikan MySQL running
2. Cek credentials di `.env`
3. Create database manual jika perlu

### Error: Storage permissions
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Error: NPM dependencies
```bash
npm cache clean --force
npm install
npm run build
```

## 📚 Fitur Utama

1. **User Registration**: Pendaftaran LOA
2. **Admin Dashboard**: Kelola requests & data
3. **QR Verification**: Verifikasi sertifikat
4. **PDF Generation**: Sertifikat bilingual
5. **Search System**: Cari LOA approved

## 🎊 Ready to Go!

Setelah setup selesai, Anda bisa:
- Akses website di http://localhost:8000
- Login admin di http://localhost:8000/admin
- Test semua fitur LOA management

**Happy Coding! 🚀**
