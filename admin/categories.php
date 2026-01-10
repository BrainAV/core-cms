<?php
/**
 * Admin: Manage Categories
 *
 * Create, list, and delete categories.
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
$edit_category = null;

// --- Handle Edit Request ---
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_category = $stmt->fetch();
}

// --- Handle Form Submission (Create & Update) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $description = trim($_POST['description']);
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

    // Auto-generate slug
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    }

    if ($name) {
        try {
            if ($id) {
                // Update
                $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ?, parent_id = ? WHERE id = ?");
                $stmt->execute([$name, $slug, $description, $parent_id, $id]);
                $msg = "Category updated successfully!";
            } else {
                // Create
                $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description, parent_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $slug, $description, $parent_id]);
                $msg = "Category created successfully!";
            }
            header("Location: categories.php?msg=" . urlencode($msg));
            exit;
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

// --- Handle Delete ---
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        header("Location: categories.php?msg=Category+deleted");
        exit;
    } catch (PDOException $e) {
        $message = "Error deleting category: " . $e->getMessage();
    }
}

// Fetch Categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

?>
<?php $page_title = 'Manage Categories'; require_once __DIR__ . '/includes/header.php'; ?>

    <div class="container">
        <!-- Create New Category -->
        <div class="card">
            <h3><?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?></h3>
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            
            <form method="post">
                <?php if ($edit_category): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_category['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required placeholder="e.g. Technology" value="<?php echo $edit_category ? htmlspecialchars($edit_category['name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Slug (Optional)</label>
                    <input type="text" name="slug" placeholder="e.g. technology" value="<?php echo $edit_category ? htmlspecialchars($edit_category['slug']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Parent Category</label>
                    <select name="parent_id">
                        <option value="">None</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if ($edit_category && $cat['id'] == $edit_category['id']) continue; // Prevent self-parenting ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($edit_category && $edit_category['parent_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"><?php echo $edit_category ? htmlspecialchars($edit_category['description']) : ''; ?></textarea>
                </div>
                <button type="submit" class="btn"><?php echo $edit_category ? 'Update Category' : 'Add Category'; ?></button>
                <?php if ($edit_category): ?>
                    <a href="categories.php" class="btn" style="background-color: #6c757d; margin-left: 10px;">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- List Categories -->
        <div class="card">
            <h3>Existing Categories</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($cat['name']); ?></strong></td>
                        <td><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                        <td><?php echo htmlspecialchars($cat['description']); ?></td>
                        <td>
                            <a href="?edit=<?php echo $cat['id']; ?>" class="btn" style="background-color: #6c757d;">Edit</a>
                            <a href="?delete=<?php echo $cat['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($categories)): ?>
                        <tr><td colspan="4" style="text-align: center; color: #777;">No categories found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>