<?php
/**
 * Registers theme settings in the Customizer.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

/**
 * Sanitizes a Customizer checkbox value.
 *
 * @param mixed $checked The submitted checkbox value.
 * @return int 1 when checked, otherwise 0.
 */
function scouting_nederland_sanitize_checkbox( $checked ) {
	return ! empty( $checked ) ? 1 : 0;
}

/**
 * Registers Scouting Nederland appearance controls.
 *
 * @param WP_Customize_Manager $wp_customize The active Customizer manager.
 * @return void
 */
function scouting_nederland_customize_register( $wp_customize ) {

	$wp_customize->add_section(
		'scouting_nederland_options',
		array(
			'title'    => __( 'Scouting Nederland opties', 'scouting-nederland' ),
			'priority' => 30,
		)
	);
	$colors    = array(
		'mgroen'  => 'Groen',
		'mblauw'  => 'Blauw',
		'mgeel'   => 'Geel',
		'mrood'   => 'Rood',
		'moranje' => 'Oranje',
		'mpaars'  => 'Paars',
	);
	$subcolors = array(
		'sgroen'  => 'Groen',
		'sblauw'  => 'Blauw',
		'sgeel'   => 'Geel',
		'srood'   => 'Rood',
		'soranje' => 'Oranje',
		'spaars'  => 'Paars',
	);
	$wp_customize->add_setting(
		'scouting_nederland_main_color',
		array(
			'default'           => 'mgroen',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_main_color',
		array(
			'type'    => 'select',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Hoofdkleur', 'scouting-nederland' ),
			'choices' => $colors,
		)
	);
	$wp_customize->add_setting(
		'scouting_nederland_sub_color',
		array(
			'default'           => 'sgroen',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_sub_color',
		array(
			'type'    => 'select',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Steunkleur', 'scouting-nederland' ),
			'choices' => $subcolors,
		)
	);
	$wp_customize->add_setting(
		'scouting_nederland_logo_stamp',
		array(
			'default'           => 1,
			'sanitize_callback' => 'scouting_nederland_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_logo_stamp',
		array(
			'type'        => 'checkbox',
			'section'     => 'scouting_nederland_options',
			'label'       => __( 'Logo stempel gebruiken', 'scouting-nederland' ),
			'description' => __( 'Toont de stempel/schaduw onder het logo.', 'scouting-nederland' ),
		)
	);

	$wp_customize->add_setting(
		'scouting_nederland_header_width',
		array(
			'default'           => 'narrow',
			'sanitize_callback' => function ( $value ) {
				return in_array( $value, array( 'narrow', 'wide' ), true ) ? $value : 'narrow';
			},
		)
	);

	$wp_customize->add_control(
		'scouting_nederland_header_width',
		array(
			'label'       => __( 'Header breedte', 'scouting-nederland' ),
			'description' => __( 'Smal toont een compacte header met hamburgermenu. Breed gebruikt de volle breedte met een horizontaal menu op desktop. Op tablet en mobiel gebruiken beide een hamburgermenu.', 'scouting-nederland' ),
			'section'     => 'scouting_nederland_options',
			'type'        => 'radio',
			'choices'     => array(
				'narrow' => __( 'Smal', 'scouting-nederland' ),
				'wide'   => __( 'Breed', 'scouting-nederland' ),
			),
		)
	);

	$wp_customize->add_setting(
		'scouting_nederland_default_hero_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'scouting_nederland_default_hero_image',
			array(
				'label'       => __( 'Standaard hero-afbeelding', 'scouting-nederland' ),
				'description' => __( 'Deze afbeelding wordt gebruikt als er geen eigen pagina-afbeelding, slider of hero-widget is ingesteld.', 'scouting-nederland' ),
				'section'     => 'scouting_nederland_options',
				'mime_type'   => 'image',
			)
		)
	);

	if ( ! class_exists( 'Scouting_Nederland_Header_Action_Control' ) ) {
		class Scouting_Nederland_Header_Action_Control extends WP_Customize_Control {
			public $type = 'scouting_header_action';
			public $field_type = 'url';
			public $toggle_label = '';

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
	}

	$header_actions = array(
		array(
			'id'          => 'search',
			'label'       => __( 'Zoekpagina URL', 'scouting-nederland' ),
			'description' => __( 'Leeg laten gebruikt de standaard WordPress-zoekpagina.', 'scouting-nederland' ),
			'field'       => 'scouting_nederland_search_url',
			'field_type'  => 'url',
			'sanitize'    => 'esc_url_raw',
			'enabled'     => 'scouting_nederland_show_search',
		),
		array(
			'id'          => 'phone',
			'label'       => __( 'Telefoonnummer', 'scouting-nederland' ),
			'description' => __( 'Vul een nummer in om de telefoonknop te gebruiken.', 'scouting-nederland' ),
			'field'       => 'scouting_nederland_phone',
			'field_type'  => 'tel',
			'sanitize'    => 'sanitize_text_field',
			'enabled'     => 'scouting_nederland_show_phone',
		),
		array(
			'id'          => 'contact',
			'label'       => __( 'Contactpagina URL', 'scouting-nederland' ),
			'description' => __( 'Vul een link in om de contactknop te gebruiken.', 'scouting-nederland' ),
			'field'       => 'scouting_nederland_contact_url',
			'field_type'  => 'url',
			'sanitize'    => 'esc_url_raw',
			'enabled'     => 'scouting_nederland_show_contact',
		),
	);
	foreach ( $header_actions as $action ) {
		$wp_customize->add_setting( $action['field'], array(
			'default'           => '',
			'sanitize_callback' => $action['sanitize'],
		) );
		$wp_customize->add_setting( $action['enabled'], array(
			'default'           => 1,
			'sanitize_callback' => 'scouting_nederland_sanitize_checkbox',
		) );
		$wp_customize->add_control( new Scouting_Nederland_Header_Action_Control(
			$wp_customize,
			'scouting_nederland_header_action_' . $action['id'],
			array(
				'label'        => $action['label'],
				'description'  => $action['description'],
				'section'      => 'scouting_nederland_options',
				'settings'     => array( 'field' => $action['field'], 'enabled' => $action['enabled'] ),
				'field_type'   => $action['field_type'],
				'toggle_label' => __( 'Tonen', 'scouting-nederland' ),
			)
		) );
	}

	$wp_customize->add_setting(
		'scouting_nederland_header_message',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_header_message',
		array(
			'type'    => 'text',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Melding in de header', 'scouting-nederland' ),
		)
	);
	$wp_customize->add_setting(
		'scouting_nederland_header_message_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_header_message_url',
		array(
			'type'    => 'url',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Link header melding', 'scouting-nederland' ),
		)
	);

	$wp_customize->add_setting(
		'scouting_nederland_hero_title_font',
		array(
			'default'           => 'impact',
			'sanitize_callback' => 'scouting_nederland_sanitize_hero_font',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_hero_title_font',
		array(
			'type'        => 'select',
			'section'     => 'scouting_nederland_options',
			'label'       => __( 'Standaard lettertype hero-koptekst', 'scouting-nederland' ),
			'description' => __( 'Dit lettertype wordt gebruikt voor hero-koppen, tenzij je op een pagina een afwijkend lettertype kiest.', 'scouting-nederland' ),
			'choices'     => scouting_nederland_get_hero_font_choices(),
		)
	);

	$social_networks = array(
		'facebook'  => 'Facebook',
		'x'         => 'X',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'instagram' => 'Instagram',
		'tiktok'    => 'TikTok',
	);

	foreach ( $social_networks as $network => $label ) {
		$wp_customize->add_setting(
			'scouting_nederland_' . $network . '_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'scouting_nederland_' . $network . '_url',
			array(
				'type'    => 'url',
				'section' => 'scouting_nederland_options',
				'label'   => $label . ' URL',
			)
		);
	}
}
add_action( 'customize_register', 'scouting_nederland_customize_register' );
