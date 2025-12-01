<?php
// Carica variabili da file .env nella root del progetto (opzionale)
// Formato .env: KEY=value (commenti con #)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) { continue; }
        if (strpos($line, '=') === false) { continue; }
        list($key, $val) = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        // rimuovi virgolette se presenti
        if ((substr($val, 0, 1) === '"' && substr($val, -1) === '"') || (substr($val, 0, 1) === "'" && substr($val, -1) === "'")) {
            $val = substr($val, 1, -1);
        }
        putenv("$key=$val");
        $_ENV[$key] = $val;
        $_SERVER[$key] = $val;
    }
}

// Configurazione: usa `root` come default per facilitare lo sviluppo locale.
// Per la produzione, sovrascrivi queste impostazioni con variabili d'ambiente
// oppure modifica direttamente questo file.
return [
    'db' => [
        'host' => getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'mysql-test',
        'port' => getenv('DB_PORT') !== false ? (int)getenv('DB_PORT') : 3306,
        'dbname' => getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'testdb',
        // Default a `root` per user e password; si può sovrascrivere con
        // le variabili d'ambiente `DB_USER` e `DB_PASS`.
        'user' => getenv('DB_USER') !== false ? getenv('DB_USER') : 'root',
        'pass' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'root',
        'charset' => 'utf8mb4',
    ],
];
