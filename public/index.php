<?php
session_start();

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);

require ROOT_PATH . '/config/database.php';
require APP_PATH . '/models/UserModel.php';
require APP_PATH . '/models/PelangganModel.php';
require APP_PATH . '/models/ReservasiModel.php';
require APP_PATH . '/controllers/BaseController.php';
require APP_PATH . '/controllers/AuthController.php';
require APP_PATH . '/controllers/ReservasiController.php';

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function baseUrl(): string
{
    $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    return rtrim($scriptName, '/');
}

function url(string $action, array $params = []): string
{
    $params = array_merge(['action' => $action], $params);
    return baseUrl() . '/index.php?' . http_build_query($params);
}

function asset(string $path): string
{
    return baseUrl() . '/' . ltrim($path, '/');
}

function uploaded(string $fileName): string
{
    return asset('uploads/' . rawurlencode(basename($fileName)));
}

function selected(string $current, string $expected): string
{
    return $current === $expected ? 'selected' : '';
}

function formatDate(?string $date): string
{
    if (!$date) {
        return '-';
    }

    $time = strtotime($date);
    return $time ? date('d M Y', $time) : $date;
}

$action = $_GET['action'] ?? 'home';
$authController = new AuthController($pdo);
$reservasiController = new ReservasiController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'login':
            $authController->login();
            break;
        case 'register':
            $authController->register();
            break;
        case 'reservasi_simpan':
            $reservasiController->store();
            break;
        case 'reservasi_update':
            $reservasiController->update();
            break;
        default:
            header('Location: ' . url('reservasi'));
            break;
    }
    exit;
}

switch ($action) {
    case 'login':
        $authController->loginForm();
        break;
    case 'register':
        $authController->registerForm();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'reservasi':
        $reservasiController->index();
        break;
    case 'reservasi_tambah':
        $reservasiController->create();
        break;
    case 'reservasi_detail':
        $reservasiController->detail();
        break;
    case 'reservasi_edit':
        $reservasiController->edit();
        break;
    case 'reservasi_hapus':
        $reservasiController->delete();
        break;
    default:
        if (!empty($_SESSION['user'])) {
            header('Location: ' . url('reservasi'));
        } else {
            header('Location: ' . url('login'));
        }
        break;
}
