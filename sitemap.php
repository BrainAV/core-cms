<?php
/**
 * Dynamic XML Sitemap Generator
 * 
 * Generates a sitemap.xml for search engines based on published content.
 */

require_once __DIR__ . '/config/db.php';

// Set Header to XML
header("Content-Type: application/xml; charset=utf-8");

$pdo = get_db_connection();
// Ensure BASE_URL is available (loaded via config/db.php -> config/config.php)
$base_url = defined('BASE_URL') ? BASE_URL : ''; 

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Homepage -->
    <url>
        <loc><?php echo $base_url; ?>/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <?php
    // Fetch Published Posts & Pages
    $stmt = $pdo->query("SELECT post_slug, created_at FROM posts WHERE post_status = 'publish' ORDER BY created_at DESC");
    while ($row = $stmt->fetch()) {
        $url = $base_url . '/' . $row['post_slug'];
        $date = date('Y-m-d', strtotime($row['created_at']));
        echo "    <url>\n";
        echo "        <loc>" . htmlspecialchars($url) . "</loc>\n";
        echo "        <lastmod>" . $date . "</lastmod>\n";
        echo "        <changefreq>monthly</changefreq>\n";
        echo "        <priority>0.8</priority>\n";
        echo "    </url>\n";
    }
    ?>
</urlset>