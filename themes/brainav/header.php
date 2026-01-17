<?php
/**
 * Theme: BrainAV
 * Template: Header
 */
$site_title = get_option('site_title', 'BrainAV');
$site_logo = get_option('site_logo', '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-9Y0EXVWK16"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-9Y0EXVWK16');
  </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' . htmlspecialchars($site_title) : htmlspecialchars($site_title); ?></title>
  
  <meta name="description" content="BrainAV is a creative technology lab building intelligent tools for the future of music.">
  <meta name="keywords" content="AI Music, OSC, Python, Music Production, BrainAV, Jason Brain">
  <meta name="author" content="Jason Brain - BrainAV.ca">
  
  <!-- Theme Assets -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/themes/brainav/style.css">
  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
  <!-- Navigation -->
  <nav id="navbar">
    <div class="nav-container">
      <a href="<?php echo BASE_URL; ?>/#top" class="logo">
        <?php echo htmlspecialchars($site_title); ?>
      </a>
      
      <!-- Dynamic Menu: 'main-menu' -->
      <?php render_menu('main-menu', 'nav-links'); ?>

      <div class="hamburger" id="hamburger">
        <span class="bar1"></span>
        <span class="bar2"></span>
        <span class="bar3"></span>
      </div>
    </div>
  </nav>

  <?php if (isset($post) && $post['is_home']): ?>
  <!-- Hero Header (Only on Homepage) -->
  <header id="top">
    <div class="header-content">
      <div class="brand-badge">🚀 AI Creative Ecosystem</div>
      <h1>BrainAV Tech Lab</h1>
      <p class="tagline">Intelligent tools for the future of music.</p>
      <p class="subtitle">Bridging the gap between Large Language Models and professional creative software.</p>
      
      <div class="cta-container">
        <a href="#products" class="btn-primary">Explore Ecosystem</a>
        <a href="https://github.com/BrainAV" class="btn-secondary">View on GitHub</a>
      </div>
    </div>
  </header>
  <?php endif; ?>

  <div class="container">
    <?php if (function_exists('render_breadcrumbs')) render_breadcrumbs($page_title ?? ''); ?>