<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SoleDomus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php $sd_css = '/css/style.css'; $sd_css_ver = file_exists(__DIR__ . '/../../public/css/style.css') ? filemtime(__DIR__ . '/../../public/css/style.css') : time(); ?>
  <link href="<?= $sd_css ?>?v=<?= $sd_css_ver ?>" rel="stylesheet">
</head>
<body class="theme-bg">
<?php $currentRoute = $_GET['route'] ?? null; ?>
<?php
// Ottieni dati utente se loggato per mostrare il saluto
$currentUser = null;
if (!empty($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../Models/User.php';
    $currentUser = User::getById((int)$_SESSION['user_id']);
}
?>
<?php if (!in_array($currentRoute, ['login','register'])): ?>
<nav class="navbar navbar-expand-lg navbar-light main-navbar">
  <div class="container">
    <a class="navbar-brand text-white fw-bold" href="/index.php">SoleDomus</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
        <li class="nav-item"><a class="nav-link text-white" href="?route=products">Catalogo</a></li>
        <?php if (!empty($_SESSION['user_id']) && $currentUser): ?>
          <li class="nav-item ms-3 me-3 text-white small">Ciao <?php echo htmlspecialchars($currentUser['full_name'] ?? $currentUser['email']); ?></li>
          <li class="nav-item"><a class="nav-link text-white" href="?route=dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="?route=cart">Carrello</a></li>
          <li class="nav-item">
            <form method="post" action="?route=logout" style="display:inline">
              <button class="btn btn-outline-light" style="padding:6px 12px;border-radius:8px;">Esci</button>
            </form>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="?route=register">Registrati</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="?route=login">Accedi</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<?php endif; ?>

<?php if (!in_array($currentRoute, ['login','register'])): ?>
  <main class="site-main container mt-4">
<?php else: ?>
  <div class="auth-page-bg" style="display:flex;align-items:center;justify-content:center;min-height:100vh;padding:4vh 2vw;background:linear-gradient(90deg,#2f6be6,#2657c9);">
    <div style="width:100%;text-align:center;margin-bottom:12px;">
      <h1 class="auth-title" style="margin:0;color:#fff;font-weight:800;letter-spacing:1px;font-size:40px;">SoleDomus</h1>
      <p style="margin:6px 0 0;color:rgba(255,255,255,0.9)">Accedi o registrati per iniziare</p>
    </div>
    <div class="auth-center" style="width:100%;display:flex;align-items:center;justify-content:center;">
<?php endif; ?>
