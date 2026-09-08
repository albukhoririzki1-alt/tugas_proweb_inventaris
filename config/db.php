<?php
/**
 * config/db.php
 * Koneksi PDO ke database MySQL inventaris_db
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'inventaris_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHAR', 'utf8mb4');
define('TRASH_RETENTION_DAYS', 30);

function purgeExpiredBarang(PDO $pdo): void
{
    $pdo->beginTransaction();
    try {
        $expiredCondition = 'b.deleted_at IS NOT NULL AND b.deleted_at <= DATE_SUB(NOW(), INTERVAL ' . (int) TRASH_RETENTION_DAYS . ' DAY)';

        $pdo->exec("DELETE p FROM peminjaman p INNER JOIN barang b ON b.id = p.barang_id WHERE {$expiredCondition}");
        $pdo->exec("DELETE FROM barang WHERE deleted_at IS NOT NULL AND deleted_at <= DATE_SUB(NOW(), INTERVAL " . (int) TRASH_RETENTION_DAYS . " DAY)");

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHAR;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            $column = $pdo->query("SHOW COLUMNS FROM barang LIKE 'deleted_at'")->fetch();
            if (!$column) {
                $pdo->exec('ALTER TABLE barang ADD deleted_at DATETIME DEFAULT NULL AFTER status');
            }
            purgeExpiredBarang($pdo);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Koneksi database gagal: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}
