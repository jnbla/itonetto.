<?php
session_start();
include "../../config/database.php";
require_once __DIR__ . "/../helpers/settings.php";

$message = "";
$messageType = "error";
$appName = appName($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';

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

            header("Location: ../controllers/DashboardController.php");
            exit();
        }

        $message = "Password salah.";
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
                <div class="auth-message <?= $messageType ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required autofocus>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Login</button>
            </form>

            <p class="auth-switch">
                Belum punya akun? <a href="register.php">Daftar</a>
            </p>
        </section>
    </main>
</body>
</html>
