<?php
/**
 * API Endpoint: Image Upload for Editor.js
 */

session_start();
require_once __DIR__ . '/../../config/db.php';

// Response Header
header('Content-Type: application/json');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => 0, 'error' => 'Unauthorized']);
    exit;
}

$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // Editor.js sends the file as 'image' by default
    $file = $_FILES['image'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_mime = mime_content_type($file['tmp_name']);

        if (in_array($file_mime, $allowed_types)) {
            // Prepare directory
            $year = date('Y');
            $month = date('m');
            // Note: ../../ because we are in admin/api/
            $upload_dir = __DIR__ . "/../../uploads/$year/$month";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Sanitize filename
            $filename = basename($file['name']);
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

            // Prevent overwrites
            $target_path = "$upload_dir/$filename";
            $i = 1;
            while (file_exists($target_path)) {
                $info = pathinfo($filename);
                $filename = $info['filename'] . "-$i." . $info['extension'];
                $target_path = "$upload_dir/$filename";
                $i++;
            }

            // Move file
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                // Save to DB
                $db_path = "uploads/$year/$month/$filename";

                try {
                    $stmt = $pdo->prepare("INSERT INTO media (file_name, file_path, file_type) VALUES (?, ?, ?)");
                    $stmt->execute([$filename, $db_path, $file_mime]);
                    
                    // Success Response for Editor.js
                    echo json_encode([
                        'success' => 1,
                        'file' => [
                            'url' => BASE_URL . '/' . $db_path
                        ]
                    ]);
                } catch (PDOException $e) {
                    echo json_encode(['success' => 0, 'error' => 'Database Error']);
                    // Cleanup
                    unlink($target_path);
                }
            } else {
                echo json_encode(['success' => 0, 'error' => 'Failed to move file']);
            }
        } else {
            echo json_encode(['success' => 0, 'error' => 'Invalid file type']);
        }
    } else {
        echo json_encode(['success' => 0, 'error' => 'Upload error code: ' . $file['error']]);
    }
} else {
    echo json_encode(['success' => 0, 'error' => 'No file uploaded']);
}