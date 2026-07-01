<article id="post-<?php the_ID(); ?>" <?php post_class(is_singular('post') ? 'scouting-nederland-single-post mb-5' : 'mb-5'); ?>>
    <?php if (is_singular('post')) : ?>
        <header class="scouting-nederland-single-post__header">
            <p class="scouting-nederland-single-post__eyebrow"><?php esc_html_e('Bericht', 'scouting-nederland'); ?></p>
            <h1 class="scouting-nederland-single-post__title"><?php the_title(); ?></h1>
            <div class="scouting-nederland-single-post__meta">
                <span><?php echo esc_html(get_the_date()); ?></span>
                <?php if (get_the_author()) : ?>
                    <span><?php esc_html_e('door', 'scouting-nederland'); ?> <?php the_author(); ?></span>
                <?php endif; ?>
            </div>
        </header>

        <div class="entry-content scouting-nederland-single-post__content">
            <?php the_content(); wp_link_pages(); ?>
        </div>

        <?php if (has_category() || has_tag()) : ?>
            <footer class="scouting-nederland-single-post__footer">
                <?php if (has_category()) : ?>
                    <div class="scouting-nederland-single-post__terms">
                        <strong><?php esc_html_e('Categorieën:', 'scouting-nederland'); ?></strong>
                        <?php the_category(', '); ?>
                    </div>
                <?php endif; ?>
                <?php if (has_tag()) : ?>
                    <div class="scouting-nederland-single-post__terms">
                        <strong><?php esc_html_e('Tags:', 'scouting-nederland'); ?></strong>
                        <?php the_tags('', ', '); ?>
                    </div>
                <?php endif; ?>
            </footer>
        <?php endif; ?>

        <nav class="scouting-nederland-single-post__navigation" aria-label="<?php esc_attr_e('Berichtnavigatie', 'scouting-nederland'); ?>">
            <div class="scouting-nederland-single-post__nav-item scouting-nederland-single-post__nav-item--previous">
                <?php previous_post_link('<span>' . esc_html__('Vorige bericht', 'scouting-nederland') . '</span>%link'); ?>
            </div>
            <div class="scouting-nederland-single-post__nav-item scouting-nederland-single-post__nav-item--next">
                <?php next_post_link('<span>' . esc_html__('Volgende bericht', 'scouting-nederland') . '</span>%link'); ?>
            </div>
        </nav>
    <?php else : ?>
        <?php if (!is_page() && has_post_thumbnail()) : ?><div class="post-thumbnail mb-3"><?php the_post_thumbnail('large'); ?></div><?php endif; ?>
        <?php if (!is_page()) : ?><header class="entry-header"><h1 class="entry-title"><?php the_title(); ?></h1><?php scoutingNederlandPostedOn(); ?></header><?php endif; ?>
        <div class="entry-content"><?php the_content(); wp_link_pages(); ?></div>
    <?php endif; ?>
</article>
