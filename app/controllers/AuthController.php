<?php

class AuthController
{
    private UserModel $users;

    public function __construct(PDO $pdo)
    {
        $this->users = new UserModel($pdo);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = $this->users->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                flash('error', 'Email atau password salah.');
                redirect('index.php?page=login');
            }

            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            flash('success', 'Selamat datang, ' . $user['name'] . '!');
            redirect($user['role'] === 'admin' ? 'index.php?page=admin&section=dashboard' : 'index.php?page=home');
        }

        render('auth/login', ['title' => 'Login']);
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['password_confirm'] ?? '';
            $phone = trim($_POST['phone'] ?? '');

            if (!$name || !$email || !$password || !$confirm) {
                flash('error', 'Semua field wajib diisi.');
                redirect('index.php?page=register');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Format email tidak valid.');
                redirect('index.php?page=register');
            }
            if ($this->users->findByEmail($email)) {
                flash('error', 'Email sudah terdaftar.');
                redirect('index.php?page=register');
            }
            if (strlen($password) < 8 || $password !== $confirm) {
                flash('error', 'Password minimal 8 karakter dan konfirmasi harus cocok.');
                redirect('index.php?page=register');
            }

            $this->users->create(compact('name', 'email', 'password', 'phone'));
            flash('success', 'Registrasi berhasil. Silakan login.');
            redirect('index.php?page=login');
        }

        render('auth/register', ['title' => 'Register']);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        session_start();
        flash('success', 'Kamu berhasil logout.');
        redirect('index.php?page=login');
    }
}
