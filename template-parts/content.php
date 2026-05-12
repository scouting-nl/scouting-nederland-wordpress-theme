<article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
    <?php if (has_post_thumbnail()) : ?><div class="post-thumbnail mb-3"><?php the_post_thumbnail('large'); ?></div><?php endif; ?>
    <?php if (!is_page()) : ?><header class="entry-header"><h1 class="entry-title"><?php the_title(); ?></h1><?php scoutingtc4_posted_on(); ?></header><?php endif; ?>
    <div class="entry-content"><?php the_content(); wp_link_pages(); ?></div>
</article>
