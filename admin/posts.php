<?php
/**
 * Admin: Manage Posts
 *
 * Lists all posts with options to edit or delete.
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = get_db_connection();

// Fetch posts with author names
$sql = "SELECT p.*, u.display_name 
        FROM posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        ORDER BY p.created_at DESC";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts - Core CMS</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #333; margin: 0; }
        .header { background-color: #1c2a38; color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.5em; margin: 0; }
        .header a { color: #fff; text-decoration: none; margin-left: 15px; }
        .container { padding: 30px; max-width: 1000px; margin: 0 auto; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 15px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.9em; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #bd2130; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; font-weight: 600; }
        tr:hover { background-color: #f1f1f1; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; }
        .status-publish { background-color: #d4edda; color: #155724; }
        .status-draft { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Manage Posts</h1>
        <div>
            <a href="index.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="top-bar">
                <h2>All Posts</h2>
                <a href="post-edit.php" class="btn">+ Add New Post</a>
            </div>

            <?php if (isset($_GET['status'])): ?>
                <div style="margin-bottom: 15px; color: green; font-weight: bold;">
                    <?php if ($_GET['status'] == 'saved') echo "Post saved successfully."; ?>
                    <?php if ($_GET['status'] == 'deleted') echo "Post deleted successfully."; ?>
                </div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($post['post_title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($post['display_name'] ?? 'Unknown'); ?></td>
                        <td><span class="status-badge status-<?php echo $post['post_status']; ?>"><?php echo $post['post_status']; ?></span></td>
                        <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                        <td>
                            <a href="post-edit.php?id=<?php echo $post['id']; ?>" class="btn" style="background-color: #6c757d;">Edit</a>
                            <a href="post-delete.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>