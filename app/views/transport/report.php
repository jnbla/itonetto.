<?php include __DIR__ . '/../layout/navbar.php'; ?>
<?php
$rows = [];
$active = 0;
$maintenance = 0;
$totalRate = 0;

while ($row = $data->fetch_assoc()) {
    if ((int) ($row['is_deleted'] ?? 0) === 1) {
        continue;
    }

    $rows[] = $row;
    $totalRate += (float) ($row['harga_per_jam'] ?? 0);

    if (($row['status'] ?? '') === 'Maintenance') {
        $maintenance++;
    } else {
        $active++;
    }
}
?>

<div class="transport-shell report-page">
    <section class="transport-hero">
        <div>
            <p class="dashboard-kicker"><?= date('d F Y') ?></p>
            <h1>Laporan Lapangan</h1>
            <p>Ringkasan data lapangan bulutangkis aktif untuk kebutuhan cetak atau arsip.</p>
        </div>

        <div class="quick-actions no-print">
            <button type="button" onclick="window.print()">Print</button>
            <a href="/IkiNet/app/controllers/TransportController.php?action=export_csv" class="button secondary">Export CSV</a>
            <a href="/IkiNet/app/controllers/TransportController.php" class="button secondary">Kembali</a>
        </div>
    </section>

    <section class="summary-grid">
        <article class="metric-card">
            <span>Total Lapangan</span>
            <strong><?= count($rows) ?></strong>
            <small>Data tidak diarsipkan</small>
        </article>
        <article class="metric-card">
            <span>Aktif</span>
            <strong><?= $active ?></strong>
            <small>Bisa dibooking</small>
        </article>
        <article class="metric-card alert">
            <span>Maintenance</span>
            <strong><?= $maintenance ?></strong>
            <small>Sementara tidak tersedia</small>
        </article>
        <article class="metric-card">
            <span>Rata-rata Harga</span>
            <strong>Rp <?= count($rows) ? number_format($totalRate / count($rows), 0, ',', '.') : 0 ?></strong>
            <small>Per jam</small>
        </article>
    </section>

    <section class="dashboard-card transport-table-card">
        <div class="section-title">
            <h2>Detail Lapangan</h2>
            <span><?= count($rows) ?> data</span>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Harga/Jam</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td>#<?= (int) $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama_lapangan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['tipe'] ?? '-') ?></td>
                            <td>Rp <?= number_format((float) ($row['harga_per_jam'] ?? 0), 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['status'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['lokasi'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
