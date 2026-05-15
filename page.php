<?php get_header(); ?>
<?php
$tc4_page_id = get_queried_object_id();
$tc4_page_title = '';
if (have_posts()) {
    the_post();
    $tc4_page_id = get_the_ID();
    $tc4_page_title = get_the_title();
    rewind_posts();
}
$tc4_page_display_settings = scoutingtc4_get_page_display_settings($tc4_page_id);
$tc4_hide_hero = !empty($tc4_page_display_settings['hide_hero']);
$tc4_hide_page_title = !empty($tc4_page_display_settings['hide_page_title']);
$tc4_hide_sidebar = !empty($tc4_page_display_settings['hide_sidebar']);
$tc4_has_sidebar = is_active_sidebar('sidebar') && !$tc4_hide_sidebar;
$tc4_rendered_slider = scoutingtc4_render_home_slider($tc4_page_id);
$tc4_hero_font_class = scoutingtc4_get_hero_title_font_class($tc4_page_id);
?>
<?php if (!$tc4_hide_hero && !$tc4_rendered_slider) : ?>
<div id="tc4-hero" class="container-fluid px-0 tc4-page-hero <?php echo esc_attr($tc4_hero_font_class); ?>">
    <div class="row no-gutters"><div class="col tc4-page-hero__media">
        <?php if (is_active_sidebar('hero')) { dynamic_sidebar('hero'); } else { ?>
            <div class="defaultbanner laag" style="background-image:url('<?php echo esc_url(scoutingtc4_get_default_hero_url()); ?>')"></div>
        <?php } ?>
        <?php if ($tc4_page_title && !$tc4_hide_page_title) : ?>
            <div class="tc4-page-hero__title <?php echo esc_attr($tc4_hero_font_class); ?>" aria-hidden="false">
                <div class="container">
                    <h1 class="<?php echo esc_attr($tc4_hero_font_class); ?>"><?php echo esc_html($tc4_page_title); ?></h1>
                </div>
            </div>
        <?php endif; ?>
    </div></div>
</div>
<?php endif; ?>
<div class="container">
    <?php if (is_active_sidebar('featured-a') || is_active_sidebar('featured-b') || is_active_sidebar('featured-c')) : ?>
    <div id="tc4-featured" class="row">
        <div class="col-sm-4"><?php dynamic_sidebar('featured-a'); ?></div>
        <div class="col-sm-4"><?php dynamic_sidebar('featured-b'); ?></div>
        <div class="col-sm-4"><?php dynamic_sidebar('featured-c'); ?></div>
    </div>
    <?php endif; ?>
</div>
<?php if (is_active_sidebar('showcase')) : ?>
<div id="tc4-showcase" class="container-fluid px-0 text-light bg-success"><div class="showcase-skyline"><div class="mover-1"></div><div class="mover-2"></div></div><div class="container"><?php dynamic_sidebar('showcase'); ?></div></div>
<?php endif; ?>
<div class="container">
    <?php if (is_active_sidebar('news')) : ?><div id="tc4-news" class="row"><div class="col-sm-12"><?php dynamic_sidebar('news'); ?></div></div><?php endif; ?>
    <?php if (is_active_sidebar('breadcrumbs')) : ?><div id="tc4-breadcrumbs" class="row"><div class="col-sm-12"><?php dynamic_sidebar('breadcrumbs'); ?></div></div><?php endif; ?>
    <div id="tc4-maincontent" class="row">
        <main id="content" role="main" class="<?php echo $tc4_has_sidebar ? 'col-md-8 col-lg-9' : 'col-md-12'; ?>">
            <?php if (have_posts()) : while (have_posts()) : the_post(); get_template_part('template-parts/content'); endwhile; the_posts_pagination(); else : ?><p><?php esc_html_e('Geen inhoud gevonden.', 'scoutingtc4'); ?></p><?php endif; ?>
        </main>
        <?php if ($tc4_has_sidebar) : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>
    </div>
    <?php if (is_active_sidebar('bottom-a') || is_active_sidebar('bottom-b')) : ?>
    <div id="tc4-bottom" class="row"><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?> bottom-a"><?php dynamic_sidebar('bottom-a'); ?></div><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?> bottom-b"><?php dynamic_sidebar('bottom-b'); ?></div></div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
