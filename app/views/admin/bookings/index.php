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

    <main class="admin-main min-vh-100 p-4 p-lg-5">
        <div class="container-fluid">

            
            
            
            <div class="d-flex justify-content-between flex-wrap gap-3 mb-4">

                
                <div>
                    <a href="index.php?page=admin&section=dashboard" class="btn btn-outline-paw btn-sm mb-2">
                        <i class="bi bi-arrow-left"></i> Dashboard
                    </a>
                    <h1 class="section-title">Kelola Booking</h1>
                </div>

                
                <a class="btn btn-primary-paw align-self-start" href="index.php?page=admin&section=bookings&action=create">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Booking Manual
                </a>
            </div>

            
            
            
            <form class="mb-3" method="get">
                <input type="hidden" name="page" value="admin">
                <input type="hidden" name="section" value="bookings">

                <select name="status" class="form-select" style="max-width:260px" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <?php foreach (['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $st): ?>
                        <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>>
                            <?= ucfirst($st) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            
            
            
            <div class="flash-wrap">
                <?php foreach (['success', 'error'] as $t): ?>
                    <?php if (!empty($_SESSION['flash_' . $t])): ?>
                        <div class="alert flash-alert flash-<?= $t ?>">
                            <?= e($_SESSION['flash_' . $t]); unset($_SESSION['flash_' . $t]); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            
            
            
            <div class="soft-card p-3 table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto Hewan</th>
                            <th>Nama User</th>
                            <th>Nama Hewan</th>
                            <th>Layanan</th>
                            <th>Tanggal & Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $i => $b): ?>
                            <tr>
                                
                                <td><?= $i + 1 ?></td>

                                
                                <td>
                                    <?php if ($b['pet_image']): ?>
                                        <img 
                                            class="table-thumb" 
                                            src="<?= baseUrl('uploads/pets/' . $b['pet_image']) ?>" 
                                            alt="<?= e($b['pet_name']) ?>"
                                        >
                                    <?php endif; ?>
                                </td>

                                
                                <td><?= e($b['user_name']) ?></td>
                                <td><?= e($b['pet_name']) ?></td>
                                <td><?= e($b['service_name']) ?></td>
                                <td><?= e($b['booking_date']) ?> <?= e(substr($b['booking_time'], 0, 5)) ?></td>

                                
                                <td><?= statusBadge($b['status']) ?></td>

                                
                                <td class="d-flex gap-2">
                                    
                                    <a 
                                        class="btn btn-info btn-sm text-white" 
                                        href="index.php?page=admin&section=bookings&action=edit&id=<?= (int) $b['id'] ?>"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    
                                    <form 
                                        method="post" 
                                        action="index.php?page=admin&section=bookings&action=delete" 
                                        onsubmit="return confirm('Hapus booking ini?')"
                                    >
                                        <?= csrfField() ?>
                                        <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
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
            
            
            

        </div> 
    </main>

    
    <script src="<?= baseUrl('assets/js/main.js') ?>"></script>

</body>
</html>