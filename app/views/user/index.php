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
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand brand-logo fs-3" href="index.php?page=home"><i class="bi bi-paw-fill paw-icon me-2"></i>PawSpa</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li><a class="nav-link fw-bold" href="index.php?page=home">Home</a></li>
        <li><a class="nav-link fw-bold" href="index.php?page=booking">Booking</a></li>
        <?php if (!empty($_SESSION['user_id'])): ?>
          <li><a class="nav-link fw-bold" href="index.php?page=riwayat">Riwayat</a></li>
          <li><a class="nav-link fw-bold" href="index.php?page=profil">Profil</a></li>
        <?php else: ?>
          <li><a class="btn btn-outline-paw btn-sm" href="index.php?page=login">Login</a></li>
          <li><a class="btn btn-primary-paw btn-sm" href="index.php?page=register">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="flash-wrap">
  <?php if (!empty($_SESSION['flash_success'])): ?><div class="alert flash-alert flash-success"><?= e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div><?php endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?><div class="alert flash-alert flash-error"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div><?php endif; ?>
</div>

<main class="page-shell">
  <section class="hero d-flex align-items-center">
    <div class="container py-5">
      <div class="row align-items-center g-5">
        <div class="col-lg-6">
          <div class="hero-kicker mb-4"><i class="bi bi-paw-fill paw-icon"></i> Grooming pet yang lembut dan playful</div>
          <h1>Salon manis untuk pet yang bersih, sehat, dan wangi</h1>
          <p class="lead mt-4 mb-4">Booking grooming kucing dan anjing dengan groomer ramah, jadwal jelas, dan layanan yang dibuat nyaman dari awal sampai pulang.</p>
          <div class="d-flex flex-wrap gap-2 mb-4">
            <a class="btn btn-primary-paw btn-lg" href="<?= empty($_SESSION['user_id']) ? 'index.php?page=register' : 'index.php?page=booking' ?>">
              <i class="bi bi-calendar-heart me-2"></i><?= empty($_SESSION['user_id']) ? 'Daftar Gratis' : 'Booking Sekarang' ?>
            </a>
            <a class="btn btn-outline-paw btn-lg" href="#layanan"><i class="bi bi-grid me-2"></i>Lihat Layanan</a>
          </div>
          <div class="row g-3">
            <div class="col-4"><div class="mini-stat"><strong><?= count($services) ?>+</strong><span>Layanan</span></div></div>
            <div class="col-4"><div class="mini-stat"><strong>08-17</strong><span>Jam buka</span></div></div>
            <div class="col-4"><div class="mini-stat"><strong>2MB</strong><span>Upload foto</span></div></div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="hero-panel">
            <img class="hero-img d-block mx-auto" src="<?= baseUrl('assets/images/anjing 5.jpg') ?>" alt="Anjing grooming PawSpa">
            <div class="row g-3 mt-2">
              <div class="col-6"><img class="w-100 rounded-4" style="height:150px;object-fit:cover" src="<?= baseUrl('assets/images/kucing 5.jpg') ?>" alt="Kucing PawSpa"></div>
              <div class="col-6"><img class="w-100 rounded-4" style="height:150px;object-fit:cover" src="<?= baseUrl('assets/images/anjing 1.jpg') ?>" alt="Anjing kecil PawSpa"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="container py-5">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="trust-card">
          <i class="bi bi-stars paw-icon fs-2"></i>
          <h2 class="h5 fw-black mt-3">Paket cute dan lengkap</h2>
          <p class="mb-0">Pilih bath, spa, full grooming, sampai treatment anti kutu sesuai kebutuhan pet.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="trust-card">
          <i class="bi bi-shield-check paw-icon fs-2"></i>
          <h2 class="h5 fw-black mt-3">Aman untuk bulu sensitif</h2>
          <p class="mb-0">Produk dipilih dengan pendekatan lembut dan higienis untuk kucing maupun anjing.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="trust-card">
          <i class="bi bi-clock-history paw-icon fs-2"></i>
          <h2 class="h5 fw-black mt-3">Reservasi lebih rapi</h2>
          <p class="mb-0">Riwayat booking, upload foto pet, dan status pesanan bisa dipantau dari akunmu.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="container py-5" id="layanan">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
      <div>
        <div class="hero-kicker mb-3"><i class="bi bi-paw-fill paw-icon"></i> Layanan PawSpa</div>
        <h2 class="section-title mb-0">Pricelist grooming favorit</h2>
      </div>
      <div class="filter-tabs d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-paw active" data-filter="Semua">Semua</button>
        <button class="btn btn-outline-paw" data-filter="Kucing">Kucing</button>
        <button class="btn btn-outline-paw" data-filter="Anjing">Anjing</button>
        <button class="btn btn-outline-paw" data-filter="Veterinary">Veterinary</button>
      </div>
    </div>
    <div class="row g-4">
      <?php foreach ($services as $service): ?>
      <div class="col-md-6 col-lg-4" data-category="<?= e($service['category']) ?>">
        <article class="service-card h-100">
          <img src="<?= baseUrl('uploads/services/' . $service['image']) ?>" alt="<?= e($service['name']) ?>">
          <div class="p-4">
            <span class="badge-cat <?= $service['category'] === 'Kucing' ? 'badge-kucing' : 'badge-anjing' ?>"><?= e($service['category']) ?></span>
            <h3 class="h5 fw-black mt-3"><?= e($service['name']) ?></h3>
            <p class="price mb-2"><?= rupiah($service['price']) ?></p>
            <p><i class="bi bi-clock me-1"></i><?= e((string) $service['duration']) ?> menit</p>
            <div class="d-flex gap-2 flex-wrap">
              <button class="btn btn-outline-paw btn-sm" data-bs-toggle="modal" data-bs-target="#service<?= (int) $service['id'] ?>">Lihat Detail</button>
              <a class="btn btn-primary-paw btn-sm" href="index.php?page=booking&service_id=<?= (int) $service['id'] ?>">Booking Sekarang</a>
            </div>
          </div>
        </article>
      </div>
      <div class="modal fade" id="service<?= (int) $service['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content soft-card overflow-hidden">
            <img src="<?= baseUrl('uploads/services/' . $service['image']) ?>" class="w-100" style="height:340px;object-fit:cover" alt="<?= e($service['name']) ?>">
            <div class="modal-body p-4">
              <h3 class="fw-black"><?= e($service['name']) ?></h3>
              <p><?= e($service['description']) ?></p>
              <p class="price"><?= rupiah($service['price']) ?></p>
              <p><i class="bi bi-clock me-1"></i><?= e((string) $service['duration']) ?> menit</p>
              <a class="btn btn-primary-paw" href="index.php?page=booking&service_id=<?= (int) $service['id'] ?>">Booking Sekarang</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="container py-5">
    <div class="mint-band p-4 p-md-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-5">
          <img class="w-100 rounded-5 shadow-sm" style="max-height:420px;object-fit:cover" src="<?= baseUrl('assets/images/kucing 3.jpg') ?>" alt="Kucing grooming">
        </div>
        <div class="col-lg-7">
          <h2 class="section-title mb-4">Alur grooming yang simpel</h2>
          <div class="row g-3">
            <div class="col-md-6"><div class="soft-card p-3 h-100"><i class="bi bi-calendar2-check paw-icon fs-3"></i><h3 class="h6 fw-black mt-2">1. Booking jadwal</h3><p class="mb-0">Pilih layanan, tanggal, jam, dan unggah foto pet.</p></div></div>
            <div class="col-md-6"><div class="soft-card p-3 h-100"><i class="bi bi-chat-heart paw-icon fs-3"></i><h3 class="h6 fw-black mt-2">2. Admin konfirmasi</h3><p class="mb-0">Status booking akan diperbarui oleh admin PawSpa.</p></div></div>
            <div class="col-md-6"><div class="soft-card p-3 h-100"><i class="bi bi-droplet paw-icon fs-3"></i><h3 class="h6 fw-black mt-2">3. Grooming dimulai</h3><p class="mb-0">Pet dirawat dengan proses bersih dan nyaman.</p></div></div>
            <div class="col-md-6"><div class="soft-card p-3 h-100"><i class="bi bi-heart paw-icon fs-3"></i><h3 class="h6 fw-black mt-2">4. Pulang wangi</h3><p class="mb-0">Pet kembali lebih segar, rapi, dan happy.</p></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="container py-5">
    <div class="wavy-band p-4 p-md-5 text-center">
      <i class="bi bi-paw-fill paw-icon fs-3"></i>
      <h2 class="section-title mt-2 mb-3">Siap bikin pet tampil paw-some?</h2>
      <p class="lead mb-4">Mulai dari layanan ringan sampai full spa, semuanya bisa kamu pesan dari satu halaman.</p>
      <a class="btn btn-primary-paw btn-lg" href="index.php?page=booking"><i class="bi bi-calendar-heart me-2"></i>Reservasi Grooming</a>
    </div>
  </section>
</main>

<footer class="py-4">
  <div class="container d-flex flex-column flex-md-row justify-content-between gap-3">
    <div><b>PawSpa</b><br>Grooming cute, bersih, dan profesional.</div>
    <div>Kontak: 0812-3456-7890<br><a href="index.php?page=booking">Booking sekarang</a></div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
</body>
</html>
