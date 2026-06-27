CREATE DATABASE IF NOT EXISTS pwl_uas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pwl_uas;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_telepon VARCHAR(15) NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pelanggan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_pelanggan VARCHAR(100) NOT NULL,
    alamat TEXT NULL,
    no_telepon VARCHAR(15) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pelanggan_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reservasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_hewan VARCHAR(100) NOT NULL,
    jenis_hewan VARCHAR(50) NOT NULL,
    jenis_layanan VARCHAR(50) NOT NULL,
    tanggal_reservasi DATE NOT NULL,
    jam_reservasi TIME NOT NULL,
    catatan_tambahan TEXT NULL,
    status ENUM('pending', 'selesai', 'batal') NOT NULL DEFAULT 'pending',
    gambar_hewan VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservasi_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;
