<?php include __DIR__ . '/../layout/navbar.php'; ?>
<?php
$rows = [];
$activeCount = 0;
$maintenanceCount = 0;
$types = [];
$locations = [];

while ($row = $data->fetch_assoc()) {
    $rows[] = $row;
    $types[trim($row['tipe'] ?? '')] = true;
    $locations[trim($row['lokasi'] ?? '')] = true;

    if (($row['status'] ?? '') === 'Maintenance') {
        $maintenanceCount++;
    } else {
        $activeCount++;
    }
}

unset($types[''], $locations['']);
$types = array_keys($types);
$locations = array_keys($locations);
sort($types);
sort($locations);
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
            <h1>Manajemen Lapangan</h1>
            <p>Kelola data lapangan bulutangkis, harga sewa, lokasi, status, dan foto lapangan.</p>
        </div>

        <div class="quick-actions">
            <?php if ($canManage): ?>
                <a href="/IkiNet/app/controllers/TransportController.php?action=create" class="button">Tambah Lapangan</a>
                <a href="/IkiNet/app/controllers/TransportController.php?action=export_excel" class="button secondary">Export Excel</a>
                <a href="/IkiNet/app/controllers/TransportController.php?action=download_template" class="button secondary">Download Template</a>
            <?php endif; ?>
            <a href="/IkiNet/app/controllers/BookingController.php?action=create" class="button secondary">Booking</a>
            <a href="/IkiNet/app/controllers/TransportController.php?action=report" class="button secondary">Laporan</a>
        </div>
    </section>

    <section class="summary-grid">
        <article class="metric-card">
            <span>Aktif</span>
            <strong><?= $activeCount ?></strong>
            <small>Lapangan bisa dibooking</small>
        </article>
        <article class="metric-card">
            <span>Maintenance</span>
            <strong><?= $maintenanceCount ?></strong>
            <small>Sedang tidak tersedia</small>
        </article>

        <article class="metric-card">
            <span>Total Data</span>
            <strong><?= count($rows) ?></strong>
            <small>Lapangan aktif</small>
        </article>
    </section>

    <section class="transport-toolbar">
        <input type="search" id="transportSearch" placeholder="Cari nama, ID, tipe, atau lokasi">
        <select id="transportType">
            <option value="">Semua tipe</option>
            <?php foreach ($types as $type): ?>
                <option value="<?= htmlspecialchars(strtolower($type)) ?>"><?= htmlspecialchars($type) ?></option>
            <?php endforeach; ?>
        </select>
        <select id="transportLocation">
            <option value="">Semua lokasi</option>
            <?php foreach ($locations as $location): ?>
                <option value="<?= htmlspecialchars(strtolower($location)) ?>"><?= htmlspecialchars($location) ?></option>
            <?php endforeach; ?>
        </select>
        <select id="transportCondition">
            <option value="">Semua status</option>
            <option value="aktif">Aktif</option>
            <option value="maintenance">Maintenance</option>
        </select>

        <input type="number" id="minAmount" min="0" placeholder="Harga min">
        <input type="number" id="maxAmount" min="0" placeholder="Harga max">
        <button type="button" id="resetTransportFilter">Reset</button>
    </section>

    <?php if ($canManage): ?>
    <section class="dashboard-card">
        <div class="section-title">
            <h2>Import Data</h2>
            <span>Bulk import</span>
        </div>

        <form method="POST" action="/IkiNet/app/controllers/TransportController.php?action=import_excel" enctype="multipart/form-data" style="display: flex; gap: 10px; align-items: flex-end;">
            <div style="flex: 1;">
                <label>
                    <span>Upload CSV/Excel File</span>
                    <input type="file" name="import_file" accept=".csv,.xlsx,.xls" required>
                </label>
            </div>
            <button type="submit">Import</button>
        </form>
    </section>
    <?php endif; ?>

    <section class="dashboard-card transport-table-card">
        <div class="section-title">
            <h2>Daftar Lapangan</h2>
            <span id="transportVisibleCount"><?= count($rows) ?> data</span>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table" id="transportListTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Harga/Jam</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <?php
                        $status = strtolower(trim($row['status'] ?? ''));
                        ?>
                        <tr
                            data-search="<?= htmlspecialchars(strtolower(($row['id'] ?? '') . ' ' . ($row['nama_lapangan'] ?? '') . ' ' . ($row['tipe'] ?? '') . ' ' . ($row['lokasi'] ?? ''))) ?>"
                            data-type="<?= htmlspecialchars(strtolower($row['tipe'] ?? '')) ?>"
                            data-location="<?= htmlspecialchars(strtolower($row['lokasi'] ?? '')) ?>"
                            data-condition="<?= htmlspecialchars($status) ?>"
                            data-amount="<?= (int) ($row['harga_per_jam'] ?? 0) ?>"
                        >
                            <td>#<?= (int) $row['id'] ?></td>
                            <td>
                                <?php if (!empty($row['gambar'])): ?>
                                    <?php 
                                        $baseName = pathinfo($row['gambar'], PATHINFO_FILENAME);
                                        $thumbPath = '/IkiNet/uploads/' . $baseName . '_thumb.jpg';
                                        $optPath = '/IkiNet/uploads/' . $baseName . '_optimized.jpg';
                                    ?>
                                    <img class="transport-thumb" src="<?= htmlspecialchars($thumbPath) ?>" alt="<?= htmlspecialchars($row['nama_lapangan']) ?>" loading="lazy">
                                <?php else: ?>
                                    <span class="thumb-empty">Tidak ada</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($row['nama_lapangan']) ?></strong></td>
                            <td><?= htmlspecialchars($row['tipe']) ?></td>
                            <td>Rp <?= number_format((float) $row['harga_per_jam'], 0, ',', '.') ?></td>
                            <td>
                                <span class="status-pill <?= $status === 'maintenance' ? 'bad' : 'good' ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['lokasi']) ?></td>
                            <td>
                                <div class="row-actions">
                                    <?php if ($canManage): ?>
                                        <a href="/IkiNet/app/controllers/TransportController.php?action=edit&id=<?= (int) $row['id'] ?>">Edit</a>
                                        <a href="/IkiNet/app/controllers/TransportController.php?action=delete&id=<?= (int) $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus lapangan ini? Data tidak dapat dipulihkan.')">Hapus</a>
                                    <?php else: ?>
                                        <a href="/IkiNet/app/controllers/BookingController.php?action=create">Booking</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
const transportRows = Array.from(document.querySelectorAll('#transportListTable tbody tr'));
const transportSearch = document.getElementById('transportSearch');
const transportType = document.getElementById('transportType');
const transportLocation = document.getElementById('transportLocation');
const transportCondition = document.getElementById('transportCondition');
const minAmount = document.getElementById('minAmount');
const maxAmount = document.getElementById('maxAmount');
const resetTransportFilter = document.getElementById('resetTransportFilter');
const transportVisibleCount = document.getElementById('transportVisibleCount');

function filterTransportRows() {
    const search = transportSearch.value.trim().toLowerCase();
    const type = transportType.value;
    const location = transportLocation.value;
    const condition = transportCondition.value;
    const min = minAmount.value === '' ? null : Number(minAmount.value);
    const max = maxAmount.value === '' ? null : Number(maxAmount.value);
    let total = 0;

    transportRows.forEach((row) => {
        const amount = Number(row.dataset.amount || 0);
        const visible = (!search || row.dataset.search.includes(search))
            && (!type || row.dataset.type === type)
            && (!location || row.dataset.location === location)
            && (!condition || row.dataset.condition === condition)
            && (min === null || amount >= min)
            && (max === null || amount <= max);

        row.hidden = !visible;
        if (visible) total++;
    });

    transportVisibleCount.textContent = total + ' data';
}

[transportSearch, transportType, transportLocation, transportCondition, minAmount, maxAmount].forEach((field) => {
    field.addEventListener('input', filterTransportRows);
});

resetTransportFilter.addEventListener('click', () => {
    transportSearch.value = '';
    transportType.value = '';
    transportLocation.value = '';
    transportCondition.value = '';
    minAmount.value = '';
    maxAmount.value = '';
    filterTransportRows();
});
</script>
