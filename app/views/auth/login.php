<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?> - PawSpa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
  <link href="<?= baseUrl('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<div class="flash-wrap">
  <?php if (!empty($_SESSION['flash_success'])): ?><div class="alert flash-alert flash-success"><?= e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div><?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?><div class="alert flash-alert flash-error"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div><?php endif; ?>
</div>
<main class="auth-bg">
  <div class="auth-card p-4 p-md-5" style="max-width:460px;width:100%">
    <div class="text-center mb-4">
      <i class="bi bi-paw-fill paw-icon fs-1"></i>
      <h1 class="h3 fw-black mt-2 brand-logo">Masuk PawSpa</h1>
      <p class="mb-0">Reservasi grooming makin manis dan mudah.</p>
    </div>
    <form method="post" action="index.php?page=login">
      <?= csrfField() ?>
      <div class="mb-3">
        <label class="form-label fw-bold">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-4">
        <label class="form-label fw-bold">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary-paw w-100" type="submit"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
    </form>
    <p class="text-center mt-4 mb-0">Belum punya akun? <a class="fw-bold" href="index.php?page=register">Daftar gratis</a></p>
  </div>
</main>
<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
</body>
</html>
