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
        <h1 class="section-title">Booking Grooming & Veterinary</h1>
        <p>Pilih tipe layanan, jadwal terbaik, lalu unggah bukti pembayaran agar reservasimu bisa segera diproses.</p>
      </div>
    </div>
    <div class="col-lg-7">
      <form class="form-card p-4 p-md-5" method="post" enctype="multipart/form-data" action="index.php?page=booking&action=store">
        <?= csrfField() ?>
        <div class="row g-4">
          <div class="col-12">
            <label class="form-label fw-bold">Tipe Layanan</label>
            <select name="service_type" id="serviceType" class="form-select" required>
              <option value="">Pilih tipe layanan</option>
              <option value="GROOMING">GROOMING</option>
              <option value="VETERINARY">VETERINARY</option>
            </select>
          </div>
          <div class="col-md-6"><label class="form-label fw-bold">Nama Pemilik</label><input class="form-control" value="<?= e($_SESSION['name'] ?? '') ?>" readonly></div>
          <div class="col-md-6"><label class="form-label fw-bold">Nama Hewan</label><input name="pet_name" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">Jenis Hewan</label><select name="pet_type" class="form-select" required><option value="">Pilih</option><option>Kucing</option><option>Anjing</option></select></div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Layanan</label>
            <select name="service_id" id="serviceSelect" class="form-select" required>
              <option value="">Pilih tipe layanan dulu</option>
              <?php foreach ($services as $s): ?>
                <?php $type = $s['category'] === 'Veterinary' ? 'VETERINARY' : 'GROOMING'; ?>
                <option value="<?= (int) $s['id'] ?>" data-type="<?= e($type) ?>" data-category="<?= e($s['category']) ?>" data-price="<?= e((string) $s['price']) ?>" <?= $selectedService === (int) $s['id'] ? 'selected' : '' ?>>
                  <?= e($s['name']) ?> - <?= rupiah($s['price']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div id="servicePrice" class="price mt-2 d-none"></div>
          </div>
          <div class="col-md-6"><label class="form-label fw-bold">Tanggal Booking</label><input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label fw-bold">Jam Booking</label><input type="time" name="booking_time" min="08:00" max="17:00" class="form-control" required></div>
          <div class="col-12"><label class="form-label fw-bold">Catatan Khusus</label><textarea name="notes" class="form-control" rows="3" required></textarea></div>
          <div class="col-12">
            <div class="hero-kicker mb-3"><i class="bi bi-person-heart paw-icon"></i> About You</div>
            <label class="form-label fw-bold">Upload Bukti Pembayaran</label>
            <input type="file" name="payment_proof" class="form-control image-input" accept=".jpg,.jpeg,.png" data-preview="#paymentPreview" required>
            <img id="paymentPreview" class="table-thumb d-none mt-3" alt="Preview bukti pembayaran">
          </div>
          <div class="col-12 d-none" id="vetPetPhotoWrap">
            <label class="form-label fw-bold">Upload Foto Hewan Veterinary (Opsional)</label>
            <input type="file" name="pet_image" class="form-control image-input" accept=".jpg,.jpeg,.png" data-preview="#petPreview">
            <img id="petPreview" class="table-thumb d-none mt-3" alt="Preview foto hewan">
          </div>
        </div>
        <button class="btn btn-primary-paw mt-4" type="submit"><i class="bi bi-calendar-check me-2"></i>Kirim Booking</button>
      </form>
    </div>
  </div>
</main>
<script src="<?= baseUrl('assets/js/main.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const typeSelect = document.getElementById('serviceType');
  const serviceSelect = document.getElementById('serviceSelect');
  const priceBox = document.getElementById('servicePrice');
  const vetPhoto = document.getElementById('vetPetPhotoWrap');
  const originalOptions = Array.from(serviceSelect.querySelectorAll('option[data-type]'));
  const selectedService = '<?= (int) $selectedService ?>';

  function rupiah(value) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(value || 0));
  }

  function setTypeFromSelected() {
    const selected = originalOptions.find((option) => option.value === selectedService);
    if (selected && !typeSelect.value) typeSelect.value = selected.dataset.type;
  }

  function rebuildServices() {
    const type = typeSelect.value;
    serviceSelect.innerHTML = '<option value="">Pilih layanan</option>';
    originalOptions.forEach((option) => {
      if (!type || option.dataset.type === type) {
        serviceSelect.appendChild(option.cloneNode(true));
      }
    });
    if (selectedService && Array.from(serviceSelect.options).some((option) => option.value === selectedService)) {
      serviceSelect.value = selectedService;
    }
    vetPhoto.classList.toggle('d-none', type !== 'VETERINARY');
    updatePrice();
  }

  function updatePrice() {
    const option = serviceSelect.selectedOptions[0];
    if (!option || !option.dataset.price) {
      priceBox.classList.add('d-none');
      priceBox.textContent = '';
      return;
    }
    priceBox.textContent = 'Harga layanan: ' + rupiah(option.dataset.price);
    priceBox.classList.remove('d-none');
  }

  setTypeFromSelected();
  rebuildServices();
  typeSelect.addEventListener('change', rebuildServices);
  serviceSelect.addEventListener('change', updatePrice);
});
</script>
</body>
</html>
