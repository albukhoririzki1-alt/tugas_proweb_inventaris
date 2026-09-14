-- ============================================================
-- MIGRATION: Menambahkan kolom foto_profil ke tabel users
-- Tanggal: 2026-09-14
-- Deskripsi: Migration untuk fitur edit profil dengan upload foto
-- ============================================================

USE inventaris_db;

-- Cek apakah kolom foto_profil sudah ada, jika belum tambahkan
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) DEFAULT NULL 
AFTER aktif;

-- Catatan: 
-- Kolom foto_profil akan menyimpan path relatif ke file foto
-- Format: uploads/profil/profil_[user_id]_[timestamp].[ext]
-- Jika NULL, akan menampilkan initial nama di sidebar
