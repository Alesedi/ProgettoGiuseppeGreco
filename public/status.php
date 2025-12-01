<?php
// Status endpoint per verificare la connessione al DB
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../app/Models/Database.php';
// Mostra errori in sviluppo
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Status DB - SoleDomus</title>
  <link href="/public/css/style.css" rel="stylesheet">
</head>
<body class="theme-bg">
  <div class="container py-5">
    <div class="card p-4">
      <h3>Status connessione DB</h3>
      <pre>
<?php
try {
    $pdo = Database::getConnection();
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    echo "Stato: OK\n";
    echo "Server version: " . $version . "\n\n";
    // test semplice: conteggio tabelle e primo user
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tabelle trovate: " . count($tables) . "\n";
    if (in_array('users', $tables)) {
        $u = $pdo->query("SELECT id, email, created_at FROM users ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        echo "\nUltimi utenti (max 5):\n";
        foreach ($u as $row) {
            echo "- ({$row['id']}) {$row['email']} - {$row['created_at']}\n";
        }
    } else {
        echo "\nTabella 'users' non trovata nel DB.\n";
    }
} catch (Exception $e) {
    echo "Stato: ERRORE\n";
    echo "Messaggio: " . $e->getMessage() . "\n";
    echo "Verifica: `config/config.php` (credenziali), MySQL attivo e `pdo_mysql` abilitato in php.ini.\n";
}
?>
      </pre>
      <a class="btn btn-sd" href="/index.php">Torna alla Home</a>
    </div>
  </div>
</body>
</html>
