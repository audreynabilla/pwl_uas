<?php

class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (nama_lengkap, username, email, password, no_telepon)
             VALUES (:nama_lengkap, :username, :email, :password, :no_telepon)'
        );

        return $stmt->execute([
            ':nama_lengkap' => $data['nama_lengkap'],
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':no_telepon' => $data['no_telepon'] ?: null,
        ]);
    }

    public function findByLogin(string $login): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :login OR email = :login LIMIT 1');
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function usernameOrEmailExists(string $username, string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1');
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
        ]);

        return (bool) $stmt->fetch();
    }
}
