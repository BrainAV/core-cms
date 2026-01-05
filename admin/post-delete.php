<?php
/**
 * Admin: Delete Post Handler
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $pdo = get_db_connection();
    // Prepare DELETE statement
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

// Redirect back to list
header("Location: posts.php?status=deleted");
exit;