<?php $currentRoute = $_GET['route'] ?? null; ?>
<?php if (!in_array($currentRoute, ['login','register'])): ?>
  <footer class="footer mt-5 py-4 footer-bg text-white">
    <div class="container d-flex justify-content-between align-items-center">
      <div>&copy; SoleDomus</div>
      <div class="small">Progetto demo — pannelli fotovoltaici</div>
    </div>
  </footer>
  </main>
<?php else: ?>
    </div> <!-- .auth-center -->
  </div> <!-- .auth-page-bg -->
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
