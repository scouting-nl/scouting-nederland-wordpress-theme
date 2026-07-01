<?php
/*
Template Name: Elementor Canvas
Template Post Type: page
*/
if (!defined('ABSPATH')) { exit; }
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('scouting-nederland-elementor-canvas-body'); ?>>
<?php wp_body_open(); ?>
<main id="content" class="scouting-nederland-elementor-canvas" role="main">
    <?php
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
    endif;
    ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
