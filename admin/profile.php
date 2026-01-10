<?php
/**
 * Admin: User Profile
 *
 * Manage account settings (Name, Email, Password).
 */

session_start();
require_once __DIR__ . '/../config/db.php';

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = get_db_connection();
$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// --- Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $display_name = trim($_POST['display_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($display_name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {
        try {
            // 1. Update Basic Info
            $stmt = $pdo->prepare("UPDATE users SET display_name = ?, user_email = ? WHERE id = ?");
            $stmt->execute([$display_name, $email, $user_id]);
            $message = "Profile updated successfully.";

            // 2. Update Password (if provided)
            if (!empty($password)) {
                if ($password === $confirm_password) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET user_pass = ? WHERE id = ?");
                    $stmt->execute([$hash, $user_id]);
                    $message .= " Password changed.";
                } else {
                    $error = "Passwords do not match.";
                }
            }

            // 3. Handle Avatar Upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['avatar'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $file_mime = mime_content_type($file['tmp_name']);

                if (in_array($file_mime, $allowed_types)) {
                    $year = date('Y');
                    $month = date('m');
                    $upload_dir = __DIR__ . "/../uploads/$year/$month";
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    // Generate unique filename: avatar_{id}_{timestamp}.ext
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = "avatar_{$user_id}_" . time() . ".{$ext}";
                    $target_path = "$upload_dir/$filename";
                    $db_path = "uploads/$year/$month/$filename";

                    if (move_uploaded_file($file['tmp_name'], $target_path)) {
                        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                        $stmt->execute([$db_path, $user_id]);
                        $message .= " Avatar updated.";
                    } else {
                        $error = "Failed to save avatar file.";
                    }
                } else {
                    $error = "Invalid file type. Only images allowed.";
                }
            }
        } catch (PDOException $e) {
            // Check for duplicate email error (SQL State 23000)
            if ($e->getCode() == 23000) {
                $error = "That email address is already in use.";
            } else {
                $error = "Database Error: " . $e->getMessage();
            }
        }
    }
}

// Fetch User Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

?>
<?php $page_title = 'My Profile'; require_once __DIR__ . '/includes/header.php'; ?>

    <div class="container">
        <div class="card">
            <h3>Edit Profile</h3>
            
            <?php if ($message): ?><p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
            <?php if ($error): ?><p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Display Name</label>
                    <input type="text" name="display_name" value="<?php echo htmlspecialchars($user['display_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Profile Picture</label>
                    <?php if (!empty($user['avatar'])): ?>
                        <div style="margin-bottom: 10px;">
                            <img src="../<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="avatar" accept="image/*">
                </div>

                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                <h4 style="margin-top: 0;">Change Password</h4>
                <p style="font-size: 0.9em; color: #666; margin-bottom: 15px;">Leave blank to keep your current password.</p>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" autocomplete="new-password">
                </div>

                <button type="submit" class="btn">Save Changes</button>
            </form>
        </div>
    </div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>