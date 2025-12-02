<?php
/**
 * Script di setup per aggiungere colonne category e size_tag alla tabella products
 * e popolare (seed) il database con i 9 tipi di pannelli solari.
 * 
 * Eseguire da terminale: php scripts/setup_products.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Models/Database.php';

$pdo = Database::getConnection();

echo "=== Setup Products Database ===\n\n";

// Step 1: Aggiungere colonne category e size_tag alla tabella products (se non esistono già)
echo "Step 1: Verifica e aggiunta colonne 'category' e 'size_tag'...\n";

try {
    // Controlla se category esiste
    $checkCategory = $pdo->query("SHOW COLUMNS FROM products LIKE 'category'")->fetch();
    if (!$checkCategory) {
        $pdo->exec("ALTER TABLE products ADD COLUMN category VARCHAR(100) DEFAULT NULL AFTER description");
        echo "  ✓ Colonna 'category' aggiunta.\n";
    } else {
        echo "  ✓ Colonna 'category' già presente.\n";
    }

    // Controlla se size_tag esiste
    $checkSize = $pdo->query("SHOW COLUMNS FROM products LIKE 'size_tag'")->fetch();
    if (!$checkSize) {
        $pdo->exec("ALTER TABLE products ADD COLUMN size_tag VARCHAR(50) DEFAULT NULL AFTER category");
        echo "  ✓ Colonna 'size_tag' aggiunta.\n";
    } else {
        echo "  ✓ Colonna 'size_tag' già presente.\n";
    }
} catch (PDOException $e) {
    die("Errore durante la modifica dello schema: " . $e->getMessage() . "\n");
}

echo "\nStep 2: Inserimento dei 9 tipi di pannelli solari...\n";

// Definiamo i 9 prodotti (3 categorie x 3 dimensioni)
$products = [
    // 1. MONOCRISTALLINO
    [
        'sku' => 'MONO-S-BALCONE',
        'name' => 'Pannello Monocristallino Full Black - Balcone',
        'description' => 'Pannello compatto per kit plug & play da balcone. Estetica curata e massima efficienza per metro quadro. Facile da spedire.',
        'category' => 'Monocristallino',
        'size_tag' => 'Small',
        'power_watt' => 150,
        'efficiency' => 20.5,
        'price' => 199.00,
        'stock' => 50
    ],
    [
        'sku' => 'MONO-M-RESIDENZIALE',
        'name' => 'Pannello Monocristallino Full Black - Residenziale Standard',
        'description' => 'La misura standard (circa 170x113 cm). Best-seller assoluto per i tetti delle case. Estetica nera elegante ed efficiente.',
        'category' => 'Monocristallino',
        'size_tag' => 'Medium',
        'power_watt' => 420,
        'efficiency' => 21.2,
        'price' => 349.00,
        'stock' => 100
    ],
    [
        'sku' => 'MONO-L-COMMERCIALE',
        'name' => 'Pannello Monocristallino Full Black - Commerciale',
        'description' => 'Pannelli più grandi e pesanti (oltre 2 metri di altezza), pensati per capannoni industriali. Potenza bruta.',
        'category' => 'Monocristallino',
        'size_tag' => 'Large',
        'power_watt' => 580,
        'efficiency' => 21.8,
        'price' => 499.00,
        'stock' => 30
    ],

    // 2. BIFACCIALE
    [
        'sku' => 'BIFA-S-GARDEN',
        'name' => 'Pannello Bifacciale - Garden',
        'description' => 'Moduli vetro-vetro più piccoli, ideali per recinzioni fotovoltaiche o ringhiere. Producono energia da entrambi i lati.',
        'category' => 'Bifacciale',
        'size_tag' => 'Small',
        'power_watt' => 300,
        'efficiency' => 19.5,
        'price' => 279.00,
        'stock' => 40
    ],
    [
        'sku' => 'BIFA-M-PERGOLA',
        'name' => 'Pannello Bifacciale - Pergola',
        'description' => 'Perfetti per creare tettoie semi-trasparenti che fanno ombra ma lasciano passare luce, producendo energia da entrambi i lati.',
        'category' => 'Bifacciale',
        'size_tag' => 'Medium',
        'power_watt' => 450,
        'efficiency' => 20.0,
        'price' => 429.00,
        'stock' => 60
    ],
    [
        'sku' => 'BIFA-L-GROUND',
        'name' => 'Pannello Bifacciale - Ground Utility',
        'description' => 'Enormi moduli per impianti a terra in campo aperto (agrivoltaico o parchi solari). Massima potenza bifacciale.',
        'category' => 'Bifacciale',
        'size_tag' => 'Large',
        'power_watt' => 650,
        'efficiency' => 21.0,
        'price' => 599.00,
        'stock' => 25
    ],

    // 3. FLESSIBILE / PORTATILE
    [
        'sku' => 'FLEX-XS-ZAINO',
        'name' => 'Pannello Flessibile - Zaino Portatile',
        'description' => 'Pannelli pieghevoli (foldable) in tessuto, per ricaricare power station o telefoni in campeggio. Leggero e trasportabile.',
        'category' => 'Flessibile',
        'size_tag' => 'Portable',
        'power_watt' => 80,
        'efficiency' => 18.0,
        'price' => 149.00,
        'stock' => 80
    ],
    [
        'sku' => 'FLEX-S-MARINE',
        'name' => 'Pannello Flessibile - Marine',
        'description' => 'Pannelli sottili e calpestabili da incollare sulla tuga delle barche a vela o superfici curve. Resistente all\'acqua salata.',
        'category' => 'Flessibile',
        'size_tag' => 'Small Flex',
        'power_watt' => 150,
        'efficiency' => 18.5,
        'price' => 229.00,
        'stock' => 35
    ],
    [
        'sku' => 'FLEX-L-VANLIFE',
        'name' => 'Pannello Flessibile - Van Life',
        'description' => 'Strisce lunghe e potenti da incollare sul tetto dei camper o furgoni convertiti, dove il peso è un fattore critico.',
        'category' => 'Flessibile',
        'size_tag' => 'Large Flex',
        'power_watt' => 300,
        'efficiency' => 19.0,
        'price' => 399.00,
        'stock' => 45
    ],
];

// Inserimento con INSERT IGNORE per evitare duplicati (basato su SKU UNIQUE)
$stmt = $pdo->prepare("
    INSERT INTO products (sku, name, description, category, size_tag, power_watt, efficiency, price, stock)
    VALUES (:sku, :name, :description, :category, :size_tag, :power_watt, :efficiency, :price, :stock)
    ON DUPLICATE KEY UPDATE
        name = VALUES(name),
        description = VALUES(description),
        category = VALUES(category),
        size_tag = VALUES(size_tag),
        power_watt = VALUES(power_watt),
        efficiency = VALUES(efficiency),
        price = VALUES(price),
        stock = VALUES(stock)
");

$count = 0;
foreach ($products as $p) {
    try {
        $stmt->execute($p);
        $count++;
        echo "  ✓ Inserito/Aggiornato: {$p['name']} ({$p['sku']})\n";
    } catch (PDOException $e) {
        echo "  ✗ Errore per {$p['sku']}: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Setup Completato! ===\n";
echo "Totale prodotti inseriti/aggiornati: $count\n";
echo "\nPuoi ora visualizzare i prodotti su: http://localhost:8000/index.php?route=products\n";
