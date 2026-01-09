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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings - Core CMS</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #333; margin: 0; }
        .header { background-color: #1c2a38; color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.5em; margin: 0; }
        .header a { color: #fff; text-decoration: none; margin-left: 15px; }
        .container { padding: 30px; max-width: 800px; margin: 0 auto; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .btn { padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        .btn:hover { background-color: #0056b3; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 100%; max-width: 300px; }
        .checkbox-label { display: flex; align-items: center; gap: 10px; font-weight: normal; cursor: pointer; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Site Settings</h1>
        <div>
            <a href="index.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

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