<?php
/**
 * Admin: Manage Users
 */
session_start();
require_once __DIR__ . '/../config/db.php';

// Auth & Role Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$pdo = get_db_connection();
$users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();
?>
<?php $page_title = 'Manage Users'; require_once __DIR__ . '/includes/header.php'; ?>
<div class="container">
    <div class="card">
        <div class="top-bar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>Users</h2>
            <a href="user-edit.php" class="btn">+ Add New User</a>
        </div>
        
        <?php if (isset($_GET['msg'])): ?>
            <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($_GET['msg']); ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td>
                        <?php if ($u['avatar']): ?>
                            <img src="../<?php echo htmlspecialchars($u['avatar']); ?>" style="width:24px; height:24px; border-radius:50%; vertical-align:middle; margin-right:5px;">
                        <?php endif; ?>
                        <?php echo htmlspecialchars($u['display_name']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($u['user_email']); ?></td>
                    <td><span style="text-transform:capitalize; background:#eee; padding:2px 6px; border-radius:4px; font-size:0.85em;"><?php echo htmlspecialchars($u['role']); ?></span></td>
                    <td>
                        <a href="user-edit.php?id=<?php echo $u['id']; ?>" class="btn" style="background-color: #6c757d; padding: 4px 8px; font-size: 0.8em;">Edit</a>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <a href="user-delete.php?id=<?php echo $u['id']; ?>" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8em;" onclick="return confirm('Delete this user?');">Delete</a>
                        <?php endif; ?>
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