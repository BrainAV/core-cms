<?php
/**
 * Admin: Theme Manager
 *
 * Switch between available themes.
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Auth & Role Check
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = '';

// --- Handle Activation ---
if (isset($_GET['activate'])) {
    $theme = $_GET['activate'];
    // Basic validation: check if folder exists or is default
    if ($theme === 'default' || is_dir(__DIR__ . '/../themes/' . $theme)) {
        update_option('active_theme', $theme);
        $message = "Theme activated: " . htmlspecialchars($theme);
    }
}

// Fetch Active Theme
$active_theme = get_option('active_theme', 'default');

// Scan Themes Directory
$themes_dir = __DIR__ . '/../themes';
$available_themes = [];

// 1. Add Default
$available_themes['default'] = [
    'name' => 'Core Default',
    'path' => 'templates/',
    'description' => 'The built-in system theme.'
];

// 2. Scan Custom
if (is_dir($themes_dir)) {
    $dirs = scandir($themes_dir);
    foreach ($dirs as $dir) {
        if ($dir === '.' || $dir === '..') continue;
        if (is_dir($themes_dir . '/' . $dir)) {
            $available_themes[$dir] = [
                'name' => ucfirst(str_replace('-', ' ', $dir)),
                'path' => 'themes/' . $dir . '/',
                'description' => 'Custom theme.'
            ];
        }
    }
}

?>
<?php $page_title = 'Theme Manager'; require_once __DIR__ . '/includes/header.php'; ?>

    <div class="container">
        <div class="card">
            <h3>Available Themes</h3>
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; text-align: left;">
                        <th style="padding: 10px;">Theme Name</th>
                        <th style="padding: 10px;">Location</th>
                        <th style="padding: 10px;">Status</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($available_themes as $slug => $info): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;"><strong><?php echo htmlspecialchars($info['name']); ?></strong></td>
                        <td style="padding: 10px;"><code><?php echo htmlspecialchars($info['path']); ?></code></td>
                        <td style="padding: 10px;">
                            <?php if ($active_theme === $slug): ?>
                                <span style="background: #28a745; color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 0.8em;">Active</span>
                            <?php else: ?>
                                <span style="color: #777;">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px;">
                            <?php if ($active_theme !== $slug): ?>
                                <a href="?activate=<?php echo urlencode($slug); ?>" class="btn" style="padding: 5px 10px; font-size: 0.8em;">Activate</a>
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