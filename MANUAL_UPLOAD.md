# 🚀 Manual GitHub Upload Guide

Jika script otomatis tidak bekerja, ikuti langkah manual ini:

## 1. Install Git (jika belum)
- Download: https://git-scm.com/download/win
- Install dengan default settings
- Restart Command Prompt

## 2. Open Command Prompt dan jalankan:

```bash
# 1. Pindah ke Desktop
cd "C:\Users\Admin\Desktop"

# 2. Rename folder (jika belum)
ren "LOA-SIPTENAN-PROJECT" "LOAKU"

# 3. Masuk ke folder project
cd "LOAKU"

# 4. Initialize Git
git init

# 5. Set user config (ganti dengan info Anda)
git config user.name "Gudangsoft"
git config user.email "admin@gudangsoft.com"

# 6. Add remote repository
git remote add origin https://github.com/Gudangsoft/LOAKU.git

# 7. Add all files
git add .

# 8. Commit files
git commit -m "Initial commit: LOA-KU Management System"

# 9. Set main branch
git branch -M main

# 10. Push to GitHub
git push -u origin main
```

## 3. Jika ada error saat push:

### Error: Authentication
```bash
# Login ke GitHub via browser, lalu:
git push -u origin main
```

### Error: Repository tidak ada
1. Buka https://github.com/Gudangsoft
2. Klik "New repository"
3. Nama: LOAKU
4. Public/Private sesuai keinginan
5. Jangan add README (sudah ada)
6. Create repository
7. Ulangi git push

## 4. Verifikasi Upload
- Buka: https://github.com/Gudangsoft/LOAKU
- Pastikan semua file ada
- Cek README.md tampil dengan baik

## 5. Alternative: GitHub Desktop
1. Download: https://desktop.github.com/
2. Install dan login GitHub
3. File → Add Local Repository
4. Pilih folder LOAKU
5. Publish repository

---

⚡ **Ready to upload your LOA-KU project!** 🎯
