<?php
include "../../config/database.php";
require_once __DIR__ . "/../helpers/auth.php";
require_once __DIR__ . "/../helpers/settings.php";

ensureSessionStarted();
if (isset($_SESSION['user'])) {
    if (isAdmin()) {
        header('Location: /IkiNet/app/controllers/TransportController.php');
        exit();
    }
    header('Location: /IkiNet/app/controllers/BookingController.php?action=user');
    exit();
}

$message = "";
$messageType = "error";
$appName = appName($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';
    $destination = $_POST["destination"] ?? 'booking';

    $query = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = (int) $user["id"];
            $_SESSION["user"] = $user["username"];
            $_SESSION["role"] = $user["role"] ?? "user";

            if ($destination === 'admin') {
                if (in_array(strtolower($_SESSION["role"] ?? 'user'), ['admin', 'administrator'], true)) {
                    header("Location: ../controllers/TransportController.php");
                    exit();
                }
                $message = "Hanya admin yang dapat mengakses halaman admin.";
            } else {
                header("Location: ../controllers/BookingController.php?action=user");
                exit();
            }
        } else {
            $message = "Password salah.";
        }
    } else {
        $message = "User tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= htmlspecialchars($appName) ?></title>
    <?php include __DIR__ . "/layout/style.php"; ?>
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-panel">
            <p class="auth-kicker"><?= htmlspecialchars($appName) ?></p>
            <h1>Login</h1>

            <?php if ($message): ?>
                <div class="auth-message <?= htmlspecialchars($messageType) ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required autofocus>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <label for="destination">Masuk Sebagai</label>
                <select name="destination" id="destination">
                    <option value="booking">Booking Lapangan</option>
                    <option value="admin">Admin</option>
                </select>

                <button type="submit">Login</button>
            </form>

            <p class="auth-switch">
                Belum punya akun? <a href="register.php">Daftar</a>
            </p>
        </section>
    </main>
</body>
</html>
