<?php
class Location {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        $this->ensureTable();
    }

    public function getAll() {
        $locations = [];
        $result = $this->conn->query("SELECT * FROM locations WHERE is_deleted = 0 ORDER BY nama_lokasi ASC");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $locations[] = $row;
            }
        }

        return $locations;
    }

    public function insert($name) {
        $name = trim($name);
        if ($name === '') {
            return false;
        }

        $stmt = $this->conn->prepare("INSERT IGNORE INTO locations (nama_lokasi) VALUES (?)");
        $stmt->bind_param("s", $name);

        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("UPDATE locations SET is_deleted = 1, deleted_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function restore($id) {
        $stmt = $this->conn->prepare("UPDATE locations SET is_deleted = 0, deleted_at = NULL WHERE id = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function getArchived() {
        $locations = [];
        $result = $this->conn->query("SELECT * FROM locations WHERE is_deleted = 1 ORDER BY deleted_at DESC");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $locations[] = $row;
            }
        }

        return $locations;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM locations WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }

    private function ensureTable() {
        $this->conn->query(
            "CREATE TABLE IF NOT EXISTS locations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama_lokasi VARCHAR(100) NOT NULL UNIQUE,
                is_deleted TINYINT(1) NOT NULL DEFAULT 0,
                deleted_at DATETIME NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )"
        );
        
        // Add missing columns to existing table
        $this->conn->query("ALTER TABLE locations ADD COLUMN IF NOT EXISTS is_deleted TINYINT(1) NOT NULL DEFAULT 0");
        $this->conn->query("ALTER TABLE locations ADD COLUMN IF NOT EXISTS deleted_at DATETIME NULL");
    }
}
