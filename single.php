<?php get_header(); ?>
<?php
$tc4_single_hero_url = '';
if (is_singular('post') && has_post_thumbnail(get_queried_object_id())) {
    $tc4_single_hero_url = get_the_post_thumbnail_url(get_queried_object_id(), 'full');
}
?>
<div id="tc4-hero" class="container-fluid px-0">
    <div class="row no-gutters"><div class="col">
        <?php if ($tc4_single_hero_url) : ?>
            <div class="defaultbanner laag tc4-single-hero" style="background-image:url('<?php echo esc_url($tc4_single_hero_url); ?>')"></div>
        <?php elseif (is_active_sidebar('hero')) : ?>
            <?php dynamic_sidebar('hero'); ?>
        <?php else : ?>
            <div class="defaultbanner laag" style="background-image:url('<?php echo esc_url(scoutingtc4_get_default_hero_url()); ?>')"></div>
        <?php endif; ?>
    </div></div>
</div>
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
        <main id="content" role="main" class="<?php echo is_active_sidebar('sidebar') ? 'col-md-8 col-lg-9' : 'col-md-12'; ?>">
            <?php if (have_posts()) : while (have_posts()) : the_post(); get_template_part('template-parts/content'); endwhile; the_posts_pagination(); else : ?><p><?php esc_html_e('Geen inhoud gevonden.', 'scoutingtc4'); ?></p><?php endif; ?>
        </main>
        <?php get_sidebar(); ?>
    </div>
    <?php if (is_active_sidebar('bottom-a') || is_active_sidebar('bottom-b')) : ?>
    <div id="tc4-bottom" class="row"><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?> bottom-a"><?php dynamic_sidebar('bottom-a'); ?></div><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?> bottom-b"><?php dynamic_sidebar('bottom-b'); ?></div></div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
