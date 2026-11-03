<?php
/**
 * Displays the search results page.
 *
 * @package Scouting_Nederland
 */

get_header();
?>
<?php
$search_query     = trim( get_search_query() );
$has_search_query = '' !== $search_query;
?>

<div class="scouting-nederland-base-main"><div class="scouting-nederland-layout-shell">
<main id="scouting-nederland-layout-content" class="scouting-nederland-search-page" role="main">
	<section class="scouting-nederland-search-hero">
		<div class="container scouting-nederland-search-hero-inner">
			<span class="scouting-nederland-search-kicker">
				<i class="fas fa-search" aria-hidden="true"></i>
				<?php esc_html_e( 'Zoeken op de site', 'scouting-nederland' ); ?>
			</span>

			<?php if ( ! $has_search_query ) : ?>
				<h1><?php esc_html_e( 'Waar wil je naar zoeken?', 'scouting-nederland' ); ?></h1>
				<p><?php esc_html_e( 'Vul een zoekterm in, dan pakken we de kaart erbij.', 'scouting-nederland' ); ?></p>
			<?php elseif ( have_posts() ) : ?>
				<h1>
					<?php
					printf(
						/* translators: %s: Search query. */
						esc_html__( 'Zoekresultaten voor: %s', 'scouting-nederland' ),
						'<span>' . esc_html( $search_query ) . '</span>'
					);
					?>
				</h1>
				<p><?php esc_html_e( 'We hebben de kaart erbij gepakt en dit spoor gevonden.', 'scouting-nederland' ); ?></p>
			<?php else : ?>
				<h1><?php esc_html_e( 'Geen spoor gevonden', 'scouting-nederland' ); ?></h1>
				<p><?php esc_html_e( 'Deze zoekterm zit waarschijnlijk nog ergens in de hike-rugzak. Probeer eens een andere route.', 'scouting-nederland' ); ?></p>
			<?php endif; ?>

			<?php
			get_search_form(
				array(
					'form_id'      => 'scouting-nederland-search-form',
					'form_class'   => 'scouting-nederland-search-form',
					'input_id'     => 'scouting-nederland-search-field',
					'input_class'  => 'scouting-nederland-search-form-input',
					'button_class' => 'scouting-nederland-search-form-button',
					'placeholder'  => __( 'Waar ben je naar op zoek?', 'scouting-nederland' ),
					'button_label' => __( 'Zoeken', 'scouting-nederland' ),
					'required'     => true,
				)
			);
			?>
		</div>
	</section>

	<section class="container scouting-nederland-search-content">
		<?php if ( ! $has_search_query ) : ?>
			<div class="scouting-nederland-search-empty">
				<div class="scouting-nederland-search-empty-icon">
					<i class="fas fa-compass" aria-hidden="true"></i>
				</div>
				<h2><?php esc_html_e( 'Nog geen zoekterm ingevuld', 'scouting-nederland' ); ?></h2>
				<p><?php esc_html_e( 'Typ bijvoorbeeld een activiteit, speltak, verhuur of contactpersoon in het zoekveld hierboven.', 'scouting-nederland' ); ?></p>
			</div>
		<?php elseif ( have_posts() ) : ?>
			<div class="scouting-nederland-search-count">
				<?php
				global $wp_query;
				printf(
					esc_html(
						/* translators: %s: Number of search results. */
						_n( '%s resultaat gevonden', '%s resultaten gevonden', (int) $wp_query->found_posts, 'scouting-nederland' )
					),
					esc_html( number_format_i18n( (int) $wp_query->found_posts ) )
				);
				?>
			</div>

			<div class="scouting-nederland-search-results">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'scouting-nederland-search-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="scouting-nederland-search-card-image" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
								<?php the_post_thumbnail( 'medium_large' ); ?>
							</a>
						<?php else : ?>
							<div class="scouting-nederland-search-card-image scouting-nederland-search-card-image-fallback" aria-hidden="true">
								<i class="fas fa-compass" aria-hidden="true"></i>
							</div>
						<?php endif; ?>

						<div class="scouting-nederland-search-card-content">
							<div class="scouting-nederland-search-card-meta">
								<?php
								$result_type_object = get_post_type_object( get_post_type() );
								echo esc_html( $result_type_object ? $result_type_object->labels->singular_name : __( 'Pagina', 'scouting-nederland' ) );
								?>
								<?php if ( 'page' !== get_post_type() && get_the_date() ) : ?>
									<span aria-hidden="true">•</span>
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								<?php endif; ?>
							</div>

							<h2 class="scouting-nederland-search-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<div class="scouting-nederland-search-card-excerpt">
								<?php the_excerpt(); ?>
							</div>

							<a class="scouting-nederland-search-card-link" href="<?php the_permalink(); ?>">
								<?php esc_html_e( 'Bekijk resultaat', 'scouting-nederland' ); ?> <span aria-hidden="true">›</span>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="scouting-nederland-search-pagination">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( '‹ Vorige', 'scouting-nederland' ),
						'next_text' => __( 'Volgende ›', 'scouting-nederland' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<div class="scouting-nederland-search-empty">
				<div class="scouting-nederland-search-empty-icon">
					<i class="fas fa-map-signs" aria-hidden="true"></i>
				</div>
				<h2><?php esc_html_e( 'De route loopt hier dood', 'scouting-nederland' ); ?></h2>
				<p><?php esc_html_e( 'Controleer je zoekterm, probeer een korter woord of ga terug naar de homepage.', 'scouting-nederland' ); ?></p>
				<div class="scouting-nederland-search-empty-actions">
					<a class="btn scouting-nederland-buttons-button scouting-nederland-buttons-button-blue" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Terug naar home', 'scouting-nederland' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</section>
</main>
</div><!-- .content -->
</div><!-- .scouting-nederland-base-main -->

<?php get_footer(); ?>
