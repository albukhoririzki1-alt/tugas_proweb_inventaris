<?php

/**
 * api/profil.php
 * API Profil Saya — Admin
 */

define('BASE_URL', '../');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

requireRole('admin');

header('Content-Type: application/json');

$pdo  = getDB();
$user = getCurrentUser();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {

    $stmt = $pdo->prepare(
        'SELECT id, nama, foto, username, peran
         FROM users
         WHERE id = ?'
    );

    $stmt->execute([$user['id']]);

    $data = $stmt->fetch();

    if (!$data) {
        http_response_code(404);
        echo json_encode([
            'error' => 'Profil tidak ditemukan'
        ]);
        exit;
    }

    echo json_encode($data);
    exit;
}

if ($method === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $passwordLama = $_POST['password_lama'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($nama === '' || $username === '') {
        http_response_code(400);
        echo json_encode([
            'error' => 'Nama dan username wajib diisi'
        ]);
        exit;
    }

    // VALIDASI PASSWORD LAMA WAJIB
    if ($passwordLama === '') {
        http_response_code(400);
        echo json_encode([
            'error' => 'Password lama wajib diisi untuk verifikasi'
        ]);
        exit;
    }

    // VERIFIKASI PASSWORD LAMA
    $userCheck = $pdo->prepare(
        'SELECT password
         FROM users
         WHERE id = ?'
    );
    $userCheck->execute([$user['id']]);
    $userData = $userCheck->fetch();

    if (!$userData || !password_verify($passwordLama, $userData['password'])) {
        http_response_code(401);
        echo json_encode([
            'error' => 'Password lama tidak valid'
        ]);
        exit;
    }

    // Cek username milik user lain
    $cek = $pdo->prepare(
        'SELECT id
         FROM users
         WHERE username = ?
         AND id != ?'
    );

    $cek->execute([
        $username,
        $user['id']
    ]);

    if ($cek->fetch()) {
        http_response_code(409);
        echo json_encode([
            'error' => 'Username sudah digunakan'
        ]);
        exit;
    }

    // =========================
    // FOTO
    // =========================

    $fotoPath = null;

    if (
        isset($_FILES['foto']) &&
        $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Gagal mengupload foto'
            ]);
            exit;
        }

        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Ukuran foto maksimal 2 MB'
            ]);
            exit;
        }

        $tmp = $_FILES['foto']['tmp_name'];

        $mime = mime_content_type($tmp);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        if (!isset($allowed[$mime])) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Format foto harus JPG, PNG, atau WebP'
            ]);
            exit;
        }

        $folder = __DIR__ . '/../assets/uploads/profiles/';

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename =
            'profile_' .
            $user['id'] .
            '_' .
            time() .
            '.' .
            $allowed[$mime];

        $destination = $folder . $filename;

        if (!move_uploaded_file($tmp, $destination)) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Foto gagal disimpan'
            ]);
            exit;
        }

        $fotoPath = 'assets/uploads/profiles/' . $filename;
    }

    // =========================
    // UPDATE DATABASE
    // =========================

    $fields = [
        'nama = ?',
        'username = ?'
    ];

    $params = [
        $nama,
        $username
    ];

    if ($fotoPath !== null) {
        $fields[] = 'foto = ?';
        $params[] = $fotoPath;
    }

    if ($password !== '') {

        if (strlen($password) < 6) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Password minimal 6 karakter'
            ]);
            exit;
        }

        if ($password !== $passwordConfirm) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Konfirmasi password tidak cocok'
            ]);
            exit;
        }

        $fields[] = 'password = ?';
        $params[] = password_hash(
            $password,
            PASSWORD_BCRYPT
        );
    }

    $params[] = $user['id'];

    $sql =
        'UPDATE users SET ' .
        implode(', ', $fields) .
        ' WHERE id = ?';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Update session
    $_SESSION['nama'] = $nama;
    $_SESSION['username'] = $username;

    // Catat riwayat
    $pdo->prepare(
        'INSERT INTO riwayat (aksi, user_id)
         VALUES (?, ?)'
    )->execute([
        'Profil akun diperbarui',
        $user['id']
    ]);

    echo json_encode([
        'success' => true,
        'nama' => $nama,
        'username' => $username,
        'foto' => $fotoPath
    ]);

    exit;
}

http_response_code(405);

echo json_encode([
    'error' => 'Method not allowed'
]);