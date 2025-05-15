<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php wp_title('|', true, 'right'); ?></title>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
  <h1>Surinaamse Recepten</h1>
  <nav>
    <?php
    wp_nav_menu([
      'theme_location' => 'hoofdmenu',
      'container' => false,
      'menu_class' => 'menu',
    ]);
    ?>
  </nav>
</header>

