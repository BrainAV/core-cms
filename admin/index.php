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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Core CMS</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #333; margin: 0; }
        .header { background-color: #1c2a38; color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header h1 { font-size: 1.5em; margin: 0; }
        .header a { color: #fff; text-decoration: none; padding: 8px 15px; background-color: #007bff; border-radius: 4px; transition: background-color 0.2s; }
        .header a:hover { background-color: #0056b3; }
        .container { padding: 30px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h2 { border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .links a { display: block; margin-bottom: 10px; color: #007bff; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Core CMS Dashboard</h1>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
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
                <a href="#">Site Settings</a>
            </div>
        </div>
    </div>
</body>
</html>
