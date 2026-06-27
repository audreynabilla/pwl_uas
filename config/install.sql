CREATE DATABASE IF NOT EXISTS pwl_uas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pwl_uas_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  address TEXT,
  role ENUM('user','admin') DEFAULT 'user',
  profile_picture VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  duration INT NOT NULL COMMENT 'dalam menit',
  category VARCHAR(50) NOT NULL COMMENT 'Kucing atau Anjing',
  image VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  pet_name VARCHAR(100) NOT NULL,
  pet_type VARCHAR(50) NOT NULL,
  pet_image VARCHAR(255) DEFAULT NULL,
  booking_date DATE NOT NULL,
  booking_time TIME NOT NULL,
  notes TEXT,
  status ENUM('pending','confirmed','processing','completed','cancelled') DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

INSERT IGNORE INTO users (id, name, email, password, phone, address, role) VALUES
(1, 'Admin PawSpa', 'admin@pawspa.com', '$2y$10$kXMD9zUBjAUMXRtTQmAvsu9bXkPxBC.IAUQ5qL1.XmZmaqjP9ZVke', '081234567890', 'PawSpa HQ', 'admin'),
(2, 'User Demo', 'user@demo.com', '$2y$10$ViLLRjjTesM.Feyo16p7Puz5UW9mT8/DqM1kxo9187stJnymvIXzi', '081111222333', 'Jakarta', 'user');

INSERT IGNORE INTO services (id, name, description, price, duration, category, image) VALUES
(1, 'Kitten Fresh Bath', 'Mandi lembut untuk kucing dengan sampo khusus, blow dry hangat, dan pembersihan telinga.', 85000, 60, 'Kucing', 'kucing 1.jpg'),
(2, 'Cat Full Grooming', 'Paket lengkap kucing: mandi, sisir bulu rontok, potong kuku, telinga, dan parfum aman pet.', 145000, 90, 'Kucing', 'kucing 2.jpg'),
(3, 'Anti Kutu Kucing', 'Perawatan anti kutu dan jamur ringan dengan produk grooming yang aman untuk bulu sensitif.', 165000, 100, 'Kucing', 'kucing 3.jpg'),
(4, 'Puppy Bubble Bath', 'Mandi ceria untuk anjing kecil dengan sampo mild, pengeringan, dan finishing wangi lembut.', 95000, 60, 'Anjing', 'anjing 1.jpg'),
(5, 'Dog Complete Spa', 'Grooming anjing lengkap: mandi, trim rapi, potong kuku, telinga, paw pad trim, dan parfum.', 185000, 120, 'Anjing', 'anjing 2.jpg'),
(6, 'Premium Fluffy Treatment', 'Perawatan premium untuk menjaga bulu anjing tetap lembut, bersih, dan mudah disisir.', 225000, 140, 'Anjing', 'anjing 3.jpg');
