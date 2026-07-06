<?php
global $conn;
if (!isset($conn) || !($conn instanceof mysqli)) {
    require __DIR__ . "/../../../config/database.php";
}

require_once __DIR__ . "/../../helpers/settings.php";
require_once __DIR__ . "/../../helpers/auth.php";
$appName = appName($conn);
$isAdmin = isAdmin();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

function isNavActive($targetPath, $currentPath) {
    $target = rtrim($targetPath, '/');
    $current = rtrim($currentPath, '/');
    return $current === $target || strpos($current, $target) === 0;
}
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
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 14px rgba(0,0,0,0.05);
}

.nav-title {
    font-size: 20px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.nav-menu {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.nav-menu a {
    margin-left: 0;
    text-decoration: none;
    color: #111;
    position: relative;
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 999px;
    transition: background 0.2s ease, color 0.2s ease;
}

.nav-menu a:hover,
.nav-menu a.active {
    background: #111;
    color: #fff;
}

.nav-menu a::after {
    display: none;
}
</style>

<div class="navbar">
    <div class="nav-title"><?= htmlspecialchars($appName) ?></div>

    <div class="nav-menu">
        <?php if ($isAdmin): ?>
            <a href="/IkiNet/app/controllers/DashboardController.php" class="<?= isNavActive('/IkiNet/app/controllers/DashboardController.php', $currentPath) ? 'active' : '' ?>">Dashboard Admin</a>
            <a href="/IkiNet/app/controllers/TransportController.php" class="<?= isNavActive('/IkiNet/app/controllers/TransportController.php', $currentPath) ? 'active' : '' ?>">Lapangan</a>
            <a href="/IkiNet/app/controllers/BookingController.php" class="<?= isNavActive('/IkiNet/app/controllers/BookingController.php', $currentPath) ? 'active' : '' ?>">Reservasi</a>
            <a href="/IkiNet/app/controllers/AnalyticsController.php" class="<?= isNavActive('/IkiNet/app/controllers/AnalyticsController.php', $currentPath) ? 'active' : '' ?>">Analytics</a>
            <a href="/IkiNet/app/controllers/TransportController.php?action=audit_log" class="<?= isNavActive('/IkiNet/app/controllers/TransportController.php', $currentPath) && strpos($currentPath, 'audit_log') !== false ? 'active' : '' ?>">Audit Log</a>
            <a href="/IkiNet/app/controllers/SettingController.php" class="<?= isNavActive('/IkiNet/app/controllers/SettingController.php', $currentPath) ? 'active' : '' ?>">Settings</a>
        <?php else: ?>
            <a href="/IkiNet/app/controllers/BookingController.php?action=user">Booking Saya</a>
            <a href="/IkiNet/app/controllers/BookingController.php?action=create">Booking Baru</a>
            <a href="/IkiNet/app/controllers/ProfileController.php">Profil</a>
        <?php endif; ?>
        <a href="/IkiNet/app/views/logout.php">Logout</a>
    </div>
</div>
