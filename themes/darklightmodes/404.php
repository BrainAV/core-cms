<?php
/**
 * Theme: Dark/Light Mode
 * Template: 404 Not Found
 */
require __DIR__ . '/header.php';
?>
<div style="text-align: center; padding: 50px 0;">
    <h1 style="font-size: 4em; margin-bottom: 0;">404</h1>
    <p style="font-size: 1.5em; opacity: 0.7;">Page Not Found</p>
    <p><a href="<?php echo BASE_URL; ?>" class="btn" style="display: inline-block; padding: 10px 20px; background-color: var(--accent-color); color: #fff; border-radius: 5px; margin-top: 20px;">Return Home</a></p>
</div>
<?php
require __DIR__ . '/footer.php';
?>