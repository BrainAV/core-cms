<?php
/**
 * Event Planner Installer
 *
 * Run this script to set up the database tables required for the Event Planner plugin.
 */

// Include the core database configuration
require_once __DIR__ . '/../../config/db.php';

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = get_db_connection();

        // The SQL schema for the events table (moved from core schema.sql)
        $sql = "CREATE TABLE IF NOT EXISTS `events` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(255) NOT NULL,
          `start_date` datetime NOT NULL,
          `end_date` datetime NOT NULL,
          `location` varchar(255) DEFAULT NULL,
          `capacity` int(11) DEFAULT NULL,
          `status` varchar(20) NOT NULL DEFAULT 'scheduled',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $pdo->exec($sql);
        $message = "Success! The 'events' table has been installed.";
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
    <title>Install Event Planner</title>
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
        <h1>Event Planner Setup</h1>
        <p>This will create the <code>events</code> table in your database.</p>
        <?php if ($message): ?>
            <p class="<?php echo $status; ?>"><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>
        <form method="post"><button type="submit" class="btn">Run Installer</button></form>
    </div>
</body>
</html>