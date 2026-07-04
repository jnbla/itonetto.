<?php include __DIR__ . '/../layout/navbar.php'; ?>
<?php require_once __DIR__ . '/../../helpers/QRCode.php'; ?>

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
            <h1>Booking Saya</h1>
            <p>Ringkasan reservasi dan pelacakan status booking kamu.</p>
        </div>

        <div class="quick-actions">
            <a href="/IkiNet/app/controllers/BookingController.php?action=create" class="button">Buat Booking Baru</a>
            <a href="/IkiNet/app/controllers/BookingController.php" class="button secondary">Kelola Booking</a>
        </div>
    </section>

    <?php
    $courtRows = [];
    while ($court = $courts->fetch_assoc()) {
        $courtRows[] = $court;
    }
    ?>

    <section class="summary-grid">
        <article class="metric-card">
            <span>Menunggu</span>
            <strong><?= $waiting ?></strong>
            <small>Reservasi yang belum dikonfirmasi</small>
        </article>
        <article class="metric-card">
            <span>Disetujui</span>
            <strong><?= $approved ?></strong>
            <small>Booking siap digunakan</small>
        </article>
        <article class="metric-card">
            <span>Selesai</span>
            <strong><?= $done ?></strong>
            <small>Booking selesai</small>
        </article>
        <article class="metric-card alert">
            <span>Dibatalkan</span>
            <strong><?= $cancelled ?></strong>
            <small>Booking dibatalkan</small>
        </article>
    </section>

    <section class="dashboard-card transport-table-card">
        <div class="booking-table-caption">
            <div>
                <h2>Riwayat Reservasi</h2>
                <small><?= count($rows) ?> reservasi</small>
            </div>
            <div class="booking-actions">
                <a href="/IkiNet/app/controllers/BookingController.php?action=create" class="button">Buat Booking Baru</a>
                <a href="/IkiNet/app/controllers/BookingController.php" class="button secondary">Kelola Booking</a>
            </div>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
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
                            <?php if (in_array($row['status'], ['Menunggu', 'Disetujui'], true)): ?>
                                <a class="button secondary" href="/IkiNet/app/controllers/BookingController.php?action=cancel&id=<?= (int) $row['id'] ?>" onclick="return confirm('Batalkan reservasi ini?')">Batalkan</a>
                            <?php else: ?>
                                <span class="muted">-</span>
                            <?php endif; ?>
                        </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="10">Belum ada reservasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="dashboard-grid three-columns booking-court-grid">
        <?php foreach ($courtRows as $court): ?>
            <article class="booking-court-card">
                <?php if (!empty($court['gambar'])): ?>
                    <img class="booking-court-image" src="/IkiNet/uploads/<?= htmlspecialchars($court['gambar']) ?>" alt="<?= htmlspecialchars($court['nama_lapangan']) ?>">
                <?php endif; ?>
                <div class="booking-court-content">
                    <h3><?= htmlspecialchars($court['nama_lapangan']) ?></h3>
                    <p><?= htmlspecialchars($court['deskripsi'] ?: 'Lapangan tersedia untuk booking. Pilih jadwal yang sesuai dan ajukan reservasi.') ?></p>
                    <ul class="booking-court-meta">
                        <li><strong>Harga:</strong> Rp <?= number_format((float) $court['harga_per_jam'], 0, ',', '.') ?>/jam</li>
                        <li><strong>Lokasi:</strong> <?= htmlspecialchars($court['lokasi']) ?></li>
                        <li><strong>Status:</strong> <?= htmlspecialchars($court['status']) ?></li>
                    </ul>
                    <a href="/IkiNet/app/controllers/BookingController.php?action=create" class="button">Booking Sekarang</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</div>
