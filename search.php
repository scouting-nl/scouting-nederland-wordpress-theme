<?php get_header(); ?>
<?php
$search_query = trim(get_search_query());
$has_search_query = $search_query !== '';
?>

<main id="content" class="tc4-search-page" role="main">
    <section class="tc4-search-hero" style="background-image:linear-gradient(180deg, rgba(255,255,255,.94) 0%, rgba(255,255,255,.88) 100%), url(\'<?php echo esc_url(get_template_directory_uri() . '/assets/images/default-slide.jpg'); ?>\');">
        <div class="container tc4-search-hero__inner">
            <span class="tc4-search-kicker">
                <i class="fas fa-search" aria-hidden="true"></i>
                <?php esc_html_e('Zoeken op de site', 'scoutingtc4'); ?>
            </span>

            <?php if (!$has_search_query) : ?>
                <h1><?php esc_html_e('Waar wil je naar zoeken?', 'scoutingtc4'); ?></h1>
                <p><?php esc_html_e('Vul een zoekterm in, dan pakken we de kaart erbij.', 'scoutingtc4'); ?></p>
            <?php elseif (have_posts()) : ?>
                <h1>
                    <?php
                    printf(
                        esc_html__('Zoekresultaten voor: %s', 'scoutingtc4'),
                        '<span>' . esc_html($search_query) . '</span>'
                    );
                    ?>
                </h1>
                <p><?php esc_html_e('We hebben de kaart erbij gepakt en dit spoor gevonden.', 'scoutingtc4'); ?></p>
            <?php else : ?>
                <h1><?php esc_html_e('Geen spoor gevonden', 'scoutingtc4'); ?></h1>
                <p><?php esc_html_e('Deze zoekterm zit waarschijnlijk nog ergens in de hike-rugzak. Probeer eens een andere route.', 'scoutingtc4'); ?></p>
            <?php endif; ?>

            <form role="search" method="get" class="tc4-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <label class="screen-reader-text" for="tc4-search-field"><?php esc_html_e('Zoeken naar:', 'scoutingtc4'); ?></label>
                <input
                    id="tc4-search-field"
                    type="search"
                    class="tc4-search-form__input"
                    placeholder="<?php echo esc_attr__('Waar ben je naar op zoek?', 'scoutingtc4'); ?>"
                    value="<?php echo esc_attr($search_query); ?>"
                    name="s"
                    required
                />
                <button type="submit" class="tc4-search-form__button">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span><?php esc_html_e('Zoeken', 'scoutingtc4'); ?></span>
                </button>
            </form>
        </div>
    </section>

    <section class="container tc4-search-content">
        <?php if (!$has_search_query) : ?>
            <div class="tc4-search-empty">
                <div class="tc4-search-empty__icon">
                    <i class="fas fa-compass" aria-hidden="true"></i>
                </div>
                <h2><?php esc_html_e('Nog geen zoekterm ingevuld', 'scoutingtc4'); ?></h2>
                <p><?php esc_html_e('Typ bijvoorbeeld een activiteit, speltak, verhuur of contactpersoon in het zoekveld hierboven.', 'scoutingtc4'); ?></p>
            </div>
        <?php elseif (have_posts()) : ?>
            <div class="tc4-search-count">
                <?php
                global $wp_query;
                printf(
                    esc_html(_n('%s resultaat gevonden', '%s resultaten gevonden', (int) $wp_query->found_posts, 'scoutingtc4')),
                    esc_html(number_format_i18n((int) $wp_query->found_posts))
                );
                ?>
            </div>

            <div class="tc4-search-results">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('tc4-search-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a class="tc4-search-card__image" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </a>
                        <?php else : ?>
                            <a class="tc4-search-card__image tc4-search-card__image--fallback" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                <i class="fas fa-compass" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>

                        <div class="tc4-search-card__content">
                            <div class="tc4-search-card__meta">
                                <?php
                                $post_type = get_post_type_object(get_post_type());
                                echo esc_html($post_type ? $post_type->labels->singular_name : __('Pagina', 'scoutingtc4'));
                                ?>
                                <?php if (get_the_date()) : ?>
                                    <span aria-hidden="true">•</span>
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                                <?php endif; ?>
                            </div>

                            <h2 class="tc4-search-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <div class="tc4-search-card__excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <a class="tc4-search-card__link" href="<?php the_permalink(); ?>">
                                <?php esc_html_e('Bekijk resultaat', 'scoutingtc4'); ?> <span aria-hidden="true">›</span>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="tc4-search-pagination">
                <?php the_posts_pagination(array(
                    'mid_size'  => 1,
                    'prev_text' => __('‹ Vorige', 'scoutingtc4'),
                    'next_text' => __('Volgende ›', 'scoutingtc4'),
                )); ?>
            </div>
        <?php else : ?>
            <div class="tc4-search-empty">
                <div class="tc4-search-empty__icon">
                    <i class="fas fa-map-signs" aria-hidden="true"></i>
                </div>
                <h2><?php esc_html_e('De route loopt hier dood', 'scoutingtc4'); ?></h2>
                <p><?php esc_html_e('Controleer je zoekterm, probeer een korter woord of ga terug naar de homepage.', 'scoutingtc4'); ?></p>
                <div class="tc4-search-empty__actions">
                    <a class="btn tc4-btn-blue" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Terug naar home', 'scoutingtc4'); ?></a>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
