<div class="py-4">
  <h2>Catalogo prodotti</h2>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <form class="flex-grow-1 me-3" method="get" action="">
      <input type="hidden" name="route" value="products">
      <div class="input-group">
        <input class="form-control" name="q" placeholder="Cerca modello" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button class="btn btn-sd" type="submit">Cerca</button>
      </div>
    </form>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <form method="post" action="?route=logout">
        <button class="btn btn-outline-secondary">Logout</button>
      </form>
    <?php endif; ?>
  </div>

  <div class="row g-3">
    <?php foreach ($products as $p): ?>
      <div class="col-md-4">
        <div class="card p-2">
          <img src="<?= htmlspecialchars($p['image'] ?? '/public/images/solar_panel_placeholder.png') ?>" class="img-fluid" style="max-height:160px; object-fit:cover">
          <h5 class="mt-2"><?= htmlspecialchars($p['name']) ?></h5>
          <div class="muted"><?= htmlspecialchars($p['power_watt']) ?> W</div>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="fw-bold">€<?= number_format($p['price'],2,',','.') ?></div>
            <div>
              <a class="btn btn-outline-primary btn-sm" href="?route=product&id=<?= $p['id'] ?>">Dettaglio</a>
              <form method="post" action="?route=cart_add" style="display:inline">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button class="btn btn-sd btn-sm">Aggiungi</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
