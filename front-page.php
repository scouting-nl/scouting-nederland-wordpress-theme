<?php get_header(); ?>
<?php
$rendered_slider = scoutingtc4_render_home_slider(get_the_ID());
$hero_url = '';
if (!$rendered_slider) {
    if (has_post_thumbnail()) {
        $hero_url = get_the_post_thumbnail_url(get_the_ID(), 'tc4_home_hero');
    }
    if (!$hero_url) {
        $hero_url = scoutingtc4_get_default_hero_url();
    }
}
?>
<?php if (!$rendered_slider) : ?>
    <?php if (is_active_sidebar('hero')) : ?>
    <div id="tc4-hero" class="container-fluid px-0">
        <div class="row no-gutters"><div class="col"><?php dynamic_sidebar('hero'); ?></div></div>
    </div>
    <?php else : ?>
    <section class="tc4-home-hero" style="background-image:url('<?php echo esc_url($hero_url); ?>')">
        <div class="tc4-home-hero__inner">
            <h1><?php the_title(); ?></h1>
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
