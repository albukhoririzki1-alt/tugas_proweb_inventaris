# ✅ Test Checklist - Fitur Edit Profil

## 🎯 Pre-Test Setup

### 1. Database Migration ✅
```sql
-- Jalankan di phpMyAdmin atau MySQL console
USE inventaris_db;

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) DEFAULT NULL 
AFTER aktif;

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS email VARCHAR(150) DEFAULT NULL 
AFTER username;

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS status ENUM('PENDING','ACTIVE','REJECTED') NOT NULL DEFAULT 'ACTIVE'
AFTER aktif;

UPDATE users SET status = 'ACTIVE' WHERE status IS NULL OR status = '';
```

### 2. Folder Verification ✅
Pastikan folder ini ada:
- `c:\xampp\htdocs\tugas_proweb_inventaris\uploads\profil\`

### 3. Browser Preparation ✅
- Clear cache (Ctrl+Shift+Del)
- Open browser console (F12)
- Ready untuk test

---

## 🧪 Test Cases

### Test 1: Login sebagai Admin
**Steps:**
1. ✅ Buka `localhost/tugas_proweb_inventaris/login.php`
2. ✅ Login dengan username: `admin`, password: `admin123`
3. ✅ Redirect ke dashboard

**Expected:**
- Login berhasil
- Sidebar muncul dengan info user
- Dropdown arrow (▼) terlihat

---

### Test 2: Akses Edit Profil Admin
**Steps:**
1. ✅ Klik pada info user di sidebar bawah
2. ✅ Dropdown menu muncul
3. ✅ Klik "👤 Edit Profil"

**Expected:**
- Halaman edit profil terbuka
- Layout 2 kolom (foto kiri, form kanan)
- Form terisi data admin saat ini
- Foto profil menampilkan initial "AD"
- Badge role "👑 Admin" terlihat

---

### Test 3: Upload Foto Profil
**Steps:**
1. ✅ Klik tombol "📷 Pilih Foto"
2. ✅ Pilih file JPG/PNG (kurang dari 2MB)
3. ✅ Preview foto langsung muncul
4. ✅ Klik "💾 Simpan Perubahan"

**Expected:**
- Preview foto muncul instant
- Tombol "🗑️ Hapus Foto" muncul
- Alert sukses hijau: "Profil berhasil diperbarui!"
- Foto muncul di sidebar (reload otomatis)

**Test Edge Cases:**
- ❌ Upload file >2MB → Error: "Ukuran file terlalu besar!"
- ❌ Upload PDF/TXT → Error: "Format file tidak didukung!"

---

### Test 4: Edit Nama dan Username
**Steps:**
1. ✅ Edit field "Nama Lengkap" → `Admin Super`
2. ✅ Edit field "Username" → `adminsystem`
3. ✅ Klik "💾 Simpan Perubahan"

**Expected:**
- Alert sukses hijau muncul
- Nama di sidebar berubah jadi "Admin Super"
- Halaman reload otomatis
- Bisa login dengan username baru

**Test Edge Cases:**
- ❌ Username sudah ada → Error: "Username sudah digunakan!"
- ❌ Nama kosong → Error: "Nama dan username harus diisi!"

---

### Test 5: Ubah Password
**Steps:**
1. ✅ Isi "Password Lama" → `admin123`
2. ✅ Isi "Password Baru" → `admin1234`
3. ✅ Isi "Konfirmasi Password" → `admin1234`
4. ✅ Klik "💾 Simpan Perubahan"

**Expected:**
- Alert sukses hijau muncul
- Logout
- Login dengan password baru berhasil

**Test Edge Cases:**
- ❌ Password lama salah → Error: "Password lama salah!"
- ❌ Password baru < 6 karakter → Error: "Password baru minimal 6 karakter!"
- ❌ Konfirmasi tidak cocok → Error: "Konfirmasi password tidak cocok!"

---

### Test 6: Hapus Foto Profil
**Steps:**
1. ✅ Upload foto terlebih dahulu
2. ✅ Klik tombol "🗑️ Hapus Foto"
3. ✅ Confirm dialog

**Expected:**
- Preview kembali ke initial
- Tombol hapus hilang
- Tidak menghapus dari server (hanya preview)

---

### Test 7: Login sebagai Guru
**Steps:**
1. ✅ Logout dari admin
2. ✅ Login dengan username: `guru`, password: `guru123`
3. ✅ Redirect ke dashboard

**Expected:**
- Login berhasil
- Sidebar muncul dengan info user guru
- Dropdown arrow (▼) terlihat

---

### Test 8: Akses Edit Profil Guru
**Steps:**
1. ✅ Klik pada info user di sidebar bawah
2. ✅ Dropdown menu muncul
3. ✅ Klik "👤 Edit Profil"

**Expected:**
- Halaman edit profil terbuka
- Layout sama seperti admin
- Form terisi data guru saat ini
- Badge role "📚 Guru" terlihat
- Semua fitur sama dengan admin

---

### Test 9: Guru Upload Foto
**Steps:**
1. ✅ Upload foto profil sebagai guru
2. ✅ Simpan perubahan

**Expected:**
- Upload berhasil
- Foto muncul di sidebar guru
- Tidak conflict dengan foto admin

---

### Test 10: Registrasi User Baru
**Steps:**
1. ✅ Buka halaman register
2. ✅ Isi form:
   - Nama: `Guru Baru`
   - Username: `gurubaru`
   - Email: `gurubaru@sekolah.com`
   - Password: `guru123`
   - Confirm: `guru123`
3. ✅ Submit

**Expected:**
- Registrasi berhasil
- Status: PENDING
- Database: kolom foto_profil = NULL
- Tidak crash karena foto_profil

---

### Test 11: User Baru Setelah Diverifikasi
**Steps:**
1. ✅ Admin verifikasi user baru (set status ACTIVE)
2. ✅ Login sebagai user baru
3. ✅ Akses edit profil

**Expected:**
- User baru bisa login
- Bisa akses edit profil
- Foto_profil NULL, tampil initial
- Bisa upload foto langsung

---

### Test 12: Responsive Mobile View
**Steps:**
1. ✅ Press F12 (Developer Tools)
2. ✅ Toggle device toolbar (Ctrl+Shift+M)
3. ✅ Set ke mobile view (375px)
4. ✅ Buka halaman edit profil

**Expected:**
- Layout berubah jadi 1 kolom
- Foto profil di atas
- Form di bawah
- Semua element readable
- Touch-friendly buttons

---

### Test 13: Browser Console Check
**Steps:**
1. ✅ Open browser console (F12)
2. ✅ Akses edit profil
3. ✅ Test semua fitur

**Expected:**
- ✅ No JavaScript errors
- ✅ No 404 errors
- ✅ API calls return 200 OK
- ✅ AJAX working properly

---

### Test 14: Backward Compatibility
**Steps:**
1. ✅ Hapus kolom foto_profil dari database:
   ```sql
   ALTER TABLE users DROP COLUMN foto_profil;
   ```
2. ✅ Akses edit profil
3. ✅ Edit nama dan password

**Expected:**
- Halaman tidak crash
- Edit nama/username/password masih jalan
- Upload foto di-skip (gracefully)
- Alert error jelas jika upload foto

**Restore:**
```sql
ALTER TABLE users ADD COLUMN foto_profil VARCHAR(255) DEFAULT NULL AFTER aktif;
```

---

### Test 15: Session Update Check
**Steps:**
1. ✅ Login
2. ✅ Edit nama di profil
3. ✅ Simpan
4. ✅ Check sidebar name
5. ✅ Navigate ke page lain

**Expected:**
- Nama di sidebar update instant
- Session $_SESSION['nama'] updated
- Nama persist across pages
- No need to re-login

---

## 🏁 Test Summary

### Pass Criteria:
- ✅ All 15 tests pass
- ✅ No console errors
- ✅ No PHP errors
- ✅ Responsive working
- ✅ Security validations working
- ✅ Backward compatible

### If Tests Fail:
1. Check database migration ran successfully
2. Check folder permissions (755)
3. Check PHP error log
4. Check browser console
5. Clear cache and retry

---

## 📊 Test Results

Test Date: _______________  
Tester: _______________

| Test # | Description | Status | Notes |
|--------|-------------|--------|-------|
| 1 | Login Admin | ⬜ Pass / ⬜ Fail | |
| 2 | Access Edit Profil Admin | ⬜ Pass / ⬜ Fail | |
| 3 | Upload Foto | ⬜ Pass / ⬜ Fail | |
| 4 | Edit Nama/Username | ⬜ Pass / ⬜ Fail | |
| 5 | Ubah Password | ⬜ Pass / ⬜ Fail | |
| 6 | Hapus Foto | ⬜ Pass / ⬜ Fail | |
| 7 | Login Guru | ⬜ Pass / ⬜ Fail | |
| 8 | Access Edit Profil Guru | ⬜ Pass / ⬜ Fail | |
| 9 | Guru Upload Foto | ⬜ Pass / ⬜ Fail | |
| 10 | Registrasi User Baru | ⬜ Pass / ⬜ Fail | |
| 11 | User Baru Edit Profil | ⬜ Pass / ⬜ Fail | |
| 12 | Responsive Mobile | ⬜ Pass / ⬜ Fail | |
| 13 | Console Check | ⬜ Pass / ⬜ Fail | |
| 14 | Backward Compatibility | ⬜ Pass / ⬜ Fail | |
| 15 | Session Update | ⬜ Pass / ⬜ Fail | |

**Overall Result:** ⬜ PASS / ⬜ FAIL

---

## 🎯 Quick Test Commands

### Check database columns:
```sql
SHOW COLUMNS FROM users;
```

### Check user data:
```sql
SELECT id, nama, username, email, foto_profil, status FROM users;
```

### Check uploaded photos:
```bash
dir c:\xampp\htdocs\tugas_proweb_inventaris\uploads\profil
```

### Test API directly:
```
http://localhost/tugas_proweb_inventaris/api/get_profil.php
http://localhost/tugas_proweb_inventaris/api/update_profil.php
```

---

**Good Luck Testing! 🚀**
