<?php

/**
 * api/barang.php — CRUD Barang
 * GET    → list semua barang (+ filter)
 * POST   → tambah barang  [admin]
 * PUT    → edit barang    [admin]
 * DELETE → soft-delete barang   [admin]
 * GET ?recycle=1 → daftar barang terhapus   [admin]
 * PUT ?action=restore → pulihkan barang   [admin]
 * DELETE ?permanent=1 → hapus permanen   [admin]
 */
define('BASE_URL', '../');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
requireLogin();

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$pdo    = getDB();
$user   = getCurrentUser();

// ─── GET ──────────────────────────────────────────────────────────────────────
if ($method === 'GET') {
    $recycle = !empty($_GET['recycle']);
    if ($recycle) {
        requireRole(['admin']);
    }

    $where = ['1=1'];
    $params = [];

    $where[] = $recycle ? 'b.deleted_at IS NOT NULL' : 'b.deleted_at IS NULL';

    if (!empty($_GET['q'])) {
        $where[] = '(b.nama LIKE ? OR b.kode LIKE ? OR b.jenis LIKE ? OR b.lokasi LIKE ? OR k.nama LIKE ?)';
        $q = '%' . $_GET['q'] . '%';
        for ($i = 0; $i < 5; $i++) {
            $params[] = $q;
        }
    }
    if (!empty($_GET['kategori'])) {
        $where[] = 'k.nama = ?';
        $params[] = $_GET['kategori'];
    }
    if (!empty($_GET['kondisi'])) {
        $where[] = 'b.kondisi = ?';
        $params[] = $_GET['kondisi'];
    }

    $sql = "
        SELECT b.*, k.nama as kategori, k.ikon
        FROM barang b
        JOIN kategori k ON k.id = b.kategori_id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY b.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll());
    exit;
}

// ─── POST (Tambah) ────────────────────────────────────────────────────────────
if ($method === 'POST') {
    requireRole(['admin']);
    $d = json_decode(file_get_contents('php://input'), true);

    if (empty($d['nama']) || empty($d['kategori_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Nama dan Kategori wajib diisi']);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO barang (nama, kategori_id, jenis, kode, tahun, kondisi, sumber, lokasi, catatan, user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $d['nama'],
        $d['kategori_id'],
        $d['jenis']   ?? null,
        $d['kode']    ?? null,
        $d['tahun']   ?? null,
        $d['kondisi'] ?? 'Baik',
        $d['sumber']  ?? 'Dana Sekolah',
        $d['lokasi']  ?? null,
        $d['catatan'] ?? null,
        $user['id'],
    ]);
    $newId = $pdo->lastInsertId();

    $pdo->prepare('INSERT INTO riwayat (aksi, user_id) VALUES (?, ?)')
        ->execute(["Barang \"{$d['nama']}\" ditambahkan", $user['id']]);

    echo json_encode(['success' => true, 'id' => $newId]);
    exit;
}

// ─── PUT (Edit) ───────────────────────────────────────────────────────────────
if ($method === 'PUT') {
    requireRole(['admin']);
    $d  = json_decode(file_get_contents('php://input'), true);
    $id = (int) ($d['id'] ?? 0);

    if (($_GET['action'] ?? '') === 'restore') {
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID tidak valid']);
            exit;
        }
        $stmt = $pdo->prepare('UPDATE barang SET deleted_at=NULL WHERE id=? AND deleted_at IS NOT NULL');
        $stmt->execute([$id]);
        if (!$stmt->rowCount()) {
            http_response_code(404);
            echo json_encode(['error' => 'Barang tidak ditemukan di recycle bin']);
            exit;
        }
        echo json_encode(['success' => true]);
        exit;
    }

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID tidak valid']);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE barang SET nama=?, kategori_id=?, jenis=?, kode=?, tahun=?,
        kondisi=?, sumber=?, lokasi=?, catatan=?, status=?
        WHERE id=? AND deleted_at IS NULL
    ");
    $stmt->execute([
        $d['nama'],
        $d['kategori_id'],
        $d['jenis']   ?? null,
        $d['kode']    ?? null,
        $d['tahun']   ?? null,
        $d['kondisi'] ?? 'Baik',
        $d['sumber']  ?? 'Dana Sekolah',
        $d['lokasi']  ?? null,
        $d['catatan'] ?? null,
        $d['status']  ?? 'Tersedia',
        $id,
    ]);

    $pdo->prepare('INSERT INTO riwayat (aksi, user_id) VALUES (?, ?)')
        ->execute(["Barang \"{$d['nama']}\" diperbarui", $user['id']]);

    echo json_encode(['success' => true]);
    exit;
}

// ─── DELETE ───────────────────────────────────────────────────────────────────
if ($method === 'DELETE') {
    requireRole(['admin']);
    $id = (int) ($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID tidak valid']);
        exit;
    }

    $nama = $pdo->prepare('SELECT nama, deleted_at FROM barang WHERE id=?');
    $nama->execute([$id]);
    $row = $nama->fetch();

    if (!$row || (empty($_GET['permanent']) && $row['deleted_at'] !== null)) {
        http_response_code(404);
        echo json_encode(['error' => 'Barang tidak ditemukan']);
        exit;
    }

    if (!empty($_GET['permanent'])) {
        $pdo->beginTransaction();
        $pdo->prepare('DELETE FROM peminjaman WHERE barang_id=?')->execute([$id]);
        $pdo->prepare('DELETE FROM barang WHERE id=? AND deleted_at IS NOT NULL')->execute([$id]);
        $pdo->commit();
        echo json_encode(['success' => true]);
        exit;
    }

    $pdo->prepare('UPDATE barang SET deleted_at=NOW() WHERE id=? AND deleted_at IS NULL')->execute([$id]);

    $pdo->prepare('INSERT INTO riwayat (aksi, user_id) VALUES (?, ?)')
        ->execute(["Barang \"{$row['nama']}\" dihapus", $user['id']]);

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
