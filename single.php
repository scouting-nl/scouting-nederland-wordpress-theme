<?php get_header(); ?>
<?php
$scouting_nederland_single_hero_url = '';
if (is_singular('post') && has_post_thumbnail(get_queried_object_id())) {
    $scouting_nederland_single_hero_url = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
}
?>
<div class="scouting-nederland-base-main"><div class="scouting-nederland-layout-shell">
<div class="scouting-nederland-base-widget-hero container-fluid px-0">
    <div class="row g-0"><div class="col">
        <?php if ($scouting_nederland_single_hero_url) : ?>
            <div class="scouting-nederland-base-default-banner scouting-nederland-base-default-banner-low scouting-nederland-single-hero" style="background-image:url('<?php echo esc_url($scouting_nederland_single_hero_url); ?>')"></div>
        <?php elseif (is_active_sidebar('hero')) : ?>
            <?php dynamic_sidebar('hero'); ?>
        <?php else : ?>
            <div class="scouting-nederland-base-default-banner scouting-nederland-base-default-banner-low" style="background-image:url('<?php echo esc_url(scoutingNederlandGetDefaultHeroUrl()); ?>')"></div>
        <?php endif; ?>
    </div></div>
</div>
<div class="container">
    <?php if (is_active_sidebar('featured-a') || is_active_sidebar('featured-b') || is_active_sidebar('featured-c')) : ?>
    <div class="row">
        <div class="col-sm-4"><?php dynamic_sidebar('featured-a'); ?></div>
        <div class="col-sm-4"><?php dynamic_sidebar('featured-b'); ?></div>
        <div class="col-sm-4"><?php dynamic_sidebar('featured-c'); ?></div>
    </div>
    <?php endif; ?>
</div>
<?php if (is_active_sidebar('showcase')) : ?>
<div class="container-fluid px-0 text-light bg-success"><div class="scouting-nederland-layout-showcase-skyline"></div><div class="container"><?php dynamic_sidebar('showcase'); ?></div></div>
<?php endif; ?>
<div class="container">
    <?php if (is_active_sidebar('news')) : ?><div class="row"><div class="col-sm-12"><?php dynamic_sidebar('news'); ?></div></div><?php endif; ?>
    <?php if (is_active_sidebar('breadcrumbs')) : ?><div class="row"><div class="col-sm-12"><?php dynamic_sidebar('breadcrumbs'); ?></div></div><?php endif; ?>
    <div class="row">
        <main id="scouting-nederland-layout-content" role="main" class="<?php echo is_active_sidebar('sidebar') ? 'col-md-8 col-lg-9' : 'col-md-12'; ?>">
            <?php if (have_posts()) : while (have_posts()) : the_post(); get_template_part('parts/content'); if (comments_open() || get_comments_number()) { comments_template(); } endwhile; the_posts_pagination(); else : ?><p><?php esc_html_e('Geen inhoud gevonden.', 'scouting-nederland'); ?></p><?php endif; ?>
        </main>
        <?php get_sidebar(); ?>
    </div>
    <?php if (is_active_sidebar('bottom-a') || is_active_sidebar('bottom-b')) : ?>
    <div class="row"><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?>"><?php dynamic_sidebar('bottom-a'); ?></div><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?>"><?php dynamic_sidebar('bottom-b'); ?></div></div>
    <?php endif; ?>
</div>
</div><!-- .content -->
</div><!-- .scouting-nederland-base-main -->
<?php get_footer(); ?>
