<?php
/**
 * Registers page-level display settings.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Returns the available hero font choices.
 *
 * @return array<string, string> Font keys and translated labels.
 */
function scouting_nederland_get_hero_font_choices() {
	return array(
		'impact'      => __( 'Impact', 'scouting-nederland' ),
		'roboto-slab' => __( 'Roboto Slab', 'scouting-nederland' ),
		'poppins'     => __( 'Poppins', 'scouting-nederland' ),
	);
}

/**
 * Sanitizes a global hero font choice.
 *
 * @param string $value The submitted font key.
 * @return string A supported font key.
 */
function scouting_nederland_sanitize_hero_font( $value ) {
	$value   = sanitize_key( $value );
	$choices = scouting_nederland_get_hero_font_choices();
	return array_key_exists( $value, $choices ) ? $value : 'impact';
}

/**
 * Sanitizes a page-specific hero font choice.
 *
 * @param string $value The submitted font key.
 * @return string A supported font key or an empty string for the default.
 */
function scouting_nederland_sanitize_page_hero_font( $value ) {
	$value = sanitize_key( $value );
	if ( '' === $value ) {
		return '';
	}
	if ( 'urban-jungle' === $value ) {
		return 'impact';
	}
	$choices = scouting_nederland_get_hero_font_choices();
	return array_key_exists( $value, $choices ) ? $value : '';
}

/**
 * Returns the configured default hero font.
 *
 * @return string The supported font key.
 */
function scouting_nederland_get_default_hero_title_font() {
	return scouting_nederland_sanitize_hero_font( get_theme_mod( 'scouting_nederland_hero_title_font', 'impact' ) );
}

/**
 * Returns the hero font for a page or the theme default.
 *
 * @param int|null $post_id The page ID, or null for the current page.
 * @return string The supported font key.
 */
function scouting_nederland_get_hero_title_font( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
	}

	$page_font = $post_id ? scouting_nederland_sanitize_page_hero_font( (string) get_post_meta( $post_id, '_scouting_nederland_hero_title_font', true ) ) : '';
	return '' !== $page_font ? $page_font : scouting_nederland_get_default_hero_title_font();
}

/**
 * Returns the CSS class for a page's hero font.
 *
 * @param int|null $post_id The page ID, or null for the current page.
 * @return string The hero font CSS class.
 */
function scouting_nederland_get_hero_title_font_class( $post_id = null ) {
	return 'scouting-nederland-hero-font-' . scouting_nederland_get_hero_title_font( $post_id );
}

/**
 * Returns saved display settings for a page.
 *
 * @param int|null $post_id The page ID, or null for the current page.
 * @return array<string, bool|string> The page display settings.
 */
function scouting_nederland_get_page_display_settings( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_queried_object_id();
	}

	return array(
		'hide_hero'         => get_post_meta( $post_id, '_scouting_nederland_hide_hero', true ) === '1',
		'hide_page_title'   => get_post_meta( $post_id, '_scouting_nederland_hide_page_title', true ) === '1',
		'hide_sidebar'      => get_post_meta( $post_id, '_scouting_nederland_hide_sidebar', true ) === '1',
		'show_hero_summary' => get_post_meta( $post_id, '_scouting_nederland_show_hero_summary', true ) === '1',
		'hero_title_font'   => scouting_nederland_sanitize_page_hero_font( (string) get_post_meta( $post_id, '_scouting_nederland_hero_title_font', true ) ),
	);
}

/**
 * Returns the page excerpt only when its hero summary has been enabled.
 *
 * An empty manual excerpt never triggers an automatically generated summary.
 *
 * @param int|null $post_id Page ID.
 * @return string Plain-text summary, or an empty string.
 */
function scouting_nederland_get_hero_summary( $post_id = null ) {
	$post_id = $post_id ? absint( $post_id ) : get_queried_object_id();
	if ( ! $post_id || '1' !== get_post_meta( $post_id, '_scouting_nederland_show_hero_summary', true ) ) {
		return '';
	}
	return trim( wp_strip_all_tags( (string) get_post_field( 'post_excerpt', $post_id ) ) );
}

/**
 * Checks whether a page hides its hero.
 *
 * @param int|null $post_id The page ID, or null for the current page.
 * @return bool Whether the hero is hidden.
 */
function scouting_nederland_page_hides_hero( $post_id = null ) {
	$settings = scouting_nederland_get_page_display_settings( $post_id );
	return ! empty( $settings['hide_hero'] );
}

/**
 * Checks whether a page hides its title.
 *
 * @param int|null $post_id The page ID, or null for the current page.
 * @return bool Whether the title is hidden.
 */
function scouting_nederland_page_hides_title( $post_id = null ) {
	$settings = scouting_nederland_get_page_display_settings( $post_id );
	return ! empty( $settings['hide_page_title'] );
}

/**
 * Checks whether a page hides its sidebar.
 *
 * @param int|null $post_id The page ID, or null for the current page.
 * @return bool Whether the sidebar is hidden.
 */
function scouting_nederland_page_hides_sidebar( $post_id = null ) {
	$settings = scouting_nederland_get_page_display_settings( $post_id );
	return ! empty( $settings['hide_sidebar'] );
}

/**
 * Registers the page display settings metabox.
 *
 * @return void
 */
function scouting_nederland_add_page_display_metabox() {
	add_meta_box(
		'scouting_nederland_page_display_settings',
		__( 'Pagina weergave', 'scouting-nederland' ),
		'scouting_nederland_render_page_display_metabox',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'scouting_nederland_add_page_display_metabox' );

/**
 * Renders controls for page display settings.
 *
 * @param WP_Post $post The page being edited.
 * @return void
 */
function scouting_nederland_render_page_display_metabox( $post ) {
	wp_nonce_field( 'scouting_nederland_save_page_display_settings', 'scouting_nederland_page_display_nonce' );
	$settings = scouting_nederland_get_page_display_settings( $post->ID );
	?>
	<p>
		<label>
			<input type="checkbox" name="scouting_nederland_hide_hero" value="1" <?php checked( $settings['hide_hero'] ); ?>>
			<?php esc_html_e( 'Standaard hero verbergen', 'scouting-nederland' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="scouting_nederland_hide_page_title" value="1" <?php checked( $settings['hide_page_title'] ); ?>>
			<?php esc_html_e( 'Paginatitel in hero verbergen', 'scouting-nederland' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="scouting_nederland_hide_sidebar" value="1" <?php checked( $settings['hide_sidebar'] ); ?>>
			<?php esc_html_e( 'Sidebar verbergen', 'scouting-nederland' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="scouting_nederland_show_hero_summary" value="1" <?php checked( $settings['show_hero_summary'] ); ?>>
			<?php esc_html_e( 'Samenvatting in HERO tonen', 'scouting-nederland' ); ?>
		</label><br>
		<span class="description"><?php esc_html_e( 'Gebruik het veld Samenvatting in de pagina-editor. De tekst verschijnt onder de HERO-kop als deze optie is ingeschakeld.', 'scouting-nederland' ); ?></span>
	</p>
	<p>
		<label for="scouting_nederland_hero_title_font"><strong><?php esc_html_e( 'Lettertype hero-koptekst', 'scouting-nederland' ); ?></strong></label><br>
		<select name="scouting_nederland_hero_title_font" id="scouting_nederland_hero_title_font" style="width:100%;">
			<?php /* translators: %s: The default hero font name configured in the Customizer. */ ?>
			<option value="" <?php selected( $settings['hero_title_font'], '' ); ?>><?php printf( esc_html__( 'Standaard uit Customizer (%s)', 'scouting-nederland' ), esc_html( scouting_nederland_get_hero_font_choices()[ scouting_nederland_get_default_hero_title_font() ] ) ); ?></option>
			<?php foreach ( scouting_nederland_get_hero_font_choices() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $settings['hero_title_font'], $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="description">
		<?php esc_html_e( 'Gebruik deze opties om de standaard hero, de titel-overlay, de sidebar of het lettertype van de hero-koptekst voor deze pagina aan te passen. Een ingestelde slider of video blijft zichtbaar.', 'scouting-nederland' ); ?>
	</p>
	<?php
}

/**
 * Validates and saves page display settings.
 *
 * @param int $post_id The page ID being saved.
 * @return void
 */
function scouting_nederland_save_page_display_metabox( $post_id ) {
	$nonce = isset( $_POST['scouting_nederland_page_display_nonce'] )
		? sanitize_text_field( wp_unslash( $_POST['scouting_nederland_page_display_nonce'] ) )
		: '';

	if (
		! wp_verify_nonce( $nonce, 'scouting_nederland_save_page_display_settings' )
		|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		|| wp_is_post_revision( $post_id )
		|| 'page' !== get_post_type( $post_id )
		|| ! current_user_can( 'edit_page', $post_id )
	) {
		return;
	}

	update_post_meta( $post_id, '_scouting_nederland_hide_hero', ! empty( $_POST['scouting_nederland_hide_hero'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_scouting_nederland_hide_page_title', ! empty( $_POST['scouting_nederland_hide_page_title'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_scouting_nederland_hide_sidebar', ! empty( $_POST['scouting_nederland_hide_sidebar'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_scouting_nederland_show_hero_summary', ! empty( $_POST['scouting_nederland_show_hero_summary'] ) ? '1' : '0' );
	$hero_title_font = isset( $_POST['scouting_nederland_hero_title_font'] )
		? sanitize_key( wp_unslash( $_POST['scouting_nederland_hero_title_font'] ) )
		: '';
	update_post_meta( $post_id, '_scouting_nederland_hero_title_font', scouting_nederland_sanitize_page_hero_font( $hero_title_font ) );
}
add_action( 'save_post_page', 'scouting_nederland_save_page_display_metabox' );
