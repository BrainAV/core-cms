<?php
/**
 * Database Connection Manager
 *
 * Establishes and provides a PDO database connection.
 */

// Require the core configuration file.
require_once __DIR__ . '/config.php';

// The database connection function.
function get_db_connection() {
    // This variable will hold the database connection object.
    static $pdo = null;

    // If the connection already exists, return it.
    if ($pdo) {
        return $pdo;
    }

    // The path to the database credentials file.
    $creds_file = __DIR__ . '/db_creds.php';

    // Check if the credentials file exists.
    if (file_exists($creds_file)) {
        require_once $creds_file;
    } else {
        // If the credentials file is missing, we cannot proceed.
        // Using `die()` is a simple way to halt execution for this foundational step.
        die("Error: Database credentials file not found. Please create `config/db_creds.php` by copying `config/db_creds-sample.php`.");
    }

    // Define the Data Source Name (DSN) for the PDO connection.
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    // Set PDO options for error handling and fetching results.
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on error.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays.
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements.
    ];

    try {
        // Attempt to create the PDO connection.
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // If the connection fails, display an error message.
        // In a production environment, you would log this error instead of displaying it.
        if (DEBUG_MODE) {
            // Provide a detailed error message in debug mode.
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        } else {
            // Provide a generic error message in production.
            die("Error: Could not connect to the database.");
        }
    }

    // Return the established PDO connection.
    return $pdo;
}
