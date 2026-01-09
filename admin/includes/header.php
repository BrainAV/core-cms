<?php
/**
 * Admin: Header
 *
 * Contains the opening HTML, <head>, and admin navigation.
 */

// Check session status (duplicated from other admin files for safety)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include necessary files
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$pdo = get_db_connection();

// Basic user info for display
$user_id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Core CMS' : 'Core CMS Admin'; ?></title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .admin-nav {
            width: 200px;
            background-color: #1c2a38;
            color: #fff;
            padding: 20px;
        }

        .admin-content {
            flex: 1;
            padding: 30px;
        }

        .admin-nav a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 4px;
        }

        .admin-nav a:hover {
            background-color: #34495e;
        }
        
        /* Common Admin Styles */
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { display: inline-block; padding: 8px 15px; background-color: #007bff; color: #fff; text-decoration: none; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9em; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #bd2130; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; font-weight: 600; }
        input[type="text"], input[type="number"], input[type="email"], textarea, select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>

<div class="admin-container">
    <nav class="admin-nav">
        <h1>Core CMS</h1>
        <a href="index.php">Dashboard</a>
        <a href="posts.php">Manage Posts</a>
        <a href="menus.php">Navigation Menus</a>
        <a href="settings.php">Site Settings</a>
        <a href="logout.php">Logout</a>
    </nav>
    <main class="admin-content">