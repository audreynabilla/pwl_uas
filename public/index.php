<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/ServiceModel.php';
require_once __DIR__ . '/../app/models/BookingModel.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function baseUrl(string $path = ''): string
{
    return '/pwl_uas/public/' . ltrim($path, '/');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash_' . $type] = $message;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function verifyCsrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            flash('error', 'Token keamanan tidak valid. Silakan coba lagi.');
            redirect($_SERVER['HTTP_REFERER'] ?? 'index.php?page=home');
        }
    }
}

function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        flash('error', 'Silakan login terlebih dahulu.');
        redirect('index.php?page=login');
    }
}

function requireAdmin(): void
{
    if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        flash('error', 'Akses admin diperlukan.');
        redirect('index.php?page=login');
    }
}

function rupiah($amount): string
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

function statusBadge(string $status): string
{
    $classes = [
        'pending' => 'status-pending',
        'confirmed' => 'status-confirmed',
        'processing' => 'status-processing',
        'completed' => 'status-completed',
        'cancelled' => 'status-cancelled',
    ];
    $class = $classes[$status] ?? 'status-pending';
    return '<span class="status-badge ' . $class . '">' . e(ucfirst($status)) . '</span>';
}

function uploadImage(string $field, string $folder, bool $required): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            flash('error', 'File gambar wajib diunggah.');
            redirect($_SERVER['HTTP_REFERER'] ?? 'index.php?page=home');
        }
        return null;
    }
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK || $_FILES[$field]['size'] > 2 * 1024 * 1024) {
        flash('error', 'Upload gagal atau ukuran gambar melebihi 2MB.');
        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php?page=home');
    }

    $tmp = $_FILES[$field]['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp);
    finfo_close($finfo);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    if (!isset($allowed[$mime])) {
        flash('error', 'Format gambar harus JPG, JPEG, atau PNG.');
        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php?page=home');
    }

    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES[$field]['name']));
    $fileName = uniqid('', true) . '_' . $safeName;
    $destinationDir = __DIR__ . '/uploads/' . $folder;
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0777, true);
    }
    if (!move_uploaded_file($tmp, $destinationDir . '/' . $fileName)) {
        flash('error', 'Gagal menyimpan gambar.');
        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php?page=home');
    }
    return $fileName;
}

function deleteUploadedFile(string $folder, string $filename): void
{
    if ($filename === '') {
        return;
    }
    $path = __DIR__ . '/uploads/' . $folder . '/' . basename($filename);
    if (is_file($path)) {
        unlink($path);
    }
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../app/views/' . $view . '.php';
}

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';
$section = $_GET['section'] ?? 'dashboard';

$auth = new AuthController($pdo);
$user = new UserController($pdo);
$admin = new AdminController($pdo);

switch ($page) {
    case 'login':
        $auth->login();
        break;
    case 'register':
        $auth->register();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'detail':
        $user->detail();
        break;
    case 'booking':
        $action === 'store' ? $user->bookingStore() : $user->bookingForm();
        break;
    case 'riwayat':
        $action === 'cancel' ? $user->cancelBooking() : $user->riwayat();
        break;
    case 'editBooking':
        $action === 'update' ? $user->editBookingUpdate() : $user->editBookingForm();
        break;
    case 'profil':
        $action === 'update' ? $user->updateProfil() : $user->profil();
        break;
    case 'admin':
        if ($section === 'services') {
            if ($action === 'create') {
                $admin->createService();
            } elseif ($action === 'edit') {
                $admin->editService();
            } elseif ($action === 'delete') {
                $admin->deleteService();
            } else {
                $admin->services();
            }
        } elseif ($section === 'bookings') {
            if ($action === 'create') {
                $admin->createBooking();
            } elseif ($action === 'edit') {
                $admin->editBooking();
            } elseif ($action === 'delete') {
                $admin->deleteBooking();
            } else {
                $admin->bookings();
            }
        } else {
            $admin->dashboard();
        }
        break;
    case 'home':
    default:
        $user->home();
}
