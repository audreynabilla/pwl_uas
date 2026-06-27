<?php

class BaseController
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require APP_PATH . '/views/' . $view . '.php';
        $content = ob_get_clean();
        require APP_PATH . '/views/layouts/main.php';
    }

    protected function redirect(string $action, array $params = []): void
    {
        header('Location: ' . url($action, $params));
        exit;
    }

    protected function requireLogin(): void
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Silakan login terlebih dahulu.';
            $this->redirect('login');
        }
    }
}
