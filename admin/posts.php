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
<?php $page_title = 'Manage Posts'; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        tr:hover { background-color: #f1f1f1; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; }
        .status-publish { background-color: #d4edda; color: #155724; }
        .status-draft { background-color: #fff3cd; color: #856404; }
    </style>
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
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>