<?php
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models\User.php';

class CartController
{
    protected static function ensureAuth()
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /public/index.php?route=login');
            exit;
        }
    }

    public static function view()
    {
        self::ensureAuth();
        $userId = (int)$_SESSION['user_id'];
        $items = Cart::itemsForUser($userId);
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/cart/view.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }

    public static function add()
    {
        self::ensureAuth();
        $userId = (int)$_SESSION['user_id'];
        $productId = (int)($_POST['product_id'] ?? 0);
        $optionId = isset($_POST['option_id']) && $_POST['option_id'] !== '' ? (int)$_POST['option_id'] : null;
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        Cart::addItem($userId, $productId, $optionId, $qty);
        header('Location: /public/index.php?route=cart');
        exit;
    }

    public static function clear()
    {
        self::ensureAuth();
        $userId = (int)$_SESSION['user_id'];
        Cart::clear($userId);
        header('Location: /public/index.php?route=cart');
        exit;
    }
}
