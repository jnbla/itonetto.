<?php
class Maintenance {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        $this->ensureTable();
    }

    public function getAll() {
        return $this->conn->query(
            "SELECT maintenance.*, transport.nama_kendaraan, transport.lokasi
             FROM maintenance
             LEFT JOIN transport ON maintenance.transport_id = transport.id
             ORDER BY maintenance.tanggal ASC, maintenance.id DESC"
        );
    }

    public function insert($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO maintenance (transport_id, tanggal, keterangan, status)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "isss",
            $data['transport_id'],
            $data['tanggal'],
            $data['keterangan'],
            $data['status']
        );

        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE maintenance SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);

        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM maintenance WHERE id = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    private function ensureTable() {
        $this->conn->query(
            "CREATE TABLE IF NOT EXISTS maintenance (
                id INT AUTO_INCREMENT PRIMARY KEY,
                transport_id INT NOT NULL,
                tanggal DATE NOT NULL,
                keterangan VARCHAR(255) NOT NULL,
                status ENUM('Terjadwal', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Terjadwal',
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX (transport_id)
            )"
        );
    }
}
