<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../views/login.php");
    exit();
}

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Setting.php";

class SettingController {
    private $model;

    public function __construct($db) {
        $this->model = new Setting($db);
    }

    public function index() {
        $settings = $this->model->getAll();
        require __DIR__ . "/../views/settings/index.php";
    }

    public function update() {
        if (!$this->canManage()) {
            $this->setFlash("error", "Akses ditolak. Hanya Administrator yang dapat mengubah settings.");
            $this->redirect("SettingController.php");
        }

        try {
            $this->model->update($_POST);
            $this->setFlash("success", "Settings berhasil diperbarui.");
        } catch (Exception $exception) {
            $this->setFlash("error", $exception->getMessage());
        }

        $this->redirect("SettingController.php");
    }

    private function canManage() {
        $role = strtolower($_SESSION["role"] ?? "user");
        return in_array($role, ["administrator", "admin"], true);
    }

    private function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    private function redirect($url) {
        header("Location: /IkiNet/app/controllers/" . $url);
        exit();
    }
}

$controller = new SettingController($conn);
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'update':
        $controller->update();
        break;

    default:
        $controller->index();
        break;
}
