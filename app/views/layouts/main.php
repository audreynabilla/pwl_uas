<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($title ?? 'Reservasi Grooming Pet') ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="<?= url('reservasi') ?>">Grooming Pet</a>
        <nav class="nav">
            <?php if (!empty($_SESSION['user'])): ?>
                <span><?= h($_SESSION['user']['nama_lengkap']) ?> (<?= h($_SESSION['user']['role']) ?>)</span>
                <a href="<?= url('reservasi') ?>">Reservasi</a>
                <a href="<?= url('reservasi_tambah') ?>">Tambah</a>
                <a class="button button-ghost" href="<?= url('logout') ?>">Logout</a>
            <?php else: ?>
                <a href="<?= url('login') ?>">Login</a>
                <a class="button button-primary" href="<?= url('register') ?>">Daftar</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="container">
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success"><?= h($_SESSION['flash_success']) ?></div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-error"><?= h($_SESSION['flash_error']) ?></div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <script src="<?= asset('js/script.js') ?>"></script>
</body>
</html>
