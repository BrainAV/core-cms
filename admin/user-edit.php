<?php
/**
 * Admin: Add/Edit User
 */
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

$pdo = get_db_connection();
$id = $_GET['id'] ?? null;
$user = ['id' => '', 'display_name' => '', 'user_email' => '', 'role' => 'editor'];
$error = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) die("User not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['display_name']);
    $email = trim($_POST['user_email']);
    $role = $_POST['role'];
    $pass = $_POST['user_pass'];
    
    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {
        try {
            if ($id) {
                // Update
                if (!empty($pass)) {
                    $hash = password_hash($pass, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET display_name=?, user_email=?, role=?, user_pass=? WHERE id=?");
                    $stmt->execute([$name, $email, $role, $hash, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE users SET display_name=?, user_email=?, role=? WHERE id=?");
                    $stmt->execute([$name, $email, $role, $id]);
                }
            } else {
                // Create
                if (empty($pass)) {
                    $error = "Password is required for new users.";
                } else {
                    $hash = password_hash($pass, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (display_name, user_email, role, user_pass) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $email, $role, $hash]);
                }
            }
            
            if (!$error) {
                header("Location: users.php?msg=User+saved");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<?php $page_title = $id ? 'Edit User' : 'Add User'; require_once __DIR__ . '/includes/header.php'; ?>
<div class="container">
    <div class="card">
        <h3><?php echo $page_title; ?></h3>
        <?php if ($error): ?><p style="color:red; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label>Display Name</label>
                <input type="text" name="display_name" value="<?php echo htmlspecialchars($user['display_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="user_email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role">
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                    <option value="editor" <?php echo $user['role'] === 'editor' ? 'selected' : ''; ?>>Editor</option>
                    <option value="subscriber" <?php echo $user['role'] === 'subscriber' ? 'selected' : ''; ?>>Subscriber</option>
                </select>
            </div>
            <div class="form-group">
                <label>Password <?php echo $id ? '(Leave blank to keep current)' : '(Required)'; ?></label>
                <input type="password" name="user_pass" autocomplete="new-password">
            </div>
            <button type="submit" class="btn">Save User</button>
        </form>
    </div>
</div>
</body>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</html>