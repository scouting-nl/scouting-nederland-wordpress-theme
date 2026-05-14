<?php get_header(); ?>
<?php
$tc4_page_id = get_queried_object_id();
$tc4_page_display_settings = scoutingtc4_get_page_display_settings($tc4_page_id);
$tc4_hide_hero = !empty($tc4_page_display_settings['hide_hero']);
$tc4_hide_page_title = !empty($tc4_page_display_settings['hide_page_title']);
$rendered_slider = scoutingtc4_render_home_slider($tc4_page_id);
$hero_url = '';
if (!$tc4_hide_hero && !$rendered_slider) {
    if (has_post_thumbnail()) {
        $hero_url = get_the_post_thumbnail_url(get_the_ID(), 'tc4_home_hero');
    }
    if (!$hero_url) {
        $hero_url = scoutingtc4_get_default_hero_url();
    }
}
?>
<?php if (!$tc4_hide_hero && !$rendered_slider) : ?>
    <?php if (is_active_sidebar('hero')) : ?>
    <div id="tc4-hero" class="container-fluid px-0">
        <div class="row no-gutters"><div class="col"><?php dynamic_sidebar('hero'); ?></div></div>
    </div>
    <?php else : ?>
    <section class="tc4-home-hero" style="background-image:url('<?php echo esc_url($hero_url); ?>')">
        <div class="tc4-home-hero__inner">
            <?php if (!$tc4_hide_page_title) : ?><h1><?php the_title(); ?></h1><?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
<?php endif; ?>

<main id="content" class="tc4-home-content" role="main">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_content();
        endwhile;
    endif;
    ?>
</main>

<?php get_footer(); ?>
