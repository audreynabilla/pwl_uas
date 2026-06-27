<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">
  <link href="<?= baseUrl('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar fixed-top">
  <div class="container">
    <a class="navbar-brand brand-logo" href="index.php?page=home"><i class="bi bi-paw-fill paw-icon me-2"></i>PawSpa</a>
    <a class="btn btn-outline-paw btn-sm" href="index.php?page=riwayat">Riwayat</a>
  </div>
</nav>
<div class="flash-wrap">
  <?php foreach (['success','error'] as $t): if (!empty($_SESSION['flash_'.$t])): ?><div class="alert flash-alert flash-<?= $t ?>"><?= e($_SESSION['flash_'.$t]); unset($_SESSION['flash_'.$t]); ?></div><?php endif; endforeach; ?>
</div>
<main class="content-offset container pb-5">
  <div class="row g-4">
    <div class="col-lg-4">
      <div class="soft-card p-4 text-center">
        <div class="hero-kicker mb-4"><i class="bi bi-person-heart paw-icon"></i> Profil member</div>
        <img id="profilePreview" class="profile-avatar <?= empty($user['profile_picture']) ? 'd-none' : '' ?>" src="<?= !empty($user['profile_picture']) ? baseUrl('uploads/profiles/' . $user['profile_picture']) : '' ?>" alt="Foto profil">
        <div class="<?= empty($user['profile_picture']) ? '' : 'd-none' ?>"><i class="bi bi-person-circle paw-icon" style="font-size:8rem"></i></div>
        <h1 class="h4 fw-black mt-3"><?= e($user['name']) ?></h1>
        <p><?= e($user['email']) ?></p>
        <a class="btn btn-danger-paw" href="index.php?page=logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
      </div>
    </div>
    <div class="col-lg-8">
      <form class="form-card p-4 p-md-5" method="post" enctype="multipart/form-data" action="index.php?page=profil&action=update">
        <?= csrfField() ?>
        <h2 class="section-title mb-4">Edit Profil</h2>
        <div class="row g-4">
          <div class="col-12"><label class="form-label fw-bold">Nama</label><input name="name" value="<?= e($user['name']) ?>" class="form-control" required></div>
          <div class="col-12"><label class="form-label fw-bold">No. HP</label><input name="phone" maxlength="15" pattern="[0-9]{0,15}" value="<?= e($user['phone']) ?>" class="form-control"></div>
          <div class="col-12"><label class="form-label fw-bold">Alamat</label><textarea name="address" class="form-control" rows="4"><?= e($user['address']) ?></textarea></div>
          <div class="col-12"><label class="form-label fw-bold">Foto Profil</label><input type="file" name="profile_picture" class="form-control image-input" accept=".jpg,.jpeg,.png" data-preview="#profilePreview"></div>
        </div>
        <button class="btn btn-primary-paw mt-4" type="submit"><i class="bi bi-save me-2"></i>Simpan Profil</button>
      </form>
    </div>
  </div>
</main>
<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
</body>
</html>
