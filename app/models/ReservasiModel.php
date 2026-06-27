<?php

class ReservasiModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(array $filters, array $user): array
    {
        $where = [];
        $params = [];

        if (($user['role'] ?? 'user') !== 'admin') {
            $where[] = 'r.user_id = :user_id';
            $params[':user_id'] = $user['id'];
        }

        if (!empty($filters['status'])) {
            $where[] = 'r.status = :status';
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['tanggal'])) {
            $where[] = 'r.tanggal_reservasi = :tanggal';
            $params[':tanggal'] = $filters['tanggal'];
        }

        $sql = 'SELECT r.*, u.nama_lengkap
                FROM reservasi r
                JOIN users u ON u.id = r.user_id';

        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY r.tanggal_reservasi DESC, r.jam_reservasi DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function stats(array $user): array
    {
        $params = [];
        $where = '';

        if (($user['role'] ?? 'user') !== 'admin') {
            $where = 'WHERE user_id = :user_id';
            $params[':user_id'] = $user['id'];
        }

        $stmt = $this->pdo->prepare(
            "SELECT
                COUNT(*) AS total,
                SUM(status = 'pending') AS pending,
                SUM(status = 'selesai') AS selesai,
                SUM(status = 'batal') AS batal
             FROM reservasi $where"
        );
        $stmt->execute($params);
        $stats = $stmt->fetch() ?: [];

        return [
            'total' => (int) ($stats['total'] ?? 0),
            'pending' => (int) ($stats['pending'] ?? 0),
            'selesai' => (int) ($stats['selesai'] ?? 0),
            'batal' => (int) ($stats['batal'] ?? 0),
        ];
    }

    public function find(int $id, array $user): ?array
    {
        $sql = 'SELECT r.*, u.nama_lengkap
                FROM reservasi r
                JOIN users u ON u.id = r.user_id
                WHERE r.id = :id';
        $params = [':id' => $id];

        if (($user['role'] ?? 'user') !== 'admin') {
            $sql .= ' AND r.user_id = :user_id';
            $params[':user_id'] = $user['id'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $reservation = $stmt->fetch();

        return $reservation ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reservasi
                (user_id, nama_hewan, jenis_hewan, jenis_layanan, tanggal_reservasi, jam_reservasi, catatan_tambahan, status, gambar_hewan)
             VALUES
                (:user_id, :nama_hewan, :jenis_hewan, :jenis_layanan, :tanggal_reservasi, :jam_reservasi, :catatan_tambahan, :status, :gambar_hewan)'
        );

        return $stmt->execute($this->bindData($data));
    }

    public function update(int $id, array $data, array $user): bool
    {
        $sql = 'UPDATE reservasi SET
                    nama_hewan = :nama_hewan,
                    jenis_hewan = :jenis_hewan,
                    jenis_layanan = :jenis_layanan,
                    tanggal_reservasi = :tanggal_reservasi,
                    jam_reservasi = :jam_reservasi,
                    catatan_tambahan = :catatan_tambahan,
                    status = :status,
                    gambar_hewan = :gambar_hewan
                WHERE id = :id';
        $params = $this->bindData($data);
        unset($params[':user_id']);
        $params[':id'] = $id;

        if (($user['role'] ?? 'user') !== 'admin') {
            $sql .= ' AND user_id = :user_id';
            $params[':user_id'] = $user['id'];
        }

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    public function delete(int $id, array $user): bool
    {
        $sql = 'DELETE FROM reservasi WHERE id = :id';
        $params = [':id' => $id];

        if (($user['role'] ?? 'user') !== 'admin') {
            $sql .= ' AND user_id = :user_id';
            $params[':user_id'] = $user['id'];
        }

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    private function bindData(array $data): array
    {
        return [
            ':user_id' => $data['user_id'],
            ':nama_hewan' => $data['nama_hewan'],
            ':jenis_hewan' => $data['jenis_hewan'],
            ':jenis_layanan' => $data['jenis_layanan'],
            ':tanggal_reservasi' => $data['tanggal_reservasi'],
            ':jam_reservasi' => $data['jam_reservasi'],
            ':catatan_tambahan' => $data['catatan_tambahan'] ?: null,
            ':status' => $data['status'],
            ':gambar_hewan' => $data['gambar_hewan'] ?: null,
        ];
    }
}
