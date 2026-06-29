<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="<?= baseUrl('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>

    <main class="admin-main min-vh-100 p-4 p-lg-5">
        <div class="container" style="max-width:900px">

            <!-- Tombol Kembali ke Daftar Booking -->
            <a class="btn btn-outline-paw mb-3" href="index.php?page=admin&section=bookings">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <!-- ============================================ -->
            <!-- FORM EDIT BOOKING                            -->
            <!-- ============================================ -->
            <form 
                class="form-card p-4" 
                method="post" 
                enctype="multipart/form-data" 
                action="index.php?page=admin&section=bookings&action=edit&id=<?= (int) $booking['id'] ?>"
            >

                <?= csrfField() ?>

                <h1 class="section-title mb-4">Edit Booking</h1>

                <div class="row g-3">

                    <!-- ===================================== -->
                    <!-- BAGIAN 1: DATA PEMILIK & LAYANAN      -->
                    <!-- ===================================== -->

                    <!-- Dropdown: Nama Pemilik -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Pemilik</label>
                        <select name="user_id" class="form-select" required>
                            <?php foreach ($users as $u): ?>
                                <option 
                                    value="<?= (int) $u['id'] ?>" 
                                    <?= (int)$booking['user_id'] === (int)$u['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($u['name']) ?> - <?= e($u['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Dropdown: Layanan -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Layanan</label>
                        <select name="service_id" class="form-select" required>
                            <?php foreach ($services as $s): ?>
                                <option 
                                    value="<?= (int) $s['id'] ?>" 
                                    <?= (int)$booking['service_id'] === (int)$s['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($s['name']) ?> - <?= rupiah($s['price']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- ===================================== -->
                    <!-- BAGIAN 2: DATA HEWAN                  -->
                    <!-- ===================================== -->

                    <!-- Input: Nama Hewan -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Hewan</label>
                        <input 
                            name="pet_name" 
                            value="<?= e($booking['pet_name']) ?>" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <!-- Dropdown: Jenis Hewan -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jenis Hewan</label>
                        <select name="pet_type" class="form-select" required>
                            <option <?= $booking['pet_type'] === 'Kucing' ? 'selected' : '' ?>>
                                Kucing
                            </option>
                            <option <?= $booking['pet_type'] === 'Anjing' ? 'selected' : '' ?>>
                                Anjing
                            </option>
                        </select>
                    </div>

                    <!-- ===================================== -->
                    <!-- BAGIAN 3: TANGGAL, JAM, & STATUS     -->
                    <!-- ===================================== -->

                    <!-- Input: Tanggal Booking -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input 
                            type="date" 
                            name="booking_date" 
                            value="<?= e($booking['booking_date']) ?>" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <!-- Input: Jam Booking (08:00 - 17:00) -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Jam</label>
                        <input 
                            type="time" 
                            name="booking_time" 
                            value="<?= e(substr($booking['booking_time'], 0, 5)) ?>" 
                            min="08:00" 
                            max="17:00" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <!-- Dropdown: Status Booking -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <?php foreach (['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $st): ?>
                                <option 
                                    value="<?= $st ?>" 
                                    <?= $booking['status'] === $st ? 'selected' : '' ?>
                                >
                                    <?= ucfirst($st) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- ===================================== -->
                    <!-- BAGIAN 4: CATATAN & FOTO             -->
                    <!-- ===================================== -->

                    <!-- Textarea: Catatan -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Catatan</label>
                        <textarea name="notes" rows="3" class="form-control"><?= e($booking['notes']) ?></textarea>
                    </div>

                    <!-- Upload & Preview: Foto Hewan -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Foto Hewan Saat Ini / Ganti</label>
                        <br>

                        <!-- Preview foto lama (jika ada) -->
                        <?php if ($booking['pet_image']): ?>
                            <img 
                                id="petPreview" 
                                class="table-thumb mb-3" 
                                src="<?= baseUrl('uploads/pets/' . $booking['pet_image']) ?>" 
                                alt="<?= e($booking['pet_name']) ?>"
                            >
                        <?php else: ?>
                            <img 
                                id="petPreview" 
                                class="table-thumb d-none mb-3" 
                                alt="Preview"
                            >
                        <?php endif; ?>

                        <!-- Input upload file -->
                        <input 
                            type="file" 
                            name="pet_image" 
                            class="form-control image-input" 
                            accept=".jpg,.jpeg,.png" 
                            data-preview="#petPreview"
                        >
                    </div>

                </div> <!-- /.row -->

                <!-- Tombol Submit -->
                <button class="btn btn-primary-paw mt-4">
                    Update Booking
                </button>

            </form>
            <!-- ============================================ -->
            <!-- AKHIR FORM                                  -->
            <!-- ============================================ -->

        </div> <!-- /.container -->
    </main>

    <!-- JavaScript -->
    <script src="<?= baseUrl('assets/js/main.js') ?>"></script>

</body>
</html>