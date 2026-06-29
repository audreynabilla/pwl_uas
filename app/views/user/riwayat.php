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
    <a class="btn btn-outline-paw btn-sm" href="index.php?page=profil">Profil</a>
  </div>
</nav>
<div class="flash-wrap">
  <?php foreach (['success','error'] as $t): if (!empty($_SESSION['flash_'.$t])): ?><div class="alert flash-alert flash-<?= $t ?>"><?= e($_SESSION['flash_'.$t]); unset($_SESSION['flash_'.$t]); ?></div><?php endif; endforeach; ?>
</div>
<main class="content-offset container pb-5">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
      <div class="hero-kicker mb-3"><i class="bi bi-clock-history paw-icon"></i> Riwayat reservasi</div>
      <h1 class="section-title mb-0">Riwayat Booking</h1>
    </div>
    <a class="btn btn-primary-paw" href="index.php?page=booking">Booking Baru</a>
  </div>
  <?php if (!$bookings): ?>
    <div class="empty-state text-center">
      <i class="bi bi-paw-fill paw-icon fs-1"></i>
      <h2 class="h4 fw-black mt-3">Belum ada booking</h2>
      <p>Yuk buat jadwal grooming pertamamu.</p>
      <a class="btn btn-primary-paw" href="index.php?page=booking">Booking Pertamamu</a>
    </div>
  <?php else: ?>
    <div class="soft-card p-3 table-responsive">
      <table class="table align-middle">
        <thead><tr><th>No.</th><th>Foto Hewan</th><th>Layanan</th><th>Nama Hewan</th><th>Tanggal & Jam</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($bookings as $i => $b): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?php if ($b['pet_image']): ?><img class="table-thumb" src="<?= baseUrl('uploads/pets/' . $b['pet_image']) ?>" alt="<?= e($b['pet_name']) ?>"><?php endif; ?></td>
            <td><?= e($b['service_name']) ?></td>
            <td><?= e($b['pet_name']) ?></td>
            <td><?= e($b['booking_date']) ?> <?= e(substr($b['booking_time'],0,5)) ?></td>
            <td><?= statusBadge($b['status']) ?></td>
            <td>
              <div class="d-flex gap-2 flex-wrap">
                <?php if ($b['status'] === 'pending'): ?>
                  <a class="btn btn-outline-paw btn-sm" href="index.php?page=editBooking&id=<?= (int) $b['id'] ?>"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                  <form method="post" action="index.php?page=riwayat&action=cancel" onsubmit="return confirm('Batalkan booking ini?')"><?= csrfField() ?><input type="hidden" name="id" value="<?= (int) $b['id'] ?>"><button data-no-spinner="true" class="btn btn-danger-paw btn-sm" type="submit">Batalkan</button></form>
                <?php elseif ($b['status'] === 'confirmed'): ?>
                  <form method="post" action="index.php?page=riwayat&action=cancel" onsubmit="return confirm('Batalkan booking ini?')"><?= csrfField() ?><input type="hidden" name="id" value="<?= (int) $b['id'] ?>"><button data-no-spinner="true" class="btn btn-danger-paw btn-sm" type="submit">Batalkan</button></form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</main>
<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
</body>
</html>
