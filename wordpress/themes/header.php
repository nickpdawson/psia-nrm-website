<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to main content</a>

<header class="site-header">
  <div class="header-top">
    <div class="container flex items-center justify-between">
      <span>Professional Ski Instructors of America &mdash; Northern Rocky Mountain Division</span>
      <a href="<?php echo home_url('/login'); ?>" style="color: rgba(255,255,255,0.85);">Log In with PSIA</a>
    </div>
  </div>
  <div class="container">
    <div class="header-main">
      <a href="<?php echo home_url(); ?>" class="header-logo" aria-label="PSIA-AASI Northern Rocky — home">
        <?php if (file_exists(ABSPATH . 'wp-content/uploads/brand/nrm-logo-2026.svg')): ?>
          <img src="<?php echo home_url('/wp-content/uploads/brand/nrm-logo-2026.svg'); ?>" alt="PSIA-AASI Northern Rocky">
        <?php elseif (file_exists(ABSPATH . 'wp-content/uploads/psia-nrm-header-logo.png')): ?>
          <img src="<?php echo home_url('/wp-content/uploads/psia-nrm-header-logo.png'); ?>" alt="PSIA-AASI Northern Rocky">
        <?php else: ?>
          <span style="font-size:1.25rem;font-weight:700;">Northern Rocky</span>
        <?php endif; ?>
      </a>

      <button class="nav-toggle" aria-expanded="false" aria-controls="primary-nav" aria-label="Menu">
        <?php echo nrm_icon('menu', 26); ?>
        <?php echo nrm_icon('x', 26); ?>
      </button>

      <?php
      wp_nav_menu([
          'theme_location'  => 'primary',
          'container'       => 'nav',
          'container_class' => 'header-nav',
          'container_id'    => 'primary-nav',
          'container_aria_label' => 'Primary',
          'items_wrap'      => '%3$s',
          'walker'          => new NRM_Nav_Walker(),
          'fallback_cb'     => 'nrm_nav_fallback',
      ]);
      ?>
    </div>
  </div>
</header>
<main id="main">
