<?php
function ensureSettingsTable($conn) {
    $conn->query(
        "CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value VARCHAR(255) NOT NULL,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )"
    );

    $defaults = [
        'app_name' => 'Badminton Court Booking',
        'institution_name' => 'Badminton Center',
        'admin_contact' => '-',
        'whatsapp_enabled' => '0',
        'whatsapp_api_url' => 'https://api.fonnte.com/send',
        'whatsapp_api_token' => '',
        'whatsapp_admin_number' => ''
    ];

    $stmt = $conn->prepare("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)");
    foreach ($defaults as $key => $value) {
        $stmt->bind_param("ss", $key, $value);
        $stmt->execute();
    }
}

function getSettingValue($conn, $key, $default = '') {
    ensureSettingsTable($conn);

    $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return $row['setting_value'] ?? $default;
}

function appName($conn) {
    return getSettingValue($conn, 'app_name', 'Badminton Court Booking');
}
