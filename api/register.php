<?php

/**
 * api/register.php — API Registrasi Pengguna Baru (Public)
 * POST → daftar akun baru dengan status PENDING
 */

require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$d = json_decode(file_get_contents('php://input'), true);

$nama     = trim($d['nama'] ?? '');
$username = trim($d['username'] ?? '');
$email    = trim($d['email'] ?? '');
$password = $d['password'] ?? '';
$confirm  = $d['confirm_password'] ?? '';

// Validasi field wajib
if (!$nama || !$username || !$email || !$password || !$confirm) {
    http_response_code(400);
    echo json_encode(['error' => 'Semua kolom wajib diisi']);
    exit;
}

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Format email tidak valid']);
    exit;
}

// Validasi panjang password
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['error' => 'Password minimal 6 karakter']);
    exit;
}

// Validasi konfirmasi password
if ($password !== $confirm) {
    http_response_code(400);
    echo json_encode(['error' => 'Password dan Konfirmasi Password tidak cocok']);
    exit;
}

$pdo = getDB();

// Cek duplikat username
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['error' => 'Username sudah digunakan']);
    exit;
}

// Cek duplikat email (skip jika kolom tidak ada)
try {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Email sudah terdaftar']);
        exit;
    }
} catch (PDOException $e) {
    // Skip jika kolom email belum ada
}

// Simpan user baru
$hash = password_hash($password, PASSWORD_BCRYPT);

try {
    // Try dengan semua kolom (jika ada yang missing, akan fallback)
    try {
        $stmt = $pdo->prepare('INSERT INTO users (nama, username, email, password, peran, aktif, status, foto_profil) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nama, $username, $email, $hash, 'guru', 1, 'PENDING', null]);
    } catch (PDOException $e) {
        // Fallback: tanpa email, foto_profil
        $stmt = $pdo->prepare('INSERT INTO users (nama, username, password, peran, aktif, status) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nama, $username, $hash, 'guru', 1, 'PENDING']);
    }
    
    $newId = $pdo->lastInsertId();
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
    exit;
}

// Catat ke riwayat (skip jika tabel riwayat tidak ada)
try {
    $riwayatTableCheck = $pdo->query("SHOW TABLES LIKE 'riwayat'")->fetch();
    if ($riwayatTableCheck) {
        $pdo->prepare('INSERT INTO riwayat (aksi, user_id) VALUES (?, ?)')
            ->execute(["Registrasi baru: {$nama} ({$username}) — menunggu verifikasi", $newId]);
    }
} catch (Exception $e) {
    // Skip logging jika tabel tidak ada
}

echo json_encode([
    'success' => true,
    'message' => 'Pendaftaran berhasil! Silakan tunggu persetujuan Admin sebelum dapat mengakses sistem.'
]);
