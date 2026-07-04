<?php
require_once __DIR__ . "/../helpers/auth.php";
requireLogin('../views/login.php');

if (isAdmin()) {
    header("Location: /IkiNet/app/controllers/TransportController.php");
    exit();
}

header("Location: /IkiNet/app/controllers/BookingController.php");
exit();
