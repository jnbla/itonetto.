<?php
require_once __DIR__ . '/../config/database.php';

$conn->query(
    "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value VARCHAR(255) NOT NULL,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )"
);

$settings = [
    'app_name' => 'Badminton Court Booking',
    'institution_name' => 'Badminton Center',
    'admin_contact' => '-',
    'whatsapp_enabled' => '0',
    'whatsapp_api_url' => 'https://api.fonnte.com/send',
    'whatsapp_api_token' => '',
    'whatsapp_admin_number' => ''
];
$settingStmt = $conn->prepare("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)");
foreach ($settings as $key => $value) {
    $settingStmt->bind_param("ss", $key, $value);
    $settingStmt->execute();
}

$courts = [
    ['Lapangan A', 'Indoor Vinyl', 75000, 'Aktif', 'Gedung Utama', 'Lapangan indoor dengan lampu LED dan karpet vinyl.'],
    ['Lapangan B', 'Indoor Standar', 60000, 'Aktif', 'Gedung Utama', 'Lapangan standar untuk latihan dan sparring.'],
    ['Lapangan VIP', 'Indoor VIP', 120000, 'Aktif', 'Gedung Barat', 'Lapangan premium dengan area tunggu khusus.'],
    ['Lapangan C', 'Outdoor', 45000, 'Maintenance', 'Area Outdoor', 'Lapangan outdoor sedang perawatan net.']
];

$stmt = $conn->prepare(
    "INSERT INTO courts (nama_lapangan, tipe, harga_per_jam, status, lokasi, deskripsi)
     SELECT ?, ?, ?, ?, ?, ?
     WHERE NOT EXISTS (SELECT 1 FROM courts WHERE nama_lapangan = ?)"
);

$inserted = 0;
foreach ($courts as $court) {
    [$name, $type, $price, $status, $location, $description] = $court;
    $stmt->bind_param("ssdssss", $name, $type, $price, $status, $location, $description, $name);
    $stmt->execute();
    $inserted += max(0, $stmt->affected_rows);
}

echo "Seed selesai.\n";
echo "Lapangan baru: $inserted\n";
