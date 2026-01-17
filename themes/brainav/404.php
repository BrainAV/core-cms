<?php
$page_title = "Page Not Found";
require_once __DIR__ . '/header.php';
?>

<section style="text-align: center; padding: 100px 0;">
    <h1 style="font-size: 4rem; color: var(--primary-accent);">404</h1>
    <h2>Lost in the Signal?</h2>
    <p>The page you are looking for does not exist.</p>
    <a href="<?php echo BASE_URL; ?>/" class="btn-primary" style="margin-top: 20px;">Return to Base</a>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>