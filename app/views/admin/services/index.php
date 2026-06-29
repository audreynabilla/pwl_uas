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
        <div class="container-fluid">

            <!-- ============================================ -->
            <!-- HEADER: Dashboard Link + Title + Add Button  -->
            <!-- ============================================ -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                <!-- Bagian Kiri: Link Dashboard & Title -->
                <div>
                    <a href="index.php?page=admin&section=dashboard" class="btn btn-outline-paw btn-sm mb-2">
                        <i class="bi bi-arrow-left"></i> Dashboard
                    </a>
                    <h1 class="section-title mb-0">Kelola Layanan</h1>
                </div>

                <!-- Bagian Kanan: Tombol Tambah Layanan -->
                <a href="index.php?page=admin&section=services&action=create" class="btn btn-primary-paw">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Layanan
                </a>
            </div>

            <!-- ============================================ -->
            <!-- FLASH MESSAGES (Success / Error)             -->
            <!-- ============================================ -->
            <div class="flash-wrap">
                <?php foreach (['success', 'error'] as $t): ?>
                    <?php if (!empty($_SESSION['flash_' . $t])): ?>
                        <div class="alert flash-alert flash-<?= $t ?>">
                            <?= e($_SESSION['flash_' . $t]); unset($_SESSION['flash_' . $t]); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- ============================================ -->
            <!-- TABEL DAFTAR LAYANAN                         -->
            <!-- ============================================ -->
            <div class="soft-card p-3 table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Durasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $i => $s): ?>
                            <tr>
                                <!-- Nomor Urut -->
                                <td><?= $i + 1 ?></td>

                                <!-- Gambar Layanan -->
                                <td>
                                    <?php if ($s['image']): ?>
                                        <img 
                                            class="table-thumb" 
                                            src="<?= baseUrl('uploads/services/' . $s['image']) ?>" 
                                            alt="<?= e($s['name']) ?>"
                                        >
                                    <?php endif; ?>
                                </td>

                                <!-- Data Layanan -->
                                <td><?= e($s['name']) ?></td>
                                <td><?= e($s['category']) ?></td>
                                <td><?= rupiah($s['price']) ?></td>
                                <td><?= (int) $s['duration'] ?> menit</td>

                                <!-- Tombol Aksi: Edit & Delete -->
                                <td class="d-flex gap-2">
                                    <!-- Tombol Edit -->
                                    <a 
                                        class="btn btn-info btn-sm text-white" 
                                        href="index.php?page=admin&section=services&action=edit&id=<?= (int) $s['id'] ?>"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Form Delete -->
                                    <form 
                                        method="post" 
                                        action="index.php?page=admin&section=services&action=delete" 
                                        onsubmit="return confirm('Hapus layanan ini?')"
                                    >
                                        <?= csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                                        <button data-no-spinner="true" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- ============================================ -->
            <!-- AKHIR TABEL                                 -->
            <!-- ============================================ -->

        </div> <!-- /.container-fluid -->
    </main>

    <!-- JavaScript -->
    <script src="<?= baseUrl('assets/js/main.js') ?>"></script>

</body>
</html>