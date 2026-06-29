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

    <!-- ============================================ -->
    <!-- LAYOUT: SIDEBAR + MAIN CONTENT              -->
    <!-- ============================================ -->
    <div class="row g-0">

        <!-- ========================================== -->
        <!-- SIDEBAR ADMIN                              -->
        <!-- ========================================== -->
        <aside class="col-lg-3 col-xl-2 admin-sidebar p-4">

            <!-- Brand Logo -->
            <h2 class="brand-logo text-white">
                <i class="bi bi-paw-fill paw-icon me-2"></i>PawSpa
            </h2>

            <!-- Greeting User -->
            <p class="small">Halo, <?= e($_SESSION['name'] ?? 'Admin') ?></p>

            <!-- Navigation Menu -->
            <nav class="d-grid gap-2">
                <a class="active" href="index.php?page=admin&section=dashboard">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
                <a href="index.php?page=admin&section=services">
                    <i class="bi bi-grid me-2"></i>Kelola Layanan
                </a>
                <a href="index.php?page=admin&section=bookings">
                    <i class="bi bi-calendar-check me-2"></i>Kelola Booking
                </a>
                <a href="index.php?page=logout">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </nav>
        </aside>
        <!-- ========================================== -->
        <!-- AKHIR SIDEBAR                             -->
        <!-- ========================================== -->

        <!-- ========================================== -->
        <!-- MAIN CONTENT                              -->
        <!-- ========================================== -->
        <main class="col-lg-9 col-xl-10 admin-main p-4 p-lg-5">

            <!-- ====================================== -->
            <!-- FLASH MESSAGES                        -->
            <!-- ====================================== -->
            <div class="flash-wrap">
                <?php foreach (['success', 'error'] as $t): ?>
                    <?php if (!empty($_SESSION['flash_' . $t])): ?>
                        <div class="alert flash-alert flash-<?= $t ?>">
                            <?= e($_SESSION['flash_' . $t]); unset($_SESSION['flash_' . $t]); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- ====================================== -->
            <!-- PAGE TITLE                            -->
            <!-- ====================================== -->
            <h1 class="section-title mb-4">Dashboard Admin</h1>

            <!-- ====================================== -->
            <!-- STATISTICS CARDS                      -->
            <!-- ====================================== -->
            <div class="row g-4 mb-4">

                <!-- Card 1: Booking Hari Ini -->
                <div class="col-md-4">
                    <div class="stat-card p-4">
                        <i class="bi bi-calendar-check fs-1 paw-icon"></i>
                        <p class="mb-1 fw-bold">Booking Hari Ini</p>
                        <h2 class="fw-black"><?= (int) $todayBookings ?></h2>
                    </div>
                </div>

                <!-- Card 2: Total User Terdaftar -->
                <div class="col-md-4">
                    <div class="stat-card p-4">
                        <i class="bi bi-people fs-1 paw-icon"></i>
                        <p class="mb-1 fw-bold">User Terdaftar</p>
                        <h2 class="fw-black"><?= (int) $totalUsers ?></h2>
                    </div>
                </div>

                <!-- Card 3: Total Layanan Tersedia -->
                <div class="col-md-4">
                    <div class="stat-card p-4">
                        <i class="bi bi-grid fs-1 paw-icon"></i>
                        <p class="mb-1 fw-bold">Layanan Tersedia</p>
                        <h2 class="fw-black"><?= (int) $totalServices ?></h2>
                    </div>
                </div>

            </div>
            <!-- ====================================== -->
            <!-- AKHIR STATISTICS CARDS                -->
            <!-- ====================================== -->

            <!-- ====================================== -->
            <!-- TABLE: BOOKING TERBARU                -->
            <!-- ====================================== -->
            <div class="soft-card p-4 table-responsive">
                <h2 class="h4 fw-black mb-3">Booking Terbaru</h2>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Layanan</th>
                            <th>Pet</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestBookings as $b): ?>
                            <tr>
                                <td><?= e($b['user_name']) ?></td>
                                <td><?= e($b['service_name']) ?></td>
                                <td><?= e($b['pet_name']) ?></td>
                                <td>
                                    <?= e($b['booking_date']) ?> <?= e(substr($b['booking_time'], 0, 5)) ?>
                                </td>
                                <td><?= statusBadge($b['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- ====================================== -->
            <!-- AKHIR TABLE                           -->
            <!-- ====================================== -->

        </main>
        <!-- ========================================== -->
        <!-- AKHIR MAIN CONTENT                        -->
        <!-- ========================================== -->

    </div>
    <!-- ============================================ -->
    <!-- AKHIR LAYOUT                                -->
    <!-- ============================================ -->

    <!-- JavaScript -->
    <script src="<?= baseUrl('assets/js/main.js') ?>"></script>

</body>
</html>