<?php
/**
 * Admin Dashboard
 *
 * The main landing page for logged-in administrators.
 */

// --- Authentication Check ---

// Always start the session to access session variables.
session_start();

// Check if the user ID is not set in the session.
// If not, the user is not logged in.
if (!isset($_SESSION['user_id'])) {
    // Redirect them to the login page.

    header("Location: login.php");
    // Stop further script execution.
    exit;
}

// --- Page Content ---

// At this point, the user is confirmed to be authenticated.
// We can now load data or display the dashboard.

// For demonstration, we'll just get the user's ID from the session.
$user_id = $_SESSION['user_id'];

// Ensure role is set (redundant check if header included, but good for standalone logic)
if (!isset($_SESSION['user_role'])) {
    $stmt = get_db_connection()->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $u = $stmt->fetch();
    $_SESSION['user_role'] = $u ? $u['role'] : 'subscriber';
}
$user_role = $_SESSION['user_role'];

?>
<?php
$page_title = "Dashboard"; // For header.php
require_once __DIR__ . '/includes/header.php';
?>
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .dashboard-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 25px;
            text-decoration: none;
            color: #333;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #007bff;
            color: #007bff;
        }
        .dashboard-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }
        .dashboard-label {
            font-weight: 600;
            font-size: 1.1em;
        }
    </style>

        <div class="card">
            <h2>Welcome, Admin #<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>!</h2>
            <p>You have successfully logged in. This is your command center.</p>
        </div>

        <h2 style="margin-top: 30px; margin-bottom: 15px;">Quick Actions</h2>
        <div class="dashboard-grid">
            <?php if ($user_role !== 'subscriber'): ?>
            <a href="posts.php" class="dashboard-card">
                <span class="dashboard-icon">📝</span>
                <span class="dashboard-label">Posts</span>
            </a>
            <a href="pages.php" class="dashboard-card">
                <span class="dashboard-icon">📄</span>
                <span class="dashboard-label">Pages</span>
            </a>
            <a href="categories.php" class="dashboard-card">
                <span class="dashboard-icon">🏷️</span>
                <span class="dashboard-label">Categories</span>
            </a>
            <a href="media.php" class="dashboard-card">
                <span class="dashboard-icon">🖼️</span>
                <span class="dashboard-label">Media</span>
            </a>
            <?php endif; ?>
            
            <?php if ($user_role === 'admin'): ?>
            <a href="users.php" class="dashboard-card">
                <span class="dashboard-icon">👥</span>
                <span class="dashboard-label">Users</span>
            </a>
            <a href="menus.php" class="dashboard-card">
                <span class="dashboard-icon">🧭</span>
                <span class="dashboard-label">Menus</span>
            </a>
            <a href="themes.php" class="dashboard-card">
                <span class="dashboard-icon">🎨</span>
                <span class="dashboard-label">Themes</span>
            </a>
            <a href="settings.php" class="dashboard-card">
                <span class="dashboard-icon">⚙️</span>
                <span class="dashboard-label">Settings</span>
            </a>
            <a href="labels.php" class="dashboard-card">
                <span class="dashboard-icon">🔤</span>
                <span class="dashboard-label">Labels</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
