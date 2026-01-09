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

?>
<?php
$page_title = "Dashboard"; // For header.php
require_once __DIR__ . '/includes/header.php';
?>

        <div class="card">
            <h2>Welcome, Admin #<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>!</h2>
            <p>You have successfully logged in. This is your command center.</p>
        </div>

        <div class="card">
            <h2>Quick Links</h2>
            <div class="links">
                <a href="posts.php">Manage Posts</a>
                <a href="#">Manage Pages</a>
                <a href="#">Manage Events</a>
                <a href="menus.php">Navigation Menus</a>
                <a href="settings.php">Site Settings</a>
            </div>
        </div>
    </div>
<?php
require_once __DIR__ . '/includes/footer.php';
?>
