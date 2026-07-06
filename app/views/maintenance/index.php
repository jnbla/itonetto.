<?php include __DIR__ . '/../layout/navbar.php'; ?>
<?php
$transportOptions = [];
while ($transport = $transports->fetch_assoc()) {
    if ((int) ($transport['is_deleted'] ?? 0) === 0) {
        $transportOptions[] = $transport;
    }
}

$rows = [];
$scheduled = 0;
$done = 0;
$canceled = 0;
while ($row = $maintenances->fetch_assoc()) {
    $rows[] = $row;
    if ($row['status'] === 'Selesai') {
        $done++;
    } elseif ($row['status'] === 'Dibatalkan') {
        $canceled++;
    } else {
        $scheduled++;
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
            <p class="dashboard-kicker">Maintenance</p>
            <h1>Jadwal Maintenance</h1>
            <p>Kelola jadwal perawatan transportasi dan pantau status pekerjaan.</p>
        </div>
    </section>

    <section class="summary-grid">
        <article class="metric-card">
            <span>Total Jadwal</span>
            <strong><?= count($rows) ?></strong>
            <small>Seluruh maintenance</small>
        </article>
        <article class="metric-card">
            <span>Terjadwal</span>
            <strong><?= $scheduled ?></strong>
            <small>Menunggu pengerjaan</small>
        </article>
        <article class="metric-card">
            <span>Selesai</span>
            <strong><?= $done ?></strong>
            <small>Sudah dikerjakan</small>
        </article>
        <article class="metric-card alert">
            <span>Dibatalkan</span>
            <strong><?= $canceled ?></strong>
            <small>Tidak berjalan</small>
        </article>
    </section>

    <section class="dashboard-card">
        <div class="section-title">
            <h2>Info singkat</h2>
            <span>Prioritas</span>
        </div>
        <p class="muted">Perbarui status maintenance dari tabel untuk menjaga lapangan tetap siap dipakai dan menghindari penumpukan jadwal yang tertunda.</p>
    </section>

    <section class="location-layout">
        <?php if ($canManage): ?>
            <article class="transport-form-card">
                <div class="section-title">
                    <h2>Tambah Jadwal</h2>
                    <span>Baru</span>
                </div>

                <form method="POST" action="/IkiNet/app/controllers/MaintenanceController.php?action=store" class="transport-form">
                    <label>
                        <span>Transport</span>
                        <select name="transport_id" required>
                            <option value="">Pilih transport</option>
                            <?php foreach ($transportOptions as $transport): ?>
                                <option value="<?= (int) $transport['id'] ?>">
                                    <?= htmlspecialchars($transport['nama_kendaraan']) ?> - <?= htmlspecialchars($transport['lokasi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>
                        <span>Tanggal</span>
                        <input type="date" name="tanggal" required>
                    </label>

                    <label>
                        <span>Status</span>
                        <select name="status" required>
                            <option value="Terjadwal">Terjadwal</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </label>

                    <label>
                        <span>Keterangan</span>
                        <input type="text" name="keterangan" placeholder="Contoh: Service rutin bulanan" required>
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
                <p class="muted">Akun staff hanya dapat melihat jadwal maintenance.</p>
            </article>
        <?php endif; ?>

        <article class="dashboard-card">
            <div class="section-title">
                <h2>Daftar Jadwal</h2>
                <span><?= count($rows) ?> data</span>
            </div>

            <div class="table-scroll">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Transport</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <?php if ($canManage): ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($row['tanggal']))) ?></td>
                                <td><?= htmlspecialchars($row['nama_kendaraan'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['lokasi'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                <td><span class="status-pill <?= $row['status'] === 'Selesai' ? 'good' : 'bad' ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                                <?php if ($canManage): ?>
                                    <td>
                                        <div class="row-actions">
                                            <form method="POST" action="/IkiNet/app/controllers/MaintenanceController.php?action=update_status&id=<?= (int) $row['id'] ?>" class="inline-form">
                                                <select name="status" onchange="this.form.submit()">
                                                    <option value="Terjadwal" <?= $row['status'] === 'Terjadwal' ? 'selected' : '' ?>>Terjadwal</option>
                                                    <option value="Selesai" <?= $row['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                                    <option value="Dibatalkan" <?= $row['status'] === 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                                </select>
                                            </form>
                                            <a href="/IkiNet/app/controllers/MaintenanceController.php?action=delete&id=<?= (int) $row['id'] ?>" onclick="return confirm('Hapus jadwal maintenance ini?')">Hapus</a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</div>
