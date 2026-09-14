-- ============================================================
-- MIGRATION: Menambahkan kolom foto_profil, email, dan status ke tabel users
-- Tanggal: 2026-09-14
-- Deskripsi: Migration untuk fitur edit profil dengan upload foto
-- ============================================================

USE inventaris_db;

-- Cek apakah kolom foto_profil sudah ada, jika belum tambahkan
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) DEFAULT NULL 
AFTER aktif;

-- Cek apakah kolom email sudah ada, jika belum tambahkan
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS email VARCHAR(150) DEFAULT NULL 
AFTER username;

-- Cek apakah kolom status sudah ada, jika belum tambahkan
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS status ENUM('PENDING','ACTIVE','REJECTED') NOT NULL DEFAULT 'ACTIVE'
AFTER aktif;

-- Update semua user existing jadi status ACTIVE
UPDATE users SET status = 'ACTIVE' WHERE status IS NULL OR status = '';

-- Catatan: 
-- - Kolom foto_profil akan menyimpan path relatif ke file foto
-- - Format: uploads/profil/profil_[user_id]_[timestamp].[ext]
-- - Jika NULL, akan menampilkan initial nama di sidebar
-- - Kolom email untuk registrasi user baru (opsional untuk user lama)
-- - Status: PENDING (menunggu verifikasi), ACTIVE (aktif), REJECTED (ditolak)
