<?php
/**
 * Admin: Manage Pages
 *
 * Lists all pages with options to edit or delete.
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = get_db_connection();

// Fetch pages with author names
$sql = "SELECT p.*, u.display_name 
        FROM posts p 
        LEFT JOIN users u ON p.author_id = u.id 
        WHERE p.post_type = 'page'
        ORDER BY p.created_at DESC";
$stmt = $pdo->query($sql);
$pages = $stmt->fetchAll();

?>
<?php $page_title = 'Manage Pages'; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        tr:hover { background-color: #f1f1f1; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 0.8em; font-weight: bold; text-transform: uppercase; }
        .status-publish { background-color: #d4edda; color: #155724; }
        .status-draft { background-color: #fff3cd; color: #856404; }
        .badge-home { background-color: #007bff; color: white; font-size: 0.7em; padding: 2px 6px; border-radius: 4px; margin-left: 5px; vertical-align: middle; }
    </style>
    <div class="container">
        <div class="card">
            <div class="top-bar">
                <h2>All Pages</h2>
                <a href="post-edit.php?type=page" class="btn">+ Add New Page</a>
            </div>

            <?php if (isset($_GET['status'])): ?>
                <div style="margin-bottom: 15px; color: green; font-weight: bold;">
                    <?php if ($_GET['status'] == 'saved') echo "Page saved successfully."; ?>
                    <?php if ($_GET['status'] == 'deleted') echo "Page deleted successfully."; ?>
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
                    <?php foreach ($pages as $page): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($page['post_title']); ?></strong>
                            <?php if (!empty($page['is_home'])): ?>
                                <span class="badge-home">HOME</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($page['display_name'] ?? 'Unknown'); ?></td>
                        <td><span class="status-badge status-<?php echo $page['post_status']; ?>"><?php echo $page['post_status']; ?></span></td>
                        <td><?php echo date('M j, Y', strtotime($page['created_at'])); ?></td>
                        <td>
                            <a href="post-edit.php?id=<?php echo $page['id']; ?>" class="btn" style="background-color: #6c757d;">Edit</a>
                            <a href="post-delete.php?id=<?php echo $page['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
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