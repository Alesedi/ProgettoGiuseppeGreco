<?php
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models/Database.php';

class CheckoutController
{
    protected static function ensureAuth()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /index.php?route=login');
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
            header('Location: /index.php?route=cart');
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

            // save card metadata (no CVV) BEFORE payment
            $last4 = substr($card_number, -4);
            $stmt = $pdo->prepare('INSERT INTO payment_cards (user_id, cardholder_name, card_brand, card_last4, exp_month, exp_year) VALUES (:uid,:cn,:brand,:last4,:m,:y)');
            $stmt->execute(['uid' => $userId, 'cn' => $card_name, 'brand' => null, 'last4' => $last4, 'm' => $exp_month, 'y' => $exp_year]);
            $cardId = $pdo->lastInsertId();

            // payment (simulate) - now includes card reference
            $stmt = $pdo->prepare('INSERT INTO payments (order_id, payment_card_id, amount, method, status, transaction_ref) VALUES (:oid,:cid,:amt,:m,:s,:t)');
            $stmt->execute(['oid' => $orderId, 'cid' => $cardId, 'amt' => $total, 'm' => 'card', 's' => 'ok', 't' => 'TX' . time()]);

            // populate purchase_history table for easy reporting
            require_once __DIR__ . '/../Models/User.php';
            require_once __DIR__ . '/../Models/Product.php';
            $user = User::getById($userId);
            $stmtHistory = $pdo->prepare('
                INSERT INTO purchase_history (
                    order_id, user_id, user_email, user_name,
                    product_id, product_name, product_sku,
                    quantity, unit_price, subtotal, order_total,
                    payment_method, payment_status,
                    card_id, card_last4, cardholder_name,
                    shipping_city, shipping_country,
                    order_status, order_date
                ) VALUES (
                    :oid, :uid, :email, :name,
                    :pid, :pname, :sku,
                    :qty, :unit, :sub, :total,
                    :method, :pstatus,
                    :cid, :last4, :cname,
                    :city, :country,
                    :status, NOW()
                )
            ');
            
            foreach ($items as $it) {
                $product = Product::find($it['product_id']);
                $stmtHistory->execute([
                    'oid' => $orderId,
                    'uid' => $userId,
                    'email' => $user['email'],
                    'name' => $user['full_name'],
                    'pid' => $it['product_id'],
                    'pname' => $product['name'],
                    'sku' => $product['sku'],
                    'qty' => $it['quantity'],
                    'unit' => $it['unit_price'],
                    'sub' => $it['unit_price'] * $it['quantity'],
                    'total' => $total,
                    'method' => 'card',
                    'pstatus' => 'ok',
                    'cid' => $cardId,
                    'last4' => $last4,
                    'cname' => $card_name,
                    'city' => $city,
                    'country' => 'Italy',
                    'status' => 'paid'
                ]);
            }

            // clear cart
            Cart::clear($userId);

            $pdo->commit();
            header('Location: /index.php?route=order_confirmation&id=' . $orderId);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            die('Errore durante il checkout: ' . $e->getMessage());
        }
    }
}
