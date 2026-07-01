<?php get_template_part('parts/header'); ?>
<?php
$scouting_nederland_page_id = get_queried_object_id();
$scouting_nederland_page_display_settings = scoutingNederlandGetPageDisplaySettings($scouting_nederland_page_id);
$scouting_nederland_hide_hero = !empty($scouting_nederland_page_display_settings['hide_hero']);
$scouting_nederland_hide_page_title = !empty($scouting_nederland_page_display_settings['hide_page_title']);
$rendered_slider = scoutingNederlandRenderHomeSlider($scouting_nederland_page_id);
$scouting_nederland_hero_font_class = scoutingNederlandGetHeroTitleFontClass($scouting_nederland_page_id);
$hero_url = '';
if (!$scouting_nederland_hide_hero && !$rendered_slider) {
    if (has_post_thumbnail()) {
        $hero_url = get_the_post_thumbnail_url(get_the_ID(), 'scouting_nederland_home_hero');
    }
    if (!$hero_url) {
        $hero_url = scoutingNederlandGetDefaultHeroUrl();
    }
}
?>
<div class="mainbody"><div class="content">
<?php if (!$scouting_nederland_hide_hero && !$rendered_slider) : ?>
    <?php if (is_active_sidebar('hero')) : ?>
    <div id="scouting-nederland-hero" class="container-fluid px-0 <?php echo esc_attr($scouting_nederland_hero_font_class); ?>">
        <div class="row no-gutters"><div class="col <?php echo esc_attr($scouting_nederland_hero_font_class); ?>"><?php dynamic_sidebar('hero'); ?></div></div>
    </div>
    <?php else : ?>
    <section class="scouting-nederland-home-hero <?php echo esc_attr($scouting_nederland_hero_font_class); ?>" style="background-image:url('<?php echo esc_url($hero_url); ?>')">
        <div class="scouting-nederland-home-hero__inner <?php echo esc_attr($scouting_nederland_hero_font_class); ?>">
            <?php if (!$scouting_nederland_hide_page_title) : ?><h1 class="<?php echo esc_attr($scouting_nederland_hero_font_class); ?>"><?php the_title(); ?></h1><?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
<?php endif; ?>

<main id="content" class="scouting-nederland-home-content" role="main">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_content();
        endwhile;
    endif;
    ?>
</main>
</div><!-- .content -->
</div><!-- .mainbody -->

<?php get_template_part('parts/footer'); ?>
