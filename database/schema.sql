CREATE DATABASE IF NOT EXISTS badminton_booking;
USE badminton_booking;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS courts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lapangan VARCHAR(150) NOT NULL,
    tipe VARCHAR(100) NOT NULL,
    harga_per_jam DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('Aktif', 'Maintenance') NOT NULL DEFAULT 'Aktif',
    lokasi VARCHAR(120) NOT NULL,
    deskripsi VARCHAR(255) NULL,
    gambar VARCHAR(255) NULL,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lokasi VARCHAR(100) NOT NULL UNIQUE,
    is_deleted TINYINT(1) NOT NULL DEFAULT 0,
    deleted_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    court_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    total_harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('Menunggu', 'Disetujui', 'Dibatalkan', 'Selesai') NOT NULL DEFAULT 'Menunggu',
    catatan VARCHAR(255) NULL,
    whatsapp_number VARCHAR(30) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_booking_schedule (court_id, tanggal, jam_mulai, jam_selesai),
    INDEX idx_booking_user (user_id),
    CONSTRAINT fk_bookings_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_bookings_court
        FOREIGN KEY (court_id) REFERENCES courts(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value VARCHAR(255) NOT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT NOT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    description VARCHAR(255) NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created_at (created_at),
    CONSTRAINT fk_audit_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('app_name', 'Badminton Court Booking'),
('institution_name', 'Badminton Center'),
('admin_contact', '-'),
('whatsapp_enabled', '0'),
('whatsapp_api_url', 'https://api.fonnte.com/send'),
('whatsapp_api_token', ''),
('whatsapp_admin_number', '');

INSERT IGNORE INTO locations (nama_lokasi) VALUES
('Gedung Utama'),
('Gedung Barat'),
('Area Outdoor');

INSERT IGNORE INTO courts (id, nama_lapangan, tipe, harga_per_jam, status, lokasi, deskripsi, gambar) VALUES
(1, 'Lapangan A', 'Indoor Vinyl', 75000, 'Aktif', 'Gedung Utama', 'Lapangan indoor dengan lampu LED dan karpet vinyl.', NULL),
(2, 'Lapangan B', 'Indoor Standar', 60000, 'Aktif', 'Gedung Utama', 'Lapangan standar untuk latihan dan sparring.', NULL),
(3, 'Lapangan VIP', 'Indoor VIP', 120000, 'Aktif', 'Gedung Barat', 'Lapangan premium dengan area tunggu khusus.', NULL),
(4, 'Lapangan C', 'Outdoor', 45000, 'Maintenance', 'Area Outdoor', 'Lapangan outdoor sedang perawatan net.', NULL);
