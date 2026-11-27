<?php
/**
 * The Customizer control for header actions and their visibility switches.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A Customizer control for a header action and its visibility switch.
 */
class Scouting_Nederland_Header_Action_Control extends WP_Customize_Control {
	/**
	 * Customizer control type.
	 *
	 * @var string
	 */
	public $type = 'scouting_header_action';

	/**
	 * HTML input type.
	 *
	 * @var string
	 */
	public $field_type = 'url';

	/**
	 * Text displayed beside the visibility checkbox.
	 *
	 * @var string
	 */
	public $toggle_label = '';

	/**
	 * Renders the field and its visibility toggle.
	 *
	 * @return void
	 */
	public function render_content() {
		?>
		<div class="scouting-header-action-setting">
			<?php if ( $this->label ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<div style="display:flex;align-items:center;gap:12px;">
				<input type="<?php echo esc_attr( $this->field_type ); ?>" style="flex:1;min-width:0;" <?php $this->link( 'field' ); ?> value="<?php echo esc_attr( $this->value( 'field' ) ); ?>" aria-label="<?php echo esc_attr( $this->label ); ?>" />
				<label style="display:flex;align-items:center;gap:5px;white-space:nowrap;">
					<input type="checkbox" value="1" <?php checked( $this->value( 'enabled' ), 1 ); ?> <?php $this->link( 'enabled' ); ?> />
					<?php echo esc_html( $this->toggle_label ); ?>
				</label>
			</div>
			<?php if ( $this->description ) : ?>
				<p class="description customize-control-description"><?php echo esc_html( $this->description ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
