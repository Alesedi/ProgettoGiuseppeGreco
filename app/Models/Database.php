<?php
class Database
{
    private static $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];
            $host = $db['host'] ?? '127.0.0.1';
            $port = $db['port'] ?? 3306;
            $dbname = $db['dbname'] ?? '';
            $charset = $db['charset'] ?? 'utf8mb4';
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
            try {
                self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                // In sviluppo mostrale, in produzione loggare
                die('DB Connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
