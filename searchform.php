<?php
/**
 * Displays the theme search form.
 *
 * @package Scouting_Nederland
 */

$scouting_nederland_search_args         = isset( $args ) && is_array( $args ) ? $args : array();
$scouting_nederland_search_form_id      = ! empty( $scouting_nederland_search_args['form_id'] )
	? sanitize_html_class( $scouting_nederland_search_args['form_id'] )
	: 'scouting-nederland-search-form';
$scouting_nederland_search_form_class   = ! empty( $scouting_nederland_search_args['form_class'] )
	? trim( wp_strip_all_tags( $scouting_nederland_search_args['form_class'] ) )
	: 'scouting-nederland-search-form';
$scouting_nederland_search_input_id     = ! empty( $scouting_nederland_search_args['input_id'] )
	? sanitize_html_class( $scouting_nederland_search_args['input_id'] )
	: 'scouting-nederland-search-field';
$scouting_nederland_search_input_class  = ! empty( $scouting_nederland_search_args['input_class'] )
	? trim( wp_strip_all_tags( $scouting_nederland_search_args['input_class'] ) )
	: 'scouting-nederland-search-form-input';
$scouting_nederland_search_button_class = ! empty( $scouting_nederland_search_args['button_class'] )
	? trim( wp_strip_all_tags( $scouting_nederland_search_args['button_class'] ) )
	: 'scouting-nederland-search-form-button';
$scouting_nederland_search_placeholder  = ! empty( $scouting_nederland_search_args['placeholder'] )
	? $scouting_nederland_search_args['placeholder']
	: __( 'Search the site…', 'scouting-nederland-wordpress-theme' );
$scouting_nederland_search_button_label = ! empty( $scouting_nederland_search_args['button_label'] )
	? $scouting_nederland_search_args['button_label']
	: __( 'Search', 'scouting-nederland-wordpress-theme' );
$scouting_nederland_search_required     = ! empty( $scouting_nederland_search_args['required'] );
?>
<form id="<?php echo esc_attr( $scouting_nederland_search_form_id ); ?>" method="get" class="<?php echo esc_attr( $scouting_nederland_search_form_class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Search the site', 'scouting-nederland-wordpress-theme' ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $scouting_nederland_search_input_id ); ?>"><?php esc_html_e( 'Search for:', 'scouting-nederland-wordpress-theme' ); ?></label>
	<input
		id="<?php echo esc_attr( $scouting_nederland_search_input_id ); ?>"
		type="search"
		class="<?php echo esc_attr( $scouting_nederland_search_input_class ); ?>"
		placeholder="<?php echo esc_attr( $scouting_nederland_search_placeholder ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s"
		<?php echo $scouting_nederland_search_required ? 'required' : ''; ?>
	>
	<button type="submit" class="<?php echo esc_attr( $scouting_nederland_search_button_class ); ?>">
		<i class="fas fa-search" aria-hidden="true"></i>
		<span><?php echo esc_html( $scouting_nederland_search_button_label ); ?></span>
	</button>
</form>
