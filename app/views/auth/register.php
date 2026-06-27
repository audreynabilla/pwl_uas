<section class="auth-shell">
    <div class="panel auth-panel">
        <h1>Daftar Akun</h1>
        <p class="muted">Buat akun baru untuk mulai melakukan reservasi grooming.</p>

        <form method="post" action="<?= url('register') ?>" class="form">
            <label>
                Nama Lengkap
                <input type="text" name="nama_lengkap" value="<?= h($old['nama_lengkap'] ?? '') ?>" required>
            </label>
            <label>
                Username
                <input type="text" name="username" value="<?= h($old['username'] ?? '') ?>" required>
            </label>
            <label>
                Email
                <input type="email" name="email" value="<?= h($old['email'] ?? '') ?>" required>
            </label>
            <label>
                No. Telepon
                <input type="text" name="no_telepon" value="<?= h($old['no_telepon'] ?? '') ?>">
            </label>
            <label>
                Password
                <input type="password" name="password" required>
            </label>
            <label>
                Konfirmasi Password
                <input type="password" name="password_confirmation" required>
            </label>
            <button class="button button-primary" type="submit">Daftar</button>
        </form>

        <p class="small">Sudah punya akun? <a href="<?= url('login') ?>">Login</a>.</p>
    </div>
</section>
