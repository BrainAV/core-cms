<?php
/**
 * Engagement Pack Installer
 *
 * Sets up the database for Comments and Newsletter features.
 */

require_once __DIR__ . '/../../config/db.php';

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = get_db_connection();

        // 1. Create Comments Table
        $sql_comments = "CREATE TABLE IF NOT EXISTS `comments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `content` text NOT NULL,
            `status` varchar(20) NOT NULL DEFAULT 'pending',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `post_id` (`post_id`),
            KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $pdo->exec($sql_comments);

        // 2. Add Newsletter Column to Users
        // We wrap this in a try-catch to prevent errors if the column already exists.
        try {
            $sql_alter = "ALTER TABLE `users` ADD COLUMN `newsletter_opt_in` TINYINT(1) NOT NULL DEFAULT 0";
            $pdo->exec($sql_alter);
        } catch (PDOException $e) {
            // SQLState 42S21: Column already exists. We can safely ignore this.
            if ($e->getCode() != '42S21') {
                throw $e; 
            }
        }

        $message = "Success! Engagement tables and columns installed.";
        $status = 'success';

    } catch (PDOException $e) {
        $message = "Installation Failed: " . $e->getMessage();
        $status = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Install Engagement Pack</title>
    <style>
        body { font-family: sans-serif; padding: 50px; text-align: center; background: #f4f4f4; }
        .card { background: white; padding: 30px; border-radius: 8px; max-width: 500px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Engagement Pack Setup</h1>
        <p>This will create the <code>comments</code> table and update the <code>users</code> table.</p>
        <?php if ($message): ?>
            <p class="<?php echo $status; ?>"><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>
        <form method="post"><button type="submit" class="btn">Run Installer</button></form>
    </div>
</body>
</html>