<?php
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models/Database.php';

class CheckoutController
{
    protected static function ensureAuth()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /public/index.php?route=login');
            exit;
        }
    }

    public static function index()
    {
        self::ensureAuth();
        $userId = (int)$_SESSION['user_id'];
        $items = Cart::itemsForUser($userId);
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/checkout/form.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }

    public static function process()
    {
        self::ensureAuth();
        $userId = (int)$_SESSION['user_id'];
        $pdo = Database::getConnection();
        $items = Cart::itemsForUser($userId);
        if (!$items) {
            header('Location: /public/index.php?route=cart');
            exit;
        }
        // Collect shipping and payment
        $shipping_name = trim($_POST['shipping_name'] ?? '');
        $street = trim($_POST['street'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $postal = trim($_POST['postal_code'] ?? '');

        $card_name = trim($_POST['card_name'] ?? '');
        $card_number = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
        $exp_month = (int)($_POST['expiry_month'] ?? 0);
        $exp_year = (int)($_POST['expiry_year'] ?? 0);

        // Simple validations
        $errors = [];
        if ($shipping_name === '' || $street === '' || $city === '' || $postal === '') $errors[] = 'Compila tutti i campi di spedizione.';
        if (strlen($card_number) < 12) $errors[] = 'Numero carta non valido.';
        if ($exp_year < (int)date('Y')) $errors[] = 'Carta scaduta.';

        if ($errors) {
            include __DIR__ . '/../Views/layout/header.php';
            include __DIR__ . '/../Views/checkout/form.php';
            include __DIR__ . '/../Views/layout/footer.php';
            exit;
        }

        // Calculate total
        $total = 0;
        foreach ($items as $it) {
            $total += $it['unit_price'] * $it['quantity'];
        }

        try {
            $pdo->beginTransaction();
            // create address
            $stmt = $pdo->prepare('INSERT INTO addresses (user_id, recipient_name, street, city, postal_code, country, is_default) VALUES (:uid,:r,:s,:c,:p,:country,0)');
            $stmt->execute(['uid' => $userId, 'r' => $shipping_name, 's' => $street, 'c' => $city, 'p' => $postal, 'country' => 'Italy']);
            $addressId = $pdo->lastInsertId();

            // create order
            $stmt = $pdo->prepare('INSERT INTO orders (user_id, address_id, total_amount, status) VALUES (:uid,:aid,:total,:status)');
            $stmt->execute(['uid' => $userId, 'aid' => $addressId, 'total' => $total, 'status' => 'paid']);
            $orderId = $pdo->lastInsertId();

            // order items
            $stmtItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_option_id, quantity, unit_price, subtotal) VALUES (:oid,:pid,:poid,:q,:unit,:sub)');
            foreach ($items as $it) {
                $sub = $it['unit_price'] * $it['quantity'];
                $stmtItem->execute(['oid' => $orderId, 'pid' => $it['product_id'], 'poid' => $it['product_option_id'], 'q' => $it['quantity'], 'unit' => $it['unit_price'], 'sub' => $sub]);
            }

            // payment (simulate)
            $last4 = substr($card_number, -4);
            $stmt = $pdo->prepare('INSERT INTO payments (order_id, amount, method, status, transaction_ref) VALUES (:oid,:amt,:m,:s,:t)');
            $stmt->execute(['oid' => $orderId, 'amt' => $total, 'm' => 'card', 's' => 'ok', 't' => 'TX' . time()]);

            // optionally save card metadata (no CVV)
            $stmt = $pdo->prepare('INSERT INTO payment_cards (user_id, cardholder_name, card_brand, card_last4, exp_month, exp_year) VALUES (:uid,:cn,:brand,:last4,:m,:y)');
            $stmt->execute(['uid' => $userId, 'cn' => $card_name, 'brand' => null, 'last4' => $last4, 'm' => $exp_month, 'y' => $exp_year]);

            // clear cart
            Cart::clear($userId);

            $pdo->commit();
            header('Location: /public/index.php?route=order_confirmation&id=' . $orderId);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            die('Errore durante il checkout: ' . $e->getMessage());
        }
    }
}
