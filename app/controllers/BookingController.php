<?php
session_start();

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../helpers/auth.php";
require_once __DIR__ . "/../helpers/settings.php";
require_once __DIR__ . "/../helpers/WhatsAppNotifier.php";
require_once __DIR__ . "/../models/Booking.php";
require_once __DIR__ . "/../models/Transport.php";

class BookingController {
    private $bookingModel;
    private $courtModel;
    private $whatsAppNotifier;

    public function __construct($db) {
        requireLogin();
        $this->bookingModel = new Booking($db);
        $this->courtModel = new Transport($db);
        $this->whatsAppNotifier = new WhatsAppNotifier($db);
    }

    public function index() {
        global $conn;
        $bookings = isAdmin()
            ? $this->bookingModel->getAll()
            : $this->bookingModel->getByUser(currentUserId());
        $canManage = isAdmin();
        $appName = appName($conn);
        require __DIR__ . "/../views/booking/index.php";
    }

    public function create() {
        $courts = $this->courtModel->getActive();
        require __DIR__ . "/../views/booking/create.php";
    }

    public function store() {
        try {
            $courtId = filter_var($_POST['court_id'] ?? null, FILTER_VALIDATE_INT);
            $date = trim($_POST['tanggal'] ?? '');
            $start = trim($_POST['jam_mulai'] ?? '');
            $end = trim($_POST['jam_selesai'] ?? '');
            $note = trim($_POST['catatan'] ?? '');
            $whatsappNumber = trim($_POST['whatsapp_number'] ?? '');

            if (!$courtId) {
                throw new Exception("Lapangan wajib dipilih.");
            }

            $court = $this->courtModel->getById($courtId);
            if (!$court || (int) ($court['is_deleted'] ?? 0) === 1 || ($court['status'] ?? '') !== 'Aktif') {
                throw new Exception("Lapangan tidak tersedia.");
            }

            if (!$this->isValidDate($date) || strtotime($date) < strtotime(date('Y-m-d'))) {
                throw new Exception("Tanggal booking tidak valid.");
            }

            if (!$this->isValidTime($start) || !$this->isValidTime($end) || $start >= $end) {
                throw new Exception("Jam booking tidak valid.");
            }

            if ($this->bookingModel->hasConflict($courtId, $date, $start, $end)) {
                throw new Exception("Jadwal bentrok dengan reservasi lain.");
            }

            $hours = (strtotime($date . ' ' . $end) - strtotime($date . ' ' . $start)) / 3600;
            $total = $hours * (float) $court['harga_per_jam'];

            $bookingId = $this->bookingModel->insert([
                'user_id' => currentUserId(),
                'court_id' => $courtId,
                'tanggal' => $date,
                'jam_mulai' => $start,
                'jam_selesai' => $end,
                'total_harga' => $total,
                'status' => 'Menunggu',
                'catatan' => $note,
                'whatsapp_number' => $whatsappNumber
            ]);

            if (!$bookingId) {
                throw new Exception("Reservasi gagal dibuat.");
            }

            $this->whatsAppNotifier->notifyBookingCreated([
                'id' => $bookingId,
                'tanggal' => $date,
                'jam_mulai' => $start,
                'jam_selesai' => $end,
                'total_harga' => $total,
                'status' => 'Menunggu',
                'whatsapp_number' => $whatsappNumber
            ], $court, currentUsername());

            $this->setFlash("success", "Reservasi berhasil dibuat dan menunggu persetujuan admin.");
            $this->redirect("BookingController.php");
        } catch (Exception $exception) {
            $this->setFlash("error", $exception->getMessage());
            $this->redirect("BookingController.php?action=create");
        }
    }

    public function updateStatus($id) {
        requireAdmin("BookingController.php");
        $status = trim($_POST['status'] ?? '');
        $allowed = ['Menunggu', 'Disetujui', 'Dibatalkan', 'Selesai'];

        if (!in_array($status, $allowed, true)) {
            $this->setFlash("error", "Status reservasi tidak valid.");
            $this->redirect("BookingController.php");
        }

        $this->bookingModel->updateStatus((int) $id, $status);
        $booking = $this->bookingModel->getById((int) $id);
        if ($booking) {
            $this->whatsAppNotifier->notifyStatusChanged($booking);
        }

        $this->setFlash("success", "Status reservasi berhasil diperbarui.");
        $this->redirect("BookingController.php");
    }

    public function cancel($id) {
        $this->bookingModel->cancelByUser((int) $id, currentUserId());
        $this->setFlash("success", "Reservasi berhasil dibatalkan.");
        $this->redirect("BookingController.php");
    }

    private function isValidDate($date) {
        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        return $parsed && $parsed->format('Y-m-d') === $date;
    }

    private function isValidTime($time) {
        return (bool) preg_match('/^\d{2}:\d{2}$/', $time);
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

$controller = new BookingController($conn);
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'status':
        $controller->updateStatus($_GET['id'] ?? 0);
        break;
    case 'cancel':
        $controller->cancel($_GET['id'] ?? 0);
        break;
    default:
        $controller->index();
        break;
}
