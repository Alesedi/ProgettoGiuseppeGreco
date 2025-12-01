<div class="py-4">
  <?php
  $first = '';
  $last = '';
  if (!empty($_SESSION['user_id'])) {
      require_once __DIR__ . '/../Models/User.php';
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

    <p class="mt-4">
      <a href="?route=products" class="btn btn-sd">Vai allo store</a>
    </p>

    <hr class="my-4">

    <h3>Prodotti in evidenza</h3>
    <div class="row g-3 mt-2">
      <div class="col-md-4">
        <div class="p-3 card-sd bg-white">
          <img class="product-image" src="/public/images/panel_360.jpg" alt="panel">
          <div class="p-3">
            <div class="product-title">Pannello SoleDomus 360W</div>
            <div class="muted">360 W • monocristallino</div>
            <div class="mt-2 d-flex justify-content-between align-items-center">
              <div class="product-price">€199</div>
              <a href="#" class="btn btn-sd btn-sm">Aggiungi</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 card-sd bg-white">
          <img class="product-image" src="/public/images/panel_420.jpg" alt="panel">
          <div class="p-3">
            <div class="product-title">Pannello SoleDomus 420W</div>
            <div class="muted">420 W • alta efficienza</div>
            <div class="mt-2 d-flex justify-content-between align-items-center">
              <div class="product-price">€249</div>
              <a href="#" class="btn btn-sd btn-sm">Aggiungi</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
