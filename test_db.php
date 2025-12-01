<?php
// Script di test per verificare la connessione al DB tramite Database::getConnection()
require __DIR__ . '/config/config.php';
require __DIR__ . '/app/Models/Database.php';

try {
    $pdo = Database::getConnection();
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo "Connessione al DB stabilita. Versione MySQL: {$version}\n";
    // Mostra le tabelle per conferma
    $stmt = $pdo->query("SHOW TABLES;");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tabelle nel DB (prime 20):\n";
    foreach (array_slice($tables, 0, 20) as $t) {
        echo " - {$t}\n";
    }
} catch (Exception $e) {
    echo "Connessione fallita: " . $e->getMessage() . "\n";
    echo "Verifica: file 'config/config.php' con credenziali corrette, servizio MySQL avviato e estensione pdo_mysql abilitata in php.ini.\n";
}
