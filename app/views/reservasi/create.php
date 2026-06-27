<section class="page-head">
    <div>
        <h1>Tambah Reservasi</h1>
        <p class="muted">Isi data grooming hewan peliharaan dengan lengkap.</p>
    </div>
</section>

<div class="panel">
    <?php
    $actionUrl = url('reservasi_simpan');
    require APP_PATH . '/views/reservasi/_form.php';
    ?>
</div>
