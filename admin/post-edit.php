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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['id'] ? 'Edit Post' : 'New Post'; ?> - Core CMS</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #333; margin: 0; }
        .header { background-color: #1c2a38; color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.5em; margin: 0; }
        .header a { color: #fff; text-decoration: none; margin-left: 15px; }
        .container { padding: 30px; max-width: 800px; margin: 0 auto; }
        .card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], 
        .form-group textarea, 
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        .form-group textarea { height: 300px; resize: vertical; }
        .btn { padding: 12px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 1em; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo $post['id'] ? 'Edit Post' : 'New Post'; ?></h1>
        <div>
            <a href="posts.php">Back to Posts</a>
        </div>
    </div>

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
</html>