<?php
function ensureSessionStarted() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function currentUserId() {
    ensureSessionStarted();
    return (int) ($_SESSION['user_id'] ?? 0);
}

function currentUsername() {
    ensureSessionStarted();
    return $_SESSION['user'] ?? '';
}

function currentUserRole() {
    ensureSessionStarted();
    return strtolower($_SESSION['role'] ?? 'user');
}

function isAdmin() {
    return in_array(currentUserRole(), ['admin', 'administrator'], true);
}

function requireLogin($redirect = '../views/login.php') {
    ensureSessionStarted();
    if (!isset($_SESSION['user'])) {
        header('Location: ' . $redirect);
        exit();
    }
}

function requireAdmin($fallback = 'DashboardController.php') {
    requireLogin();
    if (!isAdmin()) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Akses ditolak. Fitur ini hanya untuk admin.'
        ];
        header('Location: ' . $fallback);
        exit();
    }
}
