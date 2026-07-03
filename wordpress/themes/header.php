<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="header-top">
    <div class="container flex items-center justify-between">
      <span>Professional Ski Instructors of America &mdash; Northern Rocky Mountain Division</span>
      <a href="<?php echo home_url('/login'); ?>" style="color: rgba(255,255,255,0.7);">Log In with PSIA</a>
    </div>
  </div>
  <div class="container">
    <div class="header-main">
      <a href="<?php echo home_url(); ?>" class="header-logo">
        <?php if (file_exists(ABSPATH . 'wp-content/uploads/psia-nrm-header-logo.png')): ?>
          <img src="<?php echo home_url('/wp-content/uploads/psia-nrm-header-logo.png'); ?>" alt="PSIA-AASI NRM">
        <?php else: ?>
          <span style="font-size:1.25rem;font-weight:700;">PSIA-NRM</span>
        <?php endif; ?>
      </a>
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container'       => 'nav',
          'container_class' => 'header-nav',
          'items_wrap'      => '%3$s',
          'walker'          => new NRM_Nav_Walker(),
          'fallback_cb'     => 'nrm_nav_fallback',
      ]);
      ?>
    </div>
  </div>
</header>
