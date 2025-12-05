<?php
/**
 * Script per aggiungere tracciamento carta nei pagamenti e vista riepilogativa acquisti
 * Esegui: php scripts/add_purchase_tracking.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Models/Database.php';

try {
    $pdo = Database::getConnection();
    
    echo "=== Aggiornamento Database: Tracciamento Acquisti ===\n\n";
    
    // Step 1: Aggiungi colonna payment_card_id alla tabella payments
    echo "Step 1: Aggiunta colonna payment_card_id a payments...\n";
    try {
        $pdo->exec("
            ALTER TABLE payments 
            ADD COLUMN payment_card_id INT DEFAULT NULL AFTER order_id,
            ADD CONSTRAINT fk_payments_card 
            FOREIGN KEY (payment_card_id) REFERENCES payment_cards(id) ON DELETE SET NULL
        ");
        echo "✓ Colonna payment_card_id aggiunta con successo\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "ℹ Colonna payment_card_id già esistente\n";
        } else {
            throw $e;
        }
    }
    
    // Step 2: Aggiungi indice per performance
    echo "\nStep 2: Creazione indice su payment_card_id...\n";
    try {
        $pdo->exec("CREATE INDEX idx_payments_card ON payments(payment_card_id)");
        echo "✓ Indice idx_payments_card creato\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key') !== false) {
            echo "ℹ Indice idx_payments_card già esistente\n";
        } else {
            throw $e;
        }
    }
    
    // Step 3: Crea vista riepilogativa acquisti
    echo "\nStep 3: Creazione vista purchase_summary...\n";
    $pdo->exec("DROP VIEW IF EXISTS purchase_summary");
    $pdo->exec("
        CREATE VIEW purchase_summary AS
        SELECT 
          o.id AS order_id,
          o.user_id,
          u.email AS user_email,
          u.full_name AS user_name,
          o.created_at AS order_date,
          o.status AS order_status,
          o.total_amount AS order_total,
          oi.id AS order_item_id,
          oi.product_id,
          p.name AS product_name,
          p.sku AS product_sku,
          oi.quantity,
          oi.unit_price,
          oi.subtotal,
          pay.id AS payment_id,
          pay.method AS payment_method,
          pay.status AS payment_status,
          pay.transaction_ref,
          pc.id AS card_id,
          pc.cardholder_name,
          pc.card_brand,
          pc.card_last4,
          a.recipient_name AS shipping_recipient,
          a.city AS shipping_city,
          a.country AS shipping_country
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        LEFT JOIN payments pay ON o.id = pay.order_id
        LEFT JOIN payment_cards pc ON pay.payment_card_id = pc.id
        LEFT JOIN addresses a ON o.address_id = a.id
        ORDER BY o.created_at DESC, oi.id
    ");
    echo "✓ Vista purchase_summary creata con successo\n";
    
    // Step 4: Test della vista
    echo "\nStep 4: Test vista purchase_summary...\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM purchase_summary");
    $count = $stmt->fetchColumn();
    echo "✓ Vista funzionante: $count record trovati\n";
    
    // Step 5: Mostra esempio di utilizzo
    echo "\n=== Esempio di Query Riepilogativa ===\n";
    $stmt = $pdo->query("
        SELECT 
            order_id,
            user_name,
            product_name,
            quantity,
            subtotal,
            card_last4,
            order_date
        FROM purchase_summary
        LIMIT 5
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($results) {
        echo "\nUltimi acquisti:\n";
        foreach ($results as $row) {
            $cardInfo = $row['card_last4'] ? "**** {$row['card_last4']}" : "N/A";
            echo sprintf(
                "- Ordine #%d | %s | %s (x%d) | €%.2f | Carta: %s | Data: %s\n",
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
        echo "Nessun acquisto presente nel database.\n";
    }
    
    echo "\n=== ✓ Aggiornamento Completato ===\n";
    echo "\nLa vista 'purchase_summary' è ora disponibile per query rapide.\n";
    echo "Esempio di utilizzo:\n";
    echo "  SELECT * FROM purchase_summary WHERE user_id = 1;\n";
    echo "  SELECT * FROM purchase_summary WHERE order_id = 5;\n";
    echo "  SELECT * FROM purchase_summary WHERE card_last4 = '1234';\n\n";
    
} catch (Exception $e) {
    echo "ERRORE: " . $e->getMessage() . "\n";
    exit(1);
}
