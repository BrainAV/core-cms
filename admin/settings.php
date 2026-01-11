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

// Role Check
if (($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = '';

// --- Handle Save ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Site Identity
    update_option('site_title', trim($_POST['site_title']));
    update_option('site_logo', trim($_POST['site_logo']));
    update_option('site_footer_text', trim($_POST['site_footer_text']));

    // Breadcrumb Settings
    update_option('breadcrumbs_enabled', isset($_POST['breadcrumbs_enabled']) ? '1' : '0');
    update_option('breadcrumbs_separator', trim($_POST['breadcrumbs_separator']));
    update_option('breadcrumbs_home_text', trim($_POST['breadcrumbs_home_text']));
    
    // Scroll to Top Settings
    update_option('scroll_top_enabled', isset($_POST['scroll_top_enabled']) ? '1' : '0');
    update_option('scroll_top_position', $_POST['scroll_top_position']);
    update_option('scroll_top_bg_color', $_POST['scroll_top_bg_color']);
    update_option('scroll_top_icon_color', $_POST['scroll_top_icon_color']);
    update_option('scroll_top_shape', $_POST['scroll_top_shape']);

    $message = "Settings saved successfully!";
}

// Fetch Current Values
$site_title = get_option('site_title', 'Core CMS');
$site_logo = get_option('site_logo', '');
$site_footer_text = get_option('site_footer_text', '&copy; ' . date('Y') . ' Core CMS. Built with PHP & Passion.');

$bc_enabled = get_option('breadcrumbs_enabled', '0');
$bc_separator = get_option('breadcrumbs_separator', '>');
$bc_home_text = get_option('breadcrumbs_home_text', 'Home');

$st_enabled = get_option('scroll_top_enabled', '0');
$st_position = get_option('scroll_top_position', 'bottom-right');
$st_bg_color = get_option('scroll_top_bg_color', '#007bff');
$st_icon_color = get_option('scroll_top_icon_color', '#ffffff');
$st_shape = get_option('scroll_top_shape', 'rounded');

?>
<?php $page_title = 'Site Settings'; require_once __DIR__ . '/includes/header.php'; ?>
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .checkbox-label { display: flex; align-items: center; gap: 10px; font-weight: normal; cursor: pointer; }
    </style>
    <div class="container">
        <div class="card">
            <h3>Site Identity</h3>
            <form method="post">
                <div class="form-group">
                    <label>Site Title</label>
                    <input type="text" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>" required>
                </div>
                <div class="form-group">
                    <label>Logo URL (Relative or Absolute)</label>
                    <input type="text" name="site_logo" value="<?php echo htmlspecialchars($site_logo); ?>" placeholder="e.g. /assets/images/logo.png">
                </div>
                <div class="form-group">
                    <label>Footer Text (HTML Allowed)</label>
                    <textarea name="site_footer_text" rows="2"><?php echo htmlspecialchars($site_footer_text); ?></textarea>
                </div>
        </div>

        <div class="card">
            <h3>Breadcrumbs</h3>
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            
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

        </div>

        <div class="card">
            <h3>Scroll to Top</h3>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="scroll_top_enabled" value="1" <?php echo $st_enabled === '1' ? 'checked' : ''; ?>>
                    Enable Button
                </label>
            </div>
            
            <div class="form-group">
                <label>Position</label>
                <select name="scroll_top_position">
                    <option value="bottom-right" <?php echo $st_position === 'bottom-right' ? 'selected' : ''; ?>>Bottom Right</option>
                    <option value="bottom-left" <?php echo $st_position === 'bottom-left' ? 'selected' : ''; ?>>Bottom Left</option>
                    <option value="bottom-center" <?php echo $st_position === 'bottom-center' ? 'selected' : ''; ?>>Bottom Center</option>
                </select>
            </div>

            <div class="form-group">
                <label>Shape</label>
                <select name="scroll_top_shape">
                    <option value="square" <?php echo $st_shape === 'square' ? 'selected' : ''; ?>>Square</option>
                    <option value="rounded" <?php echo $st_shape === 'rounded' ? 'selected' : ''; ?>>Rounded</option>
                    <option value="circle" <?php echo $st_shape === 'circle' ? 'selected' : ''; ?>>Circle</option>
                </select>
            </div>

            <div class="form-group">
                <label>Background Color</label>
                <input type="color" name="scroll_top_bg_color" value="<?php echo htmlspecialchars($st_bg_color); ?>" style="height: 40px; width: 60px; padding: 0; border: none;">
            </div>

            <div class="form-group">
                <label>Icon Color</label>
                <input type="color" name="scroll_top_icon_color" value="<?php echo htmlspecialchars($st_icon_color); ?>" style="height: 40px; width: 60px; padding: 0; border: none;">
            </div>

                <div class="form-group" style="margin-top: 20px;">
                    <button type="submit" class="btn">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</body>


</html>