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
  <?php if (!empty($_SESSION['flash_error'])): ?><div class="alert flash-alert flash-error"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div><?php endif; ?>
</div>
<main class="auth-bg">
  <div class="auth-card p-4 p-md-5" style="max-width:540px;width:100%">
    <div class="text-center mb-4">
      <i class="bi bi-paw-fill paw-icon fs-1"></i>
      <h1 class="h3 fw-black mt-2 brand-logo">Daftar PawSpa</h1>
      <p class="mb-0">Buat akun untuk booking grooming favoritmu.</p>
    </div>
    <form method="post" action="index.php?page=register">
      <?= csrfField() ?>
      <div class="row g-3">
        <div class="col-12"><label class="form-label fw-bold">Nama</label><input name="name" class="form-control" required></div>
        <div class="col-12"><label class="form-label fw-bold">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label fw-bold">Password</label><input type="password" name="password" minlength="8" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label fw-bold">Konfirmasi Password</label><input type="password" name="password_confirm" minlength="8" class="form-control" required></div>
        <div class="col-12"><label class="form-label fw-bold">No. HP</label><input name="phone" class="form-control"></div>
      </div>
      <button class="btn btn-primary-paw w-100 mt-4" type="submit"><i class="bi bi-person-plus me-2"></i>Register</button>
    </form>
    <p class="text-center mt-4 mb-0">Sudah punya akun? <a class="fw-bold" href="index.php?page=login">Login</a></p>
  </div>
</main>
<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
</body>
</html>
