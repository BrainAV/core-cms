<?php
/**
 * Admin: Global Settings
 * 
 * Manage site-wide configuration options.
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

// --- Handle Save ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Breadcrumb Settings
    update_option('breadcrumbs_enabled', isset($_POST['breadcrumbs_enabled']) ? '1' : '0');
    update_option('breadcrumbs_separator', trim($_POST['breadcrumbs_separator']));
    update_option('breadcrumbs_home_text', trim($_POST['breadcrumbs_home_text']));
    
    $message = "Settings saved successfully!";
}

// Fetch Current Values
$bc_enabled = get_option('breadcrumbs_enabled', '0');
$bc_separator = get_option('breadcrumbs_separator', '>');
$bc_home_text = get_option('breadcrumbs_home_text', 'Home');

?>
<?php $page_title = 'Site Settings'; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .checkbox-label { display: flex; align-items: center; gap: 10px; font-weight: normal; cursor: pointer; }
    </style>
    <div class="container">
        <div class="card">
            <h3>Breadcrumbs</h3>
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="breadcrumbs_enabled" value="1" <?php echo $bc_enabled === '1' ? 'checked' : ''; ?>>
                        Enable Breadcrumbs
                    </label>
                </div>

                <div class="form-group">
                    <label>Home Link Text</label>
                    <input type="text" name="breadcrumbs_home_text" value="<?php echo htmlspecialchars($bc_home_text); ?>">
                </div>

                <div class="form-group">
                    <label>Separator Character</label>
                    <input type="text" name="breadcrumbs_separator" value="<?php echo htmlspecialchars($bc_separator); ?>" style="width: 50px;">
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" class="btn">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</body>


</html>