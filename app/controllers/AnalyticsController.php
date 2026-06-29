<?php
session_start();

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../helpers/auth.php";
require_once __DIR__ . "/../helpers/settings.php";
require_once __DIR__ . "/../models/Analytics.php";

class AnalyticsController {
    private $analytics;

    public function __construct($db) {
        requireLogin();
        requireAdmin("index.php");
        $this->analytics = new Analytics($db);
    }

    public function dashboard() {
        global $conn;
        
        $totalRevenue = $this->analytics->getTotalRevenue();
        $weeklyRevenue = $this->analytics->getRevenueByPeriod(7);
        $mostBooked = $this->analytics->getMostBookedCourts(5);
        $occupancy = $this->analytics->getOccupancyRate(7);
        $peakHours = $this->analytics->getPeakHours();
        $bookingStats = $this->analytics->getBookingStats();
        $courtStats = $this->analytics->getCourtStats();
        $monthlyComparison = $this->analytics->getMonthlyComparison();
        
        $appName = appName($conn);
        require __DIR__ . "/../views/analytics/dashboard.php";
    }

    public function monthly() {
        global $conn;
        
        $monthlyComparison = $this->analytics->getMonthlyComparison();
        $appName = appName($conn);
        require __DIR__ . "/../views/analytics/monthly.php";
    }

    public function occupancy() {
        global $conn;
        
        $occupancy = $this->analytics->getOccupancyRate(30);
        $appName = appName($conn);
        require __DIR__ . "/../views/analytics/occupancy.php";
    }
}

$controller = new AnalyticsController($conn);
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'monthly':
        $controller->monthly();
        break;
    case 'occupancy':
        $controller->occupancy();
        break;
    default:
        $controller->dashboard();
        break;
}
