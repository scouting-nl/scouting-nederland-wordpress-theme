<?php
/**
 * Displays posts in a category archive.
 *
 * @package Scouting_Nederland
 */

get_header();
?>
<div class="scouting-nederland-base-main"><div class="scouting-nederland-layout-shell">
<div class="scouting-nederland-base-widget-hero container-fluid px-0">
	<div class="row g-0"><div class="col">
		<?php
		if ( is_active_sidebar( 'hero' ) ) {
			dynamic_sidebar( 'hero' ); } else {
			?>
			<div class="scouting-nederland-base-default-banner scouting-nederland-base-default-banner-low" style="background-image:url('<?php echo esc_url( scouting_nederland_get_default_hero_url() ); ?>')"></div>
					<?php } ?>
	</div></div>
</div>
<div class="container">
	<?php if ( is_active_sidebar( 'featured-a' ) || is_active_sidebar( 'featured-b' ) || is_active_sidebar( 'featured-c' ) ) : ?>
	<div class="row">
		<div class="col-sm-4"><?php dynamic_sidebar( 'featured-a' ); ?></div>
		<div class="col-sm-4"><?php dynamic_sidebar( 'featured-b' ); ?></div>
		<div class="col-sm-4"><?php dynamic_sidebar( 'featured-c' ); ?></div>
	</div>
	<?php endif; ?>
</div>
<?php if ( is_active_sidebar( 'showcase' ) ) : ?>
<div class="container-fluid px-0 text-light bg-success"><div class="scouting-nederland-layout-showcase-skyline"></div><div class="container"><?php dynamic_sidebar( 'showcase' ); ?></div></div>
<?php endif; ?>
<div class="container scouting-nederland-category-page">
	<?php
	if ( is_active_sidebar( 'breadcrumbs' ) ) :
		?>
		<div class="row"><div class="col-sm-12"><?php dynamic_sidebar( 'breadcrumbs' ); ?></div></div><?php endif; ?>

	<header class="scouting-nederland-category-header">
		<p class="scouting-nederland-category-header-eyebrow"><?php esc_html_e( 'Category', 'scouting-nederland' ); ?></p>
		<h1 class="scouting-nederland-category-header-title"><?php single_cat_title(); ?></h1>
		<?php if ( category_description() ) : ?>
			<div class="scouting-nederland-category-header-description"><?php echo wp_kses_post( category_description() ); ?></div>
		<?php endif; ?>
	</header>

	<main id="scouting-nederland-layout-content" role="main">
		<?php if ( have_posts() ) : ?>
			<div class="scouting-nederland-category-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<?php
					$image_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : scouting_nederland_get_default_hero_url();
					$summary   = get_the_excerpt();
					if ( ! $summary ) {
						$summary = get_the_content();
					}
					$summary = scouting_nederland_trim_text_chars( $summary, 142 );
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'scouting-nederland-category-card' ); ?>>
						<a class="scouting-nederland-category-card-media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
							<span class="scouting-nederland-category-card-date"><?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
						</a>
						<div class="scouting-nederland-category-card-titlebar">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</div>
						<div class="scouting-nederland-category-card-body">
							<?php
							if ( $summary ) :
								?>
								<p><?php echo esc_html( $summary ); ?></p><?php endif; ?>
							<a class="scouting-nederland-category-card-readmore" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'scouting-nederland' ); ?> ›</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="scouting-nederland-category-pagination">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( 'Previous', 'scouting-nederland' ),
						'next_text' => __( 'Next', 'scouting-nederland' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<section class="scouting-nederland-category-empty">
				<h2><?php esc_html_e( 'No posts found', 'scouting-nederland' ); ?></h2>
				<p><?php esc_html_e( 'There are no posts in this category yet.', 'scouting-nederland' ); ?></p>
			</section>
		<?php endif; ?>
	</main>

	<?php if ( is_active_sidebar( 'bottom-a' ) || is_active_sidebar( 'bottom-b' ) ) : ?>
	<div class="row"><div class="<?php echo ( is_active_sidebar( 'bottom-a' ) && is_active_sidebar( 'bottom-b' ) ) ? 'col-md-6' : 'col-md-12'; ?>"><?php dynamic_sidebar( 'bottom-a' ); ?></div><div class="<?php echo ( is_active_sidebar( 'bottom-a' ) && is_active_sidebar( 'bottom-b' ) ) ? 'col-md-6' : 'col-md-12'; ?>"><?php dynamic_sidebar( 'bottom-b' ); ?></div></div>
	<?php endif; ?>
</div>
</div><!-- .content -->
</div><!-- .scouting-nederland-base-main -->
<?php get_footer(); ?>
