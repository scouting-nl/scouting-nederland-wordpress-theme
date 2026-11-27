<?php
/**
 * Displays the theme's not-found page.
 *
 * @package Scouting_Nederland
 */

get_header();
?>
<?php
$contact_url = get_theme_mod( 'scouting_nederland_contact_url', '' );
$hero_url    = get_template_directory_uri() . '/assets/images/default-slide.jpg';
?>

<div class="scouting-nederland-base-main"><div class="scouting-nederland-layout-shell">
<main id="scouting-nederland-layout-content" class="scouting-nederland-404-page" role="main">
	<section class="scouting-nederland-404-hero" style="background-image:linear-gradient(180deg, rgba(255,255,255,.94) 0%, rgba(255,255,255,.88) 100%), url('<?php echo esc_url( $hero_url ); ?>');">
		<div class="container scouting-nederland-404-hero-inner">
			<span class="scouting-nederland-404-kicker">
				<i class="fas fa-map-signs" aria-hidden="true"></i>
				<?php esc_html_e( 'Oops, wrong turn', 'scouting-nederland-wordpress-theme' ); ?>
			</span>

			<h1><?php esc_html_e( '404', 'scouting-nederland-wordpress-theme' ); ?></h1>
			<p><?php esc_html_e( 'This page went hiking without a route map.', 'scouting-nederland-wordpress-theme' ); ?></p>

			<?php
			get_search_form(
				array(
					'form_id'      => 'scouting-nederland-404-search-form',
					'form_class'   => 'scouting-nederland-search-form scouting-nederland-404-search-form',
					'input_id'     => 'scouting-nederland-404-search-field',
					'input_class'  => 'scouting-nederland-search-form-input',
					'button_class' => 'scouting-nederland-search-form-button',
					'placeholder'  => __( 'Search the site…', 'scouting-nederland-wordpress-theme' ),
					'button_label' => __( 'Search', 'scouting-nederland-wordpress-theme' ),
				)
			);
			?>
		</div>
	</section>

	<section class="container scouting-nederland-404-content">
		<div class="scouting-nederland-404-grid">
			<article class="scouting-nederland-404-card scouting-nederland-404-card-main">
				<div class="scouting-nederland-404-badge"><i class="fas fa-compass" aria-hidden="true"></i> <?php esc_html_e( 'Off the trail', 'scouting-nederland-wordpress-theme' ); ?></div>
				<h2><?php esc_html_e( 'We have lost the trail', 'scouting-nederland-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Maybe a Cub Scout turned a trail marker around, the map got soaked, or this page is still by the campfire. No worries: we will help you find your way back.', 'scouting-nederland-wordpress-theme' ); ?></p>

				<ul class="scouting-nederland-404-tips">
					<li><i class="fas fa-home" aria-hidden="true"></i><span><?php esc_html_e( 'Return to the homepage and start again.', 'scouting-nederland-wordpress-theme' ); ?></span></li>
					<li><i class="fas fa-search" aria-hidden="true"></i><span><?php esc_html_e( 'Search for a topic, page, or activity.', 'scouting-nederland-wordpress-theme' ); ?></span></li>
					<li><i class="fas fa-campground" aria-hidden="true"></i><span><?php esc_html_e( 'Or contact the group if you are looking for something specific.', 'scouting-nederland-wordpress-theme' ); ?></span></li>
				</ul>

				<div class="scouting-nederland-404-actions">
					<a class="btn scouting-nederland-buttons-button scouting-nederland-buttons-button-blue" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'scouting-nederland-wordpress-theme' ); ?></a>
					<?php if ( $contact_url ) : ?>
						<a class="btn scouting-nederland-buttons-button scouting-nederland-buttons-button-black" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Contact', 'scouting-nederland-wordpress-theme' ); ?></a>
					<?php endif; ?>
				</div>
			</article>

			<aside class="scouting-nederland-404-card scouting-nederland-404-card-side">
				<h3><?php esc_html_e( 'Lost and found', 'scouting-nederland-wordpress-theme' ); ?></h3>
				<p class="scouting-nederland-404-small"><?php esc_html_e( 'We have already found these around the campsite:', 'scouting-nederland-wordpress-theme' ); ?></p>
				<ul class="scouting-nederland-404-found">
					<li><?php esc_html_e( 'a route map without a route', 'scouting-nederland-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'three marshmallows and half a pioneering pole', 'scouting-nederland-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'a lost hiking challenge', 'scouting-nederland-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'but sadly not this page', 'scouting-nederland-wordpress-theme' ); ?></li>
				</ul>
			</aside>
		</div>
	</section>
</main>
</div><!-- .content -->
</div><!-- .scouting-nederland-base-main -->

<?php get_footer(); ?>
