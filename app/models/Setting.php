<?php
require_once __DIR__ . "/../helpers/settings.php";

class Setting {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        ensureSettingsTable($this->conn);
    }

    public function getAll() {
        return [
            'app_name' => getSettingValue($this->conn, 'app_name', 'Badminton Court Booking'),
            'institution_name' => getSettingValue($this->conn, 'institution_name', 'Badminton Center'),
            'admin_contact' => getSettingValue($this->conn, 'admin_contact', '-'),
            'whatsapp_enabled' => getSettingValue($this->conn, 'whatsapp_enabled', '0'),
            'whatsapp_api_url' => getSettingValue($this->conn, 'whatsapp_api_url', 'https://api.fonnte.com/send'),
            'whatsapp_api_token' => getSettingValue($this->conn, 'whatsapp_api_token', ''),
            'whatsapp_admin_number' => getSettingValue($this->conn, 'whatsapp_admin_number', '')
        ];
    }

    public function update($data) {
        $settings = [
            'app_name' => trim($data['app_name'] ?? ''),
            'institution_name' => trim($data['institution_name'] ?? ''),
            'admin_contact' => trim($data['admin_contact'] ?? ''),
            'whatsapp_enabled' => ($data['whatsapp_enabled'] ?? '') === '1' ? '1' : '0',
            'whatsapp_api_url' => trim($data['whatsapp_api_url'] ?? 'https://api.fonnte.com/send'),
            'whatsapp_api_token' => trim($data['whatsapp_api_token'] ?? ''),
            'whatsapp_admin_number' => trim($data['whatsapp_admin_number'] ?? '')
        ];

        if ($settings['app_name'] === '') {
            throw new Exception("Nama aplikasi wajib diisi.");
        }

        if ($settings['whatsapp_enabled'] === '1' && ($settings['whatsapp_api_token'] === '' || $settings['whatsapp_admin_number'] === '')) {
            throw new Exception("Token API dan nomor admin WhatsApp wajib diisi jika integrasi WhatsApp aktif.");
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO settings (setting_key, setting_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        );

        foreach ($settings as $key => $value) {
            $stmt->bind_param("ss", $key, $value);
            $stmt->execute();
        }
    }
}
