<?php

class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['phone'] ?? null,
            $data['role'] ?? 'user',
        ]);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE users SET name = ?, phone = ?, address = ?, profile_picture = COALESCE(?, profile_picture) WHERE id = ?');
        return $stmt->execute([
            $data['name'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['profile_picture'] ?? null,
            $id,
        ]);
    }

    public function allUsers(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = ? ORDER BY name ASC");
        $stmt->execute(['user']);
        return $stmt->fetchAll();
    }

    public function countUsers(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
        $stmt->execute(['user']);
        return (int) $stmt->fetchColumn();
    }
}
