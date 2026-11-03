<?php
/**
 * Registers theme supports, editor features, menus, and widget areas.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

/**
 * Strips markup and truncates text at a word boundary.
 *
 * @param string $text  Text to trim.
 * @param int    $limit Maximum number of characters.
 * @return string The trimmed text.
 */
function scouting_nederland_trim_text_chars( $text, $limit = 142 ) {
	$limit = max( 1, absint( $limit ) );
	$text  = wp_strip_all_tags( (string) $text, true );
	$text  = html_entity_decode( $text, ENT_QUOTES, get_bloginfo( 'charset' ) );
	$text  = preg_replace( '/\s+/u', ' ', trim( $text ) );

	if ( '' === $text ) {
		return '';
	}

	if ( function_exists( 'mb_strlen' ) && function_exists( 'mb_substr' ) ) {
		if ( mb_strlen( $text ) <= $limit ) {
			return $text;
		}
		$slice = mb_substr( $text, 0, max( 1, $limit - 3 ) );
	} else {
		if ( strlen( $text ) <= $limit ) {
			return $text;
		}
		$slice = substr( $text, 0, max( 1, $limit - 3 ) );
	}

	$slice = preg_replace( '/\s+\S*$/u', '', $slice );
	$slice = trim( $slice );

	if ( '' === $slice ) {
		$slice = function_exists( 'mb_substr' ) ? mb_substr( $text, 0, max( 1, $limit - 3 ) ) : substr( $text, 0, max( 1, $limit - 3 ) );
	}

	return rtrim( $slice, '.,;:!?' ) . '...';
}

/**
 * Sets up theme support and navigation menus.
 *
 * @return void
 */
function scouting_nederland_setup() {
	load_theme_textdomain( 'scouting-nederland', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_post_type_support( 'page', 'excerpt' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'custom-header',
		array(
			'default-image' => get_template_directory_uri() . '/assets/images/default-slide.jpg',
			'width'         => 1920,
			'height'        => 520,
			'flex-width'    => true,
			'flex-height'   => true,
		)
	);
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'ffffff',
		)
	);
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus(
		array(
			'primary' => __( 'Hoofdmenu', 'scouting-nederland' ),
			'footer'  => __( 'Footermenu', 'scouting-nederland' ),
		)
	);
}
add_action( 'after_setup_theme', 'scouting_nederland_setup' );

/**
 * Registers the theme's button block styles.
 *
 * @return void
 */
function scouting_nederland_register_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'scouting-nederland-blue',
			'label' => __( 'Scouting blauw', 'scouting-nederland' ),
		)
	);
	register_block_style(
		'core/button',
		array(
			'name'  => 'scouting-nederland-black',
			'label' => __( 'Scouting zwart', 'scouting-nederland' ),
		)
	);
}
add_action( 'init', 'scouting_nederland_register_block_styles' );

/**
 * Registers the theme's block pattern category and pattern.
 *
 * @return void
 */
function scouting_nederland_register_block_patterns() {
	register_block_pattern_category(
		'scouting-nederland',
		array(
			'label' => __( 'Scouting Nederland', 'scouting-nederland' ),
		)
	);

	register_block_pattern(
		'scouting-nederland/information-panel',
		array(
			'title'       => __( 'Informatieblok', 'scouting-nederland' ),
			'description' => __( 'Een kop, tekst en knop voor belangrijke informatie.', 'scouting-nederland' ),
			'categories'  => array( 'scouting-nederland' ),
			'content'     => '<!-- wp:group {"className":"scouting-nederland-pattern-information-panel","layout":{"type":"constrained"}} -->'
				. '<div class="wp-block-group scouting-nederland-pattern-information-panel">'
				. '<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Belangrijke informatie</h2><!-- /wp:heading -->'
				. '<!-- wp:paragraph --><p>Voeg hier een korte toelichting toe voor bezoekers van de website.</p><!-- /wp:paragraph -->'
				. '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Lees meer</a></div><!-- /wp:button --></div><!-- /wp:buttons -->'
				. '</div><!-- /wp:group -->',
		)
	);
}
add_action( 'init', 'scouting_nederland_register_block_patterns' );

/**
 * Gets the configured default hero image URL.
 *
 * @return string The hero image URL.
 */
function scouting_nederland_get_default_hero_url() {
	$hero_id = absint( get_theme_mod( 'scouting_nederland_default_hero_image', 0 ) );

	if ( $hero_id ) {
		$hero_url = wp_get_attachment_image_url( $hero_id, 'full' );
		if ( $hero_url ) {
			return $hero_url;
		}
	}

	$header_image = get_header_image();
	if ( $header_image ) {
		return $header_image;
	}

	return get_template_directory_uri() . '/assets/images/default-slide.jpg';
}

/**
 * Registers the theme's widget areas.
 *
 * @return void
 */
function scouting_nederland_widgets_init() {
	$areas = array(
		'hero'            => __( 'Hero', 'scouting-nederland' ),
		'featured-a'      => __( 'Featured A', 'scouting-nederland' ),
		'featured-b'      => __( 'Featured B', 'scouting-nederland' ),
		'featured-c'      => __( 'Featured C', 'scouting-nederland' ),
		'showcase'        => __( 'Showcase', 'scouting-nederland' ),
		'news'            => __( 'Nieuws', 'scouting-nederland' ),
		'breadcrumbs'     => __( 'Breadcrumbs', 'scouting-nederland' ),
		'sidebar'         => __( 'Sidebar', 'scouting-nederland' ),
		'bottom-a'        => __( 'Bottom A', 'scouting-nederland' ),
		'bottom-b'        => __( 'Bottom B', 'scouting-nederland' ),
		'footer'          => __( 'Footer', 'scouting-nederland' ),
		'footer-partners' => __( 'Footer - Partners van Scouting', 'scouting-nederland' ),
	);
	foreach ( $areas as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				/* translators: %s: Widget area name. */
				'description'   => sprintf( __( 'Widgetgebied %s', 'scouting-nederland' ), $name ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'scouting_nederland_widgets_init' );

/**
 * Displays the post date.
 *
 * @return void
 */
function scouting_nederland_posted_on() {
	echo '<p class="text-muted small">' . esc_html( get_the_date() ) . '</p>';
}

/**
 * Determines whether the requested page is built with Elementor.
 *
 * @param int|null $post_id Page ID, or null for the queried page.
 * @return bool Whether Elementor is active for the page.
 */
function scouting_nederland_is_elementor_page( $post_id = null ) {
	$post_id = $post_id ? absint( $post_id ) : absint( get_queried_object_id() );

	if ( ! $post_id || get_post_type( $post_id ) !== 'page' ) {
		return false;
	}

	return get_post_meta( $post_id, '_elementor_edit_mode', true ) === 'builder'
		|| get_post_meta( $post_id, '_elementor_data', true ) !== '';
}

/**
 * Adds the selected header width to the body classes.
 *
 * @param string[] $classes Existing body classes.
 * @return string[] Updated body classes.
 */
function scouting_nederland_header_width_body_class( $classes ) {
	$mode      = get_theme_mod( 'scouting_nederland_header_width', 'narrow' );
	$classes[] = ( 'narrow' === $mode )
		? 'scouting-nederland-header-mode-narrow'
		: 'scouting-nederland-header-mode-wide';
	return $classes;
}
add_filter( 'body_class', 'scouting_nederland_header_width_body_class' );
