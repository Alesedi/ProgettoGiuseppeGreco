<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/User.php';

class ProductController
{
    public static function index()
    {
        $q = trim($_GET['q'] ?? '');
        $products = Product::all($q ?: null);
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/product/catalog.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }

    public static function show()
    {
        $id = (int)($_GET['id'] ?? 0);
        $product = Product::find($id);
        if (!$product) {
            header('HTTP/1.0 404 Not Found');
            echo "Prodotto non trovato.";
            exit;
        }
        $options = Product::options($id);
        include __DIR__ . '/../Views/layout/header.php';
        include __DIR__ . '/../Views/product/show.php';
        include __DIR__ . '/../Views/layout/footer.php';
    }
}
