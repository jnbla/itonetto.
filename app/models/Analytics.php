<?php
class Analytics {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTotalRevenue($startDate = null, $endDate = null) {
        $query = "SELECT SUM(total_harga) as total FROM bookings WHERE status = 'Selesai'";
        $params = [];

        if ($startDate && $endDate) {
            $query .= " AND tanggal BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }

        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param("ss", ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result['total'] ?? 0;
    }

    public function getRevenueByPeriod($days = 7) {
        $stmt = $this->conn->prepare(
            "SELECT 
                DATE(tanggal) as date,
                SUM(total_harga) as revenue,
                COUNT(*) as booking_count
             FROM bookings
             WHERE status = 'Selesai' AND tanggal >= DATE_SUB(NOW(), INTERVAL ? DAY)
             GROUP BY DATE(tanggal)
             ORDER BY date DESC"
        );

        $stmt->bind_param("i", $days);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getMostBookedCourts($limit = 5) {
        $stmt = $this->conn->prepare(
            "SELECT 
                c.id,
                c.nama_lapangan,
                c.lokasi,
                COUNT(b.id) as total_bookings,
                SUM(b.total_harga) as total_revenue
             FROM courts c
             LEFT JOIN bookings b ON c.id = b.court_id AND b.status = 'Selesai'
             WHERE c.is_deleted = 0
             GROUP BY c.id
             ORDER BY total_bookings DESC
             LIMIT ?"
        );

        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOccupancyRate($days = 7) {
        $stmt = $this->conn->prepare(
            "SELECT 
                c.id,
                c.nama_lapangan,
                COUNT(CASE WHEN b.status IN ('Disetujui', 'Selesai') THEN 1 END) as booked_slots,
                (? * 24) as total_slots,
                ROUND(COUNT(CASE WHEN b.status IN ('Disetujui', 'Selesai') THEN 1 END) / (? * 24) * 100, 2) as occupancy_rate
             FROM courts c
             LEFT JOIN bookings b ON c.id = b.court_id AND b.tanggal >= DATE_SUB(NOW(), INTERVAL ? DAY)
             WHERE c.is_deleted = 0
             GROUP BY c.id
             ORDER BY occupancy_rate DESC"
        );

        $stmt->bind_param("iii", $days, $days, $days);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPeakHours() {
        $stmt = $this->conn->prepare(
            "SELECT 
                HOUR(jam_mulai) as hour,
                COUNT(*) as booking_count,
                SUM(total_harga) as revenue
             FROM bookings
             WHERE status IN ('Disetujui', 'Selesai')
             GROUP BY HOUR(jam_mulai)
             ORDER BY booking_count DESC"
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBookingStats() {
        $stmt = $this->conn->prepare(
            "SELECT 
                status,
                COUNT(*) as count
             FROM bookings
             GROUP BY status"
        );

        $stmt->execute();
        $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stats = [
            'Menunggu' => 0,
            'Disetujui' => 0,
            'Dibatalkan' => 0,
            'Selesai' => 0
        ];

        foreach ($results as $row) {
            $stats[$row['status']] = (int)$row['count'];
        }

        return $stats;
    }

    public function getCourtStats() {
        $stmt = $this->conn->prepare(
            "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN status = 'Aktif' THEN 1 END) as active,
                COUNT(CASE WHEN status = 'Maintenance' THEN 1 END) as maintenance,
                COUNT(CASE WHEN is_deleted = 1 THEN 1 END) as deleted
             FROM courts"
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getMonthlyComparison() {
        $stmt = $this->conn->prepare(
            "SELECT 
                DATE_FORMAT(tanggal, '%Y-%m') as month,
                COUNT(*) as bookings,
                SUM(total_harga) as revenue
             FROM bookings
             WHERE status = 'Selesai' AND tanggal >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
             ORDER BY month DESC"
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
