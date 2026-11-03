<?php
/**
 * Registers optional plugin recommendation notices.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

/**
 * Checks whether Scouting OpenID Connect is active on this site.
 *
 * @return bool Whether the plugin is active.
 */
function scouting_nederland_is_scouting_openid_connect_active() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$active_plugins = (array) get_option( 'active_plugins', array() );

	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, array_keys( (array) get_site_option( 'active_sitewide_plugins', array() ) ) );
	}

	foreach ( $active_plugins as $plugin_file ) {
		if ( strpos( $plugin_file, 'scouting-openid-connect/' ) === 0 ) {
			return true;
		}
	}

	return false;
}

/**
 * Displays the optional Scouting login plugin recommendation.
 *
 * @return void
 */
function scouting_nederland_scouting_openid_connect_notice() {
	if ( ! is_admin() || ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	if ( get_user_meta( get_current_user_id(), 'scouting_nederland_openid_notice_dismissed', true ) ) {
		return;
	}

	if ( scouting_nederland_is_scouting_openid_connect_active() ) {
		update_user_meta( get_current_user_id(), 'scouting_nederland_openid_notice_dismissed', 1 );
		return;
	}

	$install_url = wp_nonce_url(
		self_admin_url( 'update.php?action=install-plugin&plugin=scouting-openid-connect' ),
		'install-plugin_scouting-openid-connect'
	);
	$dismiss_url = wp_nonce_url(
		self_admin_url( 'admin-post.php?action=scouting_nederland_dismiss_openid_notice' ),
		'scouting_nederland_dismiss_openid_notice'
	);
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<strong><?php esc_html_e( 'Scouting login beschikbaar', 'scouting-nederland' ); ?></strong><br>
			<?php esc_html_e( 'Installeer optioneel de plugin Scouting OpenID Connect om gebruikers via Scouting te laten inloggen.', 'scouting-nederland' ); ?>
		</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $install_url ); ?>">
				<?php esc_html_e( 'Scouting OpenID Connect direct installeren', 'scouting-nederland' ); ?>
			</a>
			<a class="button button-link" href="<?php echo esc_url( $dismiss_url ); ?>">
				<?php esc_html_e( 'Niet meer tonen', 'scouting-nederland' ); ?>
			</a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'scouting_nederland_scouting_openid_connect_notice' );

/**
 * Dismisses the Scouting login plugin recommendation for the current user.
 *
 * @return void
 */
function scouting_nederland_dismiss_openid_notice() {
	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_die( esc_html__( 'Je hebt geen rechten om deze melding te verbergen.', 'scouting-nederland' ) );
	}

	check_admin_referer( 'scouting_nederland_dismiss_openid_notice' );
	update_user_meta( get_current_user_id(), 'scouting_nederland_openid_notice_dismissed', 1 );

	$referer = wp_get_referer();
	wp_safe_redirect( $referer ? $referer : self_admin_url( 'themes.php' ) );
	exit;
}
add_action( 'admin_post_scouting_nederland_dismiss_openid_notice', 'scouting_nederland_dismiss_openid_notice' );

/**
 * Checks whether the companion blocks plugin is active.
 *
 * @return bool Whether the plugin is active.
 */
function scouting_nederland_is_blocks_plugin_active() {
	return defined( 'SCOUTING_NEDERLAND_BLOCKS_VERSION' );
}

/**
 * Finds the installed companion blocks plugin file.
 *
 * @return string The plugin file path, or an empty string when not installed.
 */
function scouting_nederland_get_installed_blocks_plugin_file() {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	foreach ( array_keys( get_plugins() ) as $plugin_file ) {
		if ( strpos( $plugin_file, 'scouting-nederland-blocks/' ) === 0 ) {
			return $plugin_file;
		}
	}

	return '';
}

/**
 * Shows an optional companion-plugin recommendation for the theme blocks.
 *
 * The install action targets the WordPress.org plugin slug. WordPress.org theme
 * review rules permit recommended plugins only when they are hosted there and
 * installation is explicitly started by the user.
 *
 * @return void
 */
function scouting_nederland_blocks_plugin_notice() {
	if ( ! is_admin() || ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	if ( scouting_nederland_is_blocks_plugin_active() ) {
		return;
	}

	if ( get_user_meta( get_current_user_id(), 'scouting_nederland_blocks_notice_dismissed', true ) ) {
		return;
	}

	$plugin_file = scouting_nederland_get_installed_blocks_plugin_file();

	if ( $plugin_file && current_user_can( 'activate_plugins' ) ) {
		$action_url  = wp_nonce_url(
			self_admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $plugin_file ) ),
			'activate-plugin_' . $plugin_file
		);
		$button_text = __( 'Scouting Nederland Blocks activeren', 'scouting-nederland' );
	} else {
		$action_url  = wp_nonce_url(
			self_admin_url( 'update.php?action=install-plugin&plugin=scouting-nederland-blocks' ),
			'install-plugin_scouting-nederland-blocks'
		);
		$button_text = __( 'Scouting Nederland Blocks installeren', 'scouting-nederland' );
	}

	$dismiss_url = wp_nonce_url(
		self_admin_url( 'admin-post.php?action=scouting_nederland_dismiss_blocks_notice' ),
		'scouting_nederland_dismiss_blocks_notice'
	);
	?>
	<div class="notice notice-info">
		<p>
			<strong><?php esc_html_e( 'Extra Scouting Nederland blokken', 'scouting-nederland' ); ?></strong><br>
			<?php esc_html_e( 'De companion-plugin Scouting Nederland Blocks voegt de optionele Gutenberg-blokken van dit theme toe. Het theme blijft zonder deze plugin bruikbaar.', 'scouting-nederland' ); ?>
		</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $action_url ); ?>">
				<?php echo esc_html( $button_text ); ?>
			</a>
			<a class="button button-link" href="<?php echo esc_url( $dismiss_url ); ?>">
				<?php esc_html_e( 'Niet meer tonen', 'scouting-nederland' ); ?>
			</a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'scouting_nederland_blocks_plugin_notice' );

/**
 * Dismisses the companion blocks plugin recommendation for the current user.
 *
 * @return void
 */
function scouting_nederland_dismiss_blocks_notice() {
	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_die( esc_html__( 'Je hebt geen rechten om deze melding te verbergen.', 'scouting-nederland' ) );
	}

	check_admin_referer( 'scouting_nederland_dismiss_blocks_notice' );
	update_user_meta( get_current_user_id(), 'scouting_nederland_blocks_notice_dismissed', 1 );

	$referer = wp_get_referer();
	wp_safe_redirect( $referer ? $referer : self_admin_url( 'themes.php' ) );
	exit;
}
add_action( 'admin_post_scouting_nederland_dismiss_blocks_notice', 'scouting_nederland_dismiss_blocks_notice' );
