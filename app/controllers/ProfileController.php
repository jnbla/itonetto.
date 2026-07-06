<?php
require_once __DIR__ . "/../helpers/auth.php";
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/User.php";

class ProfileController {
    private $model;

    public function __construct($db) {
        requireLogin();
        $this->model = new User($db);
    }

    public function edit() {
        $user = $this->model->getById(currentUserId());
        require __DIR__ . "/../views/profile/edit.php";
    }

    public function update() {
        try {
            $userId = currentUserId();
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            if ($username === '' || $email === '') {
                throw new Exception("Username dan email wajib diisi.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email tidak valid.");
            }

            if ($password !== '' && $password !== $confirmPassword) {
                throw new Exception("Password dan konfirmasi password tidak cocok.");
            }

            $this->model->updateProfile($userId, $username, $email, $password !== '' ? $password : null);

            $_SESSION['user'] = $username;
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Profil berhasil diperbarui.'
            ];
        } catch (Exception $exception) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => $exception->getMessage()
            ];
        }

        $this->redirect('ProfileController.php');
    }

    private function redirect($url) {
        header("Location: /IkiNet/app/controllers/" . $url);
        exit();
    }
}

$controller = new ProfileController($conn);
$action = $_GET['action'] ?? 'edit';

switch ($action) {
    case 'update':
        $controller->update();
        break;
    default:
        $controller->edit();
        break;
}
