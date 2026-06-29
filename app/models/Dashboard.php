<?php
class Dashboard {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getData() {
        $courts = $this->fetchAll(
            "SELECT * FROM courts WHERE is_deleted = 0 ORDER BY updated_at DESC, id DESC"
        );
        $bookings = $this->fetchAll(
            "SELECT bookings.*, courts.nama_lapangan, courts.lokasi, users.username
             FROM bookings
             INNER JOIN courts ON bookings.court_id = courts.id
             INNER JOIN users ON bookings.user_id = users.id
             ORDER BY bookings.tanggal DESC, bookings.jam_mulai DESC
             LIMIT 8"
        );

        $activeCourts = 0;
        $maintenanceCourts = 0;
        $byType = [];
        $byLocation = [];
        $monthly = [];

        foreach ($courts as $court) {
            if (($court['status'] ?? '') === 'Maintenance') {
                $maintenanceCourts++;
            } else {
                $activeCourts++;
            }

            $type = trim($court['tipe'] ?? '') ?: 'Tidak diketahui';
            $location = trim($court['lokasi'] ?? '') ?: 'Tidak diketahui';
            $byType[$type] = ($byType[$type] ?? 0) + 1;
            $byLocation[$location] = ($byLocation[$location] ?? 0) + 1;
        }

        $bookingStatus = [
            'Menunggu' => 0,
            'Disetujui' => 0,
            'Dibatalkan' => 0,
            'Selesai' => 0
        ];

        $statusRows = $this->fetchAll("SELECT status, COUNT(*) AS total FROM bookings GROUP BY status");
        foreach ($statusRows as $row) {
            $bookingStatus[$row['status']] = (int) $row['total'];
        }

        $monthRows = $this->fetchAll(
            "SELECT DATE_FORMAT(tanggal, '%b %Y') AS bulan, COUNT(*) AS total
             FROM bookings
             GROUP BY YEAR(tanggal), MONTH(tanggal), bulan
             ORDER BY YEAR(tanggal), MONTH(tanggal)"
        );
        foreach ($monthRows as $row) {
            $monthly[$row['bulan']] = (int) $row['total'];
        }
        if (!$monthly) {
            $monthly[date('M Y')] = 0;
        }

        arsort($byType);
        arsort($byLocation);

        return [
            'courts' => $courts,
            'recentBookings' => $bookings,
            'totalCourts' => count($courts),
            'activeCourts' => $activeCourts,
            'maintenanceCourts' => $maintenanceCourts,
            'bookingStatus' => $bookingStatus,
            'waitingBookings' => $bookingStatus['Menunggu'] ?? 0,
            'approvedBookings' => $bookingStatus['Disetujui'] ?? 0,
            'completedBookings' => $bookingStatus['Selesai'] ?? 0,
            'cancelledBookings' => $bookingStatus['Dibatalkan'] ?? 0,
            'byType' => $byType,
            'byLocation' => $byLocation,
            'monthly' => $monthly,
            'types' => array_keys($byType),
            'locations' => array_keys($byLocation),
            'maxLocation' => max($byLocation ?: [1]),
            'maxType' => max($byType ?: [1]),
            'lineMax' => max($monthly ?: [1])
        ];
    }

    private function fetchAll($sql) {
        $result = $this->conn->query($sql);
        $rows = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }
}
