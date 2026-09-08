<?php

/**
 * migrate_register.php — Migrasi database untuk fitur Registrasi & Verifikasi
 * Menambahkan kolom email dan status ke tabel users
 * Aman dijalankan berkali-kali (idempotent)
 */

require_once __DIR__ . '/config/db.php';

$pdo = getDB();
$messages = [];

try {
    // 1. Tambah kolom email jika belum ada
    $cols = $pdo->query("SHOW COLUMNS FROM users LIKE 'email'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(150) DEFAULT NULL AFTER username");
        $messages[] = '✅ Kolom "email" berhasil ditambahkan.';
    } else {
        $messages[] = 'ℹ️ Kolom "email" sudah ada, dilewati.';
    }

    // 2. Tambah kolom status jika belum ada
    $cols = $pdo->query("SHOW COLUMNS FROM users LIKE 'status'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN status ENUM('ACTIVE','PENDING','REJECTED') NOT NULL DEFAULT 'ACTIVE' AFTER aktif");
        $messages[] = '✅ Kolom "status" berhasil ditambahkan.';
    } else {
        $messages[] = 'ℹ️ Kolom "status" sudah ada, dilewati.';
    }

    // 3. Tambah unique index pada email (jika belum ada)
    $indexes = $pdo->query("SHOW INDEX FROM users WHERE Column_name = 'email' AND Non_unique = 0")->fetchAll();
    if (empty($indexes)) {
        // Email bisa NULL untuk user lama, jadi gunakan unique index yang mengizinkan NULL
        $pdo->exec("ALTER TABLE users ADD UNIQUE INDEX idx_users_email (email)");
        $messages[] = '✅ Index unik pada "email" berhasil ditambahkan.';
    } else {
        $messages[] = 'ℹ️ Index unik pada "email" sudah ada, dilewati.';
    }

    $messages[] = '';
    $messages[] = '🎉 Migrasi selesai! Semua perubahan telah diterapkan.';

} catch (PDOException $e) {
    $messages[] = '❌ Error: ' . $e->getMessage();
}

// Output
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html><head><title>Migrasi Database</title>';
echo '<style>body{font-family:monospace;background:#0d0f1a;color:#e8eaf6;padding:40px;line-height:2;} h2{color:#4f8aff;}</style>';
echo '</head><body>';
echo '<h2>📦 Migrasi Database — Fitur Registrasi & Verifikasi</h2>';
foreach ($messages as $m) {
    echo '<div>' . $m . '</div>';
}
echo '<br><a href="login.php" style="color:#4f8aff;">← Kembali ke Login</a>';
echo '</body></html>';
