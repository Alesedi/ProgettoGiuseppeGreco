<div class="py-4">
  <h2>Checkout</h2>
  <?php if (empty($items)): ?>
    <div class="alert alert-info">Il carrello è vuoto.</div>
  <?php else: ?>
    <h4>Riepilogo</h4>
    <ul>
      <?php $total = 0; foreach ($items as $it): $sub = $it['unit_price'] * $it['quantity']; $total += $sub; ?>
        <li><?= htmlspecialchars($it['product_name']) ?> x <?= $it['quantity'] ?> — €<?= number_format($sub,2,',','.') ?></li>
      <?php endforeach; ?>
    </ul>
    <div class="fw-bold mb-3">Totale: €<?= number_format($total,2,',','.') ?></div>

    <form method="post" action="?route=checkout_process">
      <h5>Informazioni di spedizione</h5>
      <div class="mb-2">
        <input name="shipping_name" placeholder="Nome destinatario" class="form-control" required>
      </div>
      <div class="mb-2">
        <input name="street" placeholder="Via, numero" class="form-control" required>
      </div>
      <div class="row">
        <div class="col-md-6 mb-2"><input name="city" placeholder="Città" class="form-control" required></div>
        <div class="col-md-6 mb-2"><input name="postal_code" placeholder="CAP" class="form-control" required></div>
      </div>

      <h5>Pagamento (simulato)</h5>
      <div class="mb-2"><input name="card_name" placeholder="Titolare carta" class="form-control" required></div>
      <div class="mb-2"><input name="card_number" placeholder="Numero carta" class="form-control" required></div>
      <div class="row">
        <div class="col-md-4 mb-2"><input name="expiry_month" placeholder="MM" class="form-control" required></div>
        <div class="col-md-4 mb-2"><input name="expiry_year" placeholder="YYYY" class="form-control" required></div>
        <div class="col-md-4 mb-2"><input name="cvv" placeholder="CVV" class="form-control" required></div>
      </div>
      <button class="btn btn-sd">Paga (simulato)</button>
    </form>
  <?php endif; ?>
</div>
