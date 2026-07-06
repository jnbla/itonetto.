<?php include __DIR__ . '/../layout/navbar.php'; ?>
<?php
$courtRows = [];
while ($row = $courts->fetch_assoc()) {
    $courtRows[] = $row;
}
?>

<div class="booking-shell">
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash-message <?= htmlspecialchars($_SESSION['flash']['type']) ?>">
            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <section class="booking-topbar">
        <div>
            <p class="breadcrumb">Home › Booking › Buat</p>
            <h1>Reservasi Lapangan</h1>
            <p class="booking-intro">Atur jadwal dengan cepat, cek ketersediaan lapangan, dan submit booking di dashboard yang simple.</p>
        </div>
        <div class="booking-actions-top">
            <a href="/IkiNet/app/controllers/BookingController.php?action=user" class="button secondary">Booking Saya</a>
            <a href="/IkiNet/app/controllers/TransportController.php" class="button secondary">Lapangan</a>
        </div>
    </section>

    <section class="booking-dashboard-grid">
        <main class="booking-main-card">
            <div class="section-title">
                <h2>Form Reservasi</h2>
                <span>Isi data dengan benar</span>
            </div>

            <form method="POST" action="/IkiNet/app/controllers/BookingController.php?action=store" class="booking-form">
                <label>
                    <span>Lapangan</span>
                    <select name="court_id" required>
                        <option value="">Pilih lapangan</option>
                        <?php foreach ($courtRows as $court): ?>
                            <option value="<?= (int) $court['id'] ?>">
                                <?= htmlspecialchars($court['nama_lapangan']) ?> - Rp <?= number_format((float) $court['harga_per_jam'], 0, ',', '.') ?>/jam
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <div class="form-grid">
                    <label>
                        <span>Tanggal</span>
                        <input type="date" name="tanggal" min="<?= date('Y-m-d') ?>" required>
                    </label>
                    <label>
                        <span>Jam Mulai</span>
                        <input type="time" name="jam_mulai" min="06:00" max="23:00" required>
                    </label>
                    <label>
                        <span>Jam Selesai</span>
                        <input type="time" name="jam_selesai" min="07:00" max="23:59" required>
                    </label>
                </div>

                <label>
                    <span>Catatan</span>
                    <input type="text" name="catatan" placeholder="Contoh: latihan tim malam">
                </label>

                <label>
                    <span>No. WhatsApp</span>
                    <input type="tel" name="whatsapp_number" placeholder="081234567890">
                </label>

                <div class="form-actions">
                    <button type="submit">Ajukan Booking</button>
                    <a href="/IkiNet/app/controllers/BookingController.php" class="button secondary">Batal</a>
                </div>
            </form>
        </main>

        <aside class="booking-sidebar">
            <section class="dashboard-card compact-card">
                <div class="section-title">
                    <h2>Quick Search</h2>
                    <span>Temukan cepat</span>
                </div>
                <input type="search" placeholder="Cari lapangan, lokasi, atau harga">
                <div class="quick-list">
                    <a href="#">View Calendar</a>
                    <a href="#">Edit Profile</a>
                    <a href="#">Settings</a>
                </div>
                <div class="quick-recent">
                    <strong>Recent</strong>
                    <p>Dashboard</p>
                    <p>Projects</p>
                    <p>Analytics</p>
                </div>
            </section>

            <section class="dashboard-card compact-card">
                <div class="section-title">
                    <h2>Schedule</h2>
                    <span>Pilih tanggal</span>
                </div>
                <div class="mini-calendar">
                    <?php
                    $today = date('j');
                    $days = ['Su','Mo','Tu','We','Th','Fr','Sa'];
                    foreach ($days as $day): ?>
                        <span class="calendar-label"><?= $day ?></span>
                    <?php endforeach; ?>
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                        <span class="<?= $i == $today ? 'today' : '' ?>"><?= $i ?></span>
                    <?php endfor; ?>
                </div>
            </section>

            <section class="dashboard-card compact-card">
                <div class="section-title">
                    <h2>Featured</h2>
                    <span>Rekomendasi</span>
                </div>
                <div class="featured-card">
                    <h3>Promo diskon sore</h3>
                    <p>Dapatkan harga spesial untuk slot 18:00 - 20:00 hari ini.</p>
                </div>
                <div class="featured-links">
                    <a href="#">Lihat detail</a>
                    <a href="#">Pelajari lagi</a>
                </div>
            </section>
        </aside>
    </section>

    <section class="booking-courts-overview">
        <div class="section-title">
            <h2>Lapangan Aktif</h2>
            <span>Pilih lapangan favoritmu</span>
        </div>
        <div class="booking-preview-stack">
            <?php foreach ($courtRows as $court): ?>
                <article class="booking-court-card compact">
                    <?php if (!empty($court['gambar'])): ?>
                        <img class="booking-court-image" src="/IkiNet/uploads/<?= htmlspecialchars($court['gambar']) ?>" alt="<?= htmlspecialchars($court['nama_lapangan']) ?>">
                    <?php endif; ?>
                    <div class="booking-court-content">
                        <h3><?= htmlspecialchars($court['nama_lapangan']) ?></h3>
                        <p><?= htmlspecialchars($court['lokasi']) ?></p>
                        <div class="booking-court-meta"><strong>Rp <?= number_format((float) $court['harga_per_jam'], 0, ',', '.') ?>/jam</strong></div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>
