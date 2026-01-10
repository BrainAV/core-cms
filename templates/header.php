<?php
/**
 * Template: Header
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Core CMS' : 'Core CMS'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container">
        <a href="<?php echo BASE_URL; ?>" class="site-logo">
            <!-- Placeholder for Logo Image -->
            <!-- <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Core CMS Logo"> -->
            Core CMS
        </a>
        <nav class="site-nav">
            <?php render_menu('main-menu', 'site-menu'); ?>
        </nav>
    </div>
</header>

<main class="site-main">
    <div class="container">
        <?php if (function_exists('render_breadcrumbs')) render_breadcrumbs($page_title ?? ''); ?>