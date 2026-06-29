<?php
function bookingQrCodeUrl($booking, $size = 120) {
    $size = max(80, min(300, (int) $size));
    $data = implode("\n", [
        "Bukti Reservasi",
        "ID: #" . (int) ($booking['id'] ?? 0),
        "User: " . ($booking['username'] ?? '-'),
        "Lapangan: " . ($booking['nama_lapangan'] ?? '-'),
        "Tanggal: " . (!empty($booking['tanggal']) ? date('d/m/Y', strtotime($booking['tanggal'])) : '-'),
        "Jam: " . substr($booking['jam_mulai'] ?? '-', 0, 5) . " - " . substr($booking['jam_selesai'] ?? '-', 0, 5),
        "Total: Rp " . number_format((float) ($booking['total_harga'] ?? 0), 0, ',', '.'),
        "Status: " . ($booking['status'] ?? '-')
    ]);

    return "https://api.qrserver.com/v1/create-qr-code/?" . http_build_query([
        'size' => $size . 'x' . $size,
        'data' => $data
    ]);
}
