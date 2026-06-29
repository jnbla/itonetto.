<?php
require_once __DIR__ . "/settings.php";

class WhatsAppNotifier {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function notifyBookingCreated($booking, $court, $username) {
        $message = $this->buildBookingMessage(
            "Reservasi baru berhasil dibuat dan menunggu persetujuan admin.",
            $booking,
            $court,
            $username
        );

        return $this->sendToBookingContacts($booking, $message);
    }

    public function notifyStatusChanged($booking) {
        $message = $this->buildBookingMessage(
            "Status reservasi kamu diperbarui menjadi " . ($booking['status'] ?? '-') . ".",
            $booking,
            $booking,
            $booking['username'] ?? '-'
        );

        return $this->sendToBookingContacts($booking, $message);
    }

    private function sendToBookingContacts($booking, $message) {
        if (!$this->isEnabled()) {
            return false;
        }

        $targets = [];
        $adminNumber = $this->normalizePhone(getSettingValue($this->conn, 'whatsapp_admin_number', ''));
        $customerNumber = $this->normalizePhone($booking['whatsapp_number'] ?? '');

        if ($adminNumber !== '') {
            $targets[] = $adminNumber;
        }

        if ($customerNumber !== '' && $customerNumber !== $adminNumber) {
            $targets[] = $customerNumber;
        }

        $sent = false;
        foreach ($targets as $target) {
            $sent = $this->send($target, $message) || $sent;
        }

        return $sent;
    }

    private function send($target, $message) {
        $token = trim(getSettingValue($this->conn, 'whatsapp_api_token', ''));
        $apiUrl = trim(getSettingValue($this->conn, 'whatsapp_api_url', 'https://api.fonnte.com/send'));

        if ($token === '' || $target === '' || $message === '' || !function_exists('curl_init')) {
            return false;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $message
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $token
            ],
            CURLOPT_TIMEOUT => 10
        ]);

        curl_exec($curl);
        $error = curl_errno($curl);
        $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $error === 0 && $statusCode >= 200 && $statusCode < 300;
    }

    private function buildBookingMessage($title, $booking, $court, $username) {
        $date = !empty($booking['tanggal']) ? date('d/m/Y', strtotime($booking['tanggal'])) : '-';
        $start = !empty($booking['jam_mulai']) ? substr($booking['jam_mulai'], 0, 5) : '-';
        $end = !empty($booking['jam_selesai']) ? substr($booking['jam_selesai'], 0, 5) : '-';
        $total = number_format((float) ($booking['total_harga'] ?? 0), 0, ',', '.');

        return implode("\n", [
            appName($this->conn),
            $title,
            "",
            "Nama: " . $username,
            "Lapangan: " . ($court['nama_lapangan'] ?? '-'),
            "Tanggal: " . $date,
            "Jam: " . $start . " - " . $end,
            "Total: Rp " . $total,
            "Status: " . ($booking['status'] ?? 'Menunggu')
        ]);
    }

    private function isEnabled() {
        return getSettingValue($this->conn, 'whatsapp_enabled', '0') === '1';
    }

    private function normalizePhone($phone) {
        $phone = preg_replace('/\D+/', '', (string) $phone);

        if ($phone === '') {
            return '';
        }

        if (strpos($phone, '0') === 0) {
            return '62' . substr($phone, 1);
        }

        return $phone;
    }
}
