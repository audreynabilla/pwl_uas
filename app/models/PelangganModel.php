<?php

class PelangganModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createFromUser(int $userId, string $nama, ?string $telepon): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO pelanggan (user_id, nama_pelanggan, no_telepon)
             VALUES (:user_id, :nama_pelanggan, :no_telepon)'
        );

        return $stmt->execute([
            ':user_id' => $userId,
            ':nama_pelanggan' => $nama,
            ':no_telepon' => $telepon,
        ]);
    }
}
