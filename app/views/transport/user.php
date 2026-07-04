<?php include __DIR__ . '/../layout/navbar.php'; ?>

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
            <h1>Lapangan untuk Booking</h1>
            <p>Pilih lapangan aktif dan ajukan booking dengan cepat. Hanya lapangan yang tersedia yang akan tampil di sini.</p>
        </div>

        <div class="quick-actions">
            <a href="/IkiNet/app/controllers/BookingController.php?action=create" class="button">Booking Baru</a>
        </div>
    </section>

    <section class="dashboard-grid three-columns booking-court-grid">
        <?php $courtCount = 0; while ($row = $courts->fetch_assoc()): $courtCount++; ?>
            <article class="booking-court-card">
                <?php if (!empty($row['gambar'])): ?>
                    <img class="booking-court-image" src="/IkiNet/uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['nama_lapangan']) ?>">
                <?php endif; ?>
                <div class="booking-court-content">
                    <h3><?= htmlspecialchars($row['nama_lapangan']) ?></h3>
                    <p><?= htmlspecialchars($row['deskripsi'] ?: 'Lapangan ini sedang aktif dan siap dibooking.') ?></p>
                    <ul class="booking-court-meta">
                        <li><strong>Harga:</strong> Rp <?= number_format((float) $row['harga_per_jam'], 0, ',', '.') ?>/jam</li>
                        <li><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></li>
                        <li><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></li>
                    </ul>
                    <a href="/IkiNet/app/controllers/BookingController.php?action=create" class="button">Booking Sekarang</a>
                </div>
            </article>
        <?php endwhile; ?>

        <?php if ($courtCount === 0): ?>
            <div class="dashboard-card">
                <h2>Tidak ada lapangan aktif</h2>
                <p>Semua lapangan saat ini sedang maintenance atau tidak tersedia. Coba lagi nanti.</p>
            </div>
        <?php endif; ?>
    </section>
</div>
