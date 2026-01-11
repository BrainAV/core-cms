<?php
/**
 * Theme: Dark Mode
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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/themes/darkmode/style.css">
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
        <nav class="site-nav">
            <?php render_menu('main-menu', 'site-menu'); ?>
        </nav>
    </div>
</header>

<main class="site-main">
    <div class="container">
        <?php if (function_exists('render_breadcrumbs')) render_breadcrumbs($page_title ?? ''); ?>