<?php

class BookingModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO bookings (user_id, service_id, pet_name, pet_type, pet_image, booking_date, booking_time, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['user_id'],
            $data['service_id'],
            $data['pet_name'],
            $data['pet_type'],
            $data['pet_image'] ?? null,
            $data['booking_date'],
            $data['booking_time'],
            $data['notes'] ?? null,
            $data['status'] ?? 'pending',
        ]);
    }

    public function userBookings(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT b.*, s.name AS service_name, s.price, s.duration FROM bookings b JOIN services s ON s.id = b.service_id WHERE b.user_id = ? ORDER BY b.booking_date DESC, b.booking_time DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function all(?string $status = null): array
    {
        if ($status) {
            $stmt = $this->pdo->prepare('SELECT b.*, u.name AS user_name, s.name AS service_name FROM bookings b JOIN users u ON u.id = b.user_id JOIN services s ON s.id = b.service_id WHERE b.status = ? ORDER BY b.created_at DESC');
            $stmt->execute([$status]);
            return $stmt->fetchAll();
        }
        $stmt = $this->pdo->prepare('SELECT b.*, u.name AS user_name, s.name AS service_name FROM bookings b JOIN users u ON u.id = b.user_id JOIN services s ON s.id = b.service_id ORDER BY b.created_at DESC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function latest(int $limit = 5): array
    {
        $limit = max(1, min(20, $limit));
        $stmt = $this->pdo->prepare('SELECT b.*, u.name AS user_name, s.name AS service_name FROM bookings b JOIN users u ON u.id = b.user_id JOIN services s ON s.id = b.service_id ORDER BY b.created_at DESC LIMIT ?');
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT b.*, u.name AS user_name, s.name AS service_name FROM bookings b JOIN users u ON u.id = b.user_id JOIN services s ON s.id = b.service_id WHERE b.id = ? LIMIT 1');
        $stmt->execute([$id]);
        $booking = $stmt->fetch();
        return $booking ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE bookings SET user_id = ?, service_id = ?, pet_name = ?, pet_type = ?, pet_image = COALESCE(?, pet_image), booking_date = ?, booking_time = ?, notes = ?, status = ? WHERE id = ?');
        return $stmt->execute([
            $data['user_id'],
            $data['service_id'],
            $data['pet_name'],
            $data['pet_type'],
            $data['pet_image'] ?? null,
            $data['booking_date'],
            $data['booking_time'],
            $data['notes'] ?? null,
            $data['status'],
            $id,
        ]);
    }

    public function updateStatus(int $id, string $status, ?int $userId = null): bool
    {
        if ($userId) {
            $stmt = $this->pdo->prepare('UPDATE bookings SET status = ? WHERE id = ? AND user_id = ? AND status IN (?, ?)');
            return $stmt->execute([$status, $id, $userId, 'pending', 'confirmed']);
        }
        $stmt = $this->pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM bookings WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function countToday(): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE()');
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
