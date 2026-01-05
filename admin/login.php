<?php
/**
 * Admin Login Page
 *
 * Handles user authentication for the admin dashboard.
 */

// --- Initialization ---

// Always start the session on pages that handle authentication.
session_start();

// Require the database connection manager.
require_once __DIR__ . '/../config/db.php';

// --- Redirect If Already Logged In ---

// If the user is already logged in, redirect them to the admin dashboard.
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// --- Login Logic ---

// Variable to hold error messages.
$error_message = '';

// Check if the form has been submitted via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from the form.
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation: Ensure fields are not empty.
    if (empty($email) || empty($password)) {
        $error_message = "Please enter both email and password.";
    } else {
        try {
            // Get the database connection.
            $pdo = get_db_connection();

            // Prepare a statement to select the user by email.
            $stmt = $pdo->prepare("SELECT id, user_pass FROM users WHERE user_email = ?");
            $stmt->execute([$email]);

            // Fetch the user record.
            $user = $stmt->fetch();

            // Verify the user exists and the password is correct.
            // `password_verify` securely checks the hashed password.
            if ($user && password_verify($password, $user['user_pass'])) {
                // Password is correct. Store user ID in the session.
                $_SESSION['user_id'] = $user['id'];
                
                // Regenerate the session ID to prevent session fixation attacks.
                session_regenerate_id(true);

                // Redirect to the admin dashboard.
                header("Location: index.php");
                exit;
            } else {
                // Invalid credentials.
                $error_message = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            // Handle database errors.
            $error_message = "Database error: " . (DEBUG_MODE ? $e->getMessage() : "Could not process login.");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Core CMS</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h1 { font-size: 2em; color: #1c2a38; text-align: center; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { display: block; width: 100%; background-color: #007bff; color: #fff; padding: 12px; border: none; border-radius: 4px; font-size: 1.1em; cursor: pointer; transition: background-color 0.2s; }
        .btn:hover { background-color: #0056b3; }
        .error-message { background-color: #fbeae5; color: #d93025; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if ($error_message): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
