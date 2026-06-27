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
<nav class="navbar fixed-top">
  <div class="container">
    <a class="navbar-brand brand-logo" href="index.php?page=home"><i class="bi bi-paw-fill paw-icon me-2"></i>PawSpa</a>
    <div><a class="btn btn-outline-paw btn-sm" href="index.php?page=home">Home</a></div>
  </div>
</nav>
<div class="flash-wrap">
  <?php if (!empty($_SESSION['flash_error'])): ?><div class="alert flash-alert flash-error"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div><?php endif; ?>
</div>
<main class="content-offset container pb-5">
  <div class="row g-4 align-items-stretch">
    <div class="col-lg-5">
      <div class="form-card h-100 p-4 d-flex flex-column justify-content-center">
        <div class="hero-kicker mb-4"><i class="bi bi-calendar-heart paw-icon"></i> Reservasi PawSpa</div>
        <img src="<?= baseUrl('assets/images/kucing 5.jpg') ?>" class="hero-img mx-auto mb-4" alt="Ilustrasi booking">
        <h1 class="section-title">Booking Grooming</h1>
        <p>Pilih jadwal terbaik, unggah foto pet, lalu tim PawSpa akan menyiapkan sesi grooming yang nyaman.</p>
      </div>
    </div>
    <div class="col-lg-7">
      <form class="form-card p-4 p-md-5" method="post" enctype="multipart/form-data" action="index.php?page=booking&action=store">
        <?= csrfField() ?>
        <div class="row g-4">
          <div class="col-md-6"><label class="form-label fw-bold">Nama Pemilik</label><input class="form-control" value="<?= e($_SESSION['name'] ?? '') ?>" readonly></div>
          <div class="col-md-6"><label class="form-label fw-bold">Nama Hewan</label><input name="pet_name" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">Jenis Hewan</label><select name="pet_type" class="form-select" required><option value="">Pilih</option><option>Kucing</option><option>Anjing</option></select></div>
          <div class="col-md-6"><label class="form-label fw-bold">Layanan</label><select name="service_id" class="form-select" required><option value="">Pilih layanan</option><?php foreach ($services as $s): ?><option value="<?= (int) $s['id'] ?>" <?= $selectedService === (int) $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?> - <?= rupiah($s['price']) ?></option><?php endforeach; ?></select></div>
          <div class="col-md-6"><label class="form-label fw-bold">Tanggal Grooming</label><input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">Jam Grooming</label><input type="time" name="booking_time" min="08:00" max="17:00" class="form-control" required></div>
          <div class="col-12"><label class="form-label fw-bold">Catatan Khusus</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
          <div class="col-12"><label class="form-label fw-bold">Upload Foto Hewan</label><input type="file" name="pet_image" class="form-control image-input" accept=".jpg,.jpeg,.png" data-preview="#petPreview" required><img id="petPreview" class="table-thumb d-none mt-3" alt="Preview"></div>
        </div>
        <button class="btn btn-primary-paw mt-4" type="submit"><i class="bi bi-calendar-check me-2"></i>Kirim Booking</button>
      </form>
    </div>
  </div>
</main>
<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
</body>
</html>
