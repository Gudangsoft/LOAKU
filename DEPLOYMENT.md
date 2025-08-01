# 🚀 LOA-KU Deployment Guide

## 📦 Quick Installation (Local Development)

### **Method 1: Download & Extract**

1. **Download ZIP** dari GitHub
2. **Extract** ke folder `htdocs` (XAMPP) atau `www` (LARAGON)
3. **Rename folder** menjadi `loaku`
4. **Buka terminal** di folder project:

```bash
cd C:\xampp\htdocs\loaku

# Install dependencies
composer install

# Setup environment
copy .env.example .env
php artisan key:generate

# Setup database (edit .env first)
php artisan migrate --seed

# Start server
php artisan serve
```

5. **Akses:** http://127.0.0.1:8000

---

### **Method 2: Git Clone**

```bash
# Clone repository
git clone https://github.com/Gudangsoft/LOAKU.git
cd LOAKU

# Install dan setup
composer install
cp .env.example .env
php artisan key:generate

# Database setup
# Edit .env file terlebih dahulu
php artisan migrate --seed

# Run server
php artisan serve
```

---

## 🗃️ Database Configuration

### **1. Buat Database:**
```sql
CREATE DATABASE loa_management;
```

### **2. Edit .env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loa_management
DB_USERNAME=root
DB_PASSWORD=

APP_NAME="LOA-KU"
APP_URL=http://localhost:8000
```

### **3. Migrasi:**
```bash
php artisan migrate --seed
```

---

## 🔧 Production Deployment

### **Shared Hosting (cPanel):**

1. **Upload files** via File Manager atau FTP
2. **Extract** di folder `public_html`
3. **Move** isi folder `public` ke root `public_html`
4. **Edit** `index.php` di root:
   ```php
   require __DIR__.'/vendor/autoload.php';
   $app = require_once __DIR__.'/bootstrap/app.php';
   ```
5. **Import database** via phpMyAdmin
6. **Edit .env** dengan setting hosting

### **VPS/Cloud Server:**

```bash
# Install dependencies
sudo apt update
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql composer

# Clone & setup
git clone https://github.com/Gudangsoft/LOAKU.git /var/www/loaku
cd /var/www/loaku
composer install --no-dev
php artisan key:generate
php artisan migrate --seed

# Set permissions
sudo chown -R www-data:www-data /var/www/loaku
sudo chmod -R 755 /var/www/loaku/storage

# Configure nginx
sudo nano /etc/nginx/sites-available/loaku
```

---

## 🛠️ Troubleshooting

### **Common Issues:**

**1. Composer Error:**
```bash
composer install --ignore-platform-reqs
```

**2. Permission Error:**
```bash
# Windows
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T

# Linux/Mac
chmod -R 775 storage bootstrap/cache
```

**3. Database Connection Error:**
- Pastikan MySQL berjalan
- Cek kredensial di `.env`
- Pastikan database sudah dibuat

**4. Key Not Generated:**
```bash
php artisan key:generate --force
```

**5. Migration Error:**
```bash
php artisan migrate:fresh --seed
```

---

## 📱 Testing

### **Admin Access:**
- URL: http://localhost:8000/admin
- Email: `admin@example.com`
- Password: `password`

### **Test Features:**
1. ✅ Request LOA form
2. ✅ Admin dashboard
3. ✅ Approve/reject requests
4. ✅ PDF generation
5. ✅ QR code verification
6. ✅ Search functionality

---

## 🔄 Updates

### **Update dari GitHub:**
```bash
git pull origin main
composer install
php artisan migrate
php artisan config:clear
```

### **Manual Update:**
1. Download ZIP terbaru
2. Replace files (jangan .env)
3. Run `composer install`
4. Run `php artisan migrate`

---

## 📞 Support

**Issues:** https://github.com/Gudangsoft/LOAKU/issues
**Wiki:** https://github.com/Gudangsoft/LOAKU/wiki
**Discussions:** https://github.com/Gudangsoft/LOAKU/discussions
