<?php
require_once __DIR__ . "/../helpers/auth.php";
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../helpers/settings.php";
require_once __DIR__ . "/../models/Location.php";

class LocationController {
    private $model;

    public function __construct($db) {
        requireLogin();
        $this->model = new Location($db);
    }

    public function index() {
        global $conn;
        $locations = $this->model->getAll();
        $archivedLocations = $this->model->getArchived();
        $canManage = isAdmin();
        $appName = appName($conn);
        require __DIR__ . "/../views/location/index.php";
    }

    public function store() {
        requireAdmin("LocationController.php");

        try {
            $name = trim($_POST['nama_lokasi'] ?? '');
            if ($name === '') {
                throw new Exception("Nama lokasi wajib diisi.");
            }

            $this->model->insert($name);
            $this->setFlash("success", "Lokasi berhasil ditambahkan.");
            $this->redirect("LocationController.php");
        } catch (Exception $exception) {
            $this->setFlash("error", $exception->getMessage());
            $this->redirect("LocationController.php");
        }
    }

    public function delete($id) {
        requireAdmin("LocationController.php");
        $id = (int) $id;
        
        try {
            $location = $this->model->getById($id);
            if (!$location) {
                throw new Exception("Lokasi tidak ditemukan.");
            }
            
            $this->model->delete($id);
            $this->setFlash("success", "Lokasi '" . htmlspecialchars($location['nama_lokasi']) . "' berhasil diarsipkan.");
            $this->redirect("LocationController.php");
        } catch (Exception $exception) {
            $this->setFlash("error", $exception->getMessage());
            $this->redirect("LocationController.php");
        }
    }

    public function restore($id) {
        requireAdmin("LocationController.php");
        $id = (int) $id;
        
        try {
            $location = $this->model->getById($id);
            if (!$location) {
                throw new Exception("Lokasi tidak ditemukan.");
            }
            
            $this->model->restore($id);
            $this->setFlash("success", "Lokasi '" . htmlspecialchars($location['nama_lokasi']) . "' berhasil dipulihkan.");
            $this->redirect("LocationController.php");
        } catch (Exception $exception) {
            $this->setFlash("error", $exception->getMessage());
            $this->redirect("LocationController.php");
        }
    }

    private function setFlash($type, $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function redirect($location) {
        header("Location: /IkiNet/app/controllers/" . $location);
        exit();
    }
}

// Route handling
$action = $_GET['action'] ?? 'index';
$controller = new LocationController($conn);

switch ($action) {
    case 'store':
        $controller->store();
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id) $controller->delete($id);
        break;
    case 'restore':
        $id = $_GET['id'] ?? null;
        if ($id) $controller->restore($id);
        break;
    default:
        $controller->index();
}
