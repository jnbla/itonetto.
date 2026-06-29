<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="transport-shell">
    <section class="transport-hero">
        <div>
            <p class="dashboard-kicker"><?= htmlspecialchars($appName ?? 'Badminton Court Booking') ?></p>
            <h1>Analytics Dashboard</h1>
            <p>Analisis performa lapangan, revenue, dan booking patterns.</p>
        </div>

        <div class="quick-actions">
            <a href="/IkiNet/app/controllers/AnalyticsController.php?action=monthly" class="button secondary">Monthly Report</a>
            <a href="/IkiNet/app/controllers/AnalyticsController.php?action=occupancy" class="button secondary">Occupancy Rate</a>
        </div>
    </section>

    <!-- Revenue Summary -->
    <section class="summary-grid">
        <article class="metric-card">
            <span>Total Revenue</span>
            <strong>Rp <?= number_format((float)$totalRevenue, 0, ',', '.') ?></strong>
            <small>All-time</small>
        </article>
        <article class="metric-card">
            <span>This Week</span>
            <strong>Rp <?= number_format((float)array_sum(array_column($weeklyRevenue, 'revenue')), 0, ',', '.') ?></strong>
            <small>Last 7 days</small>
        </article>
        <article class="metric-card">
            <span>Total Bookings</span>
            <strong><?= array_sum(array_values($bookingStats)) ?></strong>
            <small>All-time</small>
        </article>
        <article class="metric-card">
            <span>Active Courts</span>
            <strong><?= $courtStats['active'] ?? 0 ?></strong>
            <small><?= $courtStats['total'] ?? 0 ?> Total</small>
        </article>
    </section>

    <!-- Booking Status Breakdown -->
    <section class="dashboard-grid-2">
        <article class="dashboard-card">
            <div class="section-title">
                <h2>Booking Status</h2>
            </div>
            <div class="stats-list">
                <div class="stat-item">
                    <span>Pending</span>
                    <strong><?= $bookingStats['Menunggu'] ?? 0 ?></strong>
                </div>
                <div class="stat-item">
                    <span>Approved</span>
                    <strong><?= $bookingStats['Disetujui'] ?? 0 ?></strong>
                </div>
                <div class="stat-item">
                    <span>Cancelled</span>
                    <strong><?= $bookingStats['Dibatalkan'] ?? 0 ?></strong>
                </div>
                <div class="stat-item">
                    <span>Completed</span>
                    <strong><?= $bookingStats['Selesai'] ?? 0 ?></strong>
                </div>
            </div>
        </article>

        <article class="dashboard-card">
            <div class="section-title">
                <h2>Court Status</h2>
            </div>
            <div class="stats-list">
                <div class="stat-item">
                    <span>Active</span>
                    <strong><?= $courtStats['active'] ?? 0 ?></strong>
                </div>
                <div class="stat-item">
                    <span>Maintenance</span>
                    <strong><?= $courtStats['maintenance'] ?? 0 ?></strong>
                </div>
                <div class="stat-item">
                    <span>Deleted</span>
                    <strong><?= $courtStats['deleted'] ?? 0 ?></strong>
                </div>
            </div>
        </article>
    </section>

    <!-- Most Booked Courts -->
    <section class="dashboard-card">
        <div class="section-title">
            <h2>Most Booked Courts</h2>
            <span><?= count($mostBooked) ?> courts</span>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Court Name</th>
                        <th>Location</th>
                        <th>Total Bookings</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mostBooked as $court): ?>
                        <tr>
                            <td><?= htmlspecialchars($court['nama_lapangan']) ?></td>
                            <td><?= htmlspecialchars($court['lokasi']) ?></td>
                            <td><?= (int)$court['total_bookings'] ?></td>
                            <td>Rp <?= number_format((float)$court['total_revenue'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Peak Hours -->
    <section class="dashboard-card">
        <div class="section-title">
            <h2>Peak Booking Hours</h2>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Hour</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($peakHours, 0, 10) as $hour): ?>
                        <tr>
                            <td><?= str_pad((int)$hour['hour'], 2, '0', STR_PAD_LEFT) ?>:00</td>
                            <td><?= (int)$hour['booking_count'] ?></td>
                            <td>Rp <?= number_format((float)$hour['revenue'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
