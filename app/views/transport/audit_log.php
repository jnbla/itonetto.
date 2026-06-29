<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="transport-shell">
    <section class="transport-hero">
        <div>
            <p class="dashboard-kicker">Admin Tools</p>
            <h1>Activity Log</h1>
            <p>Lihat semua perubahan data yang telah dilakukan di sistem.</p>
        </div>
    </section>

    <section class="dashboard-card">
        <div class="section-title">
            <h2>Recent Activities</h2>
            <span><?= count($logs) ?> log</span>
        </div>

        <div class="table-scroll">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>ID</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <small><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></small>
                            </td>
                            <td><?= htmlspecialchars($log['username'] ?? 'System') ?></td>
                            <td>
                                <span class="status-pill <?php 
                                    echo strtolower($log['action']) === 'create' ? 'good' : 
                                         (strtolower($log['action']) === 'delete' ? 'bad' : 'neutral')
                                ?>">
                                    <?= htmlspecialchars($log['action']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($log['entity_type']) ?></td>
                            <td>#<?= (int)$log['entity_id'] ?></td>
                            <td><?= htmlspecialchars($log['description'] ?? '-') ?></td>
                            <td><small><?= htmlspecialchars($log['ip_address'] ?? '-') ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
