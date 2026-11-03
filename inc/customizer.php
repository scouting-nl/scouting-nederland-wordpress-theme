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
/**
 * Sanitizes a Customizer checkbox value.
 *
 * @param mixed $checked The submitted checkbox value.
 * @return int 1 when checked, otherwise 0.
 */
function scouting_nederland_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? 1 : 0 );
}

/**
 * Registers Scouting Nederland appearance controls.
 *
 * @param WP_Customize_Manager $wp_customize The active Customizer manager.
 * @return void
 */
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
			'description' => __( 'Kies Smal voor de scouting.nl-achtige header met standaard hamburgermenu of breed met de mogelijkheid voor een horizontaal menu.', 'scouting-nederland' ),
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

	$wp_customize->add_setting(
		'scouting_nederland_hamburger_desktop',
		array(
			'default'           => 0,
			'sanitize_callback' => 'scouting_nederland_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_hamburger_desktop',
		array(
			'type'        => 'checkbox',
			'section'     => 'scouting_nederland_options',
			'label'       => __( 'Hamburger menu ook op desktop', 'scouting-nederland' ),
			'description' => __( 'Uit = horizontaal menu op desktop. Aan = op desktop hamburger-menu. alleen van toepassing op brede headers.', 'scouting-nederland' ),
		)
	);

	$wp_customize->add_setting(
		'scouting_nederland_show_search',
		array(
			'default'           => 1,
			'sanitize_callback' => 'scouting_nederland_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_show_search',
		array(
			'type'    => 'checkbox',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Zoek-icoon tonen', 'scouting-nederland' ),
		)
	);
	$wp_customize->add_setting(
		'scouting_nederland_search_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_search_url',
		array(
			'type'        => 'url',
			'section'     => 'scouting_nederland_options',
			'label'       => __( 'Zoekpagina URL', 'scouting-nederland' ),
			'description' => __( 'Leeg laten gebruikt de WordPress zoekpagina.', 'scouting-nederland' ),
		)
	);

	$wp_customize->add_setting(
		'scouting_nederland_show_phone',
		array(
			'default'           => 1,
			'sanitize_callback' => 'scouting_nederland_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_show_phone',
		array(
			'type'    => 'checkbox',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Telefoon-icoon tonen', 'scouting-nederland' ),
		)
	);
	$wp_customize->add_setting(
		'scouting_nederland_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_phone',
		array(
			'type'    => 'text',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Telefoonnummer', 'scouting-nederland' ),
		)
	);

	$wp_customize->add_setting(
		'scouting_nederland_show_contact',
		array(
			'default'           => 1,
			'sanitize_callback' => 'scouting_nederland_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_show_contact',
		array(
			'type'    => 'checkbox',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Contact-icoon tonen', 'scouting-nederland' ),
		)
	);
	$wp_customize->add_setting(
		'scouting_nederland_contact_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_contact_url',
		array(
			'type'    => 'url',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Contactpagina URL', 'scouting-nederland' ),
		)
	);

	$wp_customize->add_setting(
		'scouting_nederland_show_header_message',
		array(
			'default'           => 1,
			'sanitize_callback' => 'scouting_nederland_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'scouting_nederland_show_header_message',
		array(
			'type'    => 'checkbox',
			'section' => 'scouting_nederland_options',
			'label'   => __( 'Melding in header gebruiken', 'scouting-nederland' ),
		)
	);
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
