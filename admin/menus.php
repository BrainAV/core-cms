<?php
/**
 * Admin: Manage Menus
 *
 * Create and delete menu containers (e.g., Header, Footer).
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Role Check
if (($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$pdo = get_db_connection();
$message = $_GET['msg'] ?? '';

// --- Handle Form Submission (Create) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);

    // Auto-generate slug if empty
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }

    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO menus (name, slug) VALUES (?, ?)");
            $stmt->execute([$name, $slug]);
            $message = "Menu created successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

// --- Handle Deletion ---
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: menus.php?msg=Menu+deleted");
    exit;
}

// Fetch All Menus
$menus = $pdo->query("SELECT * FROM menus ORDER BY id ASC")->fetchAll();

?>
<?php $page_title = 'Manage Menus'; require_once __DIR__ . '/includes/header.php'; ?>

    <div class="container">
        <!-- Create New Menu -->
        <div class="card">
            <h3>Create New Menu</h3>
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            
            <form method="post" style="display: flex; gap: 15px; align-items: flex-end;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Menu Name</label>
                    <input type="text" name="name" placeholder="e.g. Main Header" required>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Slug (Optional)</label>
                    <input type="text" name="slug" placeholder="e.g. main-header">
                </div>
                <button type="submit" class="btn">Create Menu</button>
            </form>
        </div>

        <!-- List Existing Menus -->
        <div class="card">
            <h3>Existing Menus</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menus as $menu): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($menu['name']); ?></strong></td>
                        <td><code><?php echo htmlspecialchars($menu['slug']); ?></code></td>
                        <td>
                            <a href="menu-edit.php?id=<?php echo $menu['id']; ?>" class="btn">Manage Links</a>
                            <a href="?delete=<?php echo $menu['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure? This will delete all links in this menu.');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($menus)): ?>
                        <tr><td colspan="3" style="text-align: center; color: #777;">No menus found. Create one above!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>