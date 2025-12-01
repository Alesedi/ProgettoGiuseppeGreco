<?php
require_once __DIR__ . '/Database.php';

class Cart
{
    public static function getOrCreateByUser(int $userId)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id = :uid LIMIT 1');
        $stmt->execute(['uid' => $userId]);
        $cart = $stmt->fetch();
        if ($cart) return $cart;
        $stmt = $pdo->prepare('INSERT INTO carts (user_id) VALUES (:uid)');
        $stmt->execute(['uid' => $userId]);
        return ['id' => $pdo->lastInsertId(), 'user_id' => $userId];
    }

    public static function itemsForUser(int $userId)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT ci.*, p.name as product_name, p.image as product_image, po.name as option_name, po.price_delta FROM cart_items ci JOIN carts c ON ci.cart_id = c.id JOIN products p ON ci.product_id = p.id LEFT JOIN product_options po ON ci.product_option_id = po.id WHERE c.user_id = :uid');
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public static function addItem(int $userId, int $productId, ?int $optionId, int $quantity)
    {
        $pdo = Database::getConnection();
        $cart = self::getOrCreateByUser($userId);
        // get unit price = product.price + option.price_delta
        $stmt = $pdo->prepare('SELECT price FROM products WHERE id = :pid');
        $stmt->execute(['pid' => $productId]);
        $price = $stmt->fetchColumn();
        $delta = 0;
        if ($optionId) {
            $stmt = $pdo->prepare('SELECT price_delta FROM product_options WHERE id = :oid');
            $stmt->execute(['oid' => $optionId]);
            $delta = (float)$stmt->fetchColumn();
        }
        $unit = $price + $delta;
        // check if same item exists
        $stmt = $pdo->prepare('SELECT * FROM cart_items WHERE cart_id = :cid AND product_id = :pid AND (product_option_id <=> :oid) LIMIT 1');
        $stmt->execute(['cid' => $cart['id'], 'pid' => $productId, 'oid' => $optionId]);
        $existing = $stmt->fetch();
        if ($existing) {
            $stmt = $pdo->prepare('UPDATE cart_items SET quantity = quantity + :q WHERE id = :id');
            $stmt->execute(['q' => $quantity, 'id' => $existing['id']]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO cart_items (cart_id, product_id, product_option_id, quantity, unit_price) VALUES (:cid,:pid,:oid,:q,:unit)');
            $stmt->execute(['cid' => $cart['id'], 'pid' => $productId, 'oid' => $optionId, 'q' => $quantity, 'unit' => $unit]);
        }
    }

    public static function clear(int $userId)
    {
        $pdo = Database::getConnection();
        $cart = self::getOrCreateByUser($userId);
        $stmt = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = :cid');
        $stmt->execute(['cid' => $cart['id']]);
    }
}
