<?php

class ServiceModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM services ORDER BY created_at DESC, id DESC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM services WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $service = $stmt->fetch();
        return $service ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO services (name, description, price, duration, category, image) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([$data['name'], $data['description'], $data['price'], $data['duration'], $data['category'], $data['image'] ?? null]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE services SET name = ?, description = ?, price = ?, duration = ?, category = ?, image = COALESCE(?, image) WHERE id = ?');
        return $stmt->execute([$data['name'], $data['description'], $data['price'], $data['duration'], $data['category'], $data['image'] ?? null, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM services WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function count(): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM services');
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
