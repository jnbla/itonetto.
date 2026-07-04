<?php
require_once __DIR__ . "/../app/helpers/auth.php";

requireLogin('/IkiNet/app/views/login.php');

if (isAdmin()) {
    header("Location: /IkiNet/app/controllers/TransportController.php");
    exit();
}

header("Location: /IkiNet/app/controllers/BookingController.php?action=user");
exit();
