<?php
// Public front controller (file-based routing semplice)
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Models/Database.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/ProductController.php';
require_once __DIR__ . '/../app/Controllers/CartController.php';
require_once __DIR__ . '/../app/Controllers/CheckoutController.php';
require_once __DIR__ . '/../app/Controllers/OrderController.php';

 $route = $_GET['route'] ?? '';

// Se la route non è pubblica e l'utente non è autenticato, forziamo il login
// Rendiamo pubbliche home, catalogo prodotti e dettaglio prodotto oltre a login/register/logout
$publicRoutes = ['', 'home', 'products', 'product', 'login', 'register', 'logout'];
if (!in_array($route, $publicRoutes) && empty($_SESSION['user_id'])) {
    header('Location: /index.php?route=login');
    exit;
}

switch ($route) {
    case 'register':
        AuthController::register();
        break;
    case 'login':
        AuthController::login();
        break;
    case 'logout':
        AuthController::logout();
        break;
    case 'dashboard':
        // require login
        if (empty($_SESSION['user_id'])) { header('Location: /index.php?route=login'); exit; }
        include __DIR__ . '/../app/Views/layout/header.php';
        include __DIR__ . '/../app/Views/dashboard/index.php';
        include __DIR__ . '/../app/Views/layout/footer.php';
        break;
    case 'products':
        ProductController::index();
        break;
    case 'product':
        ProductController::show();
        break;
    case 'cart':
        CartController::view();
        break;
    case 'cart_add':
        CartController::add();
        break;
    case 'cart_clear':
        CartController::clear();
        break;
    case 'checkout':
        CheckoutController::index();
        break;
    case 'checkout_process':
        CheckoutController::process();
        break;
    case 'order_confirmation':
        OrderController::confirmation();
        break;
    default:
        // Home: mostra la view home.php
        include __DIR__ . '/../app/Views/layout/header.php';
        include __DIR__ . '/../app/Views/home.php';
        include __DIR__ . '/../app/Views/layout/footer.php';
        break;
}
