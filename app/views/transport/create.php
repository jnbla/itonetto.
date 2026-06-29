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
            <p class="dashboard-kicker">Manajemen Lapangan</p>
            <h1>Tambah Lapangan</h1>
            <p>Masukkan data lapangan bulutangkis beserta harga, lokasi, status, dan foto.</p>
        </div>

        <a href="/IkiNet/app/controllers/TransportController.php" class="button secondary">Kembali</a>
    </section>

    <section class="transport-form-card">
        <form method="POST" action="/IkiNet/app/controllers/TransportController.php?action=store" enctype="multipart/form-data" class="transport-form">
            <div class="form-grid">
                <label>
                    <span>Nama Lapangan</span>
                    <input type="text" name="nama" id="nama" required>
                </label>

                <label>
                    <span>Tipe</span>
                    <input type="text" name="tipe" id="tipe" placeholder="Indoor, Outdoor, VIP" required>
                </label>

                <label>
                    <span>Harga per Jam</span>
                    <input type="number" name="harga_per_jam" id="harga_per_jam" min="0" step="500" required>
                </label>

                <label>
                    <span>Status</span>
                    <select name="status" id="status" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </label>
            </div>

            <label>
                <span>Lokasi</span>
                <input type="text" name="lokasi" id="lokasi" list="lokasi-options" placeholder="Pilih atau ketik lokasi baru" required>
            </label>

            <datalist id="lokasi-options">
                <?php foreach (($locations ?? []) as $location): ?>
                    <option value="<?= htmlspecialchars($location) ?>"></option>
                <?php endforeach; ?>
            </datalist>

            <label>
                <span>Deskripsi</span>
                <input type="text" name="deskripsi" id="deskripsi" placeholder="Contoh: karpet vinyl, tersedia shuttlecock, lampu LED">
            </label>

            <label class="file-field">
                <span>Foto Lapangan</span>
                <input type="file" name="gambar" id="gambar" accept="image/*" required>
                <small>Format JPG, PNG, atau WEBP, maksimal 2 MB.</small>
            </label>

            <div class="form-actions">
                <button type="submit">Simpan</button>
                <a href="/IkiNet/app/controllers/TransportController.php">Batal</a>
            </div>
        </form>
    </section>
</div>
