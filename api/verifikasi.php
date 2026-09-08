<?php

/**
 * api/verifikasi.php — API Verifikasi Pendaftaran User (Admin Only)
 * GET → list user PENDING & REJECTED
 * PUT → approve / reject user
 */

define('BASE_URL', '../');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();
$admin  = getCurrentUser();

if ($method === 'GET') {
    $rows = $pdo->query("
        SELECT id, nama, username, email, peran, aktif, status, created_at 
        FROM users 
        WHERE status IN ('PENDING', 'REJECTED') 
        ORDER BY 
            CASE status WHEN 'PENDING' THEN 0 ELSE 1 END,
            created_at DESC
    ")->fetchAll();
    echo json_encode($rows);
    exit;
}

if ($method === 'PUT') {
    $d      = json_decode(file_get_contents('php://input'), true);
    $id     = (int) ($d['id'] ?? 0);
    $action = $d['action'] ?? '';

    if (!$id || !in_array($action, ['approve', 'reject'], true)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID dan action (approve/reject) wajib diisi']);
        exit;
    }

    // Ambil data user
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User tidak ditemukan']);
        exit;
    }

    if ($action === 'approve') {
        $pdo->prepare('UPDATE users SET status = ? WHERE id = ?')
            ->execute(['ACTIVE', $id]);

        $pdo->prepare('INSERT INTO riwayat (aksi, user_id) VALUES (?, ?)')
            ->execute(["User \"{$user['username']}\" disetujui oleh Admin", $admin['id']]);

        echo json_encode(['success' => true, 'message' => "User \"{$user['nama']}\" berhasil disetujui"]);
    } else {
        $pdo->prepare('UPDATE users SET status = ? WHERE id = ?')
            ->execute(['REJECTED', $id]);

        $pdo->prepare('INSERT INTO riwayat (aksi, user_id) VALUES (?, ?)')
            ->execute(["User \"{$user['username']}\" ditolak oleh Admin", $admin['id']]);

        echo json_encode(['success' => true, 'message' => "User \"{$user['nama']}\" telah ditolak"]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
