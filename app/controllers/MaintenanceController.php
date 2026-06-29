<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: ../views/login.php");
    exit();
}

header("Location: /IkiNet/app/controllers/BookingController.php");
exit();
