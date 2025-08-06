# 🎬 **TUTORIAL VIDEO-LIKE: MENJALANKAN APLIKASI LOA MANAGEMENT**
*Step-by-Step Guide dengan Screenshots dan Penjelasan Detail*

## 📋 **DAFTAR ISI TUTORIAL**
1. [Persiapan Environment](#1-persiapan-environment)
2. [Setup Database](#2-setup-database)
3. [Konfigurasi Aplikasi](#3-konfigurasi-aplikasi)
4. [Menjalankan Server](#4-menjalankan-server)
5. [Setup Admin Account](#5-setup-admin-account)
6. [Navigasi Dashboard Admin](#6-navigasi-dashboard-admin)
7. [Manage Data Master](#7-manage-data-master)
8. [Proses LOA Request](#8-proses-loa-request)
9. [Testing Functionality](#9-testing-functionality)
10. [Troubleshooting](#10-troubleshooting)

---

## 🎯 **1. PERSIAPAN ENVIRONMENT**

### **🔧 Requirements Check**
```bash
# Cek PHP Version (minimal 8.1)
php --version

# Cek Composer
composer --version

# Cek MySQL/MariaDB
mysql --version

# Cek Node.js (optional untuk frontend build)
node --version
npm --version
```

**Screenshot Simulasi:**
```
PS D:\LARAVEL\LOAKU> php --version
PHP 8.2.12 (cli) (built: Oct 24 2023 21:15:15) (ZTS Visual C++ 2019 x64)

PS D:\LARAVEL\LOAKU> composer --version
Composer version 2.6.5 2023-10-06 10:11:52
```

### **📁 Project Structure Check**
```bash
# Masuk ke direktori project
cd D:\LARAVEL\LOAKU

# Cek struktur folder utama
dir
```

**Expected Output:**
```
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
vendor/
.env
artisan
composer.json
package.json
```

---

## 🗄️ **2. SETUP DATABASE**

### **Step 2.1: Buat Database MySQL**
```bash
# Login ke MySQL
mysql -u root -p

# Di MySQL prompt:
CREATE DATABASE loa_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SHOW DATABASES;
EXIT;
```

### **Step 2.2: Konfigurasi .env**
```bash
# Edit file .env
notepad .env
```

**Pastikan konfigurasi database benar:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loa_management
DB_USERNAME=root
DB_PASSWORD=
```

### **Step 2.3: Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (optional)
npm install
```

---

## ⚙️ **3. KONFIGURASI APLIKASI**

### **Step 3.1: Generate App Key**
```bash
php artisan key:generate
```

### **Step 3.2: Clear Cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### **Step 3.3: Run Migrations**
```bash
# Jalankan migrasi database
php artisan migrate

# Expected output:
# INFO  Running migrations.
# 2025_01_01_000001_create_publishers_table ................. DONE
# 2025_01_01_000002_create_journals_table .................. DONE
# 2025_01_01_000003_create_loa_requests_table .............. DONE
# ... dst
```

### **Step 3.4: Seed Database**
```bash
# Seed role system
php artisan db:seed --class=RolePermissionSeeder

# Seed default data
php artisan db:seed --class=DefaultDataSeeder
```

---

## 🚀 **4. MENJALANKAN SERVER**

### **Step 4.1: Start Laravel Server**
```bash
# Start server di port 8000
php artisan serve

# Expected output:
# INFO  Server running on [http://127.0.0.1:8000].
# Press Ctrl+C to stop the server
```

### **Step 4.2: Verify Server Running**
```bash
# Di browser baru atau curl:
curl http://127.0.0.1:8000

# Atau buka browser ke: http://127.0.0.1:8000
```

**Screenshot Simulasi - Home Page:**
```
🌐 LOA MANAGEMENT SYSTEM
===========================
✅ Server is running successfully!
✅ Database connected
✅ Welcome to LOA Management System

[Request LOA] [Search LOA] [Verify LOA]
```

---

## 👤 **5. SETUP ADMIN ACCOUNT**

### **Step 5.1: Access Create Admin Page**
```
URL: http://127.0.0.1:8000/admin/create-admin
```

**Screenshot Simulasi - Create Admin Interface:**
```
🎛️ ADMIN SETUP CONTROL PANEL
================================

┌─ QUICK ACTIONS ─────────────────────┐
│ [Create Default Super Admin]       │
│ [Check Existing Users]             │  
│ [Setup Role System]                │
└────────────────────────────────────┘

┌─ SYSTEM STATUS ────────────────────┐
│ 📊 Database: ✅ Connected         │
│ 👥 Users: 0 found                 │
│ 🔐 Role System: ⚠️ Not setup     │
└────────────────────────────────────┘
```

### **Step 5.2: Setup Role System**
**Klik tombol [Setup Role System]**

**Response Expected:**
```json
{
  "success": true,
  "message": "Role system has been set up successfully!",
  "setup_info": {
    "roles": ["Super Admin", "Administrator", "Member", "Viewer"],
    "roles_count": 4,
    "permissions_count": 26,
    "users_count": 0
  }
}
```

### **Step 5.3: Create Default Admin**
**Klik tombol [Create Default Super Admin]**

**Response Expected:**
```json
{
  "admin_user": {
    "email": "admin@loasiptenan.com",
    "password": "admin123"
  },
  "message": "Default admin created successfully!"
}
```

### **Step 5.4: Verify Users Created**
**Klik tombol [Check Existing Users]**

**Expected Display:**
```
👥 EXISTING USERS (1 found)
============================
┌─ Super Administrator ──────────────┐
│ 📧 admin@loasiptenan.com          │
│ 🔐 Super Admin                    │
│ ✅ Verified                       │
│ 🆔 ID: 1                          │
└────────────────────────────────────┘
```

---

## 🏠 **6. NAVIGASI DASHBOARD ADMIN**

### **Step 6.1: Login Admin**
```
URL: http://127.0.0.1:8000/admin/login
```

**Form Login:**
```
📧 Email: admin@loasiptenan.com
🔒 Password: admin123
[LOGIN]
```

### **Step 6.2: Dashboard Overview**
**After successful login - Dashboard:**

```
🎛️ ADMIN DASHBOARD
===================

┌─ STATISTICS ────────────────────────┐
│ 📊 Total LOA Requests: 0           │
│ ✅ Approved: 0                     │
│ ⏳ Pending: 0                      │
│ ❌ Rejected: 0                     │
└─────────────────────────────────────┘

┌─ QUICK ACTIONS ─────────────────────┐
│ [View LOA Requests]                 │
│ [Manage Publishers]                 │
│ [Manage Journals]                   │
│ [View Templates]                    │
└─────────────────────────────────────┘

📊 Recent Activity: No recent activity
👥 System Users: 1 admin user
🔐 Your Role: Super Administrator
```

### **Step 6.3: Navigation Menu**
```
🔹 SIDEBAR MENU:
├── 🏠 Dashboard
├── 📝 LOA Requests
├── 🏢 Publishers  
├── 📚 Journals
├── 📄 Templates
├── 👥 Users (Super Admin only)
└── ⚙️ Settings
```

---

## 🏢 **7. MANAGE DATA MASTER**

### **Step 7.1: Manage Publishers**
```
URL: http://127.0.0.1:8000/admin/publishers
```

**Publishers List Page:**
```
🏢 PUBLISHERS MANAGEMENT
=========================

[+ Add New Publisher]    [🔍 Search] [📊 Export]

┌─ EXISTING PUBLISHERS ───────────────┐
│ 1. LP2M Universitas Siber Malang   │
│    📧 lp2m@unisma.ac.id            │
│    🌐 https://unisma.ac.id         │
│    [Edit] [Delete] [View]          │
│                                     │
│ 2. LPPM Universitas Teknologi ID   │  
│    📧 lppm@uti.ac.id               │
│    🌐 https://uti.ac.id            │
│    [Edit] [Delete] [View]          │
└─────────────────────────────────────┘
```

### **Step 7.2: Add New Publisher**
**Klik [+ Add New Publisher]**

**Form Publisher:**
```
📝 CREATE NEW PUBLISHER
========================
Publisher Name: [_________________]
Email: [_____________________]
Phone: [_____________________]  
Address: [___________________]
Website: [___________________]
Description: [_______________]

☑️ Active Publisher

[Save Publisher] [Cancel]
```

### **Step 7.3: Manage Journals**
```
URL: http://127.0.0.1:8000/admin/journals
```

**Journals List:**
```
📚 JOURNALS MANAGEMENT
======================

[+ Add New Journal]    [🔍 Search] [📊 Export]

┌─ EXISTING JOURNALS ─────────────────┐
│ 1. Menulis: Jurnal Ilmu Komputer   │
│    📰 ISSN: 2798-5903              │
│    🏢 LP2M Universitas Siber       │
│    📊 Status: ✅ Active            │
│    [Edit] [Delete] [View]          │
│                                     │
│ 2. Jurnal Teknologi & Sistem Info  │
│    📰 ISSN: 2621-2579              │  
│    🏢 LPPM UTI                     │
│    📊 Status: ✅ Active            │
│    [Edit] [Delete] [View]          │
└─────────────────────────────────────┘
```

---

## 📝 **8. PROSES LOA REQUEST**

### **Step 8.1: LOA Requests Overview**
```
URL: http://127.0.0.1:8000/admin/loa-requests
```

**LOA Requests Dashboard:**
```
📝 LOA REQUESTS MANAGEMENT
===========================

📊 SUMMARY:
- Total Requests: 0
- Pending Review: 0  
- Approved: 0
- Rejected: 0

[🔍 Search Requests] [📊 Export Data]

┌─ FILTER & SORT ─────────────────────┐
│ Status: [All ▼] [Pending ▼]        │
│ Journal: [All Journals ▼]          │
│ Date Range: [From] - [To]          │
│ [Apply Filter]                     │
└─────────────────────────────────────┘

📋 REQUEST LIST:
(No requests found - system ready to receive submissions)
```

### **Step 8.2: Simulasi LOA Request**
**Create sample LOA request via public form:**

```
URL: http://127.0.0.1:8000/ (Public home page)
Click [Request LOA]
```

**LOA Request Form (Public):**
```
📄 LOA REQUEST FORM
===================

Article Information:
- Title: [_________________________]
- Authors: [______________________]
- Author Email: [_________________]

Publication Details:  
- Journal: [Select Journal ▼]
- Volume: [____] Number: [____]
- Publication Date: [____________]

Submission Files:
- Article PDF: [Choose File]
- Supporting Docs: [Choose File]

[Submit LOA Request] [Clear Form]
```

### **Step 8.3: Process LOA Request (Admin)**
**Back to admin panel - review request:**

```
📝 LOA REQUEST DETAILS
======================

┌─ ARTICLE INFO ──────────────────────┐
│ 📄 Title: Sample Article Title     │
│ ✍️ Authors: John Doe, Jane Smith   │
│ 📧 Contact: john@example.com       │
│ 📚 Journal: Menulis Journal        │
│ 📊 Volume: 1, Number: 1           │
└─────────────────────────────────────┘

┌─ SUBMISSION FILES ──────────────────┐
│ 📎 article.pdf (2.3 MB)           │
│ 📎 supporting.docx (1.1 MB)       │
└─────────────────────────────────────┘

┌─ REVIEW ACTIONS ────────────────────┐
│ Status: ⏳ Pending Review          │
│                                     │
│ [✅ APPROVE REQUEST]               │
│ [❌ REJECT REQUEST]                │
│ [📝 REQUEST REVISION]             │
└─────────────────────────────────────┘
```

### **Step 8.4: Approve LOA Request**
**Klik [✅ APPROVE REQUEST]**

**Approval Confirmation:**
```
✅ APPROVE LOA REQUEST
=====================

┌─ APPROVAL DETAILS ──────────────────┐
│ LOA Code: LOASIP.001.001           │
│ Status: ✅ Approved                │
│ Approved Date: 2025-08-06         │
│ Approved By: Super Administrator   │
└─────────────────────────────────────┘

☑️ Generate PDF Certificate
☑️ Send notification email  
☑️ Create QR verification code

[Confirm Approval] [Cancel]
```

---

## 🧪 **9. TESTING FUNCTIONALITY**

### **Step 9.1: System Test**
```
URL: http://127.0.0.1:8000/system-test
```

**System Test Results:**
```json
{
  "database": {
    "status": "OK",
    "connection": "Connected"
  },
  "models": {
    "users_count": 1,
    "publishers_count": 2,
    "journals_count": 2,
    "loa_requests_count": 1,
    "loa_validated_count": 1
  },
  "role_system": {
    "roles_count": 4,
    "permissions_count": 26,
    "role_users_count": 1
  },
  "admin_users": [
    {
      "name": "Super Administrator",
      "email": "admin@loasiptenan.com",
      "role": "super_admin"
    }
  ],
  "storage": {
    "app_writable": true,
    "logs_writable": true,
    "framework_writable": true
  }
}
```

### **Step 9.2: QR Code Test**
```
URL: http://127.0.0.1:8000/test-qr
```

**QR Test Result:**
```json
{
  "status": "OK",
  "qr_generated": true,
  "message": "QR Code service is working"
}
```

### **Step 9.3: LOA Verification Test**
```
URL: http://127.0.0.1:8000/loa/verify
Input LOA Code: LOASIP.001.001
```

**Verification Result:**
```
🔍 LOA VERIFICATION RESULT
===========================

✅ VALID LOA CERTIFICATE

┌─ VERIFICATION DETAILS ──────────────┐
│ 📄 LOA Code: LOASIP.001.001        │
│ 📚 Journal: Menulis Journal         │  
│ 📅 Issue Date: August 6, 2025      │
│ ✍️ Authors: John Doe, Jane Smith   │
│ 🏢 Publisher: LP2M Univ Siber     │
│ ✅ Status: VERIFIED                │
└─────────────────────────────────────┘

[Download Certificate] [Share] [Print]
```

---

## 🔧 **10. TROUBLESHOOTING**

### **Common Issues & Solutions:**

#### **Issue 1: Database Table Missing Error (role_users not found)**
```bash
# Error: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'loa_management.role_users' doesn't exist

# SOLUTION 1: Fix via browser route
# Visit: http://127.0.0.1:8000/admin/fix-database
# This will automatically create missing role system tables

# SOLUTION 2: Manual database fix
php artisan fix:role-system

# SOLUTION 3: Fresh migration
php artisan migrate:fresh --seed
```

#### **Issue 2: Server tidak mau start**
```bash
# Check if port 8000 is busy
netstat -ano | findstr :8000

# Use different port
php artisan serve --port=8001
```

#### **Issue 2: Database connection error**
```bash
# Test database connection
php artisan tinker
DB::connection()->getPDO();

# Reset database
php artisan migrate:fresh --seed
```

#### **Issue 3: Permission denied errors**
```bash
# Fix storage permissions (Windows)
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/

# Or create directories manually
mkdir storage\logs
mkdir storage\framework\cache
mkdir storage\framework\sessions
mkdir storage\framework\views
```

#### **Issue 4: Role system error**
```bash
# Reset role system
php artisan migrate:fresh
php artisan db:seed --class=RolePermissionSeeder
```

---

## 🎬 **VIDEO TUTORIAL SUMMARY**

### **📹 Struktur Video yang Direkomendasikan:**

1. **Intro (0:00-1:00)**
   - Overview aplikasi LOA Management
   - Requirements dan prerequisites

2. **Setup Environment (1:00-5:00)**  
   - Install dependencies
   - Database setup
   - Configuration

3. **First Run (5:00-8:00)**
   - Start server
   - Access create-admin interface
   - Setup role system

4. **Admin Panel Tour (8:00-12:00)**
   - Login admin  
   - Dashboard navigation
   - Feature overview

5. **Data Management (12:00-18:00)**
   - Add publishers
   - Create journals
   - Manage templates

6. **LOA Process Demo (18:00-25:00)**
   - Submit LOA request (public)
   - Review and approve (admin)  
   - Generate certificate
   - QR verification

7. **Testing & Verification (25:00-28:00)**
   - System tests
   - Functionality verification
   - Troubleshooting tips

8. **Wrap Up (28:00-30:00)**
   - Summary of features
   - Next steps
   - Resources

---

## 📚 **RESOURCES & LINKS**

- **GitHub Repository:** `https://github.com/Gudangsoft/LOAKU`
- **Documentation:** `SYSTEM_STATUS.md`
- **Laravel Docs:** `https://laravel.com/docs`
- **Bootstrap Docs:** `https://getbootstrap.com/docs/5.3/`

**🎯 Total Tutorial Duration: ~30 minutes**
**📊 Difficulty Level: Beginner to Intermediate**
**🔧 Prerequisites: Basic PHP, Laravel, MySQL knowledge**
