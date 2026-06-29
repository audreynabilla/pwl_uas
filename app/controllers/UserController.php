<?php

class UserController
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

    public function home(): void
    {
        render('user/index', ['title' => 'Home', 'services' => $this->services->all()]);
    }

    public function detail(): void
    {
        $service = $this->services->find((int) ($_GET['id'] ?? 0));
        if (!$service) {
            http_response_code(404);
            echo 'Layanan tidak ditemukan.';
            return;
        }
        render('user/detail', ['title' => 'Detail Layanan', 'service' => $service]);
    }

    public function bookingForm(): void
    {
        requireLogin();
        render('user/booking', [
            'title' => 'Booking Grooming',
            'services' => $this->services->all(),
            'selectedService' => (int) ($_GET['service_id'] ?? 0),
        ]);
    }

    public function bookingStore(): void
    {
        requireLogin();
        verifyCsrf();
        $required = ['service_type', 'pet_name', 'pet_type', 'service_id', 'booking_date', 'booking_time', 'notes'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                flash('error', 'Semua field wajib diisi.');
                redirect('index.php?page=booking');
            }
        }

        $service = $this->services->find((int) $_POST['service_id']);
        if (!$service) {
            flash('error', 'Layanan tidak ditemukan.');
            redirect('index.php?page=booking');
        }

        $serviceType = $_POST['service_type'];
        $isVeterinary = $service['category'] === 'Veterinary';
        if (($serviceType === 'VETERINARY' && !$isVeterinary) || ($serviceType === 'GROOMING' && $isVeterinary) || !in_array($serviceType, ['GROOMING', 'VETERINARY'], true)) {
            flash('error', 'Tipe layanan tidak sesuai dengan layanan yang dipilih.');
            redirect('index.php?page=booking');
        }

        if ($_POST['booking_time'] < '08:00' || $_POST['booking_time'] > '17:00' || $_POST['booking_date'] < date('Y-m-d')) {
            flash('error', 'Pilih tanggal hari ini atau setelahnya, jam 08:00 sampai 17:00.');
            redirect('index.php?page=booking');
        }

        $paymentProof = uploadImage('payment_proof', 'payments', true);
        $petImage = uploadImage('pet_image', 'pets', false);
        $this->bookings->create([
            'user_id' => $_SESSION['user_id'],
            'service_id' => (int) $_POST['service_id'],
            'pet_name' => trim($_POST['pet_name']),
            'pet_type' => $_POST['pet_type'],
            'pet_image' => $petImage,
            'payment_proof' => $paymentProof,
            'booking_date' => $_POST['booking_date'],
            'booking_time' => $_POST['booking_time'],
            'notes' => trim($_POST['notes'] ?? ''),
            'status' => 'pending',
        ]);
        flash('success', 'Booking berhasil dibuat. Kami akan konfirmasi secepatnya.');
        redirect('index.php?page=riwayat');
    }

    public function riwayat(): void
    {
        requireLogin();
        render('user/riwayat', ['title' => 'Riwayat Booking', 'bookings' => $this->bookings->userBookings((int) $_SESSION['user_id'])]);
    }

    public function cancelBooking(): void
    {
        requireLogin();
        verifyCsrf();
        $bookingId = (int) ($_POST['id'] ?? 0);
        if (!$this->bookings->canUserCancel($bookingId, (int) $_SESSION['user_id'])) {
            flash('error', 'Booking hanya bisa dibatalkan saat pending/confirmed dan maksimal 24 jam setelah dibuat.');
            redirect('index.php?page=riwayat');
        }
        $this->bookings->updateStatus($bookingId, 'cancelled', (int) $_SESSION['user_id']);
        flash('success', 'Booking berhasil dibatalkan.');
        redirect('index.php?page=riwayat');
    }

    public function profil(): void
    {
        requireLogin();
        render('user/profil', ['title' => 'Profil', 'user' => $this->users->findById((int) $_SESSION['user_id'])]);
    }

    public function updateProfil(): void
    {
        requireLogin();
        verifyCsrf();
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (!$name || ($phone && !preg_match('/^[0-9]{1,15}$/', $phone))) {
            flash('error', 'Nama wajib diisi dan nomor HP hanya angka maksimal 15 karakter.');
            redirect('index.php?page=profil');
        }

        $profilePicture = uploadImage('profile_picture', 'profiles', false);
        $this->users->updateProfile((int) $_SESSION['user_id'], compact('name', 'phone', 'address') + ['profile_picture' => $profilePicture]);
        $_SESSION['name'] = $name;
        flash('success', 'Profil berhasil diperbarui.');
        redirect('index.php?page=profil');
    }
}
