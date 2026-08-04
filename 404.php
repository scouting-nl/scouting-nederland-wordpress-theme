<?php get_header(); ?>
<?php
$search_query = trim(get_search_query());
$contact_url = get_theme_mod('scouting_nederland_contact_url', '');
$hero_url = get_template_directory_uri() . '/assets/images/default-slide.jpg';
?>

<div class="scouting-nederland-base-main"><div class="scouting-nederland-layout-shell">
<main id="scouting-nederland-layout-content" class="scouting-nederland-404-page" role="main">
    <section class="scouting-nederland-404-hero" style="background-image:linear-gradient(180deg, rgba(255,255,255,.94) 0%, rgba(255,255,255,.88) 100%), url('<?php echo esc_url($hero_url); ?>');">
        <div class="container scouting-nederland-404-hero__inner">
            <span class="scouting-nederland-404-kicker">
                <i class="fas fa-map-signs" aria-hidden="true"></i>
                <?php esc_html_e('Oeps, verkeerd gelopen', 'scouting-nederland'); ?>
            </span>

            <h1><?php esc_html_e('404', 'scouting-nederland'); ?></h1>
            <p><?php esc_html_e('Deze pagina is op hike zonder routekaart vertrokken.', 'scouting-nederland'); ?></p>

            <form role="search" method="get" class="scouting-nederland-search-form scouting-nederland-404-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <label class="screen-reader-text" for="scouting-nederland-404-search-field"><?php esc_html_e('Zoeken naar:', 'scouting-nederland'); ?></label>
                <input
                    id="scouting-nederland-404-search-field"
                    type="search"
                    class="scouting-nederland-search-form__input"
                    placeholder="<?php echo esc_attr__('Zoek op de site…', 'scouting-nederland'); ?>"
                    value="<?php echo esc_attr($search_query); ?>"
                    name="s"
                />
                <button type="submit" class="scouting-nederland-search-form__button">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span><?php esc_html_e('Zoeken', 'scouting-nederland'); ?></span>
                </button>
            </form>
        </div>
    </section>

    <section class="container scouting-nederland-404-content">
        <div class="scouting-nederland-404-grid">
            <article class="scouting-nederland-404-card scouting-nederland-404-card--main">
                <div class="scouting-nederland-404-badge"><i class="fas fa-compass" aria-hidden="true"></i> <?php esc_html_e('Spoor bijster', 'scouting-nederland'); ?></div>
                <h2><?php esc_html_e('We zijn het spoor even kwijt', 'scouting-nederland'); ?></h2>
                <p><?php esc_html_e('Misschien heeft een welp de routemarkering omgedraaid, is de kaart natgeregend of ligt deze pagina nog bij het kampvuur. Geen zorgen: we helpen je terug naar het goede pad.', 'scouting-nederland'); ?></p>

                <ul class="scouting-nederland-404-tips">
                    <li><i class="fas fa-home" aria-hidden="true"></i><span><?php esc_html_e('Ga terug naar de homepage en begin opnieuw.', 'scouting-nederland'); ?></span></li>
                    <li><i class="fas fa-search" aria-hidden="true"></i><span><?php esc_html_e('Zoek op een onderwerp, pagina of activiteit.', 'scouting-nederland'); ?></span></li>
                    <li><i class="fas fa-campground" aria-hidden="true"></i><span><?php esc_html_e('Of neem contact op met de groep als je iets specifieks zoekt.', 'scouting-nederland'); ?></span></li>
                </ul>

                <div class="scouting-nederland-404-actions">
                    <a class="btn scouting-nederland-buttons-button scouting-nederland-buttons-button--blue" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Terug naar home', 'scouting-nederland'); ?></a>
                    <?php if ($contact_url) : ?>
                        <a class="btn scouting-nederland-buttons-button scouting-nederland-buttons-button--black" href="<?php echo esc_url($contact_url); ?>"><?php esc_html_e('Contact', 'scouting-nederland'); ?></a>
                    <?php endif; ?>
                </div>
            </article>

            <aside class="scouting-nederland-404-card scouting-nederland-404-card--side">
                <h3><?php esc_html_e('Verloren voorwerpen', 'scouting-nederland'); ?></h3>
                <p class="scouting-nederland-404-small"><?php esc_html_e('We hebben al gevonden op het terrein:', 'scouting-nederland'); ?></p>
                <ul class="scouting-nederland-404-found">
                    <li><?php esc_html_e('een routekaart zonder route', 'scouting-nederland'); ?></li>
                    <li><?php esc_html_e('drie marshmallows en een halve pionierpaal', 'scouting-nederland'); ?></li>
                    <li><?php esc_html_e('een verdwaalde hike-opdracht', 'scouting-nederland'); ?></li>
                    <li><?php esc_html_e('maar helaas niet deze pagina', 'scouting-nederland'); ?></li>
                </ul>
            </aside>
        </div>
    </section>
</main>
</div><!-- .content -->
</div><!-- .scouting-nederland-base-main -->

<?php get_footer(); ?>
