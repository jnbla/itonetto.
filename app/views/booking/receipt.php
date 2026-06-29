<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Reservasi - <?= htmlspecialchars($appName) ?></title>
    <?php include __DIR__ . "/../layout/style.php"; ?>
</head>
<body class="receipt-page">
    <main class="receipt-shell">
        <?php if (!$booking): ?>
            <section class="receipt-card">
                <p class="dashboard-kicker"><?= htmlspecialchars($appName) ?></p>
                <h1>Booking tidak ditemukan</h1>
                <p>Data reservasi tidak tersedia atau ID booking tidak valid.</p>
            </section>
        <?php else: ?>
            <section class="receipt-card">
                <div class="receipt-header">
                    <div>
                        <p class="dashboard-kicker"><?= htmlspecialchars($appName) ?></p>
                        <h1>Bukti Reservasi</h1>
                    </div>
                    <span class="status-pill <?= strtolower($booking['status']) === 'dibatalkan' ? 'bad' : 'good' ?>">
                        <?= htmlspecialchars($booking['status']) ?>
                    </span>
                </div>

                <div class="receipt-number">#<?= (int) $booking['id'] ?></div>

                <div class="receipt-list">
                    <p><strong>Nama</strong><span><?= htmlspecialchars($booking['username']) ?></span></p>
                    <p><strong>Lapangan</strong><span><?= htmlspecialchars($booking['nama_lapangan']) ?></span></p>
                    <p><strong>Lokasi</strong><span><?= htmlspecialchars($booking['lokasi']) ?></span></p>
                    <p><strong>Tanggal</strong><span><?= htmlspecialchars(date('d M Y', strtotime($booking['tanggal']))) ?></span></p>
                    <p><strong>Jam</strong><span><?= htmlspecialchars(substr($booking['jam_mulai'], 0, 5)) ?> - <?= htmlspecialchars(substr($booking['jam_selesai'], 0, 5)) ?></span></p>
                    <p><strong>Total</strong><span>Rp <?= number_format((float) $booking['total_harga'], 0, ',', '.') ?></span></p>
                    <p><strong>Catatan</strong><span><?= htmlspecialchars($booking['catatan'] ?: '-') ?></span></p>
                </div>

                <button class="receipt-print" type="button" onclick="window.print()">Print</button>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
