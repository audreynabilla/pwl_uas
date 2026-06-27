<?php

class AdminController
{
    private UserModel $users;
    private ServiceModel $services;
    private BookingModel $bookings;

    public function __construct(PDO $pdo)
    {
        $this->users = new UserModel($pdo);
        $this->services = new ServiceModel($pdo);
        $this->bookings = new BookingModel($pdo);
    }

    public function dashboard(): void
    {
        requireAdmin();
        render('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'todayBookings' => $this->bookings->countToday(),
            'totalUsers' => $this->users->countUsers(),
            'totalServices' => $this->services->count(),
            'latestBookings' => $this->bookings->latest(5),
        ]);
    }

    public function services(): void
    {
        requireAdmin();
        render('admin/services/index', ['title' => 'Kelola Layanan', 'services' => $this->services->all()]);
    }

    public function createService(): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $data = $this->servicePayload();
            $data['image'] = uploadImage('image', 'services', true);
            $this->services->create($data);
            flash('success', 'Layanan berhasil ditambahkan.');
            redirect('index.php?page=admin&section=services');
        }
        render('admin/services/create', ['title' => 'Tambah Layanan']);
    }

    public function editService(): void
    {
        requireAdmin();
        $service = $this->services->find((int) ($_GET['id'] ?? 0));
        if (!$service) {
            flash('error', 'Layanan tidak ditemukan.');
            redirect('index.php?page=admin&section=services');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $data = $this->servicePayload();
            $data['image'] = uploadImage('image', 'services', false);
            $this->services->update((int) $service['id'], $data);
            flash('success', 'Layanan berhasil diperbarui.');
            redirect('index.php?page=admin&section=services');
        }
        render('admin/services/edit', ['title' => 'Edit Layanan', 'service' => $service]);
    }

    public function deleteService(): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            flash('error', 'Aksi hapus harus melalui form yang aman.');
            redirect('index.php?page=admin&section=services');
        }
        verifyCsrf();
        $this->services->delete((int) ($_POST['id'] ?? $_GET['id'] ?? 0));
        flash('success', 'Layanan berhasil dihapus.');
        redirect('index.php?page=admin&section=services');
    }

    public function bookings(): void
    {
        requireAdmin();
        $status = $_GET['status'] ?? null;
        $allowed = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];
        render('admin/bookings/index', [
            'title' => 'Kelola Booking',
            'bookings' => $this->bookings->all(in_array($status, $allowed, true) ? $status : null),
            'status' => $status,
        ]);
    }

    public function createBooking(): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $data = $this->bookingPayload(true);
            $data['pet_image'] = uploadImage('pet_image', 'pets', true);
            $this->bookings->create($data);
            flash('success', 'Booking manual berhasil ditambahkan.');
            redirect('index.php?page=admin&section=bookings');
        }
        render('admin/bookings/create', ['title' => 'Tambah Booking', 'users' => $this->users->allUsers(), 'services' => $this->services->all()]);
    }

    public function editBooking(): void
    {
        requireAdmin();
        $booking = $this->bookings->find((int) ($_GET['id'] ?? 0));
        if (!$booking) {
            flash('error', 'Booking tidak ditemukan.');
            redirect('index.php?page=admin&section=bookings');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $data = $this->bookingPayload(true);
            $data['pet_image'] = uploadImage('pet_image', 'pets', false);
            $this->bookings->update((int) $booking['id'], $data);
            flash('success', 'Booking berhasil diperbarui.');
            redirect('index.php?page=admin&section=bookings');
        }
        render('admin/bookings/edit', ['title' => 'Edit Booking', 'booking' => $booking, 'users' => $this->users->allUsers(), 'services' => $this->services->all()]);
    }

    public function deleteBooking(): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            flash('error', 'Aksi hapus harus melalui form yang aman.');
            redirect('index.php?page=admin&section=bookings');
        }
        verifyCsrf();
        $this->bookings->delete((int) ($_POST['id'] ?? $_GET['id'] ?? 0));
        flash('success', 'Booking berhasil dihapus.');
        redirect('index.php?page=admin&section=bookings');
    }

    private function servicePayload(): array
    {
        foreach (['name', 'description', 'price', 'duration', 'category'] as $field) {
            if (empty($_POST[$field])) {
                flash('error', 'Semua field layanan wajib diisi.');
                redirect($_SERVER['HTTP_REFERER'] ?? 'index.php?page=admin&section=services');
            }
        }
        return [
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'price' => (float) $_POST['price'],
            'duration' => (int) $_POST['duration'],
            'category' => $_POST['category'],
        ];
    }

    private function bookingPayload(bool $withStatus): array
    {
        foreach (['user_id', 'service_id', 'pet_name', 'pet_type', 'booking_date', 'booking_time'] as $field) {
            if (empty($_POST[$field])) {
                flash('error', 'Semua field booking wajib diisi.');
                redirect($_SERVER['HTTP_REFERER'] ?? 'index.php?page=admin&section=bookings');
            }
        }
        return [
            'user_id' => (int) $_POST['user_id'],
            'service_id' => (int) $_POST['service_id'],
            'pet_name' => trim($_POST['pet_name']),
            'pet_type' => $_POST['pet_type'],
            'booking_date' => $_POST['booking_date'],
            'booking_time' => $_POST['booking_time'],
            'notes' => trim($_POST['notes'] ?? ''),
            'status' => $withStatus ? ($_POST['status'] ?? 'pending') : 'pending',
        ];
    }
}
