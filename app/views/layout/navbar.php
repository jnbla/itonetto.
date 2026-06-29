<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $conn;
if (!isset($conn) || !($conn instanceof mysqli)) {
    require __DIR__ . "/../../../config/database.php";
}

require_once __DIR__ . "/../../helpers/settings.php";
require_once __DIR__ . "/../../helpers/auth.php";
$appName = appName($conn);
$isAdmin = isAdmin();
?>

<?php include __DIR__ . "/style.php"; ?>

<style>
.navbar {
    position: sticky;
    top: 0;
    z-index: 999;
    width: 100%;
    box-sizing: border-box;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding: 16px 32px;
    background: #fff;
}

.nav-title {
    font-size: 20px;
    font-weight: 500;
}

.nav-menu a {
    margin-left: 25px;
    text-decoration: none;
    color: black;
    position: relative;
    font-size: 14px;
}

.nav-menu a::after {
    content: "";
    position: absolute;
    width: 0%;
    height: 1px;
    left: 0;
    bottom: -2px;
    background: black;
    transition: 0.3s;
}

.nav-menu a:hover::after {
    width: 100%;
}
</style>

<div class="navbar">
    <div class="nav-title"><?= htmlspecialchars($appName) ?></div>

    <div class="nav-menu">
        <a href="/IkiNet/app/controllers/DashboardController.php">Dashboard</a>
        <a href="/IkiNet/app/controllers/TransportController.php">Lapangan</a>
        <a href="/IkiNet/app/controllers/BookingController.php">Reservasi</a>
        <?php if ($isAdmin): ?>
            <a href="/IkiNet/app/controllers/AnalyticsController.php">Analytics</a>
            <a href="/IkiNet/app/controllers/TransportController.php?action=audit_log">Audit Log</a>
        <?php endif; ?>
        <a href="/IkiNet/app/controllers/SettingController.php">Settings</a>
        <a href="/IkiNet/app/views/logout.php">Logout</a>
    </div>
</div>
