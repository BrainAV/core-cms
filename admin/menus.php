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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menus - Core CMS</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #333; margin: 0; }
        .header { background-color: #1c2a38; color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.5em; margin: 0; }
        .header a { color: #fff; text-decoration: none; margin-left: 15px; }
        .container { padding: 30px; max-width: 800px; margin: 0 auto; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { padding: 8px 15px; background-color: #007bff; color: #fff; text-decoration: none; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9em; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #bd2130; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; font-weight: 600; }
        input[type="text"] { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 200px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Manage Menus</h1>
        <div>
            <a href="index.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

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
</html>