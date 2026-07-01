<?php
/*
Template Name: Elementor Full Width
Template Post Type: page
*/
if (!defined('ABSPATH')) { exit; }

get_template_part('parts/header');
?>
<div class="mainbody scouting-nederland-elementor-template scouting-nederland-elementor-template--full-width">
    <main id="content" class="scouting-nederland-elementor-content" role="main">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
        endif;
        ?>
    </main>
</div>
<?php get_template_part('parts/footer'); ?>
