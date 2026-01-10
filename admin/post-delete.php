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

$redirect = 'posts.php';

if (isset($_GET['id'])) {
    $pdo = get_db_connection();
    
    // Check type before deleting to know where to redirect
    $stmt = $pdo->prepare("SELECT post_type FROM posts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $post = $stmt->fetch();
    if ($post && $post['post_type'] === 'page') {
        $redirect = 'pages.php';
    }

    // Prepare DELETE statement
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

// Redirect back to list
header("Location: $redirect?status=deleted");
exit;