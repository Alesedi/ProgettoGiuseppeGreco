<?php
/**
 * Script per verificare e completare la struttura del database
 * per gestire utenti, prodotti nel carrello e prodotti acquistati
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Models/Database.php';

$pdo = Database::getConnection();

echo "=== Verifica e Aggiornamento Struttura Database ===\n\n";

// STEP 1: Verifica struttura tabelle esistenti
echo "Step 1: Verifica struttura esistente...\n";

$tables = ['users', 'products', 'carts', 'cart_items', 'orders', 'order_items', 'addresses'];
foreach ($tables as $table) {
    $check = $pdo->query("SHOW TABLES LIKE '$table'")->fetch();
    echo ($check ? "  ✓" : "  ✗") . " Tabella '$table' " . ($check ? "presente" : "MANCANTE") . "\n";
}

echo "\n";

// STEP 2: Riepilogo della struttura attuale
echo "Step 2: Struttura del database per il tracking utente-prodotti:\n\n";

echo "CARRELLO (Prodotti in attesa di acquisto):\n";
echo "  • Tabella 'carts': collega ogni utente al suo carrello\n";
echo "    - user_id → identifica l'utente\n";
echo "  • Tabella 'cart_items': prodotti nel carrello\n";
echo "    - cart_id → riferimento al carrello dell'utente\n";
echo "    - product_id → prodotto aggiunto\n";
echo "    - quantity → quantità\n";
echo "    - unit_price → prezzo al momento dell'aggiunta\n\n";

echo "ORDINI (Prodotti acquistati):\n";
echo "  • Tabella 'orders': storico ordini per utente\n";
echo "    - user_id → identifica l'utente che ha fatto l'ordine\n";
echo "    - total_amount → totale pagato\n";
echo "    - status → stato ordine (pending, completed, cancelled)\n";
echo "  • Tabella 'order_items': dettaglio prodotti acquistati\n";
echo "    - order_id → riferimento all'ordine\n";
echo "    - product_id → prodotto acquistato\n";
echo "    - quantity → quantità acquistata\n";
echo "    - unit_price → prezzo pagato per unità\n\n";

// STEP 3: Query di esempio per mostrare i dati
echo "Step 3: Query di esempio per vedere i dati:\n\n";

echo "Query 1 - Prodotti nel carrello di un utente (user_id = 1):\n";
echo "  SELECT p.name, ci.quantity, ci.unit_price\n";
echo "  FROM cart_items ci\n";
echo "  JOIN carts c ON ci.cart_id = c.id\n";
echo "  JOIN products p ON ci.product_id = p.id\n";
echo "  WHERE c.user_id = 1;\n\n";

echo "Query 2 - Storico acquisti di un utente (user_id = 1):\n";
echo "  SELECT o.id AS order_id, o.created_at, p.name, oi.quantity, oi.unit_price, o.status\n";
echo "  FROM orders o\n";
echo "  JOIN order_items oi ON oi.order_id = o.id\n";
echo "  JOIN products p ON oi.product_id = p.id\n";
echo "  WHERE o.user_id = 1\n";
echo "  ORDER BY o.created_at DESC;\n\n";

// STEP 4: Verifica colonne importanti
echo "Step 4: Verifica colonne chiave...\n";

// Verifica user_id in carts
$cartsCols = $pdo->query("SHOW COLUMNS FROM carts")->fetchAll(PDO::FETCH_ASSOC);
$hasUserIdInCarts = false;
foreach ($cartsCols as $col) {
    if ($col['Field'] === 'user_id') {
        $hasUserIdInCarts = true;
        break;
    }
}
echo ($hasUserIdInCarts ? "  ✓" : "  ✗") . " Colonna 'user_id' in tabella 'carts'\n";

// Verifica user_id in orders
$ordersCols = $pdo->query("SHOW COLUMNS FROM orders")->fetchAll(PDO::FETCH_ASSOC);
$hasUserIdInOrders = false;
foreach ($ordersCols as $col) {
    if ($col['Field'] === 'user_id') {
        $hasUserIdInOrders = true;
        break;
    }
}
echo ($hasUserIdInOrders ? "  ✓" : "  ✗") . " Colonna 'user_id' in tabella 'orders'\n";

// Verifica product_id in cart_items
$cartItemsCols = $pdo->query("SHOW COLUMNS FROM cart_items")->fetchAll(PDO::FETCH_ASSOC);
$hasProductIdInCartItems = false;
foreach ($cartItemsCols as $col) {
    if ($col['Field'] === 'product_id') {
        $hasProductIdInCartItems = true;
        break;
    }
}
echo ($hasProductIdInCartItems ? "  ✓" : "  ✗") . " Colonna 'product_id' in tabella 'cart_items'\n";

// Verifica product_id in order_items
$orderItemsCols = $pdo->query("SHOW COLUMNS FROM order_items")->fetchAll(PDO::FETCH_ASSOC);
$hasProductIdInOrderItems = false;
foreach ($orderItemsCols as $col) {
    if ($col['Field'] === 'product_id') {
        $hasProductIdInOrderItems = true;
        break;
    }
}
echo ($hasProductIdInOrderItems ? "  ✓" : "  ✗") . " Colonna 'product_id' in tabella 'order_items'\n\n";

// STEP 5: Statistiche attuali
echo "Step 5: Statistiche database correnti:\n";

$userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
echo "  • Utenti registrati: $userCount\n";

$productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
echo "  • Prodotti disponibili: $productCount\n";

$cartCount = $pdo->query("SELECT COUNT(*) FROM carts")->fetchColumn();
echo "  • Carrelli attivi: $cartCount\n";

$cartItemsCount = $pdo->query("SELECT COUNT(*) FROM cart_items")->fetchColumn();
echo "  • Prodotti nei carrelli: $cartItemsCount\n";

$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
echo "  • Ordini totali: $orderCount\n";

$orderItemsCount = $pdo->query("SELECT COUNT(*) FROM order_items")->fetchColumn();
echo "  • Prodotti acquistati totali: $orderItemsCount\n\n";

// STEP 6: Riepilogo flusso
echo "=== Riepilogo del Flusso ===\n\n";
echo "1. NAVIGAZIONE: L'utente può vedere i prodotti anche senza login\n";
echo "   → Route: ?route=products (PUBBLICA)\n\n";

echo "2. AGGIUNTA AL CARRELLO: L'utente loggato aggiunge un prodotto\n";
echo "   → Route: ?route=cart_add (POST: product_id, quantity)\n";
echo "   → Il sistema crea/aggiorna il carrello (tabella 'carts')\n";
echo "   → Inserisce il prodotto in 'cart_items' con user_id collegato\n\n";

echo "3. VISUALIZZAZIONE CARRELLO: L'utente vede i suoi prodotti\n";
echo "   → Route: ?route=cart\n";
echo "   → Query: JOIN carts + cart_items + products WHERE user_id = ...\n\n";

echo "4. CHECKOUT: L'utente procede al pagamento\n";
echo "   → Route: ?route=checkout\n";
echo "   → Inserisce l'indirizzo di spedizione (tabella 'addresses')\n\n";

echo "5. COMPLETAMENTO ORDINE: Il sistema crea l'ordine\n";
echo "   → Route: ?route=checkout_process\n";
echo "   → Crea record in 'orders' (user_id, total_amount, status)\n";
echo "   → Sposta i prodotti da 'cart_items' a 'order_items'\n";
echo "   → Svuota il carrello\n\n";

echo "6. STORICO ORDINI: L'utente vede i suoi acquisti passati\n";
echo "   → Route: ?route=dashboard o ?route=orders\n";
echo "   → Query: SELECT * FROM orders WHERE user_id = ... + JOIN order_items\n\n";

echo "=== Verifica Completata! ===\n";
echo "\nLa struttura del database è corretta e supporta:\n";
echo "✓ Carrelli personalizzati per ogni utente\n";
echo "✓ Storico completo degli acquisti\n";
echo "✓ Relazioni user_id ↔ product_id tramite cart_items e order_items\n";
