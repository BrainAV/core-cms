<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id && $id != $_SESSION['user_id']) {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: users.php?msg=User+deleted");
exit;