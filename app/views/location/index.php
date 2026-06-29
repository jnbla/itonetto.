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
            <p class="dashboard-kicker">Master Data</p>
            <h1>Lokasi</h1>
            <p>Tambahkan lokasi agar muncul sebagai pilihan saat membuat atau mengedit data transport.</p>
        </div>

        <a href="/IkiNet/app/controllers/TransportController.php?action=create" class="button secondary">Tambah Transport</a>
    </section>

    <section class="location-layout">
        <?php $canManageLocation = in_array(strtolower($_SESSION["role"] ?? "Administrator"), ["administrator", "admin"], true); ?>

        <?php if ($canManageLocation): ?>
            <article class="transport-form-card">
                <div class="section-title">
                    <h2>Tambah Lokasi</h2>
                    <span>Baru</span>
                </div>

                <form method="POST" action="/IkiNet/app/controllers/LocationController.php?action=store" class="transport-form">
                    <label>
                        <span>Nama Lokasi</span>
                        <input type="text" name="nama_lokasi" placeholder="Contoh: Terminal 3" required>
                    </label>

                    <div class="form-actions">
                        <button type="submit">Simpan</button>
                    </div>
                </form>
            </article>
        <?php else: ?>
            <article class="dashboard-card">
                <div class="section-title">
                    <h2>Akses</h2>
                    <span>Staff</span>
                </div>
                <p class="muted">Akun staff hanya dapat melihat daftar lokasi.</p>
            </article>
        <?php endif; ?>

        <article class="dashboard-card">
            <div class="section-title">
                <h2>Daftar Lokasi</h2>
                <span><?= count($locations) ?> data</span>
            </div>

            <?php if ($locations): ?>
                <div class="location-list">
                    <?php foreach ($locations as $location): ?>
                        <div class="location-item">
                            <strong><?= htmlspecialchars($location['nama_lokasi']) ?></strong>
                            <?php if ($canManageLocation): ?>
                                <a href="/IkiNet/app/controllers/LocationController.php?action=delete&id=<?= (int) $location['id'] ?>" onclick="return confirm('Hapus lokasi ini?')">Hapus</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="muted">Belum ada lokasi manual. Tambahkan lokasi pertama dari form ini.</p>
            <?php endif; ?>
        </article>

        <?php if ($canManageLocation && !empty($archivedLocations)): ?>
            <article class="dashboard-card">
                <div class="section-title">
                    <h2>Lokasi Terarsip</h2>
                    <span><?= count($archivedLocations) ?> data</span>
                </div>

                <div class="location-list">
                    <?php foreach ($archivedLocations as $location): ?>
                        <div class="location-item">
                            <strong><?= htmlspecialchars($location['nama_lokasi']) ?></strong>
                            <small class="muted">Dihapus: <?= date('d/m/Y H:i', strtotime($location['deleted_at'])) ?></small>
                            <a href="/IkiNet/app/controllers/LocationController.php?action=restore&id=<?= (int) $location['id'] ?>" onclick="return confirm('Pulihkan lokasi ini?')">Pulihkan</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
        <?php endif; ?>
    </section>
</div>
