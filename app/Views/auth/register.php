  <div class="card card-sd auth-card auth-card-right" style="max-width:360px;margin:6vh auto;padding:28px;box-sizing:border-box;">
    <h3 class="mb-3">Crea il tuo account</h3>
    <?php if (!empty($errors) && is_array($errors)): ?>
      <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
    <?php endif; ?>
    <form method="post" action="?route=register">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nome</label>
          <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($_POST['first_name'] ?? '') ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Cognome</label>
          <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($_POST['last_name'] ?? '') ?>">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Username (opzionale)</label>
        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Conferma Password</label>
        <input type="password" name="password_confirm" class="form-control" required>
      </div>
      <div class="d-flex justify-content-between align-items-center">
        <button class="btn btn-sd">Registrati</button>
        <a href="?route=login" class="small">Hai già un account? Accedi</a>
      </div>
    </form>
    </div>
  </div>
