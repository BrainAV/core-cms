<?php
/**
 * API Endpoint: List Media for Editor.js
 */
session_start();
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => 0, 'error' => 'Unauthorized']);
    exit;
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->query("SELECT id, file_name, file_path, file_type FROM media WHERE file_type LIKE 'image/%' ORDER BY uploaded_at DESC");
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => 1, 'items' => $media]);
} catch (Exception $e) {
    echo json_encode(['success' => 0, 'error' => $e->getMessage()]);
}
