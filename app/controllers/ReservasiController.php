<?php

class ReservasiController extends BaseController
{
    private ReservasiModel $reservations;
    private array $animalTypes = ['Kucing', 'Anjing', 'Kelinci', 'Hamster', 'Burung'];
    private array $serviceTypes = ['Grooming Basic', 'Grooming Lengkap', 'Potong Kuku', 'Mandi + Blow'];
    private array $statuses = ['pending', 'selesai', 'batal'];

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->reservations = new ReservasiModel($pdo);
    }

    public function index(): void
    {
        $this->requireLogin();
        $filters = [
            'status' => in_array($_GET['status'] ?? '', $this->statuses, true) ? $_GET['status'] : '',
            'tanggal' => $this->isValidDate($_GET['tanggal'] ?? '') ? $_GET['tanggal'] : '',
        ];

        $this->view('reservasi/index', [
            'title' => 'Daftar Reservasi',
            'reservations' => $this->reservations->getAll($filters, $_SESSION['user']),
            'stats' => $this->reservations->stats($_SESSION['user']),
            'filters' => $filters,
            'statuses' => $this->statuses,
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();
        $this->view('reservasi/create', [
            'title' => 'Tambah Reservasi',
            'animalTypes' => $this->animalTypes,
            'serviceTypes' => $this->serviceTypes,
            'statuses' => $this->statuses,
            'old' => ['status' => 'pending'],
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();
        [$data, $errors] = $this->validatedData();
        $data['user_id'] = $_SESSION['user']['id'];
        $data['gambar_hewan'] = null;

        if (!$errors) {
            [$fileName, $fileError] = $this->uploadImage();
            if ($fileError) {
                $errors[] = $fileError;
            } else {
                $data['gambar_hewan'] = $fileName;
            }
        }

        if ($errors) {
            $this->view('reservasi/create', [
                'title' => 'Tambah Reservasi',
                'errors' => $errors,
                'old' => $data,
                'animalTypes' => $this->animalTypes,
                'serviceTypes' => $this->serviceTypes,
                'statuses' => $this->statuses,
            ]);
            return;
        }

        $this->reservations->create($data);
        $_SESSION['flash_success'] = 'Reservasi berhasil ditambahkan.';
        $this->redirect('reservasi');
    }

    public function detail(): void
    {
        $this->requireLogin();
        $reservation = $this->findReservation();

        $this->view('reservasi/detail', [
            'title' => 'Detail Reservasi',
            'reservation' => $reservation,
        ]);
    }

    public function edit(): void
    {
        $this->requireLogin();
        $reservation = $this->findReservation();

        $this->view('reservasi/edit', [
            'title' => 'Edit Reservasi',
            'reservation' => $reservation,
            'old' => $reservation,
            'animalTypes' => $this->animalTypes,
            'serviceTypes' => $this->serviceTypes,
            'statuses' => $this->statuses,
        ]);
    }

    public function update(): void
    {
        $this->requireLogin();
        $reservation = $this->findReservation();
        [$data, $errors] = $this->validatedData();
        $data['user_id'] = $reservation['user_id'];
        $data['gambar_hewan'] = $reservation['gambar_hewan'];

        if (!$errors) {
            [$fileName, $fileError] = $this->uploadImage();
            if ($fileError) {
                $errors[] = $fileError;
            } elseif ($fileName) {
                $this->deleteImage($reservation['gambar_hewan']);
                $data['gambar_hewan'] = $fileName;
            }
        }

        if ($errors) {
            $this->view('reservasi/edit', [
                'title' => 'Edit Reservasi',
                'errors' => $errors,
                'reservation' => $reservation,
                'old' => $data,
                'animalTypes' => $this->animalTypes,
                'serviceTypes' => $this->serviceTypes,
                'statuses' => $this->statuses,
            ]);
            return;
        }

        $this->reservations->update((int) $reservation['id'], $data, $_SESSION['user']);
        $_SESSION['flash_success'] = 'Reservasi berhasil diperbarui.';
        $this->redirect('reservasi_detail', ['id' => $reservation['id']]);
    }

    public function delete(): void
    {
        $this->requireLogin();
        $reservation = $this->findReservation();
        $this->reservations->delete((int) $reservation['id'], $_SESSION['user']);
        $this->deleteImage($reservation['gambar_hewan']);

        $_SESSION['flash_success'] = 'Reservasi berhasil dihapus.';
        $this->redirect('reservasi');
    }

    private function validatedData(): array
    {
        $data = [
            'nama_hewan' => trim($_POST['nama_hewan'] ?? ''),
            'jenis_hewan' => $_POST['jenis_hewan'] ?? '',
            'jenis_layanan' => $_POST['jenis_layanan'] ?? '',
            'tanggal_reservasi' => $_POST['tanggal_reservasi'] ?? '',
            'jam_reservasi' => $_POST['jam_reservasi'] ?? '',
            'catatan_tambahan' => trim($_POST['catatan_tambahan'] ?? ''),
            'status' => $_POST['status'] ?? 'pending',
        ];
        $errors = [];

        if ($data['nama_hewan'] === '') {
            $errors[] = 'Nama hewan wajib diisi.';
        }
        if (!in_array($data['jenis_hewan'], $this->animalTypes, true)) {
            $errors[] = 'Jenis hewan wajib dipilih dari opsi yang tersedia.';
        }
        if (!in_array($data['jenis_layanan'], $this->serviceTypes, true)) {
            $errors[] = 'Jenis layanan wajib dipilih dari opsi yang tersedia.';
        }
        if (!$this->isValidDate($data['tanggal_reservasi'])) {
            $errors[] = 'Tanggal reservasi wajib diisi dengan format tanggal yang valid.';
        }
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $data['jam_reservasi'])) {
            $errors[] = 'Jam reservasi wajib diisi dengan format jam yang valid.';
        }
        if (!in_array($data['status'], $this->statuses, true)) {
            $errors[] = 'Status reservasi tidak valid.';
        }

        return [$data, $errors];
    }

    private function isValidDate(string $date): bool
    {
        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        return $parsed && $parsed->format('Y-m-d') === $date;
    }

    private function uploadImage(): array
    {
        if (empty($_FILES['gambar_hewan']) || $_FILES['gambar_hewan']['error'] === UPLOAD_ERR_NO_FILE) {
            return [null, null];
        }

        $file = $_FILES['gambar_hewan'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [null, 'Upload gambar gagal. Silakan coba lagi.'];
        }
        if ($file['size'] > 2 * 1024 * 1024) {
            return [null, 'Ukuran gambar maksimal 2MB.'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];

        if (!isset($allowed[$mime])) {
            return [null, 'Format gambar harus JPG, JPEG, atau PNG.'];
        }

        $uploadDir = PUBLIC_PATH . '/uploads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid('pet_', true) . '.' . $allowed[$mime];
        $target = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            return [null, 'Gambar tidak dapat disimpan.'];
        }

        return [$fileName, null];
    }

    private function findReservation(): array
    {
        $id = (int) ($_GET['id'] ?? 0);
        $reservation = $this->reservations->find($id, $_SESSION['user']);

        if (!$reservation) {
            $_SESSION['flash_error'] = 'Reservasi tidak ditemukan.';
            $this->redirect('reservasi');
        }

        return $reservation;
    }

    private function deleteImage(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $path = PUBLIC_PATH . '/uploads/' . basename($fileName);
        if (is_file($path)) {
            unlink($path);
        }
    }
}
