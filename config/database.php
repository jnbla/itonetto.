<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "badminton_booking";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ensure database tables exist
$conn->query(
    "CREATE TABLE IF NOT EXISTS locations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_lokasi VARCHAR(100) NOT NULL UNIQUE,
        is_deleted TINYINT(1) NOT NULL DEFAULT 0,
        deleted_at DATETIME NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    )"
);

// Ensure audit_logs table exists
$conn->query(
    "CREATE TABLE IF NOT EXISTS audit_logs (
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
    )"
);

// Insert default locations if empty
$checkLocations = $conn->query("SELECT COUNT(*) as count FROM locations WHERE is_deleted = 0");
$result = $checkLocations->fetch_assoc();
if ($result['count'] == 0) {
    $conn->query("INSERT IGNORE INTO locations (nama_lokasi) VALUES ('Gedung Utama'), ('Gedung Barat'), ('Area Outdoor')");
}
?>
