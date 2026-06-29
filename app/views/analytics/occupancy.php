<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="transport-shell">
    <section class="transport-hero">
        <div>
            <p class="dashboard-kicker">Analytics</p>
            <h1>Occupancy Rate</h1>
            <p>Tingkat penggunaan lapangan dalam 30 hari terakhir.</p>
        </div>

        <a href="/IkiNet/app/controllers/AnalyticsController.php?action=dashboard" class="button secondary">Back to Dashboard</a>
    </section>

    <section class="dashboard-card">
        <div class="section-title">
            <h2>Court Occupancy</h2>
            <span><?= count($occupancy) ?> courts</span>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Court Name</th>
                        <th>Booked Slots</th>
                        <th>Total Slots</th>
                        <th>Occupancy Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($occupancy as $court): ?>
                        <?php $rate = (float)$court['occupancy_rate']; ?>
                        <tr>
                            <td><?= htmlspecialchars($court['nama_lapangan']) ?></td>
                            <td><?= (int)$court['booked_slots'] ?></td>
                            <td><?= (int)$court['total_slots'] ?></td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= min(100, $rate) ?>%; background-color: <?php 
                                        echo $rate >= 75 ? '#27ae60' : ($rate >= 50 ? '#f39c12' : '#e74c3c')
                                    ?>"></div>
                                </div>
                                <small><?= number_format($rate, 1) ?>%</small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<style>
.progress-bar {
    width: 100px;
    height: 20px;
    background-color: #ecf0f1;
    border-radius: 4px;
    overflow: hidden;
    display: inline-block;
    margin-right: 10px;
}

.progress-fill {
    height: 100%;
    transition: width 0.3s ease;
}
</style>
