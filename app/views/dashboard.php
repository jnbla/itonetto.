<?php
function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if (!isset($dashboardData)) {
    require_once __DIR__ . "/../../config/database.php";
    require_once __DIR__ . "/../models/Dashboard.php";
    require_once __DIR__ . "/../helpers/settings.php";
    require_once __DIR__ . "/../helpers/QRCode.php";

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

    <section class="admin-dashboard-header">
        <div class="admin-header-copy">
            <p class="dashboard-kicker"><?= date('d F Y') ?></p>
            <h1><?= h($appName) ?></h1>
            <p>Selamat datang, <?= h($_SESSION["user"]) ?>. Kelola booking, lapangan, dan aktivitas operasional dari satu panel utama.</p>
        </div>

        <div class="admin-header-actions">
            <a class="button" href="../controllers/BookingController.php?action=create">Buat Booking</a>
            <a class="button secondary" href="../controllers/TransportController.php">Kelola Lapangan</a>
        </div>
    </section>

    <section class="dashboard-main">
        <div class="summary-grid">
            <article class="metric-card">
                <span>Total Lapangan</span>
                <strong><?= $totalCourts ?></strong>
                <small>Jumlah lapangan tersedia</small>
            </article>
            <article class="metric-card">
                <span>Lapangan Aktif</span>
                <strong><?= $activeCourts ?></strong>
                <small>Siap digunakan</small>
            </article>
            <article class="metric-card alert">
                <span>Menunggu</span>
                <strong><?= $waitingBookings ?></strong>
                <small>Perlu konfirmasi</small>
            </article>
            <article class="metric-card">
                <span>Disetujui</span>
                <strong><?= $approvedBookings ?></strong>
                <small>Booking berjalan</small>
            </article>
        </div>

        <div class="panel-grid">
            <main class="dashboard-panel">
                <section class="dashboard-card">
                    <div class="section-title">
                        <h2>Daftar Lapangan</h2>
                        <span id="filteredCount"><?= $totalCourts ?> data</span>
                    </div>
                    <div class="dashboard-filter simple-filter">
                        <input id="searchInput" type="search" placeholder="Cari lapangan atau ID..." />
                        <select id="conditionFilter">
                            <option value="">Semua Status</option>
                            <option value="active">Active</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
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
                </section>

                <section class="dashboard-card">
                    <div class="section-title">
                        <h2>Aktivitas Terbaru</h2>
                        <span><?= count($recentBookings) ?> item</span>
                    </div>
                    <div class="alert-list compact">
                        <?php foreach ($recentBookings as $booking): ?>
                            <div class="notification-item">
                                <strong><?= h($booking['nama_lapangan']) ?></strong>
                                <span><?= h($booking['username']) ?> — <?= h(date('d M Y', strtotime($booking['tanggal']))) ?>, <?= h(substr($booking['jam_mulai'], 0, 5)) ?></span>
                            </div>
                        <?php endforeach; ?>
                        <?php if (!$recentBookings): ?>
                            <p>Belum ada reservasi.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </main>

            <aside class="dashboard-side">
                <section class="dashboard-card">
                    <div class="section-title">
                        <h2>Upcoming Booking</h2>
                    </div>
                    <?php $upcoming = $recentBookings[0] ?? null; ?>
                    <?php if ($upcoming): ?>
                        <div class="booking-overview-preview">
                            <div><strong><?= h($upcoming['nama_lapangan']) ?></strong></div>
                            <div><?= h($upcoming['username']) ?> — <?= h(date('d M Y', strtotime($upcoming['tanggal']))) ?>, <?= h(substr($upcoming['jam_mulai'], 0, 5)) ?></div>
                            <?php if (function_exists('bookingReceiptUrl')): ?>
                                <img src="<?= h(bookingReceiptUrl($upcoming['id'] ?? $upcoming['booking_id'])) ?>" alt="QR" class="booking-qr-preview" />
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p>Tidak ada booking mendatang.</p>
                    <?php endif; ?>
                </section>

                <section class="dashboard-card">
                    <div class="section-title">
                        <h2>Profil Singkat</h2>
                    </div>
                    <div class="profile-card simple-profile-card">
                        <div class="profile-avatar"><?= h(strtoupper(substr($_SESSION["user"], 0, 1))) ?></div>
                        <div>
                            <strong><?= h($_SESSION["user"]) ?></strong>
                            <span><?= h($userRole) ?></span>
                            <a href="/IkiNet/app/views/logout.php">Logout</a>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
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
