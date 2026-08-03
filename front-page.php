<?php get_header(); ?>
<?php
$scouting_nederland_page_id = get_queried_object_id();
$scouting_nederland_page_display_settings = scoutingNederlandGetPageDisplaySettings($scouting_nederland_page_id);
$scouting_nederland_hide_hero = !empty($scouting_nederland_page_display_settings['hide_hero']);
$scouting_nederland_hide_page_title = !empty($scouting_nederland_page_display_settings['hide_page_title']);
$rendered_slider = false;
$scouting_nederland_hero_font_class = scoutingNederlandGetHeroTitleFontClass($scouting_nederland_page_id);
$hero_url = '';
if (!$scouting_nederland_hide_hero) {
    if (has_post_thumbnail()) {
        $hero_url = get_the_post_thumbnail_url(get_the_ID(), 'scouting_nederland_home_hero');
    }
    if (!$hero_url) {
        $hero_url = scoutingNederlandGetDefaultHeroUrl();
    }
}
?>
<div class="scouting-nederland-base-main"><div class="scouting-nederland-layout-shell">
<?php $rendered_slider = scoutingNederlandRenderHomeSlider($scouting_nederland_page_id); ?>
<?php if (!$scouting_nederland_hide_hero && !$rendered_slider) : ?>
    <?php if (is_active_sidebar('hero')) : ?>
    <div class="scouting-nederland-base-widget-hero container-fluid px-0 <?php echo esc_attr($scouting_nederland_hero_font_class); ?>">
        <div class="row g-0"><div class="col <?php echo esc_attr($scouting_nederland_hero_font_class); ?>"><?php dynamic_sidebar('hero'); ?></div></div>
    </div>
    <?php else : ?>
    <section class="scouting-nederland-homepage-hero <?php echo esc_attr($scouting_nederland_hero_font_class); ?>" style="background-image:url('<?php echo esc_url($hero_url); ?>')">
        <div class="scouting-nederland-homepage-hero__inner <?php echo esc_attr($scouting_nederland_hero_font_class); ?>">
            <?php if (!$scouting_nederland_hide_page_title) : ?><h1 class="<?php echo esc_attr($scouting_nederland_hero_font_class); ?>"><?php the_title(); ?></h1><?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
<?php endif; ?>

<main id="scouting-nederland-layout-content" class="scouting-nederland-homepage-content" role="main">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_content();
        endwhile;
    endif;
    ?>
</main>
</div><!-- .content -->
</div><!-- .scouting-nederland-base-main -->

<?php get_footer(); ?>
