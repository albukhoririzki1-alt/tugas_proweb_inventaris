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

// Cek duplikat email
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['error' => 'Email sudah terdaftar']);
    exit;
}

// Simpan user baru dengan status PENDING
$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare('INSERT INTO users (nama, username, email, password, peran, aktif, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$nama, $username, $email, $hash, 'guru', 1, 'PENDING']);
$newId = $pdo->lastInsertId();

// Catat ke riwayat
$pdo->prepare('INSERT INTO riwayat (aksi, user_id) VALUES (?, ?)')
    ->execute(["Registrasi baru: {$nama} ({$username}) — menunggu verifikasi", $newId]);

echo json_encode([
    'success' => true,
    'message' => 'Pendaftaran berhasil! Silakan tunggu persetujuan Admin sebelum dapat mengakses sistem.'
]);
