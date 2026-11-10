<?php
/**
 * Registers optional plugin recommendation notices.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

/**
 * Defines optional plugin recommendations published on WordPress.org.
 *
 * @return array[] Recommendations keyed by plugin slug, with translated labels
 *                 and a stable per-user dismissal meta key.
 */
function scouting_nederland_get_plugin_recommendations() {
	return array(
		'scouting-openid-connect' => array(
			'title'              => __( 'Scouting login beschikbaar', 'scouting-nederland-wordpress-theme' ),
			'description'        => __( 'Met de optionele plugin Scouting OpenID Connect kunnen gebruikers via Scouting Nederland inloggen. Het theme werkt ook zonder deze plugin.', 'scouting-nederland-wordpress-theme' ),
			'install_text'       => __( 'Scouting OpenID Connect installeren', 'scouting-nederland-wordpress-theme' ),
			'activate_text'      => __( 'Scouting OpenID Connect activeren', 'scouting-nederland-wordpress-theme' ),
			'dismissed_meta_key' => 'scouting_nederland_openid_notice_dismissed',
		),
	);
}

/**
 * Finds the installed file for a WordPress.org plugin slug.
 *
 * @param string $slug Plugin directory slug.
 * @return string The plugin file path, or an empty string when not installed.
 */
function scouting_nederland_get_installed_plugin_file( $slug ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	foreach ( array_keys( get_plugins() ) as $plugin_file ) {
		if ( strpos( $plugin_file, $slug . '/' ) === 0 ) {
			return $plugin_file;
		}
	}

	return '';
}

/**
 * Renders one recommendation for an eligible theme administrator.
 *
 * @param string $slug           WordPress.org plugin slug.
 * @param array  $recommendation {
 *     Translated notice content and persistent dismissal settings.
 *
 *     @type string $title              Notice heading.
 *     @type string $description        Optional plugin description.
 *     @type string $install_text       Installation button text.
 *     @type string $activate_text      Activation button text.
 *     @type string $dismissed_meta_key Per-user dismissal meta key.
 * }
 * @return void
 */
function scouting_nederland_render_plugin_recommendation_notice( $slug, $recommendation ) {
	if ( ! is_admin() || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	if ( get_user_meta( get_current_user_id(), $recommendation['dismissed_meta_key'], true ) ) {
		return;
	}

	$plugin_file = scouting_nederland_get_installed_plugin_file( $slug );
	if ( $plugin_file ) {
		if ( is_plugin_active( $plugin_file ) || ! current_user_can( 'activate_plugin', $plugin_file ) ) {
			return;
		}
		$action_url  = wp_nonce_url(
			self_admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $plugin_file ) ),
			'activate-plugin_' . $plugin_file
		);
		$button_text = $recommendation['activate_text'];
	} elseif ( current_user_can( 'install_plugins' ) ) {
		$action_url  = wp_nonce_url(
			self_admin_url( 'update.php?action=install-plugin&plugin=' . rawurlencode( $slug ) ),
			'install-plugin_' . $slug
		);
		$button_text = $recommendation['install_text'];
	} else {
		return;
	}

	$dismiss_url = wp_nonce_url(
		add_query_arg(
			array(
				'action' => 'scouting_nederland_dismiss_plugin_notice',
				'plugin' => $slug,
			),
			self_admin_url( 'admin-post.php' )
		),
		'scouting_nederland_dismiss_plugin_notice_' . $slug
	);
	?>
	<div class="notice notice-info">
		<p>
			<strong><?php echo esc_html( $recommendation['title'] ); ?></strong><br>
			<?php echo esc_html( $recommendation['description'] ); ?>
		</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $action_url ); ?>">
				<?php echo esc_html( $button_text ); ?>
			</a>
			<a class="button button-secondary" href="<?php echo esc_url( 'https://wordpress.org/plugins/' . $slug . '/' ); ?>">
				<?php esc_html_e( 'Meer informatie', 'scouting-nederland-wordpress-theme' ); ?>
			</a>
			<a class="button button-link" href="<?php echo esc_url( $dismiss_url ); ?>">
				<?php esc_html_e( 'Niet meer tonen', 'scouting-nederland-wordpress-theme' ); ?>
			</a>
		</p>
	</div>
	<?php
}

/**
 * Displays the configured optional plugin recommendations.
 *
 * @return void
 */
function scouting_nederland_plugin_recommendation_notices() {
	foreach ( scouting_nederland_get_plugin_recommendations() as $slug => $recommendation ) {
		scouting_nederland_render_plugin_recommendation_notice( $slug, $recommendation );
	}
}
add_action( 'admin_notices', 'scouting_nederland_plugin_recommendation_notices' );

/**
 * Dismisses a configured recommendation for the current user.
 *
 * @return void
 */
function scouting_nederland_dismiss_plugin_notice() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Je hebt geen rechten om deze melding te verbergen.', 'scouting-nederland-wordpress-theme' ) );
	}

	$slug            = isset( $_GET['plugin'] ) && is_string( $_GET['plugin'] ) ? sanitize_key( wp_unslash( $_GET['plugin'] ) ) : '';
	$recommendations = scouting_nederland_get_plugin_recommendations();
	if ( ! isset( $recommendations[ $slug ] ) ) {
		wp_die( esc_html__( 'Ongeldige pluginaanbeveling.', 'scouting-nederland-wordpress-theme' ), '', array( 'response' => 400 ) );
	}

	check_admin_referer( 'scouting_nederland_dismiss_plugin_notice_' . $slug );
	update_user_meta( get_current_user_id(), $recommendations[ $slug ]['dismissed_meta_key'], 1 );

	$referer = wp_get_referer();
	wp_safe_redirect( $referer ? $referer : self_admin_url( 'themes.php' ) );
	exit;
}
add_action( 'admin_post_scouting_nederland_dismiss_plugin_notice', 'scouting_nederland_dismiss_plugin_notice' );
