<?php

/**
 * api/update_profil.php
 * API endpoint untuk update profil user (nama, username, password, foto)
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

// Pastikan user sudah login
requireLogin();

$currentUser = getCurrentUser();
$userId = $currentUser['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $pdo = getDB();
    
    // Ambil data dari POST
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $passwordLama = $_POST['password_lama'] ?? '';
    $passwordBaru = $_POST['password_baru'] ?? '';
    
    // Validasi input
    if (empty($nama) || empty($username)) {
        echo json_encode(['success' => false, 'message' => 'Nama dan username harus diisi!']);
        exit;
    }
    
    // Cek apakah username sudah digunakan oleh user lain
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
    $stmt->execute([$username, $userId]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username sudah digunakan!']);
        exit;
    }
    
    // Ambil data user saat ini
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User tidak ditemukan!']);
        exit;
    }
    
    // Cek apakah kolom foto_profil ada
    $hasFotoProfilColumn = false;
    try {
        $columnCheck = $pdo->query("SHOW COLUMNS FROM users LIKE 'foto_profil'")->fetch();
        $hasFotoProfilColumn = !empty($columnCheck);
    } catch (Exception $e) {
        // Kolom belum ada, skip
    }
    
    // Handle foto profil upload
    $fotoProfilPath = $hasFotoProfilColumn && isset($user['foto_profil']) ? $user['foto_profil'] : null;
    
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto_profil'];
        
        // Validasi tipe file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $fileType = mime_content_type($file['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Format file tidak didukung! Gunakan JPG atau PNG.']);
            exit;
        }
        
        // Validasi ukuran file (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar! Maksimal 2MB.']);
            exit;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profil_' . $userId . '_' . time() . '.' . $extension;
        $uploadDir = __DIR__ . '/../uploads/profil/';
        $uploadPath = $uploadDir . $filename;
        
        // Pastikan direktori ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Hapus foto lama jika ada
            if ($hasFotoProfilColumn && !empty($user['foto_profil']) && file_exists(__DIR__ . '/../' . $user['foto_profil'])) {
                @unlink(__DIR__ . '/../' . $user['foto_profil']);
            }
            
            $fotoProfilPath = 'uploads/profil/' . $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengupload foto!']);
            exit;
        }
    }
    
    // Prepare update query
    $updateFields = ['nama = ?', 'username = ?'];
    $updateParams = [$nama, $username];
    
    // Tambahkan foto_profil jika kolom ada
    if ($hasFotoProfilColumn) {
        $updateFields[] = 'foto_profil = ?';
        $updateParams[] = $fotoProfilPath;
    }
    
    // Handle password update jika diisi
    if (!empty($passwordLama) && !empty($passwordBaru)) {
        // Verifikasi password lama
        if (!password_verify($passwordLama, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Password lama salah!']);
            exit;
        }
        
        // Validasi password baru
        if (strlen($passwordBaru) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password baru minimal 6 karakter!']);
            exit;
        }
        
        // Hash password baru
        $updateFields[] = 'password = ?';
        $updateParams[] = password_hash($passwordBaru, PASSWORD_DEFAULT);
    }
    
    // Add user ID untuk WHERE clause
    $updateParams[] = $userId;
    
    // Execute update
    $sql = 'UPDATE users SET ' . implode(', ', $updateFields) . ' WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($updateParams);
    
    // Update session data
    $_SESSION['nama'] = $nama;
    $_SESSION['username'] = $username;
    
    // Log aktivitas (hanya jika tabel riwayat ada)
    try {
        $tableExists = $pdo->query("SHOW TABLES LIKE 'riwayat'")->fetch();
        if ($tableExists) {
            $pdo->prepare('INSERT INTO riwayat (aksi, user_id) VALUES (?, ?)')
                ->execute(["Update Profil: {$nama}", $userId]);
        }
    } catch (Exception $e) {
        // Skip logging jika tabel tidak ada
    }
    
    // Return success dengan data terbaru
    echo json_encode([
        'success' => true,
        'message' => 'Profil berhasil diperbarui!',
        'data' => [
            'nama' => $nama,
            'username' => $username,
            'foto_profil' => $fotoProfilPath ? $fotoProfilPath : null
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
