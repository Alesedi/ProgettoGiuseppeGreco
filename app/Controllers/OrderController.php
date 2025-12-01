<?php
require_once __DIR__ . '/../Models/Database.php';

class OrderController
{
    public static function confirmation()
    {
        $id = (int)($_GET['id'] ?? 0);
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        if (!$order) {
            echo 'Ordine non trovato.'; exit;
        }
        $stmt = $pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :oid');
        $stmt->execute(['oid' => $id]);
        $items = $stmt->fetchAll();
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/order/confirmation.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }
}
