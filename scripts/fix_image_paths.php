<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Models/Database.php';

$pdo = Database::getConnection();

echo "Correzione percorsi immagini...\n";

$pdo->exec("UPDATE products SET image = REPLACE(image, '/public/', '/') WHERE image LIKE '/public/%'");

echo "✓ Percorsi aggiornati da '/public/images/...' a '/images/...'\n";

$products = $pdo->query("SELECT sku, image FROM products")->fetchAll(PDO::FETCH_ASSOC);
foreach ($products as $p) {
    echo "  {$p['sku']}: {$p['image']}\n";
}
