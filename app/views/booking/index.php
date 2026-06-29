<?php include __DIR__ . '/../layout/navbar.php'; ?>
<?php require_once __DIR__ . '/../../helpers/QRCode.php'; ?>
<?php
$rows = [];
$waiting = 0;
$approved = 0;
$done = 0;
$cancelled = 0;

while ($row = $bookings->fetch_assoc()) {
    $rows[] = $row;
    if (($row['status'] ?? '') === 'Menunggu') {
        $waiting++;
    } elseif (($row['status'] ?? '') === 'Disetujui') {
        $approved++;
    } elseif (($row['status'] ?? '') === 'Selesai') {
        $done++;
    } elseif (($row['status'] ?? '') === 'Dibatalkan') {
        $cancelled++;
    }
}
?>

<div class="transport-shell">
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash-message <?= htmlspecialchars($_SESSION['flash']['type']) ?>">
            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <section class="transport-hero">
        <div>
            <p class="dashboard-kicker"><?= htmlspecialchars($appName ?? 'Badminton Court Booking') ?></p>
            <h1>Reservasi Lapangan</h1>
            <p><?= $canManage ? 'Pantau dan kelola status semua reservasi pengguna.' : 'Lihat riwayat dan status reservasi lapangan kamu.' ?></p>
        </div>

        <div class="quick-actions">
            <a href="/IkiNet/app/controllers/BookingController.php?action=create" class="button">Buat Booking</a>
            <a href="/IkiNet/app/controllers/TransportController.php" class="button secondary">Daftar Lapangan</a>
        </div>
    </section>

    <section class="summary-grid">
        <article class="metric-card">
            <span>Menunggu</span>
            <strong><?= $waiting ?></strong>
            <small>Perlu konfirmasi</small>
        </article>
        <article class="metric-card">
            <span>Disetujui</span>
            <strong><?= $approved ?></strong>
            <small>Siap digunakan</small>
        </article>
        <article class="metric-card">
            <span>Selesai</span>
            <strong><?= $done ?></strong>
            <small>Booking selesai</small>
        </article>
        <article class="metric-card alert">
            <span>Dibatalkan</span>
            <strong><?= $cancelled ?></strong>
            <small>Tidak aktif</small>
        </article>
    </section>

    <section class="dashboard-card transport-table-card">
        <div class="section-title">
            <h2>Daftar Reservasi</h2>
            <span><?= count($rows) ?> data</span>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <?php if ($canManage): ?><th>User</th><?php endif; ?>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>WhatsApp</th>
                        <th>QR</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <?php $status = strtolower($row['status'] ?? ''); ?>
                        <tr>
                            <td>#<?= (int) $row['id'] ?></td>
                            <?php if ($canManage): ?><td><?= htmlspecialchars($row['username']) ?></td><?php endif; ?>
                            <td>
                                <strong><?= htmlspecialchars($row['nama_lapangan']) ?></strong><br>
                                <span class="muted"><?= htmlspecialchars($row['lokasi']) ?></span>
                            </td>
                            <td><?= htmlspecialchars(date('d M Y', strtotime($row['tanggal']))) ?></td>
                            <td><?= htmlspecialchars(substr($row['jam_mulai'], 0, 5)) ?> - <?= htmlspecialchars(substr($row['jam_selesai'], 0, 5)) ?></td>
                            <td>Rp <?= number_format((float) $row['total_harga'], 0, ',', '.') ?></td>
                            <td><span class="status-pill <?= in_array($status, ['dibatalkan', 'menunggu'], true) ? 'bad' : 'good' ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                            <td><?= htmlspecialchars($row['catatan'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($row['whatsapp_number'] ?: '-') ?></td>
                            <td>
                                <a href="<?= htmlspecialchars(bookingQrCodeUrl($row, 240)) ?>" target="_blank" rel="noopener" title="Buka QR Code">
                                    <img class="booking-qr-code" src="<?= htmlspecialchars(bookingQrCodeUrl($row, 100)) ?>" alt="QR booking #<?= (int) $row['id'] ?>">
                                </a>
                            </td>
                            <td>
                                <?php if ($canManage): ?>
                                    <form method="POST" action="/IkiNet/app/controllers/BookingController.php?action=status&id=<?= (int) $row['id'] ?>" class="inline-form">
                                        <select name="status" onchange="this.form.submit()">
                                            <?php foreach (['Menunggu', 'Disetujui', 'Dibatalkan', 'Selesai'] as $option): ?>
                                                <option value="<?= $option ?>" <?= $row['status'] === $option ? 'selected' : '' ?>><?= $option ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                <?php elseif (in_array($row['status'], ['Menunggu', 'Disetujui'], true)): ?>
                                    <a href="/IkiNet/app/controllers/BookingController.php?action=cancel&id=<?= (int) $row['id'] ?>" onclick="return confirm('Batalkan reservasi ini?')">Batalkan</a>
                                <?php else: ?>
                                    <span class="muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="<?= $canManage ? 11 : 10 ?>">Belum ada reservasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
