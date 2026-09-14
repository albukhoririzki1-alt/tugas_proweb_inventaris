# Update Edit Profil v2.0

## 📋 What's New

### ✅ Fitur Lengkap untuk Admin & Guru
Sekarang **semua user** (Admin dan Guru) bisa:
- ✅ Upload/edit foto profil
- ✅ Edit nama lengkap
- ✅ Edit username
- ✅ Ubah password dengan verifikasi

### ✅ UI/UX Improvements
- 🎨 **Desain 2 kolom** - Foto profil di kiri, form di kanan
- 📱 **Responsive** - Otomatis 1 kolom di mobile
- ✨ **Animasi** - Smooth transitions dan hover effects
- 🎯 **Icons** - Setiap field punya icon yang jelas
- 💡 **Info boxes** - Tips dan warning dengan color coding
- 🖼️ **Photo overlay** - Hover effect pada foto profil

### ✅ Auto-Registration Support
- 🆕 User baru dari **register.php** otomatis punya kolom `foto_profil` (NULL)
- 🔄 Backward compatible - Tidak crash jika kolom belum ada
- 📝 Auto-detect kolom database yang ada

### ✅ Database Updates
Tabel `users` sekarang punya kolom tambahan:
- `foto_profil` VARCHAR(255) - Path ke foto profil
- `email` VARCHAR(150) - Email user (untuk registrasi)
- `status` ENUM - Status akun (PENDING, ACTIVE, REJECTED)

---

## 🗂️ File Changes

### Modified Files:
1. **`inventaris_db.sql`**
   - ✅ Added: `foto_profil` column
   - ✅ Added: `email` column
   - ✅ Added: `status` column

2. **`database_migration_foto_profil.sql`**
   - ✅ Migration script lengkap
   - ✅ Support untuk kolom: foto_profil, email, status
   - ✅ Update user existing jadi status ACTIVE

3. **`api/register.php`**
   - ✅ Auto-detect database columns
   - ✅ Dynamic INSERT query based on available columns
   - ✅ Support foto_profil (default NULL)
   - ✅ Better error handling

4. **`pages/edit_profil.php`**
   - ✅ Modern 2-column layout
   - ✅ Better form validation
   - ✅ Enhanced UI with icons and colors
   - ✅ Responsive design

5. **`api/update_profil.php`**
   - ✅ Check kolom foto_profil sebelum update
   - ✅ Backward compatible
   - ✅ Better error messages

6. **`api/get_profil.php`**
   - ✅ Check kolom foto_profil sebelum query
   - ✅ Backward compatible

7. **`includes/sidebar.php`**
   - ✅ Dropdown menu untuk semua user
   - ✅ Load foto profil via AJAX
   - ✅ Fallback ke initial jika foto tidak ada

---

## 🚀 Installation Guide

### Step 1: Update Database
Jalankan migration SQL di phpMyAdmin atau MySQL console:

```sql
USE inventaris_db;

-- Tambah kolom foto_profil
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) DEFAULT NULL 
AFTER aktif;

-- Tambah kolom email
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS email VARCHAR(150) DEFAULT NULL 
AFTER username;

-- Tambah kolom status
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS status ENUM('PENDING','ACTIVE','REJECTED') NOT NULL DEFAULT 'ACTIVE'
AFTER aktif;

-- Update user existing
UPDATE users SET status = 'ACTIVE' WHERE status IS NULL OR status = '';
```

**ATAU** jalankan file migration:
```bash
mysql -u root inventaris_db < database_migration_foto_profil.sql
```

### Step 2: Verify Folders
Pastikan folder ini ada dan writable:
```
tugas_proweb_inventaris/
└── uploads/
    └── profil/
```

Jika belum ada, buat manual:
```bash
mkdir -p tugas_proweb_inventaris/uploads/profil
chmod 755 tugas_proweb_inventaris/uploads
chmod 755 tugas_proweb_inventaris/uploads/profil
```

### Step 3: Test Fitur

#### Test sebagai Admin:
1. Login: `admin` / `admin123`
2. Klik user info di sidebar bawah
3. Pilih "Edit Profil"
4. Test upload foto, edit nama, username, password

#### Test sebagai Guru:
1. Login: `guru` / `guru123`
2. Klik user info di sidebar bawah
3. Pilih "Edit Profil"
4. Test semua fitur sama seperti admin

#### Test Registrasi:
1. Buka halaman register
2. Daftar akun baru
3. Setelah diverifikasi admin dan login
4. Akun baru sudah bisa edit profil langsung

---

## 🎯 Features Detail

### 1. Edit Profil untuk Semua User
- Admin dan Guru punya akses yang sama
- Menu "Edit Profil" di dropdown sidebar
- Semua field bisa diedit kecuali role/peran

### 2. Upload Foto Profil
- **Validation**: JPG/PNG, max 2MB
- **Preview**: Real-time sebelum upload
- **Storage**: `uploads/profil/profil_[user_id]_[timestamp].[ext]`
- **Fallback**: Initial nama (2 huruf) jika belum upload
- **Auto-delete**: Foto lama otomatis terhapus saat upload baru

### 3. Auto-Registration Support
Saat user baru register via `api/register.php`:
- ✅ Kolom `foto_profil` otomatis NULL
- ✅ Kolom `email` terisi dari form register
- ✅ Kolom `status` set ke PENDING (menunggu verifikasi)
- ✅ Setelah diverifikasi, user bisa langsung edit profil

### 4. Backward Compatibility
Jika database belum di-update:
- ✅ API tidak crash
- ✅ Fitur edit nama/username/password tetap jalan
- ✅ Upload foto di-skip sampai kolom ditambahkan
- ✅ Error handling graceful

---

## 🔒 Security Features

### Validation:
- ✅ File type validation (MIME type check)
- ✅ File size validation (max 2MB)
- ✅ Username uniqueness check
- ✅ Password verification (untuk ubah password)
- ✅ Password strength (min 6 karakter)
- ✅ Password confirmation match

### Protection:
- ✅ Session-based authentication
- ✅ SQL injection protection (prepared statements)
- ✅ XSS protection (htmlspecialchars)
- ✅ CSRF ready (form validation)
- ✅ Path traversal protection

---

## 🎨 UI/UX Highlights

### Layout:
- **Desktop**: 2 kolom (foto kiri, form kanan)
- **Mobile**: 1 kolom stack
- **Sticky**: Foto profil card sticky on scroll

### Visual:
- **Gradient**: Background gradients untuk cards
- **Icons**: Emoji icons untuk visual clarity
- **Badges**: Role badge dengan color coding
- **Shadows**: Box shadows untuk depth
- **Borders**: Accent color borders

### Interactions:
- **Focus**: Input glow effect saat focus
- **Hover**: Smooth transitions
- **Animation**: Slide down untuk alerts
- **Preview**: Real-time foto preview
- **Overlay**: Hover overlay pada foto profil

---

## 📊 Database Schema

### Tabel `users` (Updated):
```sql
CREATE TABLE users (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  nama         VARCHAR(150) NOT NULL,
  username     VARCHAR(80)  NOT NULL UNIQUE,
  email        VARCHAR(150) DEFAULT NULL,           -- ✅ NEW
  password     VARCHAR(255) NOT NULL,
  peran        ENUM('admin','guru') NOT NULL DEFAULT 'guru',
  aktif        TINYINT(1)   NOT NULL DEFAULT 1,
  status       ENUM('PENDING','ACTIVE','REJECTED') NOT NULL DEFAULT 'ACTIVE',  -- ✅ NEW
  foto_profil  VARCHAR(255) DEFAULT NULL,           -- ✅ NEW
  created_at   DATETIME     DEFAULT CURRENT_TIMESTAMP
);
```

### Kolom Baru:
- **foto_profil**: Path relatif foto (e.g., `uploads/profil/profil_1_1234567890.jpg`)
- **email**: Email user untuk registrasi dan notifikasi
- **status**: Status akun (PENDING = menunggu verifikasi, ACTIVE = aktif, REJECTED = ditolak)

---

## 🐛 Troubleshooting

### Problem: "Terjadi kesalahan saat menyimpan data"
**Solusi:**
1. Cek apakah migration SQL sudah dijalankan
2. Buka browser console (F12) untuk lihat error detail
3. Pastikan kolom `foto_profil` sudah ada di tabel users

### Problem: Foto tidak bisa diupload
**Solusi:**
1. Cek permission folder `uploads/profil/` (chmod 755 atau 777)
2. Cek `php.ini`: `upload_max_filesize = 2M` dan `post_max_size = 3M`
3. Restart Apache setelah edit php.ini

### Problem: Username sudah digunakan
**Solusi:**
- Username harus unique
- Pilih username lain yang belum terdaftar

### Problem: Password lama salah
**Solusi:**
- Pastikan memasukkan password lama yang benar
- Password case-sensitive

### Problem: Foto tidak muncul di sidebar
**Solusi:**
1. Clear browser cache (Ctrl+F5)
2. Cek database apakah path foto_profil sudah terisi
3. Cek file foto ada di `uploads/profil/`
4. Cek permission file (chmod 644)

### Problem: Guru tidak bisa akses edit profil
**Solusi:**
- Fitur sudah accessible untuk semua user
- Pastikan sudah login sebagai guru
- Clear cache dan refresh browser

---

## 📱 Access Control

### Who Can Access Edit Profil?
- ✅ **Admin** - Full access
- ✅ **Guru** - Full access
- ❌ **Guest/Unauthenticated** - Redirect to login

### What Can Be Edited?
- ✅ Foto profil
- ✅ Nama lengkap
- ✅ Username
- ✅ Password
- ❌ Role/Peran (controlled by admin only via Kelola User)
- ❌ Status akun (controlled by admin only via Verifikasi User)

---

## 🔄 Changelog

### v2.0 (2026-09-14)
- ✅ Added: Fitur edit profil untuk guru
- ✅ Added: Auto-registration support dengan foto_profil
- ✅ Added: Email dan status column di users table
- ✅ Improved: UI/UX dengan 2-column layout
- ✅ Improved: Backward compatibility checks
- ✅ Improved: Error handling di semua API
- ✅ Fixed: Registration tidak crash jika kolom belum ada

### v1.0 (Initial Release)
- ✅ Basic edit profil untuk admin
- ✅ Upload foto profil
- ✅ Edit nama dan username
- ✅ Ubah password

---

## 📞 Support

Untuk pertanyaan atau issue:
1. Baca dokumentasi lengkap di `FITUR_EDIT_PROFIL.md`
2. Baca cara install di `CARA_INSTALL_FITUR_EDIT_PROFIL.txt`
3. Check troubleshooting section di atas

---

**Updated**: 2026-09-14  
**Version**: 2.0  
**Compatibility**: Admin & Guru  
**Status**: Production Ready ✅
