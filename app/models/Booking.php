<?php
class Booking {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        $this->ensureSchema();
    }

    public function getAll() {
        $stmt = $this->conn->prepare(
            "SELECT bookings.*, courts.nama_lapangan, courts.lokasi, users.username
             FROM bookings
             INNER JOIN courts ON bookings.court_id = courts.id
             INNER JOIN users ON bookings.user_id = users.id
             ORDER BY bookings.tanggal DESC, bookings.jam_mulai DESC"
        );
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByUser($userId) {
        $stmt = $this->conn->prepare(
            "SELECT bookings.*, courts.nama_lapangan, courts.lokasi, users.username
             FROM bookings
             INNER JOIN courts ON bookings.court_id = courts.id
             INNER JOIN users ON bookings.user_id = users.id
             WHERE bookings.user_id = ?
             ORDER BY bookings.tanggal DESC, bookings.jam_mulai DESC"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare(
            "SELECT bookings.*, courts.nama_lapangan, courts.harga_per_jam, courts.lokasi, users.username
             FROM bookings
             INNER JOIN courts ON bookings.court_id = courts.id
             INNER JOIN users ON bookings.user_id = users.id
             WHERE bookings.id = ?
             LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function hasConflict($courtId, $date, $start, $end, $ignoreId = null) {
        if ($ignoreId) {
            $stmt = $this->conn->prepare(
                "SELECT id FROM bookings
                 WHERE court_id = ?
                 AND tanggal = ?
                 AND status != 'Dibatalkan'
                 AND id != ?
                 AND jam_mulai < ?
                 AND jam_selesai > ?
                 LIMIT 1"
            );
            $stmt->bind_param("isiss", $courtId, $date, $ignoreId, $end, $start);
        } else {
            $stmt = $this->conn->prepare(
                "SELECT id FROM bookings
                 WHERE court_id = ?
                 AND tanggal = ?
                 AND status != 'Dibatalkan'
                 AND jam_mulai < ?
                 AND jam_selesai > ?
                 LIMIT 1"
            );
            $stmt->bind_param("isss", $courtId, $date, $end, $start);
        }

        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function insert($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO bookings (user_id, court_id, tanggal, jam_mulai, jam_selesai, total_harga, status, catatan, whatsapp_number)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "iisssdsss",
            $data['user_id'],
            $data['court_id'],
            $data['tanggal'],
            $data['jam_mulai'],
            $data['jam_selesai'],
            $data['total_harga'],
            $data['status'],
            $data['catatan'],
            $data['whatsapp_number']
        );
        return $stmt->execute() ? $this->conn->insert_id : false;
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function cancelByUser($id, $userId) {
        $stmt = $this->conn->prepare(
            "UPDATE bookings SET status = 'Dibatalkan'
             WHERE id = ? AND user_id = ? AND status IN ('Menunggu', 'Disetujui')"
        );
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }

    private function ensureSchema() {
        $result = $this->conn->query("SHOW COLUMNS FROM bookings LIKE 'whatsapp_number'");
        if ($result && $result->num_rows === 0) {
            $this->conn->query("ALTER TABLE bookings ADD COLUMN whatsapp_number VARCHAR(30) NULL AFTER catatan");
        }
    }
}
