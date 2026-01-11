<?php
/**
 * Admin: Edit Media Metadata
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
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: media.php");
    exit;
}

// Handle Post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alt_text = trim($_POST['alt_text']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    try {
        $stmt = $pdo->prepare("UPDATE media SET alt_text = ?, title = ?, description = ? WHERE id = ?");
        $stmt->execute([$alt_text, $title, $description, $id]);
        $message = "Media updated successfully!";
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}

// Fetch Media
$stmt = $pdo->prepare("SELECT * FROM media WHERE id = ?");
$stmt->execute([$id]);
$media = $stmt->fetch();

if (!$media) {
    die("Media not found.");
}

?>
<?php $page_title = 'Edit Media'; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    </style>
<div class="container">
    <div class="card">
        <h3>Edit Media Details</h3>
        <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
        <?php if ($error): ?><p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

        <div style="text-align: center; margin-bottom: 20px; background: #f9f9f9; padding: 20px; border-radius: 8px;">
            <?php if (strpos($media['file_type'], 'image') !== false): ?>
                <img src="../<?php echo htmlspecialchars($media['file_path']); ?>" alt="Preview" style="max-width: 100%; max-height: 300px; height: auto;">
            <?php else: ?>
                <div style="font-size: 5em;">📄</div>
            <?php endif; ?>
            <p><strong>Filename:</strong> <?php echo htmlspecialchars($media['file_name']); ?></p>
            <p><strong>URL:</strong> <input type="text" value="<?php echo BASE_URL . '/' . htmlspecialchars($media['file_path']); ?>" readonly style="width: 80%; padding: 5px;" onclick="this.select();"></p>
        </div>

        <form method="post">
            <div class="form-group">
                <label>Alternative Text (Alt Text)</label>
                <input type="text" name="alt_text" value="<?php echo htmlspecialchars($media['alt_text'] ?? ''); ?>" placeholder="Describe the image for screen readers">
                <small style="color: #666;">Important for SEO and Accessibility.</small>
            </div>

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($media['title'] ?? ''); ?>" placeholder="Image Title">
            </div>

            <div class="form-group">
                <label>Description / Caption</label>
                <textarea name="description" rows="3"><?php echo htmlspecialchars($media['description'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn">Update Media</button>
            <a href="media.php" class="btn" style="background-color: #6c757d; margin-left: 10px;">Back to Library</a>
        </form>
    </div>
</div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>