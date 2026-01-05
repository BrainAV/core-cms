<?php
/**
 * Admin Installer Script
 *
 * Run this file once to create your initial admin user.
 * SECURITY WARNING: DELETE THIS FILE IMMEDIATELY AFTER USE.
 */

require_once __DIR__ . '/config/db.php';

$message = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $name = trim($_POST['display_name'] ?? 'Admin');

    if ($email && $password) {
        $pdo = get_db_connection();

        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE user_email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = "Error: User with this email already exists.";
            $msg_type = "error";
        } else {
            // Hash password securely
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

            // Insert Admin User
            $sql = "INSERT INTO users (user_email, user_pass, display_name, role) VALUES (?, ?, ?, 'admin')";
            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute([$email, $hashed_pass, $name]);
                $message = "Success! Admin user created. <a href='admin/login.php'>Login here</a>.<br><br><strong>⚠️ IMPORTANT: Delete this file (`install_admin.php`) from your server now.</strong>";
                $msg_type = "success";
            } catch (PDOException $e) {
                $message = "Database Error: " . $e->getMessage();
                $msg_type = "error";
            }
        }
    } else {
        $message = "Please fill in all fields.";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Admin User - Core CMS</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { margin-top: 0; color: #1c2a38; }
        input { width: 100%; padding: 10px; margin: 10px 0 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        button { width: 100%; padding: 12px; background-color: #28a745; color: #fff; border: none; border-radius: 4px; font-size: 1em; cursor: pointer; }
        button:hover { background-color: #218838; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Admin User</h2>
        <?php if ($message): ?>
            <div class="message <?php echo $msg_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($msg_type !== 'success'): ?>
        <form method="post">
            <label>Display Name</label>
            <input type="text" name="display_name" placeholder="e.g. Jason Brain" required>

            <label>Email Address</label>
            <input type="email" name="email" placeholder="admin@example.com" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Create Account</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>