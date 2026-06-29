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
        <div class="container" style="max-width:860px">

            <!-- ============================================ -->
            <!-- TOMBOL KEMBALI KE DAFTAR LAYANAN             -->
            <!-- ============================================ -->
            <a class="btn btn-outline-paw mb-3" href="index.php?page=admin&section=services">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <!-- ============================================ -->
            <!-- FORM EDIT LAYANAN                            -->
            <!-- ============================================ -->
            <form 
                class="form-card p-4" 
                method="post" 
                enctype="multipart/form-data" 
                action="index.php?page=admin&section=services&action=edit&id=<?= (int) $service['id'] ?>"
            >

                <?= csrfField() ?>

                <h1 class="section-title mb-4">Edit Layanan</h1>

                <div class="row g-3">

                    <!-- ===================================== -->
                    <!-- BAGIAN 1: INFORMASI DASAR             -->
                    <!-- ===================================== -->

                    <!-- Input: Nama Layanan (terisi) -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Layanan</label>
                        <input 
                            name="name" 
                            value="<?= e($service['name']) ?>" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <!-- Dropdown: Kategori (terpilih sesuai data) -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category" class="form-select" required>
                            <option <?= $service['category'] === 'Kucing' ? 'selected' : '' ?>>
                                Kucing
                            </option>
                            <option <?= $service['category'] === 'Anjing' ? 'selected' : '' ?>>
                                Anjing
                            </option>
                        </select>
                    </div>

                    <!-- ===================================== -->
                    <!-- BAGIAN 2: HARGA & DURASI              -->
                    <!-- ===================================== -->

                    <!-- Input: Harga (terisi) -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Harga</label>
                        <input 
                            type="number" 
                            min="0" 
                            name="price" 
                            value="<?= e((string) $service['price']) ?>" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <!-- Input: Durasi dalam menit (terisi) -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Durasi/menit</label>
                        <input 
                            type="number" 
                            min="1" 
                            name="duration" 
                            value="<?= (int) $service['duration'] ?>" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <!-- ===================================== -->
                    <!-- BAGIAN 3: DESKRIPSI & GAMBAR          -->
                    <!-- ===================================== -->

                    <!-- Textarea: Deskripsi (terisi) -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea 
                            name="description" 
                            class="form-control" 
                            rows="4" 
                            required
                        ><?= e($service['description']) ?></textarea>
                    </div>

                    <!-- Preview & Upload: Gambar Layanan -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Gambar Saat Ini / Ganti</label>
                        <br>

                        <!-- Preview gambar lama -->
                        <img 
                            id="imgPreview" 
                            class="table-thumb mb-3" 
                            src="<?= baseUrl('uploads/services/' . $service['image']) ?>" 
                            alt="Gambar saat ini"
                        >

                        <!-- Input upload gambar baru (opsional) -->
                        <input 
                            type="file" 
                            name="image" 
                            class="form-control image-input" 
                            accept=".jpg,.jpeg,.png" 
                            data-preview="#imgPreview"
                        >
                    </div>

                </div> <!-- /.row -->

                <!-- ============================================ -->
                <!-- TOMBOL SUBMIT                               -->
                <!-- ============================================ -->
                <button class="btn btn-primary-paw mt-4">
                    <i class="bi bi-save me-2"></i>Update
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