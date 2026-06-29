<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: /IkiNet/app/views/login.php");
    exit();
} else {
    header("Location: /IkiNet/app/controllers/DashboardController.php");
    exit();
}
