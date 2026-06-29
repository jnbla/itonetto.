<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../helpers/settings.php";
require_once __DIR__ . "/../models/Booking.php";

$bookingModel = new Booking($conn);
$bookingId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$booking = $bookingId ? $bookingModel->getById($bookingId) : null;
$appName = appName($conn);

require __DIR__ . "/../views/booking/receipt.php";
