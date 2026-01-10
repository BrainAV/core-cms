<?php
/**
 * Admin: Media Library
 *
 * Upload and manage files.
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = get_db_connection();
$message = '';
$error = '';

// --- Handle Upload ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        $file_mime = mime_content_type($file['tmp_name']);
        
        if (in_array($file_mime, $allowed_types)) {
            // Prepare directory
            $year = date('Y');
            $month = date('m');
            $upload_dir = __DIR__ . "/../uploads/$year/$month";
            
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
                // Store relative path for frontend use
                $db_path = "uploads/$year/$month/$filename";
                
                try {
                    $stmt = $pdo->prepare("INSERT INTO media (file_name, file_path, file_type) VALUES (?, ?, ?)");
                    $stmt->execute([$filename, $db_path, $file_mime]);
                    $message = "File uploaded successfully!";
                } catch (PDOException $e) {
                    $error = "Database Error: " . $e->getMessage();
                    // Attempt to clean up file if DB fails
                    unlink($target_path);
                }
            } else {
                $error = "Failed to move uploaded file.";
            }
        } else {
            $error = "Invalid file type. Allowed: JPG, PNG, GIF, WEBP, PDF.";
        }
    } else {
        $error = "Upload error code: " . $file['error'];
    }
}

// --- Handle Delete ---
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Get path
    $stmt = $pdo->prepare("SELECT file_path FROM media WHERE id = ?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();
    
    if ($media) {
        $full_path = __DIR__ . '/../' . $media['file_path'];
        
        // Delete from DB
        $stmt = $pdo->prepare("DELETE FROM media WHERE id = ?");
        $stmt->execute([$id]);
        
        // Delete file
        if (file_exists($full_path)) {
            unlink($full_path);
        }
        
        header("Location: media.php?msg=deleted");
        exit;
    }
}

// Fetch Media
$media_items = $pdo->query("SELECT * FROM media ORDER BY uploaded_at DESC")->fetchAll();

?>
<?php $page_title = 'Media Library'; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 20px; margin-top: 20px; }
        .media-item { border: 1px solid #ddd; border-radius: 4px; padding: 10px; background: #fff; text-align: center; position: relative; }
        .media-item img { max-width: 100%; height: auto; max-height: 100px; object-fit: cover; }
        .media-item .file-icon { font-size: 3em; color: #ccc; display: block; margin-bottom: 10px; }
        .media-item .filename { font-size: 0.8em; word-break: break-all; margin-bottom: 10px; display: block; }
        .media-actions { margin-top: 10px; }
        .upload-area { border: 2px dashed #ccc; padding: 30px; text-align: center; background: #f9f9f9; border-radius: 8px; margin-bottom: 20px; }
    </style>

    <div class="container">
        <div class="card">
            <h3>Upload New File</h3>
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            <?php if ($error): ?><p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?><p style="color: green;">File deleted.</p><?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="upload-area">
                <input type="file" name="file" required>
                <button type="submit" class="btn">Upload</button>
                <p style="font-size: 0.9em; color: #666; margin-top: 10px;">Allowed: JPG, PNG, GIF, WEBP, PDF</p>
            </form>
        </div>

        <div class="card">
            <h3>Library</h3>
            <div class="media-grid">
                <?php foreach ($media_items as $item): ?>
                    <div class="media-item">
                        <?php if (strpos($item['file_type'], 'image') !== false): ?>
                            <img src="../<?php echo htmlspecialchars($item['file_path']); ?>" alt="<?php echo htmlspecialchars($item['file_name']); ?>">
                        <?php else: ?>
                            <div class="file-icon">📄</div>
                        <?php endif; ?>
                        
                        <span class="filename"><?php echo htmlspecialchars($item['file_name']); ?></span>
                        
                        <div class="media-actions">
                            <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8em;" onclick="return confirm('Delete this file?');">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($media_items)): ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: #777;">No files found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>