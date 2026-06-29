<?php
class ExcelHandler {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function exportCourtsToExcel() {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="lapangan_' . date('Ymd_His') . '.xlsx"');

        $result = $this->conn->query("SELECT * FROM courts WHERE is_deleted = 0 ORDER BY nama_lapangan ASC");
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        // Create basic Excel format (CSV for simplicity, can upgrade to PHPOffice later)
        $this->createCSV($rows, ['id', 'nama_lapangan', 'tipe', 'harga_per_jam', 'status', 'lokasi', 'deskripsi']);
    }

    public function exportBookingsToExcel($startDate = null, $endDate = null) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="bookings_' . date('Ymd_His') . '.xlsx"');

        $query = "SELECT 
                    b.id,
                    u.username,
                    c.nama_lapangan,
                    b.tanggal,
                    b.jam_mulai,
                    b.jam_selesai,
                    b.total_harga,
                    b.status
                  FROM bookings b
                  JOIN users u ON b.user_id = u.id
                  JOIN courts c ON b.court_id = c.id
                  WHERE 1=1";

        $params = [];
        if ($startDate && $endDate) {
            $query .= " AND b.tanggal BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }

        $query .= " ORDER BY b.tanggal DESC";

        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param("ss", ...$params);
        }
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->createCSV($rows, ['id', 'username', 'nama_lapangan', 'tanggal', 'jam_mulai', 'jam_selesai', 'total_harga', 'status']);
    }

    public function exportAnalyticsToExcel() {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="analytics_' . date('Ymd_His') . '.xlsx"');

        // Revenue data
        $revenueStmt = $this->conn->prepare(
            "SELECT DATE(tanggal) as date, SUM(total_harga) as revenue, COUNT(*) as bookings
             FROM bookings WHERE status = 'Selesai' AND tanggal >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(tanggal) ORDER BY date DESC"
        );
        $revenueStmt->execute();
        $revenue = $revenueStmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $this->createCSV($revenue, ['date', 'revenue', 'bookings']);
    }

    public function importCourtsFromExcel($file) {
        if (!$file || $file["error"] !== UPLOAD_ERR_OK) {
            throw new Exception("Upload file gagal.");
        }

        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if (!in_array($ext, ['csv', 'xlsx', 'xls'])) {
            throw new Exception("Format file harus CSV atau Excel.");
        }

        $lines = file($file["tmp_name"]);
        $imported = 0;
        $errors = [];

        // Skip header
        for ($i = 1; $i < count($lines); $i++) {
            $data = str_getcsv(trim($lines[$i]));

            if (count($data) < 5) {
                $errors[] = "Baris " . ($i + 1) . ": Data tidak lengkap";
                continue;
            }

            try {
                $this->importCourtRow($data);
                $imported++;
            } catch (Exception $e) {
                $errors[] = "Baris " . ($i + 1) . ": " . $e->getMessage();
            }
        }

        return [
            'imported' => $imported,
            'errors' => $errors,
            'total' => count($lines) - 1
        ];
    }

    private function importCourtRow($data) {
        $nama = trim($data[0]);
        $tipe = trim($data[1]);
        $harga = (float)$data[2];
        $status = trim($data[3]);
        $lokasi = trim($data[4]);
        $deskripsi = isset($data[5]) ? trim($data[5]) : '';

        // Validation
        if (empty($nama)) {
            throw new Exception("Nama lapangan tidak boleh kosong");
        }
        if (empty($tipe)) {
            throw new Exception("Tipe lapangan tidak boleh kosong");
        }
        if ($harga < 0) {
            throw new Exception("Harga harus angka positif");
        }
        if (!in_array($status, ['Aktif', 'Maintenance'])) {
            throw new Exception("Status harus 'Aktif' atau 'Maintenance'");
        }
        if (empty($lokasi)) {
            throw new Exception("Lokasi tidak boleh kosong");
        }

        // Check duplicate
        $stmt = $this->conn->prepare("SELECT id FROM courts WHERE nama_lapangan = ? AND is_deleted = 0");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Lapangan dengan nama '$nama' sudah ada");
        }

        // Insert
        $insertStmt = $this->conn->prepare(
            "INSERT INTO courts (nama_lapangan, tipe, harga_per_jam, status, lokasi, deskripsi)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        $insertStmt->bind_param("ssdss", $nama, $tipe, $harga, $status, $lokasi, $deskripsi);
        if (!$insertStmt->execute()) {
            throw new Exception("Gagal insert ke database");
        }
    }

    private function createCSV($rows, $columns) {
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Header
        fputcsv($output, $columns, ';');

        // Data
        foreach ($rows as $row) {
            $line = [];
            foreach ($columns as $col) {
                $line[] = $row[$col] ?? '';
            }
            fputcsv($output, $line, ';');
        }

        fclose($output);
    }

    public function getImportTemplate() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="template_lapangan.csv"');

        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Headers
        fputcsv($output, ['nama_lapangan', 'tipe', 'harga_per_jam', 'status', 'lokasi', 'deskripsi'], ';');

        // Example rows
        fputcsv($output, ['Lapangan A', 'Indoor Vinyl', '75000', 'Aktif', 'Gedung Utama', 'Lapangan premium'], ';');
        fputcsv($output, ['Lapangan B', 'Indoor Standar', '60000', 'Aktif', 'Gedung Utama', 'Lapangan standar'], ';');

        fclose($output);
    }
}
