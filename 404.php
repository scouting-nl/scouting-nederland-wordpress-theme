<?php get_header(); ?>
<?php
$hero_url = get_template_directory_uri() . '/assets/images/default-slide.jpg';
$contact_url = get_theme_mod('scoutingtc4_contact_url', '');
?>
<main id="content" class="tc4-404-page" role="main">
    <section class="tc4-404-hero" style="background-image:url('<?php echo esc_url($hero_url); ?>')">
        <div class="tc4-404-hero__overlay"></div>
        <div class="container tc4-404-hero__inner">
            <span class="tc4-404-kicker"><?php esc_html_e('Oeps, verkeerd gelopen', 'scoutingtc4'); ?></span>
            <h1>404</h1>
            <p class="tc4-404-lead"><?php esc_html_e('Deze pagina is op hike zonder routekaart vertrokken.', 'scoutingtc4'); ?></p>
        </div>
    </section>

    <section class="container tc4-404-content">
        <div class="tc4-404-grid">
            <article class="tc4-404-card tc4-404-card--main">
                <div class="tc4-404-badge"><i class="fas fa-map-signs" aria-hidden="true"></i> <?php esc_html_e('Spoor bijster', 'scoutingtc4'); ?></div>
                <h2><?php esc_html_e('We zijn het spoor even kwijt', 'scoutingtc4'); ?></h2>
                <p><?php esc_html_e('Misschien heeft een welp de routemarkering omgedraaid, is de kaart natgeregend of ligt deze pagina nog bij het kampvuur. Geen zorgen: we helpen je terug naar het goede pad.', 'scoutingtc4'); ?></p>

                <ul class="tc4-404-tips">
                    <li><i class="fas fa-compass" aria-hidden="true"></i><span><?php esc_html_e('Ga terug naar de homepage en begin opnieuw.', 'scoutingtc4'); ?></span></li>
                    <li><i class="fas fa-search" aria-hidden="true"></i><span><?php esc_html_e('Zoek op een onderwerp, pagina of activiteit.', 'scoutingtc4'); ?></span></li>
                    <li><i class="fas fa-campground" aria-hidden="true"></i><span><?php esc_html_e('Of neem contact op met de groep als je iets specifieks zoekt.', 'scoutingtc4'); ?></span></li>
                </ul>

                <div class="tc4-404-actions">
                    <a class="btn tc4-btn-blue" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Terug naar home', 'scoutingtc4'); ?></a>
                    <?php if ($contact_url) : ?>
                        <a class="btn tc4-btn-black" href="<?php echo esc_url($contact_url); ?>"><?php esc_html_e('Contact', 'scoutingtc4'); ?></a>
                    <?php endif; ?>
                </div>
            </article>

            <aside class="tc4-404-card tc4-404-card--side">
                <h3><?php esc_html_e('Verloren voorwerpen', 'scoutingtc4'); ?></h3>
                <p class="tc4-404-small"><?php esc_html_e('We hebben al gevonden op het terrein:', 'scoutingtc4'); ?></p>
                <ul class="tc4-404-found">
                    <li><?php esc_html_e('een routekaart zonder route', 'scoutingtc4'); ?></li>
                    <li><?php esc_html_e('drie marshmallows en een halve pionierpaal', 'scoutingtc4'); ?></li>
                    <li><?php esc_html_e('een verdwaalde hike-opdracht', 'scoutingtc4'); ?></li>
                    <li><?php esc_html_e('maar helaas niet deze pagina', 'scoutingtc4'); ?></li>
                </ul>

                <form role="search" method="get" class="tc4-404-search" action="<?php echo esc_url(home_url('/')); ?>">
                    <label class="screen-reader-text" for="tc4-404-search-field"><?php esc_html_e('Zoeken naar:', 'scoutingtc4'); ?></label>
                    <input id="tc4-404-search-field" type="search" class="tc4-404-search__input" placeholder="<?php echo esc_attr__('Zoek op de site…', 'scoutingtc4'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" />
                    <button type="submit" class="btn tc4-btn-black"><?php esc_html_e('Zoeken', 'scoutingtc4'); ?></button>
                </form>
            </aside>
        </div>
    </section>
</main>
<?php get_footer(); ?>
