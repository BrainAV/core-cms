<?php
/**
 * Admin: Edit/Create Post
 *
 * Handles form submission for adding or updating posts.
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = get_db_connection();
$error = '';
$post = [
    'id' => '',
    'post_title' => '',
    'post_slug' => '',
    'post_content' => '',
    'post_status' => 'publish'
];

// --- Handle POST Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['post_title']);
    $slug = trim($_POST['post_slug']);
    $content = $_POST['post_content'];
    $status = $_POST['post_status'];
    $author_id = $_SESSION['user_id'];

    // Auto-generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }

    if (empty($title)) {
        $error = "Title is required.";
    } else {
        try {
            if ($id) {
                // UPDATE existing post
                $stmt = $pdo->prepare("UPDATE posts SET post_title = ?, post_slug = ?, post_content = ?, post_status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $content, $status, $id]);
            } else {
                // INSERT new post
                $stmt = $pdo->prepare("INSERT INTO posts (author_id, post_title, post_slug, post_content, post_status) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$author_id, $title, $slug, $content, $status]);
            }
            header("Location: posts.php?status=saved");
            exit;
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// --- Handle GET (Edit Mode) ---
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $fetched_post = $stmt->fetch();
    if ($fetched_post) {
        $post = $fetched_post;
    }
}
?>
<?php $page_title = $post['id'] ? 'Edit Post' : 'New Post'; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; box-sizing: border-box; }
        .form-group textarea { height: 300px; resize: vertical; }
        .error { color: red; margin-bottom: 15px; }
    </style>
    <div class="container">
        <div class="card">
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">

                <div class="form-group">
                    <label for="post_title">Title</label>
                    <input type="text" id="post_title" name="post_title" value="<?php echo htmlspecialchars($post['post_title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="post_slug">Slug (URL Friendly Name)</label>
                    <input type="text" id="post_slug" name="post_slug" value="<?php echo htmlspecialchars($post['post_slug']); ?>" placeholder="Leave empty to auto-generate">
                </div>

                <div class="form-group">
                    <label for="post_content">Content</label>
                    <textarea id="post_content" name="post_content"><?php echo htmlspecialchars($post['post_content']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="post_status">Status</label>
                    <select id="post_status" name="post_status">
                        <option value="publish" <?php echo $post['post_status'] === 'publish' ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo $post['post_status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="archived" <?php echo $post['post_status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>

                <button type="submit" class="btn">Save Post</button>
            </form>
        </div>
    </div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>