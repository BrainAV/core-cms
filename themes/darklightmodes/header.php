<?php
/**
 * Theme: Dark/Light Mode
 * Template: Header
 */
$site_title = get_option('site_title', 'Core CMS');
$site_logo = get_option('site_logo', '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' . htmlspecialchars($site_title) : htmlspecialchars($site_title); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/themes/darklightmodes/style.css">
    <script>
        // Prevent FOUC (Flash of Unstyled Content)
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            }
        })();
    </script>
</head>
<body>

<header class="site-header">
    <div class="container">
        <a href="<?php echo BASE_URL; ?>" class="site-logo">
            <?php if ($site_logo): ?>
                <img src="<?php echo htmlspecialchars($site_logo); ?>" alt="<?php echo htmlspecialchars($site_title); ?>">
            <?php endif; ?>
            <?php echo htmlspecialchars($site_title); ?>
        </a>
        <div style="display: flex; align-items: center;">
            <nav class="site-nav">
                <?php render_menu('main-menu', 'site-menu'); ?>
            </nav>
            <button id="theme-toggle" title="Toggle Dark/Light Mode">🌗</button>
        </div>
    </div>
</header>

<main class="site-main">
    <div class="container">
        <?php if (function_exists('render_breadcrumbs')) render_breadcrumbs($page_title ?? ''); ?>