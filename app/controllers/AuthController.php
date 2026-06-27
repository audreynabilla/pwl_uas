<?php

class AuthController extends BaseController
{
    private UserModel $users;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->users = new UserModel($pdo);
    }

    public function loginForm(): void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('reservasi');
        }

        $this->view('auth/login', ['title' => 'Login']);
    }

    public function login(): void
    {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];

        if ($login === '') {
            $errors[] = 'Username atau email wajib diisi.';
        }

        if ($password === '') {
            $errors[] = 'Password wajib diisi.';
        }

        if (!$errors) {
            $user = $this->users->findByLogin($login);
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => (int) $user['id'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ];
                $_SESSION['flash_success'] = 'Login berhasil. Selamat datang!';
                $this->redirect('reservasi');
            }

            $errors[] = 'Username/email atau password tidak sesuai.';
        }

        $this->view('auth/login', [
            'title' => 'Login',
            'errors' => $errors,
            'old' => ['login' => $login],
        ]);
    }

    public function registerForm(): void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('reservasi');
        }

        $this->view('auth/register', ['title' => 'Daftar Akun']);
    }

    public function register(): void
    {
        $data = [
            'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? '',
            'no_telepon' => trim($_POST['no_telepon'] ?? ''),
        ];
        $errors = [];

        if ($data['nama_lengkap'] === '') {
            $errors[] = 'Nama lengkap wajib diisi.';
        }
        if ($data['username'] === '' || !preg_match('/^[A-Za-z0-9_]{4,50}$/', $data['username'])) {
            $errors[] = 'Username minimal 4 karakter dan hanya boleh huruf, angka, atau underscore.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }
        if (strlen($data['password']) < 6) {
            $errors[] = 'Password minimal 6 karakter.';
        }
        if ($data['password'] !== $data['password_confirmation']) {
            $errors[] = 'Konfirmasi password tidak sama.';
        }
        if (!$errors && $this->users->usernameOrEmailExists($data['username'], $data['email'])) {
            $errors[] = 'Username atau email sudah digunakan.';
        }

        if ($errors) {
            unset($data['password'], $data['password_confirmation']);
            $this->view('auth/register', [
                'title' => 'Daftar Akun',
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $this->users->create($data);
        $_SESSION['flash_success'] = 'Registrasi berhasil. Silakan login.';
        $this->redirect('login');
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        session_start();
        $_SESSION['flash_success'] = 'Logout berhasil.';
        $this->redirect('login');
    }
}
