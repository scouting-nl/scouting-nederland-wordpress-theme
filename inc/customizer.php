<?php
/**
 * Registers theme settings in the Customizer.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	require_once get_template_directory() . '/inc/class-scouting-nederland-header-action-control.php';

	$wp_customize->add_section(
		'scouting_nederland_options',
		array(
			'title'    => __( 'Scouting Nederland options', 'scouting-nederland' ),
			'priority' => 30,
		)
	);
	$colors    = array(
		'mgroen'  => __( 'Green', 'scouting-nederland' ),
		'mblauw'  => __( 'Blue', 'scouting-nederland' ),
		'mgeel'   => __( 'Yellow', 'scouting-nederland' ),
		'mrood'   => __( 'Red', 'scouting-nederland' ),
		'moranje' => __( 'Orange', 'scouting-nederland' ),
		'mpaars'  => __( 'Purple', 'scouting-nederland' ),
	);
	$subcolors = array(
		'sgroen'  => __( 'Green', 'scouting-nederland' ),
		'sblauw'  => __( 'Blue', 'scouting-nederland' ),
		'sgeel'   => __( 'Yellow', 'scouting-nederland' ),
		'srood'   => __( 'Red', 'scouting-nederland' ),
		'soranje' => __( 'Orange', 'scouting-nederland' ),
		'spaars'  => __( 'Purple', 'scouting-nederland' ),
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
			'label'   => __( 'Primary color', 'scouting-nederland' ),
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
			'label'   => __( 'Accent color', 'scouting-nederland' ),
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
			'label'       => __( 'Use logo stamp', 'scouting-nederland' ),
			'description' => __( 'Displays the stamp/shadow below the logo.', 'scouting-nederland' ),
		)
	);

	$wp_customize->add_setting(
		'scouting_nederland_header_width',
		array(
			'default'           => 'wide',
			'sanitize_callback' => function ( $value ) {
				return in_array( $value, array( 'narrow', 'wide' ), true ) ? $value : 'wide';
			},
		)
	);

	$wp_customize->add_control(
		'scouting_nederland_header_width',
		array(
			'label'       => __( 'Header width', 'scouting-nederland' ),
			'description' => __( 'Narrow displays a compact header with a hamburger menu. Wide uses the full width with a horizontal menu on desktop. Both use a hamburger menu on tablets and mobile devices.', 'scouting-nederland' ),
			'section'     => 'scouting_nederland_options',
			'type'        => 'radio',
			'choices'     => array(
				'narrow' => __( 'Narrow', 'scouting-nederland' ),
				'wide'   => __( 'Wide', 'scouting-nederland' ),
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
				'label'       => __( 'Default hero image', 'scouting-nederland' ),
				'description' => __( 'This image is used when no custom page image, slider, or hero widget is set.', 'scouting-nederland' ),
				'section'     => 'scouting_nederland_options',
				'mime_type'   => 'image',
			)
		)
	);

	// Keep each action toggle beside its corresponding input field.

	$header_actions = array(
		array(
			'id'          => 'search',
			'label'       => __( 'Search page URL', 'scouting-nederland' ),
			'description' => __( 'Leave empty to use the default WordPress search page.', 'scouting-nederland' ),
			'field'       => 'scouting_nederland_search_url',
			'field_type'  => 'url',
			'sanitize'    => 'esc_url_raw',
			'enabled'     => 'scouting_nederland_show_search',
		),
		array(
			'id'          => 'phone',
			'label'       => __( 'Phone number', 'scouting-nederland' ),
			'description' => __( 'Enter a number to enable the call button.', 'scouting-nederland' ),
			'field'       => 'scouting_nederland_phone',
			'field_type'  => 'tel',
			'sanitize'    => 'sanitize_text_field',
			'enabled'     => 'scouting_nederland_show_phone',
		),
		array(
			'id'          => 'contact',
			'label'       => __( 'Contact page URL', 'scouting-nederland' ),
			'description' => __( 'Enter a link to enable the contact button.', 'scouting-nederland' ),
			'field'       => 'scouting_nederland_contact_url',
			'field_type'  => 'url',
			'sanitize'    => 'esc_url_raw',
			'enabled'     => 'scouting_nederland_show_contact',
		),
	);
	foreach ( $header_actions as $action ) {
		$wp_customize->add_setting(
			$action['field'],
			array(
				'default'           => '',
				'sanitize_callback' => $action['sanitize'],
			)
		);
		$wp_customize->add_setting(
			$action['enabled'],
			array(
				'default'           => 1,
				'sanitize_callback' => 'scouting_nederland_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			new Scouting_Nederland_Header_Action_Control(
				$wp_customize,
				'scouting_nederland_header_action_' . $action['id'],
				array(
					'label'        => $action['label'],
					'description'  => $action['description'],
					'section'      => 'scouting_nederland_options',
					'settings'     => array(
						'field'   => $action['field'],
						'enabled' => $action['enabled'],
					),
					'field_type'   => $action['field_type'],
					'toggle_label' => __( 'Show', 'scouting-nederland' ),
				)
			)
		);
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
			'label'   => __( 'Header notice', 'scouting-nederland' ),
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
			'label'   => __( 'Header notice link', 'scouting-nederland' ),
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
			'label'       => __( 'Default hero heading font', 'scouting-nederland' ),
			'description' => __( 'This font is used for hero headings unless you choose a different font for a page.', 'scouting-nederland' ),
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
