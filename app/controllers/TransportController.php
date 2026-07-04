<?php
require_once __DIR__ . "/../helpers/auth.php";
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../helpers/settings.php";
require_once __DIR__ . "/../helpers/ImageOptimizer.php";
require_once __DIR__ . "/../helpers/ExcelHandler.php";
require_once __DIR__ . "/../models/Transport.php";
require_once __DIR__ . "/../models/AuditLog.php";

class TransportController {
    private $model;
    private $auditLog;
    private $imageOptimizer;
    private $excelHandler;
    private $uploadDir;

    public function __construct($db) {
        requireLogin();
        $this->model = new Transport($db);
        $this->auditLog = new AuditLog($db);
        $this->uploadDir = __DIR__ . "/../../uploads/";
        $this->imageOptimizer = new ImageOptimizer($this->uploadDir);
        $this->excelHandler = new ExcelHandler($db);
    }

    public function index() {
        global $conn;
        $appName = appName($conn);

        if (isAdmin()) {
            $data = $this->model->getAll();
            $canManage = true;
            require __DIR__ . "/../views/transport/index.php";
            return;
        }

        $courts = $this->model->getActive();
        require __DIR__ . "/../views/transport/user.php";
    }

    public function report() {
        global $conn;
        $data = $this->model->getAll();
        $appName = appName($conn);
        require __DIR__ . "/../views/transport/report.php";
    }

    public function exportCsv() {
        $data = $this->model->getAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=laporan_lapangan_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nama Lapangan', 'Tipe', 'Harga per Jam', 'Status', 'Lokasi']);

        while ($row = $data->fetch_assoc()) {
            fputcsv($output, [
                $row['id'] ?? '',
                $row['nama_lapangan'] ?? '',
                $row['tipe'] ?? '',
                $row['harga_per_jam'] ?? '',
                $row['status'] ?? '',
                $row['lokasi'] ?? ''
            ]);
        }

        fclose($output);
        exit();
    }

    public function create() {
        requireAdmin("TransportController.php");
        $locations = $this->model->getLocations();
        require __DIR__ . "/../views/transport/create.php";
    }

    public function store() {
        requireAdmin("TransportController.php");

        try {
            // Validate image upload first
            if (!isset($_FILES["gambar"]) || $_FILES["gambar"]["error"] === UPLOAD_ERR_NO_FILE) {
                throw new Exception("File gambar wajib diupload.");
            }

            $data = $this->validateInput($_POST);
            $imageData = $this->imageOptimizer->processUpload($_FILES["gambar"]);
            
            if (!$imageData || empty($imageData['optimized'])) {
                throw new Exception("Gagal memproses gambar.");
            }
            
            $data['gambar'] = $imageData['optimized'];

            $courtId = $this->model->insert($data);
            
            // Log activity
            $this->auditLog->log(
                $_SESSION['user_id'] ?? 0,
                'CREATE',
                'courts',
                $courtId,
                "Lapangan '{$data['nama']}' ditambahkan",
                null,
                $data
            );

            $this->setFlash("success", "Data lapangan berhasil ditambahkan.");
            $this->redirect("TransportController.php");
        } catch (Exception $exception) {
            $this->setFlash("error", $exception->getMessage());
            $this->redirect("TransportController.php?action=create");
        }
    }

    public function edit($id) {
        requireAdmin("TransportController.php");
        $data = $this->model->getById((int) $id);
        $locations = $this->model->getLocations();
        require __DIR__ . "/../views/transport/edit.php";
    }

    public function update($id) {
        requireAdmin("TransportController.php");
        $id = (int) $id;

        try {
            $data = $this->validateInput($_POST);
            $current = $this->model->getById($id);
            if (!$current) {
                throw new Exception("Data lapangan tidak ditemukan.");
            }

            $oldData = $current;
            
            if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] !== UPLOAD_ERR_NO_FILE) {
                $imageData = $this->imageOptimizer->processUpload($_FILES["gambar"]);
                
                if (!$imageData || empty($imageData['optimized'])) {
                    throw new Exception("Gagal memproses gambar.");
                }
                
                $data['gambar'] = $imageData['optimized'];
                
                // Delete old image
                if ($current['gambar']) {
                    $this->imageOptimizer->deleteImages($current['gambar']);
                }
            }

            $this->model->update($id, $data);
            
            // Log activity
            $this->auditLog->log(
                $_SESSION['user_id'] ?? 0,
                'UPDATE',
                'courts',
                $id,
                "Lapangan '{$data['nama']}' diperbarui",
                $oldData,
                $data
            );

            $this->setFlash("success", "Data lapangan berhasil diperbarui.");
            $this->redirect("TransportController.php");
        } catch (Exception $exception) {
            $this->setFlash("error", $exception->getMessage());
            $this->redirect("TransportController.php?action=edit&id=" . $id);
        }
    }

    public function delete($id) {
        requireAdmin("TransportController.php");
        $id = (int) $id;
        
        $court = $this->model->getById($id);
        if ($court && $court['gambar']) {
            $this->imageOptimizer->deleteImages($court['gambar']);
        }
        
        $this->model->delete($id);
        
        // Log activity
        $this->auditLog->log(
            $_SESSION['user_id'] ?? 0,
            'DELETE',
            'courts',
            $id,
            "Lapangan '{$court['nama_lapangan']}' dihapus",
            $court,
            null
        );
        
        $this->setFlash("success", "Data lapangan berhasil dihapus.");
        $this->redirect("TransportController.php");
    }

    private function validateInput($data) {
        $name = trim($data['nama'] ?? '');
        $type = trim($data['tipe'] ?? '');
        $price = filter_var($data['harga_per_jam'] ?? null, FILTER_VALIDATE_FLOAT);
        $status = trim($data['status'] ?? '');
        $location = trim($data['lokasi'] ?? '');
        $description = trim($data['deskripsi'] ?? '');
        $allowedStatuses = ['Aktif', 'Maintenance'];

        if ($name === '') {
            throw new Exception("Nama lapangan wajib diisi.");
        }

        if ($type === '') {
            throw new Exception("Tipe lapangan wajib diisi.");
        }

        if ($price === false || $price < 0) {
            throw new Exception("Harga per jam harus angka valid.");
        }

        if (!in_array($status, $allowedStatuses, true)) {
            throw new Exception("Status lapangan tidak valid.");
        }

        if ($location === '') {
            throw new Exception("Lokasi lapangan wajib diisi.");
        }

        return [
            'nama' => $name,
            'tipe' => $type,
            'harga_per_jam' => $price,
            'status' => $status,
            'lokasi' => $location,
            'deskripsi' => $description
        ];
    }

    public function exportExcel() {
        requireAdmin("TransportController.php");
        $this->excelHandler->exportCourtsToExcel();
        exit();
    }

    public function importExcel() {
        requireAdmin("TransportController.php");

        try {
            $result = $this->excelHandler->importCourtsFromExcel($_FILES["import_file"] ?? null);
            
            $message = "Berhasil import {$result['imported']} dari {$result['total']} data.";
            if (!empty($result['errors'])) {
                $message .= "\n" . count($result['errors']) . " error: " . implode("\n", array_slice($result['errors'], 0, 5));
            }
            
            $this->setFlash("success", $message);
            $this->redirect("TransportController.php");
        } catch (Exception $exception) {
            $this->setFlash("error", $exception->getMessage());
            $this->redirect("TransportController.php");
        }
    }

    public function downloadTemplate() {
        requireAdmin("TransportController.php");
        $this->excelHandler->getImportTemplate();
        exit();
    }

    public function auditLog() {
        requireAdmin("TransportController.php");
        global $conn;
        $logs = $this->auditLog->getRecent(100);
        $appName = appName($conn);
        require __DIR__ . "/../views/transport/audit_log.php";
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

$controller = new TransportController($conn);
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'edit':
        $controller->edit($_GET['id'] ?? 0);
        break;
    case 'update':
        $controller->update($_GET['id'] ?? 0);
        break;
    case 'delete':
        $controller->delete($_GET['id'] ?? 0);
        break;
    case 'report':
        $controller->report();
        break;
    case 'export_csv':
        $controller->exportCsv();
        break;
    case 'export_excel':
        $controller->exportExcel();
        break;
    case 'import_excel':
        $controller->importExcel();
        break;
    case 'download_template':
        $controller->downloadTemplate();
        break;
    case 'audit_log':
        $controller->auditLog();
        break;
    default:
        $controller->index();
        break;
}
