<?php
/**
 * Main Entry Point
 *
 * This is the primary file that handles incoming requests.
 */

// 1. Bootstrap
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
$pdo = get_db_connection();
$theme_path = get_theme_path();

// 2. Routing Logic
// Get the requested path from the URL (e.g., "/my-first-post")
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path if the CMS is in a subdirectory, and trim slashes
// For now, we assume it's at the root or handled by RewriteBase
$path = trim($request_uri, '/');

// 3. Dispatch
if ($path === '' || $path === 'index.php') {
    // --- HOMEPAGE ---
    
    // Check for a designated static homepage
    $stmt = $pdo->query("SELECT * FROM posts WHERE is_home = 1 AND post_status = 'publish' LIMIT 1");
    $home_page = $stmt->fetch();

    if ($home_page) {
        $page_title = $home_page['post_title'];
        require $theme_path . '/header.php';
        echo "<h1>" . htmlspecialchars($home_page['post_title']) . "</h1>";
        echo "<div class='entry-content'>" . render_blocks($home_page['post_content']) . "</div>";
        require $theme_path . '/footer.php';
        exit;
    }

    // For now, we'll just list the latest posts inline (Temporary View)
    // In the next step, we will move this to `templates/home.php`
    $stmt = $pdo->query("SELECT * FROM posts WHERE post_status = 'publish' ORDER BY created_at DESC LIMIT 5");
    $posts = $stmt->fetchAll();
    
    // Simple output for testing routing
    $page_title = 'Home';
    require $theme_path . '/header.php';

    echo "<h1>Welcome to Core CMS</h1>";
    echo "<ul>";
    foreach ($posts as $p) {
        echo "<li><a href='" . BASE_URL . "/" . htmlspecialchars($p['post_slug']) . "'>" . htmlspecialchars($p['post_title']) . "</a></li>";
    }
    echo "</ul>";
    require $theme_path . '/footer.php';

} else {
    // --- SINGLE POST LOOKUP ---
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE post_slug = ? AND post_status = 'publish'");
    $stmt->execute([$path]);
    $post = $stmt->fetch();

    if ($post) {
        // Found! Render the post (Temporary View)
        $page_title = $post['post_title'];
        require $theme_path . '/header.php';

        echo "<h1>" . htmlspecialchars($post['post_title']) . "</h1>";
        echo "<div class='entry-content'>" . render_blocks($post['post_content']) . "</div>";
        echo "<hr><a href='" . BASE_URL . "'>&larr; Back Home</a>";
        require $theme_path . '/footer.php';
    } else {
        // --- 404 NOT FOUND ---
        http_response_code(404);
        require $theme_path . '/404.php';
    }
}
