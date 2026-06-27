<?php
date_default_timezone_set('Asia/Bangkok');

$host = '127.0.0.1';
$db = 'pwl_uas_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

function runInstaller(PDO $serverPdo, string $db): void
{
    $install = static function () use ($serverPdo): void {
        $sql = file_get_contents(__DIR__ . '/install.sql');
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
            $serverPdo->exec($statement);
        }
    };

    $stmt = $serverPdo->prepare('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?');
    $stmt->execute([$db]);
    $exists = (bool) $stmt->fetchColumn();

    if (!$exists) {
        $install();
        return;
    }

    $serverPdo->exec("USE `$db`");
    $tables = ['users', 'services', 'bookings'];
    foreach ($tables as $table) {
        $stmt = $serverPdo->prepare('SHOW TABLES LIKE ?');
        $stmt->execute([$table]);
        if (!$stmt->fetchColumn()) {
            $install();
            return;
        }
    }
}

try {
    $serverDsn = "mysql:host=$host;charset=$charset";
    $serverPdo = new PDO($serverDsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    runInstaller($serverPdo, $db);

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
