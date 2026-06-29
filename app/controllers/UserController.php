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
        $required = ['pet_name', 'pet_type', 'service_id', 'booking_date', 'booking_time'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                flash('error', 'Semua field wajib diisi.');
                redirect('index.php?page=booking');
            }
        }
        if ($_POST['booking_time'] < '08:00' || $_POST['booking_time'] > '17:00' || $_POST['booking_date'] < date('Y-m-d')) {
            flash('error', 'Pilih tanggal hari ini atau setelahnya, jam 08:00 sampai 17:00.');
            redirect('index.php?page=booking');
        }

        $petImage = uploadImage('pet_image', 'pets', true);
        $this->bookings->create([
            'user_id' => $_SESSION['user_id'],
            'service_id' => (int) $_POST['service_id'],
            'pet_name' => trim($_POST['pet_name']),
            'pet_type' => $_POST['pet_type'],
            'pet_image' => $petImage,
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
        $this->bookings->updateStatus((int) ($_POST['id'] ?? 0), 'cancelled', (int) $_SESSION['user_id']);
        flash('success', 'Booking berhasil dibatalkan.');
        redirect('index.php?page=riwayat');
    }

    public function editBookingForm(): void
    {
        requireLogin();
        $id = (int) ($_GET['id'] ?? 0);
        $booking = $this->bookings->findForUser($id, (int) $_SESSION['user_id']);
        if (!$booking) {
            flash('error', 'Booking tidak ditemukan.');
            redirect('index.php?page=riwayat');
        }
        if ($booking['status'] !== 'pending') {
            flash('error', 'Booking hanya dapat diedit saat status pending.');
            redirect('index.php?page=riwayat');
        }
        render('user/edit_booking', [
            'title' => 'Edit Booking',
            'booking' => $booking,
            'services' => $this->services->all(),
        ]);
    }

    public function editBookingUpdate(): void
    {
        requireLogin();
        verifyCsrf();
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        $booking = $this->bookings->findForUser($id, (int) $_SESSION['user_id']);
        if (!$booking || $booking['status'] !== 'pending') {
            flash('error', 'Booking tidak dapat diperbarui.');
            redirect('index.php?page=riwayat');
        }

        $required = ['pet_name', 'pet_type', 'service_id', 'booking_date', 'booking_time'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                flash('error', 'Semua field wajib diisi.');
                redirect('index.php?page=editBooking&id=' . $id);
            }
        }
        if ($_POST['booking_time'] < '08:00' || $_POST['booking_time'] > '17:00' || $_POST['booking_date'] < date('Y-m-d')) {
            flash('error', 'Pilih tanggal hari ini atau setelahnya, jam 08:00 sampai 17:00.');
            redirect('index.php?page=editBooking&id=' . $id);
        }

        $petImage = uploadImage('pet_image', 'pets', false);
        $paymentProof = uploadImage('payment_proof', 'payments', false);

        if ($petImage && $booking['pet_image']) {
            deleteUploadedFile('pets', $booking['pet_image']);
        }
        if ($paymentProof && !empty($booking['payment_proof'])) {
            deleteUploadedFile('payments', $booking['payment_proof']);
        }

        $updated = $this->bookings->updateBooking($id, (int) $_SESSION['user_id'], [
            'service_id' => (int) $_POST['service_id'],
            'pet_name' => trim($_POST['pet_name']),
            'pet_type' => $_POST['pet_type'],
            'pet_image' => $petImage,
            'payment_proof' => $paymentProof,
            'booking_date' => $_POST['booking_date'],
            'booking_time' => $_POST['booking_time'],
            'notes' => trim($_POST['notes'] ?? ''),
        ]);

        if (!$updated) {
            flash('error', 'Booking tidak dapat diperbarui. Pastikan status masih pending.');
            redirect('index.php?page=riwayat');
        }

        flash('success', 'Booking berhasil diperbarui.');
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
