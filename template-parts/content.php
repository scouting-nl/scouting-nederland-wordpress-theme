<article id="post-<?php the_ID(); ?>" <?php post_class(is_singular('post') ? 'tc4-single-post mb-5' : 'mb-5'); ?>>
    <?php if (is_singular('post')) : ?>
        <header class="tc4-single-post__header">
            <p class="tc4-single-post__eyebrow"><?php esc_html_e('Bericht', 'scoutingtc4'); ?></p>
            <h1 class="tc4-single-post__title"><?php the_title(); ?></h1>
            <div class="tc4-single-post__meta">
                <span><?php echo esc_html(get_the_date()); ?></span>
                <?php if (get_the_author()) : ?>
                    <span><?php esc_html_e('door', 'scoutingtc4'); ?> <?php the_author(); ?></span>
                <?php endif; ?>
            </div>
        </header>

        <div class="entry-content tc4-single-post__content">
            <?php the_content(); wp_link_pages(); ?>
        </div>

        <?php if (has_category() || has_tag()) : ?>
            <footer class="tc4-single-post__footer">
                <?php if (has_category()) : ?>
                    <div class="tc4-single-post__terms">
                        <strong><?php esc_html_e('Categorieën:', 'scoutingtc4'); ?></strong>
                        <?php the_category(', '); ?>
                    </div>
                <?php endif; ?>
                <?php if (has_tag()) : ?>
                    <div class="tc4-single-post__terms">
                        <strong><?php esc_html_e('Tags:', 'scoutingtc4'); ?></strong>
                        <?php the_tags('', ', '); ?>
                    </div>
                <?php endif; ?>
            </footer>
        <?php endif; ?>

        <nav class="tc4-single-post__navigation" aria-label="<?php esc_attr_e('Berichtnavigatie', 'scoutingtc4'); ?>">
            <div class="tc4-single-post__nav-item tc4-single-post__nav-item--previous">
                <?php previous_post_link('<span>' . esc_html__('Vorige bericht', 'scoutingtc4') . '</span>%link'); ?>
            </div>
            <div class="tc4-single-post__nav-item tc4-single-post__nav-item--next">
                <?php next_post_link('<span>' . esc_html__('Volgende bericht', 'scoutingtc4') . '</span>%link'); ?>
            </div>
        </nav>
    <?php else : ?>
        <?php if (!is_page() && has_post_thumbnail()) : ?><div class="post-thumbnail mb-3"><?php the_post_thumbnail('large'); ?></div><?php endif; ?>
        <?php if (!is_page()) : ?><header class="entry-header"><h1 class="entry-title"><?php the_title(); ?></h1><?php scoutingtc4_posted_on(); ?></header><?php endif; ?>
        <div class="entry-content"><?php the_content(); wp_link_pages(); ?></div>
    <?php endif; ?>
</article>
