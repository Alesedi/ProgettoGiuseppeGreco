<?php
/**
 * Script per creare tabella fisica purchase_history (invece di vista)
 * Questa tabella sarà popolata automaticamente ad ogni acquisto
 * Esegui: php scripts/create_purchase_history_table.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Models/Database.php';

try {
    $pdo = Database::getConnection();
    
    echo "=== Creazione Tabella Purchase History ===\n\n";
    
    // Step 1: Crea tabella purchase_history
    echo "Step 1: Creazione tabella purchase_history...\n";
    
    $pdo->exec("DROP TABLE IF EXISTS purchase_history");
    
    $pdo->exec("
        CREATE TABLE purchase_history (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            user_id INT NOT NULL,
            user_email VARCHAR(255),
            user_name VARCHAR(255),
            product_id INT NOT NULL,
            product_name VARCHAR(255),
            product_sku VARCHAR(100),
            quantity INT NOT NULL,
            unit_price DECIMAL(10,2) NOT NULL,
            subtotal DECIMAL(12,2) NOT NULL,
            order_total DECIMAL(12,2),
            payment_method VARCHAR(50),
            payment_status VARCHAR(50),
            card_id INT DEFAULT NULL,
            card_last4 VARCHAR(4),
            cardholder_name VARCHAR(255),
            shipping_city VARCHAR(100),
            shipping_country VARCHAR(100),
            order_status VARCHAR(50),
            order_date DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_order_id (order_id),
            INDEX idx_user_id (user_id),
            INDEX idx_product_id (product_id),
            INDEX idx_card_last4 (card_last4),
            INDEX idx_order_date (order_date),
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    echo "✓ Tabella purchase_history creata con successo\n";
    
    // Step 2: Popola con dati esistenti
    echo "\nStep 2: Popolamento con ordini esistenti...\n";
    
    $pdo->exec("
        INSERT INTO purchase_history (
            order_id, user_id, user_email, user_name,
            product_id, product_name, product_sku,
            quantity, unit_price, subtotal, order_total,
            payment_method, payment_status,
            card_id, card_last4, cardholder_name,
            shipping_city, shipping_country,
            order_status, order_date
        )
        SELECT 
            o.id AS order_id,
            o.user_id,
            u.email AS user_email,
            u.full_name AS user_name,
            oi.product_id,
            p.name AS product_name,
            p.sku AS product_sku,
            oi.quantity,
            oi.unit_price,
            oi.subtotal,
            o.total_amount AS order_total,
            pay.method AS payment_method,
            pay.status AS payment_status,
            pc.id AS card_id,
            pc.card_last4,
            pc.cardholder_name,
            a.city AS shipping_city,
            a.country AS shipping_country,
            o.status AS order_status,
            o.created_at AS order_date
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        LEFT JOIN payments pay ON o.id = pay.order_id
        LEFT JOIN payment_cards pc ON pay.payment_card_id = pc.id
        LEFT JOIN addresses a ON o.address_id = a.id
    ");
    
    $count = $pdo->query("SELECT COUNT(*) FROM purchase_history")->fetchColumn();
    echo "✓ $count record importati da ordini esistenti\n";
    
    // Step 3: Rimuovi la vista se esiste
    echo "\nStep 3: Pulizia vista purchase_summary (se esiste)...\n";
    try {
        $pdo->exec("DROP VIEW IF EXISTS purchase_summary");
        echo "✓ Vista purchase_summary rimossa\n";
    } catch (Exception $e) {
        echo "ℹ Vista non esistente o già rimossa\n";
    }
    
    // Step 4: Mostra esempio dati
    echo "\n=== Esempio Record da purchase_history ===\n";
    $stmt = $pdo->query("
        SELECT 
            id,
            order_id,
            user_name,
            product_name,
            quantity,
            subtotal,
            card_last4,
            order_date
        FROM purchase_history
        ORDER BY order_date DESC
        LIMIT 5
    ");
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($results) {
        echo "\nUltimi acquisti:\n";
        foreach ($results as $row) {
            $cardInfo = $row['card_last4'] ? "**** {$row['card_last4']}" : "N/A";
            echo sprintf(
                "- ID: %d | Ordine #%d | %s | %s (x%d) | €%.2f | Carta: %s | %s\n",
                $row['id'],
                $row['order_id'],
                $row['user_name'] ?? 'Guest',
                $row['product_name'],
                $row['quantity'],
                $row['subtotal'],
                $cardInfo,
                $row['order_date']
            );
        }
    } else {
        echo "Nessun record presente.\n";
    }
    
    echo "\n=== ✓ Tabella purchase_history Creata ===\n";
    echo "\nOra puoi visualizzarla in DBeaver come una normale tabella!\n";
    echo "La tabella sarà aggiornata automaticamente ad ogni nuovo acquisto.\n\n";
    echo "Query di esempio:\n";
    echo "  SELECT * FROM purchase_history WHERE user_id = 1;\n";
    echo "  SELECT * FROM purchase_history WHERE order_id = 5;\n";
    echo "  SELECT * FROM purchase_history WHERE card_last4 = '1234';\n";
    echo "  SELECT product_name, SUM(quantity) as vendite FROM purchase_history GROUP BY product_name;\n\n";
    
} catch (Exception $e) {
    echo "ERRORE: " . $e->getMessage() . "\n";
    exit(1);
}
