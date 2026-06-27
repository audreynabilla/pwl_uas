<section class="page-head">
    <div>
        <h1>Daftar Reservasi</h1>
        <p class="muted">Kelola reservasi grooming dan pantau statusnya.</p>
    </div>
    <a class="button button-primary" href="<?= url('reservasi_tambah') ?>">Tambah Reservasi</a>
</section>

<section class="stats-grid">
    <div class="stat"><span>Total</span><strong><?= h($stats['total']) ?></strong></div>
    <div class="stat"><span>Pending</span><strong><?= h($stats['pending']) ?></strong></div>
    <div class="stat"><span>Selesai</span><strong><?= h($stats['selesai']) ?></strong></div>
    <div class="stat"><span>Batal</span><strong><?= h($stats['batal']) ?></strong></div>
</section>

<section class="panel">
    <form method="get" action="<?= h($_SERVER['PHP_SELF']) ?>" class="filters">
        <input type="hidden" name="action" value="reservasi">
        <label>
            Status
            <select name="status">
                <option value="">Semua status</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= h($status) ?>" <?= selected($filters['status'], $status) ?>><?= h(ucfirst($status)) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Tanggal
            <input type="date" name="tanggal" value="<?= h($filters['tanggal']) ?>">
        </label>
        <button class="button button-primary" type="submit">Filter</button>
        <a class="button button-ghost" href="<?= url('reservasi') ?>">Reset</a>
    </form>
</section>

<section class="panel table-panel">
    <?php if (!$reservations): ?>
        <div class="empty-state">
            <h2>Belum ada reservasi</h2>
            <p class="muted">Tambahkan reservasi pertama untuk mulai mengelola jadwal grooming.</p>
            <a class="button button-primary" href="<?= url('reservasi_tambah') ?>">Tambah Reservasi</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Hewan</th>
                        <th>Layanan</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Pelanggan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td>
                                <?php if ($reservation['gambar_hewan']): ?>
                                    <img class="thumb" src="<?= uploaded($reservation['gambar_hewan']) ?>" alt="Gambar <?= h($reservation['nama_hewan']) ?>">
                                <?php else: ?>
                                    <span class="thumb placeholder">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= h($reservation['nama_hewan']) ?></strong>
                                <span class="muted block"><?= h($reservation['jenis_hewan']) ?></span>
                            </td>
                            <td><?= h($reservation['jenis_layanan']) ?></td>
                            <td><?= h(formatDate($reservation['tanggal_reservasi'])) ?><span class="muted block"><?= h(substr($reservation['jam_reservasi'], 0, 5)) ?></span></td>
                            <td><span class="badge badge-<?= h($reservation['status']) ?>"><?= h(ucfirst($reservation['status'])) ?></span></td>
                            <td><?= h($reservation['nama_lengkap']) ?></td>
                            <td class="row-actions">
                                <a href="<?= url('reservasi_detail', ['id' => $reservation['id']]) ?>">Detail</a>
                                <a href="<?= url('reservasi_edit', ['id' => $reservation['id']]) ?>">Edit</a>
                                <a class="danger js-confirm-delete" href="<?= url('reservasi_hapus', ['id' => $reservation['id']]) ?>">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
