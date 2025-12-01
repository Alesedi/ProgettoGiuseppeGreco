  <div class="card card-sd auth-card auth-card-right" style="max-width:360px;margin:6vh auto;padding:28px;box-sizing:border-box;">
    <?php if (!empty($errors) && is_array($errors)): ?>
      <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
    <?php endif; ?>

    <form method="post" action="?route=login">
      <h4 class="text-center mb-3">SoleDomus</h4>
      <p class="text-center muted small mb-3">Accedi al tuo account</p>

      <div class="mb-3">
        <input type="text" name="login" class="form-control" placeholder="Username o Email" required value="<?php echo htmlspecialchars($_POST['login'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="#" class="small muted">Password dimenticata</a>
        <button class="btn btn-sd">LOGIN</button>
      </div>

      <div class="mb-0">
        <a href="?route=register" class="btn btn-register w-100">REGISTRATI</a>
      </div>
    </form>
    </div>
  </div>
