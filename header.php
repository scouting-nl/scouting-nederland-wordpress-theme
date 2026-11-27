<?php
/**
 * Displays the document head and global site header.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="scouting-nederland-base-skip-link screen-reader-text" href="#scouting-nederland-layout-content"><?php esc_html_e( 'Skip to content', 'scouting-nederland-wordpress-theme' ); ?></a>
<?php
$navclass   = 'narrow' === get_theme_mod( 'scouting_nederland_header_width', 'wide' )
	? 'scouting-nederland-header-desktop-collapsed'
	: 'navbar-expand-lg';
$show_stamp = get_theme_mod( 'scouting_nederland_logo_stamp', 1 );
$logoclass  = 'navbar-brand scouting-nederland-header-logo-link';
if ( $show_stamp ) {
	$logoclass .= ' scouting-nederland-header-logo-link-with-stamp';
}
$custom_logo_id  = get_theme_mod( 'custom_logo' );
$custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : '';

$header_show_phone = (bool) get_theme_mod( 'scouting_nederland_show_phone', 1 );

$header_show_contact = (bool) get_theme_mod( 'scouting_nederland_show_contact', 1 );

$header_show_search = (bool) get_theme_mod( 'scouting_nederland_show_search', 1 );

$header_search_url = get_theme_mod( 'scouting_nederland_search_url', '' );
// An empty custom URL opens WordPress's own search page, including its search form.
$header_search_url = $header_search_url ? $header_search_url : add_query_arg( 's', '', home_url( '/' ) );
?>
<nav id="scouting-nederland-header-nav" class="navbar navbar-light fixed-top <?php echo esc_attr( $navclass ); ?>" aria-label="<?php esc_attr_e( 'Main navigation', 'scouting-nederland-wordpress-theme' ); ?>">
	<div class="container-fluid">
		<div id="scouting-nederland-header-logo" class="navbar-brand me-auto">
			<a class="<?php echo esc_attr( $logoclass ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( $show_stamp ) : ?>
					<img class="scouting-nederland-header-logo-stamp" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/stamp-shadow.png' ); ?>" alt="" aria-hidden="true">
				<?php endif; ?>
				<?php if ( $custom_logo_url ) : ?>
					<img class="scouting-nederland-header-logo-image-custom" src="<?php echo esc_url( $custom_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php else : ?>
					<img class="scouting-nederland-header-logo-image-default" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/snl-logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php endif; ?>
			</a>
		</div>

		<div id="scouting-nederland-header-message" class="flex-grow-1">
			<?php
			$msg = get_theme_mod( 'scouting_nederland_header_message', '' );
			$url = get_theme_mod( 'scouting_nederland_header_message_url', '' );
			?>
			<?php if ( $msg ) : ?>
				<a class="scouting-nederland-header-message-link text-danger" href="<?php echo esc_url( $url ? $url : home_url( '/' ) ); ?>"><?php echo esc_html( $msg ); ?> <i class="fas fa-exclamation-triangle" aria-hidden="true"></i></a>
			<?php endif; ?>
		</div>

		<div id="scouting-nederland-header-actions">
			<?php
			$phone = get_theme_mod( 'scouting_nederland_phone', '' );
			if ( $header_show_phone && $phone ) :
				?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" class="scouting-nederland-header-action-phone" aria-label="<?php esc_attr_e( 'Call', 'scouting-nederland-wordpress-theme' ); ?>">
					<i class="fas fa-phone scouting-nederland-header-action-icon" aria-hidden="true"></i>
				</a>
			<?php endif; ?>
			<?php
			$contact = get_theme_mod( 'scouting_nederland_contact_url', '' );
			if ( $header_show_contact && $contact ) :
				?>
				<a href="<?php echo esc_url( $contact ); ?>" class="scouting-nederland-header-action-contact" aria-label="<?php esc_attr_e( 'Contact', 'scouting-nederland-wordpress-theme' ); ?>">
					<i class="fas fa-envelope scouting-nederland-header-action-icon" aria-hidden="true"></i>
				</a>
			<?php endif; ?>
			<?php
			if ( $header_show_search ) :
				?>
				<a href="<?php echo esc_url( $header_search_url ); ?>" class="scouting-nederland-header-action-search" aria-label="<?php esc_attr_e( 'Search', 'scouting-nederland-wordpress-theme' ); ?>">
					<i class="fas fa-search scouting-nederland-header-action-icon" aria-hidden="true"></i>
				</a>
			<?php endif; ?>
			<button class="navbar-toggler navbar-toggler-right scouting-nederland-header-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#scouting-nederland-header-offcanvas" aria-controls="scouting-nederland-header-offcanvas" aria-label="<?php esc_attr_e( 'Show navigation', 'scouting-nederland-wordpress-theme' ); ?>">
				<i id="scouting-nederland-header-toggle-icon" class="fa fa-bars scouting-nederland-header-action-icon" aria-hidden="true"></i><span class="scouting-nederland-header-menu-label"><?php esc_html_e( 'Menu', 'scouting-nederland-wordpress-theme' ); ?></span>
			</button>
		</div>

		<div class="scouting-nederland-header-desktop-menu collapse navbar-collapse">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
					'fallback_cb'    => '__return_false',
					'walker'         => new ScoutingNederlandBootstrapNavwalker(),
				)
			);
			?>
		</div>
	</div>
</nav>

<div class="offcanvas offcanvas-end scouting-nederland-header-offcanvas" tabindex="-1" id="scouting-nederland-header-offcanvas" aria-labelledby="scouting-nederland-header-offcanvas-label">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="scouting-nederland-header-offcanvas-label"><?php bloginfo( 'name' ); ?></h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'scouting-nederland-wordpress-theme' ); ?>"></button>
	</div>
	<div class="offcanvas-body">
		<?php
		$mobile_msg     = get_theme_mod( 'scouting_nederland_header_message', '' );
		$mobile_msg_url = get_theme_mod( 'scouting_nederland_header_message_url', '' );
		?>
		<?php if ( $mobile_msg ) : ?>
			<div class="scouting-nederland-header-offcanvas-message">
				<a href="<?php echo esc_url( $mobile_msg_url ? $mobile_msg_url : home_url( '/' ) ); ?>">
					<?php echo esc_html( $mobile_msg ); ?> <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
				</a>
			</div>
		<?php endif; ?>

		<nav class="scouting-nederland-header-offcanvas-menu" aria-label="<?php esc_attr_e( 'Mobile navigation', 'scouting-nederland-wordpress-theme' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'scouting-nederland-header-offcanvas-nav',
					'fallback_cb'    => '__return_false',
				)
			);
			?>
		</nav>

		<div class="scouting-nederland-header-offcanvas-actions">
			<?php
			$mobile_phone = get_theme_mod( 'scouting_nederland_phone', '' );
			if ( $header_show_phone && $mobile_phone ) :
				?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $mobile_phone ) ); ?>" class="scouting-nederland-header-action-phone" aria-label="<?php esc_attr_e( 'Call', 'scouting-nederland-wordpress-theme' ); ?>">
					<i class="fas fa-phone" aria-hidden="true"></i>
					<span><?php esc_html_e( 'Call', 'scouting-nederland-wordpress-theme' ); ?></span>
				</a>
			<?php endif; ?>

			<?php
			$mobile_contact = get_theme_mod( 'scouting_nederland_contact_url', '' );
			if ( $header_show_contact && $mobile_contact ) :
				?>
				<a href="<?php echo esc_url( $mobile_contact ); ?>" class="scouting-nederland-header-action-contact" aria-label="<?php esc_attr_e( 'Contact', 'scouting-nederland-wordpress-theme' ); ?>">
					<i class="fas fa-envelope" aria-hidden="true"></i>
					<span><?php esc_html_e( 'Contact', 'scouting-nederland-wordpress-theme' ); ?></span>
				</a>
			<?php endif; ?>

			<?php
			if ( $header_show_search ) :
				?>
				<a href="<?php echo esc_url( $header_search_url ); ?>" class="scouting-nederland-header-action-search" aria-label="<?php esc_attr_e( 'Search', 'scouting-nederland-wordpress-theme' ); ?>">
					<i class="fas fa-search" aria-hidden="true"></i>
					<span><?php esc_html_e( 'Search', 'scouting-nederland-wordpress-theme' ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>

