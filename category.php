<?php get_header(); ?>
<div id="tc4-hero" class="container-fluid px-0">
    <div class="row no-gutters"><div class="col">
        <?php if (is_active_sidebar('hero')) { dynamic_sidebar('hero'); } else { ?>
            <div class="defaultbanner laag" style="background-image:url('<?php echo esc_url(scoutingtc4_get_default_hero_url()); ?>')"></div>
        <?php } ?>
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
<div class="container tc4-category-page">
    <?php if (is_active_sidebar('breadcrumbs')) : ?><div id="tc4-breadcrumbs" class="row"><div class="col-sm-12"><?php dynamic_sidebar('breadcrumbs'); ?></div></div><?php endif; ?>

    <header class="tc4-category-header">
        <p class="tc4-category-header__eyebrow"><?php esc_html_e('Categorie', 'scoutingtc4'); ?></p>
        <h1 class="tc4-category-header__title"><?php single_cat_title(); ?></h1>
        <?php if (category_description()) : ?>
            <div class="tc4-category-header__description"><?php echo wp_kses_post(category_description()); ?></div>
        <?php endif; ?>
    </header>

    <main id="content" role="main" class="tc4-category-main">
        <?php if (have_posts()) : ?>
            <div class="tc4-category-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $image_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : scoutingtc4_get_default_hero_url();
                    $summary = get_the_excerpt();
                    if (!$summary) {
                        $summary = get_the_content();
                    }
                    $summary = scoutingtc4_trim_text_chars($summary, 142);
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('tc4-category-card'); ?>>
                        <a class="tc4-category-card__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                            <span class="tc4-category-card__date"><?php echo esc_html(get_the_date('d F Y')); ?></span>
                        </a>
                        <div class="tc4-category-card__titlebar">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </div>
                        <div class="tc4-category-card__body">
                            <?php if ($summary) : ?><p><?php echo esc_html($summary); ?></p><?php endif; ?>
                            <a class="tc4-category-card__readmore" href="<?php the_permalink(); ?>"><?php esc_html_e('Lees meer', 'scoutingtc4'); ?> ›</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="tc4-category-pagination">
                <?php the_posts_pagination(array(
                    'mid_size' => 1,
                    'prev_text' => __('Vorige', 'scoutingtc4'),
                    'next_text' => __('Volgende', 'scoutingtc4'),
                )); ?>
            </div>
        <?php else : ?>
            <section class="tc4-category-empty">
                <h2><?php esc_html_e('Geen berichten gevonden', 'scoutingtc4'); ?></h2>
                <p><?php esc_html_e('Er zijn nog geen berichten binnen deze categorie.', 'scoutingtc4'); ?></p>
            </section>
        <?php endif; ?>
    </main>

    <?php if (is_active_sidebar('bottom-a') || is_active_sidebar('bottom-b')) : ?>
    <div id="tc4-bottom" class="row"><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?> bottom-a"><?php dynamic_sidebar('bottom-a'); ?></div><div class="<?php echo (is_active_sidebar('bottom-a') && is_active_sidebar('bottom-b')) ? 'col-md-6' : 'col-md-12'; ?> bottom-b"><?php dynamic_sidebar('bottom-b'); ?></div></div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
