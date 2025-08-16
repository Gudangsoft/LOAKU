# Website Settings Documentation

## Overview
Fitur Website Settings memungkinkan admin untuk mengatur konfigurasi website secara dinamis melalui panel admin, termasuk:
- Nama website
- Logo website
- Favicon
- Deskripsi website
- Informasi kontak

## Features Implemented

### 1. Database Structure
- **Table**: `website_settings`
- **Columns**: 
  - `id` - Primary key
  - `key` - Setting key (unique)
  - `value` - Setting value (text/path untuk images)
  - `type` - Setting type (text, image, file)
  - `description` - Setting description
  - `created_at`, `updated_at` - Timestamps

### 2. Model & Helper Functions
- **Model**: `App\Models\WebsiteSetting`
- **Helper Functions**:
  - `setting($key, $default)` - Get setting value
  - `site_name()` - Get site name
  - `site_logo()` - Get logo URL
  - `site_favicon()` - Get favicon URL

### 3. Admin Interface
**Route**: `/admin/website-settings`

**Features**:
- Form untuk update informasi umum (nama, deskripsi, email, telepon, alamat)
- Upload logo dengan preview
- Upload favicon dengan preview
- Delete logo/favicon functionality
- Validation untuk file uploads
- Error handling

### 4. Dynamic Frontend Integration
- Website title menggunakan `site_name()`
- Navbar menggunakan logo dan nama dinamis
- Favicon otomatis terpasang jika ada
- Global middleware untuk share settings ke semua views

## Usage

### Admin Panel
1. Login sebagai admin
2. Navigate to **Website Settings** di sidebar
3. Update informasi website sesuai kebutuhan
4. Upload logo/favicon jika diperlukan
5. Klik **Simpan Pengaturan**

### Developer Usage
```php
// Get any setting
$siteName = setting('site_name', 'Default Name');

// Use helper functions
echo site_name(); // Website name
echo site_logo(); // Logo URL or null
echo site_favicon(); // Favicon URL or null
```

### Blade Templates
```blade
{{-- Use in any view --}}
<title>{{ site_name() }}</title>

@if(site_logo())
    <img src="{{ site_logo() }}" alt="{{ site_name() }}">
@endif

@if(site_favicon())
    <link rel="icon" href="{{ site_favicon() }}">
@endif
```

## File Structure
```
├── app/
│   ├── Http/
│   │   ├── Controllers/Admin/
│   │   │   └── WebsiteSettingController.php
│   │   └── Middleware/
│   │       └── ShareWebsiteSettings.php
│   ├── Models/
│   │   └── WebsiteSetting.php
│   └── helpers.php
├── database/
│   ├── migrations/
│   │   └── 2025_08_16_095438_create_website_settings_table.php
│   └── seeders/
│       └── WebsiteSettingSeeder.php
├── resources/views/admin/
│   └── website-settings/
│       └── index.blade.php
└── routes/
    └── web.php (admin routes)
```

## Settings Available

### Text Settings
- `site_name` - Nama website
- `site_description` - Deskripsi website
- `admin_email` - Email admin
- `phone` - Nomor telepon
- `address` - Alamat

### Image Settings
- `logo` - Logo website (PNG, JPG, JPEG, SVG, max 2MB)
- `favicon` - Favicon (PNG, ICO, JPG, JPEG, max 1MB)

## Security Features
- File upload validation
- Image type restrictions
- File size limits
- Admin-only access
- CSRF protection
- Error handling with logging

## API Endpoints

### Admin Routes (Protected)
- `GET /admin/website-settings` - Show settings form
- `PUT /admin/website-settings/update` - Update settings
- `POST /admin/website-settings/delete-image` - Delete logo/favicon

## Installation Steps Completed
1. ✅ Migration created and executed
2. ✅ Model with helper methods created
3. ✅ Controller with CRUD operations
4. ✅ Admin view with upload functionality
5. ✅ Routes registered
6. ✅ Sidebar menu added
7. ✅ Helper functions created
8. ✅ Middleware for global sharing
9. ✅ Frontend integration updated
10. ✅ Seeder for default data
11. ✅ Storage link verified

## Testing Completed
- ✅ Admin panel access
- ✅ Settings form rendering
- ✅ File upload validation
- ✅ Dynamic title and logo display
- ✅ Middleware sharing settings
- ✅ Helper functions working

## Next Steps (Optional)
1. Add more setting types (color picker, rich text)
2. Setting categories/tabs
3. Import/export settings
4. Setting history/versioning
5. Cache optimization for settings
