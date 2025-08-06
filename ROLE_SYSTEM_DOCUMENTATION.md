# LOA Management System - Role Documentation

## 🎭 **Sistem Role Overview**

Sistem LOA Management memiliki 3 role utama:

### **1. Admin Role** 👑
- **Role:** `admin` (atau `is_admin = 1`)
- **Login:** `admin@admin.com` / `admin`
- **Dashboard:** `/admin/dashboard`
- **Akses:**
  - Mengelola semua data sistem
  - User management (create, edit, delete, verify users)
  - Publisher management (semua publishers)
  - Journal management (semua journals)
  - LOA request management (semua requests)
  - System settings & configuration
  - Generate reports
  - Tutorial management

### **2. Publisher Role** 🏢
- **Role:** `publisher`
- **Login:** `publisher@test.com` / `publisher123`
- **Dashboard:** `/publisher/dashboard`
- **Akses:**
  - Membuat dan mengelola publisher companies milik sendiri
  - Membuat dan mengelola journals under publisher milik sendiri
  - Review & approve/reject LOA requests untuk journals yang dimiliki
  - Generate LOA certificates untuk requests yang diapprove
  - Update profile dan password
  - View statistics untuk journals milik sendiri

### **3. Member Role** 👤
- **Role:** `member` 
- **Login:** `member@test.com` / `member123`
- **Dashboard:** `/member/dashboard`
- **Akses:**
  - Submit LOA requests ke journals yang tersedia
  - View status LOA requests yang disubmit
  - Download approved LOA certificates
  - Update profile dan password
  - View statistics requests milik sendiri

---

## 🔐 **Authentication Flow**

```
Login Form (/admin/login)
         ↓
AuthController->login()
         ↓
    Check Role:
         ↓
    ┌─────────────────────────────────┐
    │ is_admin = 1 OR role = 'admin'  │ → Admin Dashboard
    │ role = 'publisher'              │ → Publisher Dashboard  
    │ role = 'member'                 │ → Member Dashboard
    └─────────────────────────────────┘
```

---

## 🗃️ **Database Structure**

### **Users Table**
```sql
- id (primary key)
- name (string)
- email (string, unique)
- password (hashed)
- role (enum: 'admin', 'member', 'publisher')
- is_admin (boolean) -- for backward compatibility
- email_verified_at (timestamp)
- created_at, updated_at
```

### **Publishers Table**
```sql
- id (primary key)
- user_id (foreign key → users.id) -- Publisher owner
- name (string)
- address (text)
- phone (string)
- email (string)
- website (string)
- logo (string)
- created_at, updated_at
```

### **Journals Table**  
```sql
- id (primary key)
- user_id (foreign key → users.id) -- Journal owner
- publisher_id (foreign key → publishers.id)
- name (string)
- e_issn, p_issn (string)
- chief_editor (string)
- logo, ttd_stample (string)
- website (string)
- created_at, updated_at
```

---

## 🎯 **Role Permissions Matrix**

| Feature | Admin | Publisher | Member |
|---------|-------|-----------|--------|
| **Dashboard** | ✅ Full system stats | ✅ Own publishers/journals | ✅ Own requests |
| **User Management** | ✅ All users | ❌ | ❌ |
| **Publisher Management** | ✅ All publishers | ✅ Own publishers only | ❌ |
| **Journal Management** | ✅ All journals | ✅ Own journals only | ❌ |
| **LOA Requests** | ✅ All requests | ✅ Requests to own journals | ✅ Own submissions |
| **Approve/Reject LOA** | ✅ All requests | ✅ Own journals only | ❌ |
| **Generate LOA** | ✅ All | ✅ Own journals | ❌ |
| **System Config** | ✅ | ❌ | ❌ |

---

## 🚦 **Navigation & Routes**

### **Admin Routes** (`/admin/*`)
```php
- /admin/dashboard
- /admin/users (CRUD users)
- /admin/publishers (CRUD publishers)  
- /admin/journals (CRUD journals)
- /admin/loa-requests (manage all requests)
- /admin/loa-templates
- /admin/tutorials
```

### **Publisher Routes** (`/publisher/*`)
```php
- /publisher/dashboard
- /publisher/publishers (own publishers)
- /publisher/publishers/create
- /publisher/journals (own journals)
- /publisher/journals/create
- /publisher/loa-requests (requests to own journals)
- /publisher/loa-requests/{id} (approve/reject)
- /publisher/profile
```

### **Member Routes** (`/member/*`)
```php
- /member/dashboard
- /member/loa-requests (own requests)
- /member/loa-requests/create
- /member/profile
```

---

## 👥 **Test Accounts**

### **Admin Account**
```
Email: admin@admin.com
Password: admin
Role: admin (is_admin = 1)
Access: Full system control
```

### **Publisher Accounts**
```
Email: publisher@test.com
Password: publisher123
Role: publisher
Owns: Scientific Publications Ltd (2 journals)

Email: sarah@test.com  
Password: publisher123
Role: publisher
Owns: Academic Research Press (1 journal)
```

### **Member Accounts**
```
Email: member@test.com
Password: member123
Role: member
Can: Submit LOA requests

Email: member2@test.com
Password: member123  
Role: member
Can: Submit LOA requests
```

---

## 🎨 **UI Differences by Role**

### **Admin Interface** (Blue theme)
- Comprehensive sidebar with all system modules
- Full statistics dashboard
- User management tools
- System-wide controls

### **Publisher Interface** (Blue-purple gradient)  
- Publisher-focused sidebar
- Own publishers/journals statistics
- LOA request approval tools
- Publication management

### **Member Interface** (Clean member theme)
- Simple sidebar for personal use
- Own request statistics  
- Request submission forms
- Personal profile management

---

## 🔄 **Data Ownership Model**

### **Publishers:**
- Owned by users with `publisher` role
- `publishers.user_id` → `users.id`

### **Journals:**  
- Owned by users with `publisher` role
- `journals.user_id` → `users.id`
- Linked to publishers: `journals.publisher_id` → `publishers.id`

### **LOA Requests:**
- Submitted by `member` users
- Processed by `publisher` users (journal owners)
- Managed by `admin` users (system-wide)

---

## 🚀 **Setup Instructions**

1. **Run Migration:**
```bash
php artisan migrate
```

2. **Seed Test Data:**
```bash
php artisan db:seed --class=PublisherUserSeeder
```

3. **Start Server:**
```bash
php artisan serve
```

4. **Access System:**
- Admin: `localhost:8000/admin/login`
- Publisher: Login as publisher, auto-redirect to `/publisher/dashboard`
- Member: Login as member, auto-redirect to `/member/dashboard`

---

## ✨ **Role Konsistensi**

Sistem role sudah tersinkronisasi dengan:
- ✅ Authentication flow
- ✅ Database relationships  
- ✅ Authorization middleware
- ✅ UI components
- ✅ Navigation menus
- ✅ Dashboard statistics
- ✅ Feature permissions

**Role names:**
- `admin` - System administrator
- `publisher` - Publication manager  
- `member` - Regular user/author

Semua konsisten di database, controller, view, dan routing! 🎯
