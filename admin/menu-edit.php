<?php
/**
 * Admin: Manage Menu Items
 *
 * Add, remove, and reorder links within a specific menu.
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
$menu_id = $_GET['id'] ?? null;

if (!$menu_id) {
    header("Location: menus.php");
    exit;
}

// Fetch Menu Details
$stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
$stmt->execute([$menu_id]);
$menu = $stmt->fetch();

if (!$menu) {
    die("Menu not found.");
}

$message = '';

// --- Handle Add Page ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_page'])) {
    $page_id = $_POST['page_id'];
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

    $stmt = $pdo->prepare("SELECT post_title, post_slug FROM posts WHERE id = ?");
    $stmt->execute([$page_id]);
    $page = $stmt->fetch();

    if ($page) {
        $label = $page['post_title'];
        $url = '/' . $page['post_slug'];
        $target_id = $page['id'];
        $stmt = $pdo->prepare("INSERT INTO menu_items (menu_id, label, url, parent_id, sort_order, target_id) VALUES (?, ?, ?, ?, 0, ?)");
        $stmt->execute([$menu_id, $label, $url, $parent_id, $target_id]);
        $message = "Page added successfully.";
    }
}

// --- Handle Add Item ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $label = trim($_POST['label']);
    $url = trim($_POST['url']);
    $sort_order = (int) $_POST['sort_order'];
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
    
    if ($label && $url) {
        $stmt = $pdo->prepare("INSERT INTO menu_items (menu_id, label, url, parent_id, sort_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$menu_id, $label, $url, $parent_id, $sort_order]);
        $message = "Link added successfully.";
    }
}

// --- Handle Delete Item ---
if (isset($_GET['delete_item'])) {
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ? AND menu_id = ?");
    $stmt->execute([$_GET['delete_item'], $menu_id]);
    header("Location: menu-edit.php?id=$menu_id&msg=deleted");
    exit;
}

// --- Handle Reorder ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_order'])) {
    foreach ($_POST['order'] as $item_id => $order) {
        $stmt = $pdo->prepare("UPDATE menu_items SET sort_order = ? WHERE id = ? AND menu_id = ?");
        $stmt->execute([(int)$order, $item_id, $menu_id]);
    }
    $message = "Order updated successfully.";
}

// Fetch Items
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE menu_id = ? ORDER BY sort_order ASC");
$stmt->execute([$menu_id]);
$items = $stmt->fetchAll();

// Fetch Pages AND Posts for Dropdown
$page_stmt = $pdo->query("SELECT id, post_title, post_type FROM posts WHERE post_status = 'publish' ORDER BY post_type ASC, post_title ASC");
$pages = $page_stmt->fetchAll();

?>
<?php $page_title = 'Edit Menu: ' . $menu['name']; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .form-row { display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 0.85em; font-weight: bold; margin-bottom: 4px; }
    </style>

    <div class="container">
        <!-- Add Existing Page -->
        <div class="card">
            <h3>Add Page</h3>
            <form method="post" class="form-row">
                <input type="hidden" name="add_page" value="1">
                <div class="form-group">
                    <label>Select Content</label>
                    <select name="page_id" required style="padding: 9px; min-width: 200px;">
                        <option value="">-- Choose Content --</option>
                        <?php foreach ($pages as $p): ?>
                            <option value="<?php echo $p['id']; ?>">
                                <?php echo htmlspecialchars($p['post_title']); ?> (<?php echo ucfirst($p['post_type']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Parent Item (Optional)</label>
                    <select name="parent_id" style="padding: 9px;">
                        <option value="">(No Parent)</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Add Page</button>
                </div>
            </form>
        </div>

        <!-- Add New Item -->
        <div class="card">
            <h3>Add Custom Link</h3>
            <?php if ($message): ?><p style="color: green;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?><p style="color: green;">Link deleted.</p><?php endif; ?>
            
            <form method="post">
                <input type="hidden" name="add_item" value="1">
                <div class="form-row">
                    <div class="form-group">
                        <label>Label</label>
                        <input type="text" name="label" placeholder="e.g. Home" required>
                    </div>
                    <div class="form-group">
                        <label>URL</label>
                        <input type="text" name="url" placeholder="e.g. / or /about" required>
                    </div>
                    <div class="form-group">
                        <label>Parent</label>
                        <select name="parent_id" style="padding: 8px;">
                            <option value="">(None)</option>
                            <?php foreach ($items as $item): ?>
                                <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['label']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Order</label>
                        <input type="number" name="sort_order" value="0" style="width: 60px;">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Add to Menu</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- List Items -->
        <div class="card">
            <h3>Menu Items</h3>
            <form method="post">
                <input type="hidden" name="save_order" value="1">
                <table>
                    <thead>
                        <tr>
                            <th width="80">Order</th>
                            <th>Label</th>
                            <th>URL</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <input type="number" name="order[<?php echo $item['id']; ?>]" value="<?php echo $item['sort_order']; ?>" style="width: 60px; padding: 5px;">
                            </td>
                            <td><strong><?php echo htmlspecialchars($item['label']); ?></strong></td>
                            <td><code><?php echo htmlspecialchars($item['url']); ?></code></td>
                            <td>
                                <a href="?id=<?php echo $menu_id; ?>&delete_item=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Remove this link?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($items)): ?>
                            <tr><td colspan="4" style="text-align: center; color: #777;">No links yet. Add one above.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (!empty($items)): ?>
                    <div style="margin-top: 15px;">
                        <button type="submit" class="btn">Save Order</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>