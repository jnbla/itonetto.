<?php include __DIR__ . '/../layout/navbar.php'; ?>
<?php
$courtRows = [];
while ($row = $courts->fetch_assoc()) {
    $courtRows[] = $row;
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
            <p class="dashboard-kicker">Reservasi</p>
            <h1>Buat Booking</h1>
            <p>Pilih lapangan tersedia dan atur jadwalmu dengan cepat. Booking yang bentrok akan otomatis dicegah.</p>
        </div>

        <a href="/IkiNet/app/controllers/BookingController.php" class="button secondary">Kembali</a>
    </section>

    <section class="transport-form-layout">
        <div class="transport-form-card">
            <form method="POST" action="/IkiNet/app/controllers/BookingController.php?action=store" class="transport-form">
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

                <label>
                    <span>Catatan</span>
                    <input type="text" name="catatan" placeholder="Opsional">
                </label>

                <label>
                    <span>No. WhatsApp</span>
                    <input type="tel" name="whatsapp_number" placeholder="Contoh: 081234567890">
                </label>
            </div>

            <div class="form-actions">
                <button type="submit">Ajukan Booking</button>
                <a href="/IkiNet/app/controllers/BookingController.php">Batal</a>
            </div>
        </form>
        </div>

        <aside class="transport-preview-card">
            <div class="section-title">
                <h2>Lapangan Aktif</h2>
                <span>Pilih lapangan terbaik untuk jadwalmu</span>
            </div>
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
        </aside>
    </section>
</div>
