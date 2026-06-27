<section class="page-head">
    <div>
        <h1>Edit Reservasi</h1>
        <p class="muted">Perbarui jadwal, layanan, status, atau gambar hewan.</p>
    </div>
</section>

<div class="panel">
    <?php
    $actionUrl = url('reservasi_update', ['id' => $reservation['id']]);
    require APP_PATH . '/views/reservasi/_form.php';
    ?>
</div>
