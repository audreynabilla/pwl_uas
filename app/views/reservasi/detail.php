<section class="page-head">
    <div>
        <h1>Detail Reservasi</h1>
        <p class="muted">Ringkasan data reservasi grooming.</p>
    </div>
    <div class="actions">
        <a class="button button-ghost" href="<?= url('reservasi') ?>">Kembali</a>
        <a class="button button-primary" href="<?= url('reservasi_edit', ['id' => $reservation['id']]) ?>">Edit</a>
    </div>
</section>

<section class="detail-layout">
    <div class="panel">
        <dl class="detail-list">
            <dt>Pelanggan</dt>
            <dd><?= h($reservation['nama_lengkap']) ?></dd>
            <dt>Nama Hewan</dt>
            <dd><?= h($reservation['nama_hewan']) ?></dd>
            <dt>Jenis Hewan</dt>
            <dd><?= h($reservation['jenis_hewan']) ?></dd>
            <dt>Layanan</dt>
            <dd><?= h($reservation['jenis_layanan']) ?></dd>
            <dt>Tanggal dan Jam</dt>
            <dd><?= h(formatDate($reservation['tanggal_reservasi'])) ?>, <?= h(substr($reservation['jam_reservasi'], 0, 5)) ?></dd>
            <dt>Status</dt>
            <dd><span class="badge badge-<?= h($reservation['status']) ?>"><?= h(ucfirst($reservation['status'])) ?></span></dd>
            <dt>Catatan</dt>
            <dd><?= nl2br(h($reservation['catatan_tambahan'] ?: '-')) ?></dd>
        </dl>
    </div>

    <div class="panel image-panel">
        <?php if ($reservation['gambar_hewan']): ?>
            <img src="<?= uploaded($reservation['gambar_hewan']) ?>" alt="Gambar <?= h($reservation['nama_hewan']) ?>">
        <?php else: ?>
            <div class="empty-image">Belum ada gambar</div>
        <?php endif; ?>
    </div>
</section>
