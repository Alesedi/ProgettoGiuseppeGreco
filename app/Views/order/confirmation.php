<div class="py-4">
  <h2>Ordine confermato</h2>
  <p>Numero ordine: <strong>#<?= htmlspecialchars($order['id']) ?></strong></p>
  <p>Totale: €<?= number_format($order['total_amount'],2,',','.') ?></p>
  <h4>Articoli</h4>
  <ul>
    <?php foreach ($items as $it): ?>
      <li><?= htmlspecialchars($it['name'] ?? $it['product_name']) ?> x <?= $it['quantity'] ?> — €<?= number_format($it['unit_price'],2,',','.') ?></li>
    <?php endforeach; ?>
  </ul>
  <a class="btn btn-sd" href="?route=dashboard">Torna alla dashboard</a>
</div>
