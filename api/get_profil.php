<?php

/**
 * api/get_profil.php
 * API endpoint untuk mendapatkan data profil user saat ini
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

// Pastikan user sudah login
requireLogin();

$currentUser = getCurrentUser();
$userId = $currentUser['id'];

try {
    $pdo = getDB();
    
    // Cek apakah kolom foto_profil ada
    $hasFotoProfilColumn = false;
    try {
        $columnCheck = $pdo->query("SHOW COLUMNS FROM users LIKE 'foto_profil'")->fetch();
        $hasFotoProfilColumn = !empty($columnCheck);
    } catch (Exception $e) {
        // Kolom belum ada
    }
    
    // Ambil data user dari database
    if ($hasFotoProfilColumn) {
        $stmt = $pdo->prepare('SELECT id, nama, username, peran, foto_profil, created_at FROM users WHERE id = ?');
    } else {
        $stmt = $pdo->prepare('SELECT id, nama, username, peran, created_at FROM users WHERE id = ?');
    }
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User tidak ditemukan!']);
        exit;
    }
    
    // Return data profil
    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $user['id'],
            'nama' => $user['nama'],
            'username' => $user['username'],
            'peran' => $user['peran'],
            'foto_profil' => ($hasFotoProfilColumn && isset($user['foto_profil'])) ? $user['foto_profil'] : null,
            'created_at' => $user['created_at']
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
