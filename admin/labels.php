<?php
/**
 * Admin: Label Editor
 *
 * Manage UI text overrides.
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Auth & Role Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$pdo = get_db_connection();
$message = $_GET['msg'] ?? '';

// --- Handle Save ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = trim($_POST['key']);
    $value = trim($_POST['value']);

    // Sanitize key (only lowercase letters, numbers, and underscores)
    $key = preg_replace('/[^a-z0-9_]/', '', strtolower($key));

    if ($key && $value) {
        update_option('label_' . $key, $value);
        $message = "Label '$key' saved successfully.";
    }
}

// --- Handle Delete ---
if (isset($_GET['delete'])) {
    $key = $_GET['delete'];
    delete_option('label_' . $key);
    header("Location: labels.php?msg=Label+deleted");
    exit;
}

// Fetch All Labels
$stmt = $pdo->prepare("SELECT * FROM options WHERE option_name LIKE 'label_%' ORDER BY option_name ASC");
$stmt->execute();
$labels = $stmt->fetchAll();

?>
<?php $page_title = 'Label Editor'; require_once __DIR__ . '/includes/header.php'; ?>

    <div class="container">
        <!-- Add/Edit Label -->
        <div class="card">
            <h3>Add / Edit Label</h3>
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            
            <form method="post" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Key (Code Identifier)</label>
                    <input type="text" name="key" placeholder="e.g. read_more_btn" required style="width: 100%;">
                    <small style="color: #666;">Use <code>get_label('key', 'Default')</code> in themes.</small>
                </div>
                <div style="flex: 2; min-width: 300px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Text Value</label>
                    <input type="text" name="value" placeholder="e.g. Continue Reading..." required style="width: 100%;">
                </div>
                <button type="submit" class="btn">Save Label</button>
            </form>
        </div>

        <!-- List Labels -->
        <div class="card">
            <h3>Existing Overrides</h3>
            <table>
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($labels as $label): ?>
                        <?php $clean_key = str_replace('label_', '', $label['option_name']); ?>
                        <tr>
                            <td><code><?php echo htmlspecialchars($clean_key); ?></code></td>
                            <td><?php echo htmlspecialchars($label['option_value']); ?></td>
                            <td>
                                <a href="?delete=<?php echo urlencode($clean_key); ?>" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8em;" onclick="return confirm('Delete this override?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($labels)): ?>
                        <tr><td colspan="3" style="text-align: center; color: #777;">No labels defined yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>