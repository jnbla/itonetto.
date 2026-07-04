<?php
require_once __DIR__ . "/../helpers/auth.php";
requireLogin('../views/login.php');

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Dashboard.php";
require_once __DIR__ . "/../helpers/settings.php";

$dashboard = new Dashboard($conn);
$dashboardData = $dashboard->getData();

require __DIR__ . "/../views/dashboard.php";
