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
				<?php esc_html_e( 'Search the site', 'scouting-nederland-wordpress-theme' ); ?>
			</span>

			<?php if ( ! $has_search_query ) : ?>
				<h1><?php esc_html_e( 'What would you like to search for?', 'scouting-nederland-wordpress-theme' ); ?></h1>
				<p><?php esc_html_e( 'Enter a search term and we will get the map out.', 'scouting-nederland-wordpress-theme' ); ?></p>
			<?php elseif ( have_posts() ) : ?>
				<h1>
					<?php
					printf(
						/* translators: %s: Search query. */
						esc_html__( 'Search results for: %s', 'scouting-nederland-wordpress-theme' ),
						'<span>' . esc_html( $search_query ) . '</span>'
					);
					?>
				</h1>
				<p><?php esc_html_e( 'We checked the map and found this trail.', 'scouting-nederland-wordpress-theme' ); ?></p>
			<?php else : ?>
				<h1><?php esc_html_e( 'No trail found', 'scouting-nederland-wordpress-theme' ); ?></h1>
				<p><?php esc_html_e( 'This search term is probably still somewhere in the hiking backpack. Try another route.', 'scouting-nederland-wordpress-theme' ); ?></p>
			<?php endif; ?>

			<?php
			get_search_form(
				array(
					'form_id'      => 'scouting-nederland-search-form',
					'form_class'   => 'scouting-nederland-search-form',
					'input_id'     => 'scouting-nederland-search-field',
					'input_class'  => 'scouting-nederland-search-form-input',
					'button_class' => 'scouting-nederland-search-form-button',
					'placeholder'  => __( 'What are you looking for?', 'scouting-nederland-wordpress-theme' ),
					'button_label' => __( 'Search', 'scouting-nederland-wordpress-theme' ),
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
				<h2><?php esc_html_e( 'No search term entered yet', 'scouting-nederland-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Enter an activity, age section, rental, or contact person in the search field above.', 'scouting-nederland-wordpress-theme' ); ?></p>
			</div>
		<?php elseif ( have_posts() ) : ?>
			<div class="scouting-nederland-search-count">
				<?php
				global $wp_query;
				printf(
					esc_html(
						/* translators: %s: Number of search results. */
						_n( '%s result found', '%s results found', (int) $wp_query->found_posts, 'scouting-nederland-wordpress-theme' )
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
								echo esc_html( $result_type_object ? $result_type_object->labels->singular_name : __( 'Page', 'scouting-nederland-wordpress-theme' ) );
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
								<?php esc_html_e( 'View result', 'scouting-nederland-wordpress-theme' ); ?> <span aria-hidden="true">›</span>
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
						'prev_text' => __( '‹ Previous', 'scouting-nederland-wordpress-theme' ),
						'next_text' => __( 'Next ›', 'scouting-nederland-wordpress-theme' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<div class="scouting-nederland-search-empty">
				<div class="scouting-nederland-search-empty-icon">
					<i class="fas fa-map-signs" aria-hidden="true"></i>
				</div>
				<h2><?php esc_html_e( 'This route is a dead end', 'scouting-nederland-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Check your search term, try a shorter word, or return to the homepage.', 'scouting-nederland-wordpress-theme' ); ?></p>
				<div class="scouting-nederland-search-empty-actions">
					<a class="btn scouting-nederland-buttons-button scouting-nederland-buttons-button-blue" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'scouting-nederland-wordpress-theme' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</section>
</main>
</div><!-- .content -->
</div><!-- .scouting-nederland-base-main -->

<?php get_footer(); ?>
