<?php
function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if (!isset($dashboardData)) {
    require_once __DIR__ . "/../../config/database.php";
    require_once __DIR__ . "/../models/Dashboard.php";
    require_once __DIR__ . "/../helpers/settings.php";

    $dashboard = new Dashboard($conn);
    $dashboardData = $dashboard->getData();
}

extract($dashboardData);
$userRole = $_SESSION["role"] ?? "user";
$canManageDashboard = in_array(strtolower($userRole), ['admin', 'administrator'], true);
$appName = appName($conn);
?>

<?php include __DIR__ . "/layout/navbar.php"; ?>

<div class="dashboard-shell">
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash-message <?= h($_SESSION['flash']['type']) ?>">
            <?= h($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <section class="dashboard-hero">
        <div>
            <p class="dashboard-kicker"><?= date('d F Y') ?></p>
            <h1><?= h($appName) ?></h1>
            <p>Selamat datang, <?= h($_SESSION["user"]) ?>. Kelola lapangan dan reservasi bulutangkis dari satu tempat.</p>
        </div>

        <div class="profile-card">
            <div class="profile-avatar"><?= h(strtoupper(substr($_SESSION["user"], 0, 1))) ?></div>
            <div>
                <strong><?= h($_SESSION["user"]) ?></strong>
                <span><?= h($userRole) ?></span>
            </div>
            <a href="/IkiNet/app/views/logout.php">Logout</a>
        </div>
    </section>

    <section class="summary-grid">
        <article class="metric-card">
            <span>Total Lapangan</span>
            <strong><?= $totalCourts ?></strong>
            <small>Data aktif sistem</small>
        </article>
        <article class="metric-card">
            <span>Siap Booking</span>
            <strong><?= $activeCourts ?></strong>
            <small>Lapangan aktif</small>
        </article>
        <article class="metric-card alert">
            <span>Menunggu</span>
            <strong><?= $waitingBookings ?></strong>
            <small>Reservasi perlu konfirmasi</small>
        </article>
        <article class="metric-card">
            <span>Disetujui</span>
            <strong><?= $approvedBookings ?></strong>
            <small>Reservasi berjalan</small>
        </article>
    </section>

    <section class="dashboard-tools">
        <form class="dashboard-filter" id="dashboardFilter">
            <input type="search" id="searchInput" placeholder="Cari nama, ID, tipe, atau lokasi lapangan">
            <select id="typeFilter">
                <option value="">Semua tipe</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= h(strtolower($type)) ?>"><?= h($type) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="conditionFilter">
                <option value="">Semua status</option>
                <option value="aktif">Aktif</option>
                <option value="maintenance">Maintenance</option>
            </select>
            <select id="locationFilter">
                <option value="">Semua lokasi</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= h(strtolower($location)) ?>"><?= h($location) ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <div class="quick-actions">
            <a class="button" href="../controllers/BookingController.php?action=create">Buat Booking</a>
            <a class="button secondary" href="../controllers/BookingController.php?action=user">Booking Saya</a>
            <a class="button secondary" href="../controllers/TransportController.php">Kelola Lapangan</a>
        </div>
    </section>

    <section class="dashboard-grid two-columns">
        <article class="dashboard-card">
            <div class="section-title">
                <h2>Status Booking</h2>
                <span>Ringkasan</span>
            </div>
            <div class="type-stats">
                <?php foreach ($bookingStatus as $status => $count): ?>
                    <div>
                        <strong><?= h($status) ?></strong>
                        <span><?= (int) $count ?> reservasi</span>
                        <progress value="<?= (int) $count ?>" max="<?= max($bookingStatus ?: [1]) ?>"></progress>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>

        <article class="dashboard-card">
            <div class="section-title">
                <h2>Lapangan per Lokasi</h2>
                <span>Bar</span>
            </div>
            <div class="bar-list">
                <?php foreach (array_slice($byLocation, 0, 6, true) as $location => $count): ?>
                    <div class="bar-row">
                        <span><?= h($location) ?></span>
                        <div><b style="width: <?= max(6, ($count / $maxLocation) * 100) ?>%;"></b></div>
                        <em><?= $count ?></em>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
    </section>

    <section class="dashboard-grid two-columns">
        <article class="dashboard-card">
            <div class="section-title">
                <h2>Tren Booking</h2>
                <span>Bulanan</span>
            </div>
            <div class="line-chart">
                <?php $index = 0; $totalMonths = max(1, count($monthly) - 1); ?>
                <?php foreach ($monthly as $month => $count): ?>
                    <?php
                    $left = ($index / $totalMonths) * 100;
                    $bottom = $lineMax > 0 ? ($count / $lineMax) * 78 : 0;
                    ?>
                    <span class="line-point" style="left: <?= $left ?>%; bottom: <?= $bottom ?>%;" title="<?= h($month) ?>: <?= $count ?>"></span>
                    <?php $index++; ?>
                <?php endforeach; ?>
            </div>
            <div class="line-labels">
                <?php foreach ($monthly as $month => $count): ?>
                    <span><?= h($month) ?></span>
                <?php endforeach; ?>
            </div>
        </article>

        <article class="dashboard-card">
            <div class="section-title">
                <h2>Tipe Lapangan</h2>
                <span>Statistik</span>
            </div>
            <div class="type-stats">
                <?php foreach (array_slice($byType, 0, 5, true) as $type => $count): ?>
                    <div>
                        <strong><?= h($type) ?></strong>
                        <span><?= $count ?> lapangan</span>
                        <progress value="<?= $count ?>" max="<?= $maxType ?>"></progress>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
    </section>

    <section class="dashboard-grid three-columns">
        <article class="dashboard-card wide">
            <div class="section-title">
                <h2>Daftar Lapangan</h2>
                <span id="filteredCount"><?= $totalCourts ?> data</span>
            </div>
            <table class="dashboard-table" id="transportTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courts as $row): ?>
                        <tr
                            data-id="<?= (int) $row['id'] ?>"
                            data-name="<?= h(strtolower($row['nama_lapangan'] ?? '')) ?>"
                            data-type="<?= h(strtolower($row['tipe'] ?? '')) ?>"
                            data-condition="<?= h(strtolower($row['status'] ?? '')) ?>"
                            data-location="<?= h(strtolower($row['lokasi'] ?? '')) ?>"
                        >
                            <td>#<?= (int) $row['id'] ?></td>
                            <td><?= h($row['nama_lapangan'] ?? '-') ?></td>
                            <td><?= h($row['tipe'] ?? '-') ?></td>
                            <td><span class="status-pill <?= strtolower($row['status'] ?? '') === 'maintenance' ? 'bad' : 'good' ?>"><?= h($row['status'] ?? '-') ?></span></td>
                            <td><?= h($row['lokasi'] ?? '-') ?></td>
                            <td>
                                <?php if ($canManageDashboard): ?>
                                    <a href="../controllers/TransportController.php?action=edit&id=<?= (int) $row['id'] ?>">Edit</a>
                                <?php else: ?>
                                    <a href="../controllers/BookingController.php?action=create">Booking</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </article>

        <article class="dashboard-card">
            <div class="section-title">
                <h2>Reservasi Terbaru</h2>
                <span><?= count($recentBookings) ?> data</span>
            </div>
            <div class="alert-list">
                <?php foreach ($recentBookings as $booking): ?>
                    <a href="../controllers/BookingController.php">
                        <strong><?= h($booking['nama_lapangan']) ?></strong>
                        <span><?= h($booking['username']) ?> - <?= h(date('d M Y', strtotime($booking['tanggal']))) ?>, <?= h(substr($booking['jam_mulai'], 0, 5)) ?></span>
                    </a>
                <?php endforeach; ?>
                <?php if (!$recentBookings): ?>
                    <p>Belum ada reservasi.</p>
                <?php endif; ?>
            </div>
        </article>
    </section>
</div>

<script>
const rows = Array.from(document.querySelectorAll('#transportTable tbody tr'));
const searchInput = document.getElementById('searchInput');
const typeFilter = document.getElementById('typeFilter');
const conditionFilter = document.getElementById('conditionFilter');
const locationFilter = document.getElementById('locationFilter');
const filteredCount = document.getElementById('filteredCount');

function applyDashboardFilter() {
    const search = searchInput.value.trim().toLowerCase();
    const type = typeFilter.value;
    const condition = conditionFilter.value;
    const location = locationFilter.value;
    let visible = 0;

    rows.forEach((row) => {
        const matchesSearch = !search || row.dataset.name.includes(search) || row.dataset.id.includes(search);
        const matchesType = !type || row.dataset.type === type;
        const matchesCondition = !condition || row.dataset.condition === condition;
        const matchesLocation = !location || row.dataset.location === location;
        const shouldShow = matchesSearch && matchesType && matchesCondition && matchesLocation;

        row.hidden = !shouldShow;
        if (shouldShow) visible++;
    });

    filteredCount.textContent = visible + ' data';
}

[searchInput, typeFilter, conditionFilter, locationFilter].forEach((field) => {
    field.addEventListener('input', applyDashboardFilter);
});
</script>
