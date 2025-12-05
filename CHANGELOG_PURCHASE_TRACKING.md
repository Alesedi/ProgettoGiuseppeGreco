# Aggiornamento Database: Tracciamento Acquisti

## Modifiche Implementate

### 1. Schema Database (`db/sole_domus_schema.sql`)

**Tabella `payments` - Aggiunta colonna**:
```sql
ALTER TABLE payments 
ADD COLUMN payment_card_id INT DEFAULT NULL,
ADD CONSTRAINT fk_payments_card 
FOREIGN KEY (payment_card_id) REFERENCES payment_cards(id) ON DELETE SET NULL;
```

**Nuova Vista SQL `purchase_summary`**:
```sql
CREATE VIEW purchase_summary AS
SELECT 
  o.id AS order_id,
  o.user_id,
  u.email AS user_email,
  u.full_name AS user_name,
  o.created_at AS order_date,
  o.status AS order_status,
  o.total_amount AS order_total,
  oi.product_id,
  p.name AS product_name,
  p.sku AS product_sku,
  oi.quantity,
  oi.unit_price,
  oi.subtotal,
  pay.method AS payment_method,
  pay.status AS payment_status,
  pc.card_last4,
  pc.cardholder_name,
  a.city AS shipping_city
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN order_items oi ON o.id = oi.order_id
JOIN products p ON oi.product_id = p.id
LEFT JOIN payments pay ON o.id = pay.order_id
LEFT JOIN payment_cards pc ON pay.payment_card_id = pc.id
LEFT JOIN addresses a ON o.address_id = a.id;
```

### 2. Script di Migrazione (`scripts/add_purchase_tracking.php`)

Script automatico che:
- ✅ Aggiunge colonna `payment_card_id` a `payments`
- ✅ Crea indice `idx_payments_card` per performance
- ✅ Crea vista `purchase_summary`
- ✅ Testa la vista con query di esempio
- ✅ Mostra ultimi acquisti con dettagli carta

**Esecuzione**:
```powershell
php scripts/add_purchase_tracking.php
```

### 3. CheckoutController (`app/Controllers/CheckoutController.php`)

**Modifiche al processo di checkout**:

**Prima**:
```php
// Salvava carta DOPO il pagamento (no collegamento)
$stmt = $pdo->prepare('INSERT INTO payments (order_id, amount, method, status, transaction_ref) VALUES (...)');
$stmt->execute(...);

$stmt = $pdo->prepare('INSERT INTO payment_cards (...) VALUES (...)');
$stmt->execute(...);
```

**Dopo**:
```php
// Salva carta PRIMA e collega al pagamento
$stmt = $pdo->prepare('INSERT INTO payment_cards (...) VALUES (...)');
$stmt->execute(...);
$cardId = $pdo->lastInsertId();

$stmt = $pdo->prepare('INSERT INTO payments (order_id, payment_card_id, amount, method, status, transaction_ref) VALUES (...)');
$stmt->execute(['oid' => $orderId, 'cid' => $cardId, ...]);
```

### 4. Documentazione Aggiornata (`DOCUMENTAZIONE.md`)

- ✅ Schema `payments` con nuova colonna `payment_card_id`
- ✅ Sezione dedicata alla vista `purchase_summary`
- ✅ Esempi di query riepilogative
- ✅ Diagramma relazioni aggiornato (payment_cards → payments)
- ✅ Aggiunto script `add_purchase_tracking.php` alla struttura file
- ✅ Flow acquisto aggiornato con step 5 (salvataggio carta)
- ✅ Sezione "Query Riepilogative Utili" con 4 esempi SQL

## Benefici

### Prima
❌ Non si poteva sapere quale carta specifica era stata usata per un ordine  
❌ Tabella `payments` aveva solo `method = 'card'` generico  
❌ Impossibile fare report per carta  

### Dopo
✅ Ogni pagamento è collegato alla carta specifica usata  
✅ Query immediate: "Tutti gli ordini pagati con carta **** 1234"  
✅ Vista `purchase_summary` unisce user, prodotto, quantità, carta in una query  
✅ Report avanzati: totale speso per carta, ordini per utente con dettagli carta  

## Esempi di Utilizzo

### Query 1: Riepilogo Ordine
```sql
SELECT 
  order_id, 
  user_name, 
  product_name, 
  quantity, 
  subtotal, 
  card_last4, 
  order_date 
FROM purchase_summary 
WHERE order_id = 5;
```

**Output**:
```
order_id | user_name    | product_name                  | quantity | subtotal | card_last4 | order_date
---------|--------------|-------------------------------|----------|----------|------------|-------------------
5        | Mario Rossi  | Pannello Monocristallino 420W | 2        | 698.00   | 1234       | 2025-12-05 10:30:15
5        | Mario Rossi  | Pannello Bifacciale Garden    | 1        | 279.00   | 1234       | 2025-12-05 10:30:15
```

### Query 2: Tutti gli Acquisti di un Utente
```sql
SELECT * FROM purchase_summary WHERE user_id = 3;
```

### Query 3: Totale per Prodotto
```sql
SELECT 
  product_name, 
  SUM(quantity) as units_sold,
  SUM(subtotal) as total_revenue 
FROM purchase_summary 
GROUP BY product_id, product_name 
ORDER BY total_revenue DESC;
```

### Query 4: Ordini con Carta Specifica
```sql
SELECT * FROM purchase_summary 
WHERE card_last4 = '1234' 
ORDER BY order_date DESC;
```

## Rollback (se necessario)

```sql
-- Rimuovi vista
DROP VIEW IF EXISTS purchase_summary;

-- Rimuovi foreign key e colonna
ALTER TABLE payments 
DROP FOREIGN KEY fk_payments_card,
DROP COLUMN payment_card_id;

-- Rimuovi indice
DROP INDEX idx_payments_card ON payments;
```

## Note Tecniche

- **Backward compatibility**: `payment_card_id` è `NULL` di default, ordini esistenti senza carta continuano a funzionare
- **Performance**: Indice su `payment_card_id` per query veloci
- **Vista SQL**: Non occupa spazio, è solo una query salvata
- **ON DELETE SET NULL**: Se una carta viene cancellata, il pagamento rimane ma perde il riferimento

## Test

```powershell
# 1. Esegui migrazione
php scripts/add_purchase_tracking.php

# 2. Verifica struttura
php scripts/verify_database_structure.php

# 3. Fai un acquisto di test dal browser
# Vai su localhost:8000, logga, acquista un prodotto

# 4. Verifica in database
mysql -u root -p testdb -e "SELECT * FROM purchase_summary LIMIT 10;"
```

## Data: 5 Dicembre 2025
## Versione: 1.1
## Autore: Giuseppe Greco / SoleDomus Team
