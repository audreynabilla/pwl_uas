<form method="post" action="<?= h($actionUrl) ?>" class="form" enctype="multipart/form-data">
    <div class="form-grid">
        <label>
            Nama Hewan
            <input type="text" name="nama_hewan" value="<?= h($old['nama_hewan'] ?? '') ?>" required>
        </label>

        <label>
            Jenis Hewan
            <select name="jenis_hewan" required>
                <option value="">Pilih jenis hewan</option>
                <?php foreach ($animalTypes as $type): ?>
                    <option value="<?= h($type) ?>" <?= selected($old['jenis_hewan'] ?? '', $type) ?>><?= h($type) ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Jenis Layanan
            <select name="jenis_layanan" required>
                <option value="">Pilih layanan</option>
                <?php foreach ($serviceTypes as $service): ?>
                    <option value="<?= h($service) ?>" <?= selected($old['jenis_layanan'] ?? '', $service) ?>><?= h($service) ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Tanggal Reservasi
            <input type="date" name="tanggal_reservasi" value="<?= h($old['tanggal_reservasi'] ?? '') ?>" required>
        </label>

        <label>
            Jam Reservasi
            <input type="time" name="jam_reservasi" value="<?= h(substr($old['jam_reservasi'] ?? '', 0, 5)) ?>" required>
        </label>

        <label>
            Status
            <select name="status" required>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= h($status) ?>" <?= selected($old['status'] ?? 'pending', $status) ?>><?= h(ucfirst($status)) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <label>
        Catatan Tambahan
        <textarea name="catatan_tambahan" rows="4"><?= h($old['catatan_tambahan'] ?? '') ?></textarea>
    </label>

    <label>
        Gambar Hewan (JPG/PNG, maksimal 2MB)
        <input type="file" name="gambar_hewan" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
    </label>

    <?php if (!empty($reservation['gambar_hewan'])): ?>
        <div class="current-image">
            <span>Gambar saat ini:</span>
            <img src="<?= uploaded($reservation['gambar_hewan']) ?>" alt="Gambar <?= h($reservation['nama_hewan']) ?>">
        </div>
    <?php endif; ?>

    <div class="actions">
        <button class="button button-primary" type="submit">Simpan</button>
        <a class="button button-ghost" href="<?= url('reservasi') ?>">Batal</a>
    </div>
</form>
