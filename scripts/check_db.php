<?php
// scripts/check_db.php
// Verifica connessione PDO usando config/config.php
require_once __DIR__ . '/../config/config.php';
$cfg = require __DIR__ . '/../config/config.php';

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $cfg['db']['host'],
        $cfg['db']['port'],
        $cfg['db']['dbname'],
        $cfg['db']['charset']
    );

    $pdo = new PDO($dsn, $cfg['db']['user'], $cfg['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Connected to MySQL successfully\n";
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo "MySQL version: " . $version . "\n";

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    if ($tables) {
        echo "Tables found: " . implode(', ', $tables) . "\n";
    } else {
        echo "No tables found in database `{$cfg['db']['dbname']}`\n";
    }

    exit(0);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
