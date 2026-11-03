<?php
/**
 * Displays a singular page.
 *
 * @package Scouting_Nederland
 */

get_header();
?>
<?php
$scouting_nederland_page_id    = get_queried_object_id();
$scouting_nederland_page_title = '';
if ( have_posts() ) {
	the_post();
	$scouting_nederland_page_id    = get_the_ID();
	$scouting_nederland_page_title = get_the_title();
	rewind_posts();
}
$scouting_nederland_page_display_settings = scouting_nederland_get_page_display_settings( $scouting_nederland_page_id );
$scouting_nederland_hide_hero             = ! empty( $scouting_nederland_page_display_settings['hide_hero'] );
$scouting_nederland_hide_page_title       = ! empty( $scouting_nederland_page_display_settings['hide_page_title'] );
$scouting_nederland_hide_sidebar          = ! empty( $scouting_nederland_page_display_settings['hide_sidebar'] );
$scouting_nederland_is_elementor_page     = scouting_nederland_is_elementor_page( $scouting_nederland_page_id );
if ( $scouting_nederland_is_elementor_page ) {
	$scouting_nederland_hide_hero       = true;
	$scouting_nederland_hide_page_title = true;
	$scouting_nederland_hide_sidebar    = true;
}
$scouting_nederland_has_sidebar          = is_active_sidebar( 'sidebar' ) && ! $scouting_nederland_hide_sidebar;
$scouting_nederland_rendered_slider      = false;
$scouting_nederland_hero_font_class      = scouting_nederland_get_hero_title_font_class( $scouting_nederland_page_id );
$scouting_nederland_page_container_class = $scouting_nederland_is_elementor_page ? '' : 'container';
$scouting_nederland_maincontent_class    = $scouting_nederland_is_elementor_page ? '' : 'row';
$scouting_nederland_content_class        = 'col-md-12';
if ( $scouting_nederland_is_elementor_page ) {
	$scouting_nederland_content_class = '';
} elseif ( $scouting_nederland_has_sidebar ) {
	$scouting_nederland_content_class = 'col-md-8 col-lg-9';
}
?>
<div class="scouting-nederland-base-main"><div class="scouting-nederland-layout-shell">
<?php
if ( ! $scouting_nederland_is_elementor_page ) {
	$scouting_nederland_rendered_slider = scouting_nederland_render_home_slider( $scouting_nederland_page_id ); }
?>
<?php if ( ! $scouting_nederland_is_elementor_page && ! $scouting_nederland_hide_hero && ! $scouting_nederland_rendered_slider ) : ?>
<div class="scouting-nederland-base-widget-hero container-fluid px-0 scouting-nederland-page-hero <?php echo esc_attr( $scouting_nederland_hero_font_class ); ?>">
	<div class="row g-0"><div class="col scouting-nederland-page-hero-media">
		<?php
		if ( is_active_sidebar( 'hero' ) ) {
			dynamic_sidebar( 'hero' ); } else {
			?>
			<div class="scouting-nederland-base-default-banner scouting-nederland-base-default-banner-low scouting-nederland-page-hero-fallback" style="background-image:url('<?php echo esc_url( scouting_nederland_get_default_hero_url() ); ?>')"></div>
					<?php } ?>
		<?php if ( $scouting_nederland_page_title && ! $scouting_nederland_hide_page_title ) : ?>
			<div class="scouting-nederland-page-hero-title <?php echo esc_attr( $scouting_nederland_hero_font_class ); ?>" aria-hidden="false">
				<div class="container">
					<h1 class="<?php echo esc_attr( $scouting_nederland_hero_font_class ); ?>"><?php echo esc_html( $scouting_nederland_page_title ); ?></h1>
				</div>
			</div>
		<?php endif; ?>
	</div></div>
</div>
<?php endif; ?>
<?php if ( ! $scouting_nederland_is_elementor_page ) : ?>
<div class="container">
	<?php if ( is_active_sidebar( 'featured-a' ) || is_active_sidebar( 'featured-b' ) || is_active_sidebar( 'featured-c' ) ) : ?>
	<div class="row scouting-nederland-page-featured">
		<div class="col-sm-4"><?php dynamic_sidebar( 'featured-a' ); ?></div>
		<div class="col-sm-4"><?php dynamic_sidebar( 'featured-b' ); ?></div>
		<div class="col-sm-4"><?php dynamic_sidebar( 'featured-c' ); ?></div>
	</div>
	<?php endif; ?>
</div>
	<?php if ( is_active_sidebar( 'showcase' ) ) : ?>
<div class="container-fluid px-0 text-light bg-success"><div class="scouting-nederland-layout-showcase-skyline"></div><div class="container"><?php dynamic_sidebar( 'showcase' ); ?></div></div>
<?php endif; ?>
<?php endif; ?>
<div class="<?php echo esc_attr( $scouting_nederland_page_container_class ); ?>">
	<?php if ( ! $scouting_nederland_is_elementor_page ) : ?>
		<?php
		if ( is_active_sidebar( 'news' ) ) :
			?>
			<div class="row"><div class="col-sm-12"><?php dynamic_sidebar( 'news' ); ?></div></div><?php endif; ?>
		<?php
		if ( is_active_sidebar( 'breadcrumbs' ) ) :
			?>
			<div class="row"><div class="col-sm-12"><?php dynamic_sidebar( 'breadcrumbs' ); ?></div></div><?php endif; ?>
	<?php endif; ?>
	<div class="<?php echo esc_attr( $scouting_nederland_maincontent_class ); ?>">
		<main id="scouting-nederland-layout-content" role="main" class="<?php echo esc_attr( $scouting_nederland_content_class ); ?>">
			<?php if ( have_posts() ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<?php if ( $scouting_nederland_is_elementor_page ) : ?>
						<?php the_content(); ?>
					<?php else : ?>
						<?php get_template_part( 'parts/content' ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
				<?php if ( ! $scouting_nederland_is_elementor_page ) : ?>
					<?php the_posts_pagination(); ?>
				<?php endif; ?>
			<?php else : ?>
				<p><?php esc_html_e( 'Geen inhoud gevonden.', 'scouting-nederland' ); ?></p>
			<?php endif; ?>
		</main>
		<?php if ( $scouting_nederland_has_sidebar ) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>
	</div>
	<?php if ( ! $scouting_nederland_is_elementor_page && ( is_active_sidebar( 'bottom-a' ) || is_active_sidebar( 'bottom-b' ) ) ) : ?>
	<div class="row"><div class="<?php echo ( is_active_sidebar( 'bottom-a' ) && is_active_sidebar( 'bottom-b' ) ) ? 'col-md-6' : 'col-md-12'; ?>"><?php dynamic_sidebar( 'bottom-a' ); ?></div><div class="<?php echo ( is_active_sidebar( 'bottom-a' ) && is_active_sidebar( 'bottom-b' ) ) ? 'col-md-6' : 'col-md-12'; ?>"><?php dynamic_sidebar( 'bottom-b' ); ?></div></div>
	<?php endif; ?>
</div>
</div><!-- .content -->
</div><!-- .scouting-nederland-base-main -->
<?php get_footer(); ?>
