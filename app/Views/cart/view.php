<div class="py-4">
  <h2>Carrello</h2>
  <?php if (empty($items)): ?>
    <div class="alert alert-info">Il carrello è vuoto.</div>
  <?php else: ?>
    <table class="table">
      <thead><tr><th>Prodotto</th><th>Opzione</th><th>Qty</th><th>Unit</th><th>Subtotal</th></tr></thead>
      <tbody>
        <?php $total = 0; foreach ($items as $it): $subtotal = $it['unit_price'] * $it['quantity']; $total += $subtotal; ?>
          <tr>
            <td><?= htmlspecialchars($it['product_name']) ?></td>
            <td><?= htmlspecialchars($it['option_name'] ?? '') ?></td>
            <td><?= $it['quantity'] ?></td>
            <td>€<?= number_format($it['unit_price'],2,',','.') ?></td>
            <td>€<?= number_format($subtotal,2,',','.') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="d-flex justify-content-between">
      <div></div>
      <div>
        <div class="fw-bold">Totale: €<?= number_format($total,2,',','.') ?></div>
        <a class="btn btn-sd mt-2" href="?route=checkout">Vai al checkout</a>
        <form method="post" action="?route=cart_clear" style="display:inline">
          <button class="btn btn-outline-secondary mt-2">Svuota carrello</button>
        </form>
      </div>
    </div>
  <?php endif; ?>
</div>
