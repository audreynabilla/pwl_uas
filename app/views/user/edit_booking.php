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
    <div><a class="btn btn-outline-paw btn-sm" href="index.php?page=riwayat">Riwayat</a></div>
  </div>
</nav>
<div class="flash-wrap">
  <?php foreach (['success','error'] as $t): if (!empty($_SESSION['flash_'.$t])): ?><div class="alert flash-alert flash-<?= $t ?>"><?= e($_SESSION['flash_'.$t]); unset($_SESSION['flash_'.$t]); ?></div><?php endif; endforeach; ?>
</div>
<main class="content-offset container pb-5">
  <div class="row g-4 align-items-stretch">
    <div class="col-lg-5">
      <div class="form-card h-100 p-4 d-flex flex-column justify-content-center">
        <div class="hero-kicker mb-4"><i class="bi bi-pencil-square paw-icon"></i> Perbarui reservasi</div>
        <img src="<?= baseUrl('assets/images/kucing 5.jpg') ?>" class="hero-img mx-auto mb-4" alt="Ilustrasi edit booking">
        <h1 class="section-title">Edit Booking</h1>
        <p>Ubah detail booking yang masih menunggu konfirmasi admin. Status tetap pending sampai PawSpa mengonfirmasi.</p>
      </div>
    </div>
    <div class="col-lg-7">
      <form class="form-card p-4 p-md-5" method="post" enctype="multipart/form-data" action="index.php?page=editBooking&action=update&id=<?= (int) $booking['id'] ?>">
        <?= csrfField() ?>
        <input type="hidden" name="id" value="<?= (int) $booking['id'] ?>">
        <div class="row g-4">
          <div class="col-md-6"><label class="form-label fw-bold">ID Booking</label><input class="form-control" value="#<?= (int) $booking['id'] ?>" readonly></div>
          <div class="col-md-6"><label class="form-label fw-bold">Status</label><input class="form-control" value="<?= e(ucfirst($booking['status'])) ?>" readonly></div>
          <div class="col-md-6"><label class="form-label fw-bold">Nama Pemilik</label><input class="form-control" value="<?= e($_SESSION['name'] ?? '') ?>" readonly></div>
          <div class="col-md-6"><label class="form-label fw-bold">Nama Hewan</label><input name="pet_name" class="form-control" value="<?= e($booking['pet_name']) ?>" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">Jenis Hewan</label><select name="pet_type" class="form-select" required><option value="">Pilih</option><option<?= $booking['pet_type'] === 'Kucing' ? ' selected' : '' ?>>Kucing</option><option<?= $booking['pet_type'] === 'Anjing' ? ' selected' : '' ?>>Anjing</option></select></div>
          <div class="col-md-6"><label class="form-label fw-bold">Layanan</label><select name="service_id" class="form-select" required><option value="">Pilih layanan</option><?php foreach ($services as $s): ?><option value="<?= (int) $s['id'] ?>"<?= (int) $booking['service_id'] === (int) $s['id'] ? ' selected' : '' ?>><?= e($s['name']) ?> - <?= rupiah($s['price']) ?></option><?php endforeach; ?></select></div>
          <div class="col-md-6"><label class="form-label fw-bold">Tanggal Grooming</label><input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" value="<?= e($booking['booking_date']) ?>" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">Jam Grooming</label><input type="time" name="booking_time" min="08:00" max="17:00" value="<?= e(substr($booking['booking_time'], 0, 5)) ?>" class="form-control" required></div>
          <div class="col-12"><label class="form-label fw-bold">Catatan Khusus</label><textarea name="notes" class="form-control" rows="3"><?= e($booking['notes'] ?? '') ?></textarea></div>
          <div class="col-12">
            <label class="form-label fw-bold">Upload Foto Hewan</label>
            <?php if ($booking['pet_image']): ?>
              <img id="petPreview" class="table-thumb mb-3" src="<?= baseUrl('uploads/pets/' . $booking['pet_image']) ?>" alt="<?= e($booking['pet_name']) ?>">
            <?php else: ?>
              <img id="petPreview" class="table-thumb d-none mb-3" alt="Preview">
            <?php endif; ?>
            <input type="file" name="pet_image" class="form-control image-input" accept=".jpg,.jpeg,.png" data-preview="#petPreview">
            <small class="text-muted">Opsional. JPG/PNG maks. 2MB. Kosongkan jika tidak ingin mengganti foto.</small>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold">Upload Bukti Pembayaran</label>
            <?php if (!empty($booking['payment_proof'])): ?>
              <img id="paymentPreview" class="table-thumb mb-3" src="<?= baseUrl('uploads/payments/' . $booking['payment_proof']) ?>" alt="Bukti pembayaran">
            <?php else: ?>
              <img id="paymentPreview" class="table-thumb d-none mb-3" alt="Preview bukti pembayaran">
            <?php endif; ?>
            <input type="file" name="payment_proof" class="form-control image-input" accept=".jpg,.jpeg,.png" data-preview="#paymentPreview">
            <small class="text-muted">Opsional. JPG/PNG maks. 2MB. Kosongkan jika tidak ingin mengganti bukti.</small>
          </div>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-4">
          <button class="btn btn-primary-paw" type="submit"><i class="bi bi-check-circle me-2"></i>Simpan Perubahan</button>
          <a class="btn btn-outline-paw" href="index.php?page=riwayat">Batal</a>
        </div>
      </form>
    </div>
  </div>
</main>
<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
</body>
</html>
