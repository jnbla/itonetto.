<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="transport-shell">
    <section class="transport-hero">
        <div>
            <p class="dashboard-kicker">Analytics</p>
            <h1>Monthly Comparison</h1>
            <p>Perbandingan performa month-by-month.</p>
        </div>

        <a href="/IkiNet/app/controllers/AnalyticsController.php?action=dashboard" class="button secondary">Back to Dashboard</a>
    </section>

    <section class="dashboard-card">
        <div class="section-title">
            <h2>Monthly Revenue & Bookings</h2>
            <span><?= count($monthlyComparison) ?> months</span>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Avg per Booking</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthlyComparison as $month): ?>
                        <?php 
                            $bookings = (int)$month['bookings'];
                            $revenue = (float)$month['revenue'];
                            $avg = $bookings > 0 ? $revenue / $bookings : 0;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($month['month']) ?></td>
                            <td><?= $bookings ?></td>
                            <td>Rp <?= number_format($revenue, 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($avg, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
