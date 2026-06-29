<?php
include "../../config/database.php";
require_once __DIR__ . "/../helpers/settings.php";

$message = "";
$messageType = "error";
$appName = appName($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $rawPassword = $_POST["password"] ?? '';

    if ($username === '' || strlen($rawPassword) < 6 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Username, email, dan password minimal 6 karakter wajib valid.";
    } else {
        $cek = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $cek->bind_param("ss", $username, $email);
        $cek->execute();
        $result = $cek->get_result();

        if ($result->num_rows > 0) {
            $message = "Username atau email sudah digunakan.";
        } else {
            $countResult = $conn->query("SELECT COUNT(*) AS total FROM users");
            $countRow = $countResult ? $countResult->fetch_assoc() : ['total' => 1];
            $role = ((int) ($countRow['total'] ?? 1) === 0) ? 'admin' : 'user';
            $password = password_hash($rawPassword, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password, $role);

            if ($stmt->execute()) {
                $message = $role === 'admin'
                    ? "Registrasi berhasil. Akun pertama otomatis menjadi admin."
                    : "Registrasi berhasil. Silakan login.";
                $messageType = "success";
            } else {
                $message = "Registrasi gagal.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?= htmlspecialchars($appName) ?></title>
    <?php include __DIR__ . "/layout/style.php"; ?>
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-panel">
            <p class="auth-kicker"><?= htmlspecialchars($appName) ?></p>
            <h1>Register</h1>

            <?php if ($message): ?>
                <div class="auth-message <?= $messageType ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required autofocus>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" minlength="6" required>

                <button type="submit">Daftar</button>
            </form>

            <p class="auth-switch">
                Sudah punya akun? <a href="login.php">Login</a>
            </p>
        </section>
    </main>
</body>
</html>
