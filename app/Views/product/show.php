<div class="py-4">
  <div class="row">
    <div class="col-md-6">
      <img src="<?= htmlspecialchars($product['image'] ?? '/public/images/solar_panel_placeholder.png') ?>" class="img-fluid">
    </div>
    <div class="col-md-6">
      <h2><?= htmlspecialchars($product['name']) ?></h2>
      <p class="muted"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      <div class="fw-bold">Prezzo: €<?= number_format($product['price'],2,',','.') ?></div>

      <form method="post" action="?route=cart_add">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <div class="mb-2">
          <label>Opzioni</label>
          <select name="option_id" class="form-select">
            <option value="">Nessuna</option>
            <?php foreach ($options as $opt): ?>
              <option value="<?= $opt['id'] ?>"><?= htmlspecialchars($opt['name']) ?> (+€<?= number_format($opt['price_delta'],2,',','.') ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2">
          <label>Quantità</label>
          <input type="number" name="quantity" value="1" min="1" class="form-control" style="width:100px">
        </div>
        <button class="btn btn-sd">Aggiungi al carrello</button>
      </form>
    </div>
  </div>
</div>
