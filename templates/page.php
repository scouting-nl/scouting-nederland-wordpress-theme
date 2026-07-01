<?php get_template_part('parts/header'); ?>
<?php
$scouting_nederland_page_id = get_queried_object_id();
$scouting_nederland_page_title = '';
if (have_posts()) {
    the_post();
    $scouting_nederland_page_id = get_the_ID();
    $scouting_nederland_page_title = get_the_title();
    rewind_posts();
}
$scouting_nederland_page_display_settings = scoutingNederlandGetPageDisplaySettings($scouting_nederland_page_id);
$scouting_nederland_hide_hero = !empty($scouting_nederland_page_display_settings['hide_hero']);
$scouting_nederland_hide_page_title = !empty($scouting_nederland_page_display_settings['hide_page_title']);
$scouting_nederland_hide_sidebar = !empty($scouting_nederland_page_display_settings['hide_sidebar']);
$scouting_nederland_has_sidebar = is_active_sidebar('sidebar') && !$scouting_nederland_hide_sidebar;
$scouting_nederland_rendered_slider = scoutingNederlandRenderHomeSlider($scouting_nederland_page_id);
$scouting_nederland_hero_font_class = scoutingNederlandGetHeroTitleFontClass($scouting_nederland_page_id);
?>
<?php if (!$scouting_nederland_hide_hero && !$scouting_nederland_rendered_slider) : ?>
<div id="scouting-nederland-hero" class="container-fluid px-0 scouting-nederland-page-hero <?php echo esc_attr($scouting_nederland_hero_font_class); ?>">
    <div class="row no-gutters"><div class="col scouting-nederland-page-hero__media">
        <?php if (is_active_sidebar('hero')) { dynamic_sidebar('hero'); } else { ?>
            <div class="defaultbanner laag" style="background-image:url('<?php echo esc_url(scoutingNederlandGetDefaultHeroUrl()); ?>')"></div>
        <?php } ?>
        <?php if ($scouting_nederland_page_title && !$scouting_nederland_hide_page_title) : ?>
            <div class="scouting-nederland-page-hero__title <?php echo esc_attr($scouting_nederland_hero_font_class); ?>" aria-hidden="false">
                <div class="container">
                    <h1 class="<?php echo esc_attr($scouting_nederland_hero_font_class); ?>"><?php echo esc_html($scouting_nederland_page_title); ?></h1>
                </div>
            </div>
        <?php endif; ?>
    </div></div>
</div>
<?php endif; ?>
<div class="container">
    <?php if (is_active_sidebar('featured-a') || is_active_sidebar('featured-b') || is_active_sidebar('featured-c')) : ?>
    <div id="scouting-nederland-featured" class="row">
        <div class="col-sm-4"><?php dynamic_sidebar('featured-a'); ?></div>
        <div class="col-sm-4"><?php dynamic_sidebar('featured-b'); ?></div>
        <div class="col-sm-4"><?php dynamic_sidebar('featured-c'); ?></div>
    </div>
    <?php endif; ?>
</div>
<?php if (is_active_sidebar('showcase')) : ?>
<div id="scouting-nederland-showcase" class="container-fluid px-0 text-light bg-success"><div class="showcase-skyline"><div class="mover-1"></div><div class="mover-2"></div></div><div class="container"><?php dynamic_sidebar('showcase'); ?></div></div>
<?php endif; ?>
<div class="container">
    <?php if (is_active_sidebar('news')) : ?><div id="scouting-nederland-news" class="row"><div class="col-sm-12"><?php dynamic_sidebar('news'); ?></div></div><?php endif; ?>
    <?php if (is_active_sidebar('breadcrumbs')) : ?><div id="scouting-nederland-breadcrumbs" class="row"><div class="col-sm-12"><?php dynamic_sidebar('breadcrumbs'); ?></div></div><?php endif; ?>
    <div id="scouting-nederland-maincontent" class="row">
        <main id="content" role="main" class="<?php echo $scouting_nederland_has_sidebar ? 'col-md-8 col-lg-9' : 'col-md-12'; ?>">
            <?php if (have_posts()) : while (have_posts()) : the_post(); get_template_part('parts/content'); endwhile; the_posts_pagination(); else : ?><p><?php esc_html_e('Geen inhoud gevonden.', 'scouting-nederland'); ?></p><?php endif; ?>
        </main>
        <?php if ($scouting_nederland_has_sidebar) : ?>
            <?php get_template_part('parts/sidebar'); ?>
        <?php endif; ?>
    </div>
    <?php if (is_active_sidebar('bottom-a') || is_active_sidebar('bottom-b')) : ?>
    <div id="scouting-nederland-bottom" class="row"><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?> bottom-a"><?php dynamic_sidebar('bottom-a'); ?></div><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?> bottom-b"><?php dynamic_sidebar('bottom-b'); ?></div></div>
    <?php endif; ?>
</div>
<?php get_template_part('parts/footer'); ?>
