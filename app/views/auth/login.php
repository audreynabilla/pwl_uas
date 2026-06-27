<section class="auth-shell">
    <div class="panel auth-panel">
        <h1>Login</h1>
        <p class="muted">Masuk untuk mengelola reservasi grooming hewan peliharaan.</p>

        <form method="post" action="<?= url('login') ?>" class="form">
            <label>
                Username atau Email
                <input type="text" name="login" value="<?= h($old['login'] ?? '') ?>" required>
            </label>
            <label>
                Password
                <input type="password" name="password" required>
            </label>
            <button class="button button-primary" type="submit">Login</button>
        </form>

        <p class="small">Belum punya akun? <a href="<?= url('register') ?>">Daftar di sini</a>.</p>
    </div>
</section>
