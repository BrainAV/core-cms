<?php
/**
 * Admin Logout Handler
 *
 * Destroys the user session and redirects to the login page.
 */

// Initialize the session.
session_start();

// Unset all of the session variables.
$_SESSION = [];

// If it's desired to kill the session, also delete the session cookie.
// This ensures the session ID is invalidated on the client side as well.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session storage.
session_destroy();

// Redirect to login page.
header("Location: login.php");
exit;