<?php include __DIR__ . '/../layout/navbar.php'; ?>
<?php
if (!$data) {
    die("Data tidak ditemukan.");
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
            <p class="dashboard-kicker">Manajemen Lapangan</p>
            <h1>Edit Lapangan</h1>
            <p>Perbarui data lapangan dan ganti foto jika diperlukan.</p>
        </div>

        <a href="/IkiNet/app/controllers/TransportController.php" class="button secondary">Kembali</a>
    </section>

    <section class="transport-form-layout">
        <aside class="transport-preview-card">
            <?php if (!empty($data['gambar'])): ?>
                <img src="/IkiNet/uploads/<?= htmlspecialchars($data['gambar']) ?>" alt="<?= htmlspecialchars($data['nama_lapangan']) ?>">
            <?php else: ?>
                <div class="preview-empty">Tidak ada gambar</div>
            <?php endif; ?>

            <div>
                <strong><?= htmlspecialchars($data['nama_lapangan']) ?></strong>
                <span><?= htmlspecialchars($data['tipe']) ?> - <?= htmlspecialchars($data['lokasi']) ?></span>
            </div>
        </aside>

        <section class="transport-form-card">
            <form method="POST" action="/IkiNet/app/controllers/TransportController.php?action=update&id=<?= (int) $data['id'] ?>" enctype="multipart/form-data" class="transport-form">
                <div class="form-grid">
                    <label>
                        <span>Nama Lapangan</span>
                        <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($data['nama_lapangan']) ?>" required>
                    </label>

                    <label>
                        <span>Tipe</span>
                        <input type="text" name="tipe" id="tipe" value="<?= htmlspecialchars($data['tipe']) ?>" required>
                    </label>

                    <label>
                        <span>Harga per Jam</span>
                        <input type="number" name="harga_per_jam" id="harga_per_jam" min="0" step="500" value="<?= htmlspecialchars($data['harga_per_jam']) ?>" required>
                    </label>

                    <label>
                        <span>Status</span>
                        <select name="status" id="status" required>
                            <option value="Aktif" <?= $data['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Maintenance" <?= $data['status'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        </select>
                    </label>
                </div>

                <label>
                    <span>Lokasi</span>
                    <input type="text" name="lokasi" id="lokasi" list="lokasi-options" value="<?= htmlspecialchars($data['lokasi']) ?>" required>
                </label>

                <datalist id="lokasi-options">
                    <?php foreach (($locations ?? []) as $location): ?>
                        <option value="<?= htmlspecialchars($location) ?>"></option>
                    <?php endforeach; ?>
                </datalist>

                <label>
                    <span>Deskripsi</span>
                    <input type="text" name="deskripsi" id="deskripsi" value="<?= htmlspecialchars($data['deskripsi'] ?? '') ?>">
                </label>

                <label class="file-field">
                    <span>Ganti Foto</span>
                    <input type="file" name="gambar" id="gambar" accept="image/*">
                    <small>Kosongkan jika tidak ingin mengganti foto.</small>
                </label>

                <div class="form-actions">
                    <button type="submit">Update</button>
                    <a href="/IkiNet/app/controllers/TransportController.php">Batal</a>
                </div>
            </form>
        </section>
    </section>
</div>
