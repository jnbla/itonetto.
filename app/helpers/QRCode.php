<?php
function bookingQrCodeUrl($booking, $size = 120) {
    $size = max(80, min(300, (int) $size));

    return "https://api.qrserver.com/v1/create-qr-code/?" . http_build_query([
        'size' => $size . 'x' . $size,
        'data' => bookingReceiptUrl((int) ($booking['id'] ?? 0))
    ]);
}

function bookingReceiptUrl($bookingId) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = qrCodePublicHost($_SERVER['HTTP_HOST'] ?? 'localhost');

    return $scheme . '://' . $host . '/IkiNet/app/controllers/BookingReceiptController.php?id=' . (int) $bookingId;
}

function qrCodePublicHost($host) {
    $port = '';
    $hostname = $host;

    if (strpos($host, ':') !== false && substr_count($host, ':') === 1) {
        [$hostname, $port] = explode(':', $host, 2);
        $port = ':' . $port;
    }

    $localHosts = ['localhost', '127.0.0.1', '::1'];
    if (!in_array(strtolower($hostname), $localHosts, true)) {
        return $host;
    }

    $localIp = qrCodeLocalIp();
    return $localIp ? $localIp . $port : $host;
}

function qrCodeLocalIp() {
    $candidates = [
        $_SERVER['SERVER_ADDR'] ?? '',
        gethostbyname(gethostname())
    ];

    foreach ($candidates as $candidate) {
        if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $candidate;
        }

        if (filter_var($candidate, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && strpos($candidate, '127.') !== 0) {
            return $candidate;
        }
    }

    return null;
}
