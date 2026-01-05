<?php
/**
 * Global Configuration
 */

// 1. Project Constants
define('ROOT_PATH', dirname(__DIR__)); // Points to the root folder

// Simple base URL detection (can be hardcoded if needed)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . "://" . $host);

// 2. Timezone (Critical for Event Planner)
date_default_timezone_set('UTC'); // We will store in UTC, display in local

// 3. Error Handling
define('DEBUG_MODE', true); // Set to false in Production

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}