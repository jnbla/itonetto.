<?php
class Transport {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM courts ORDER BY nama_lapangan ASC");
    }

    public function getActive() {
        $stmt = $this->conn->prepare("SELECT * FROM courts WHERE is_deleted = 0 AND status = 'Aktif' ORDER BY nama_lapangan ASC");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getLocations() {
        $locations = [];
        
        try {
            // Try to get from locations table first
            $stmt = $this->conn->prepare(
                "SELECT DISTINCT nama_lokasi as lokasi FROM locations
                 WHERE is_deleted = 0
                 ORDER BY nama_lokasi ASC"
            );
            
            if ($stmt && $stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $locations[] = $row['lokasi'];
                }
            }
        } catch (Exception $e) {
            // Fallback: get from courts table if locations table has issues
            $result = $this->conn->query(
                "SELECT DISTINCT lokasi FROM courts
                 WHERE lokasi IS NOT NULL AND lokasi != '' AND is_deleted = 0
                 ORDER BY lokasi ASC"
            );
            
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $locations[] = $row['lokasi'];
                }
            }
        }
        
        return $locations;
    }

    public function insert($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO courts (nama_lapangan, tipe, harga_per_jam, status, lokasi, deskripsi, gambar)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssdssss",
            $data['nama'],
            $data['tipe'],
            $data['harga_per_jam'],
            $data['status'],
            $data['lokasi'],
            $data['deskripsi'],
            $data['gambar']
        );
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM courts WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $data) {
        if (!empty($data['gambar'])) {
            $stmt = $this->conn->prepare(
                "UPDATE courts
                 SET nama_lapangan = ?, tipe = ?, harga_per_jam = ?, status = ?, lokasi = ?, deskripsi = ?, gambar = ?
                 WHERE id = ?"
            );
            $stmt->bind_param(
                "ssdssssi",
                $data['nama'],
                $data['tipe'],
                $data['harga_per_jam'],
                $data['status'],
                $data['lokasi'],
                $data['deskripsi'],
                $data['gambar'],
                $id
            );
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE courts
                 SET nama_lapangan = ?, tipe = ?, harga_per_jam = ?, status = ?, lokasi = ?, deskripsi = ?
                 WHERE id = ?"
            );
            $stmt->bind_param(
                "ssdsssi",
                $data['nama'],
                $data['tipe'],
                $data['harga_per_jam'],
                $data['status'],
                $data['lokasi'],
                $data['deskripsi'],
                $id
            );
        }

        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM courts WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
