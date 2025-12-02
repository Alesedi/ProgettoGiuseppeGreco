<div class="py-4">
  <?php
  $first = '';
  $last = '';
  if (!empty($_SESSION['user_id'])) {
      $u = User::getById((int)$_SESSION['user_id']);
      if ($u) {
          $full = trim($u['full_name'] ?? '');
          if ($full !== '') {
              $parts = preg_split('/\s+/', $full);
              $first = $parts[0] ?? '';
              $last = $parts[count($parts)-1] ?? '';
          }
      }
  }
  ?>

  <div class="container">
    <h1 class="display-5">Ciao <?php echo htmlspecialchars(trim($first . ' ' . $last)); ?>, benvenuto su <strong>SoleDomus</strong></h1>
    <p class="lead muted">Benvenuto in SoleDomus — qui puoi confrontare e scegliere pannelli fotovoltaici ad alta efficienza, visualizzare offerte e aggiungere prodotti al carrello per completare l'acquisto. Troverai schede tecniche, prezzi e opzioni di configurazione per ogni pannello.</p>

    <!-- Login rapido dalla homepage -->
    <?php if (empty($_SESSION['user_id'])): ?>
      <div class="mt-3" style="max-width:460px;">
        <form method="post" action="?route=login" class="row gx-2 gy-2 align-items-center">
          <div class="col-12 col-md-5">
            <input type="text" name="login" class="form-control" placeholder="Email o username" required>
          </div>
          <div class="col-12 col-md-5">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="col-12 col-md-2">
            <button class="btn btn-sd w-100" type="submit">Accedi</button>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <p class="mt-4">
      <a href="?route=products" class="btn btn-sd">Vai allo store</a>
    </p>

    <hr class="my-4">

    <h3>Prodotti in evidenza</h3>
    <?php
    // Carica 2-3 prodotti in evidenza dal database
    require_once __DIR__ . '/../Models/Database.php';
    require_once __DIR__ . '/../Models/Product.php';
    $featuredProducts = Product::getAll(3);
    ?>
    <div class="row g-3 mt-2">
      <?php foreach ($featuredProducts as $prod): ?>
      <div class="col-md-4">
        <div class="card-sd bg-white overflow-hidden">
          <img class="product-image" src="<?= htmlspecialchars($prod['image'] ?? '/images/solar_panel_placeholder.png') ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
          <div class="p-3">
            <div class="product-title"><?= htmlspecialchars($prod['name']) ?></div>
            <div class="muted"><?= htmlspecialchars($prod['power_watt']) ?> W • <?= htmlspecialchars($prod['category'] ?? 'pannello solare') ?></div>
            <div class="mt-2 d-flex justify-content-between align-items-center">
              <div class="product-price">€<?= number_format($prod['price'], 0, ',', '.') ?></div>
              <a href="?route=product&id=<?= $prod['id'] ?>" class="btn btn-sd btn-sm">Vedi</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
