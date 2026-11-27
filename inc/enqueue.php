<?php
/**
 * Registers the theme's frontend and admin assets.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

/**
 * Determines whether the current page has slider media enabled.
 *
 * @return bool Whether slider media should be initialized.
 */
function scouting_nederland_has_home_media() {
	$post_id = is_page() ? get_queried_object_id() : 0;
	return $post_id && get_post_meta( $post_id, '_scouting_nederland_slider_show', true ) === '1';
}

/**
 * Enqueues the theme's styles and scripts.
 *
 * @return void
 */
function scouting_nederland_assets() {
	wp_enqueue_style( 'scouting-nederland-fontawesome', get_theme_file_uri( '/assets/vendor/font-awesome/css/all.min.css' ), array(), '7.3.1' );

	wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/vendor/bootstrap/css/bootstrap.min.css' ), array( 'scouting-nederland-fontawesome' ), '5.3.8' );
	wp_enqueue_style( 'scouting-nederland-wordpress', get_theme_file_uri( '/assets/css/wordpress.css' ), array( 'bootstrap' ), SCOUTING_NEDERLAND_VERSION );
	wp_enqueue_style( 'scouting-nederland-base', get_template_directory_uri() . '/assets/css/base.css', array( 'scouting-nederland-wordpress' ), SCOUTING_NEDERLAND_VERSION );
	wp_enqueue_style( 'scouting-nederland-utilities', get_template_directory_uri() . '/assets/css/utilities.css', array( 'scouting-nederland-base' ), SCOUTING_NEDERLAND_VERSION );
	wp_enqueue_style( 'scouting-nederland-header', get_template_directory_uri() . '/assets/css/header.css', array( 'scouting-nederland-utilities' ), SCOUTING_NEDERLAND_VERSION );
	wp_enqueue_style( 'scouting-nederland-footer', get_template_directory_uri() . '/assets/css/footer.css', array( 'scouting-nederland-header' ), SCOUTING_NEDERLAND_VERSION );
	wp_enqueue_style( 'scouting-nederland-homepage', get_template_directory_uri() . '/assets/css/homepage.css', array( 'scouting-nederland-footer' ), SCOUTING_NEDERLAND_VERSION );
	wp_enqueue_style( 'scouting-nederland-blocks', get_template_directory_uri() . '/assets/css/blocks.css', array( 'scouting-nederland-homepage' ), SCOUTING_NEDERLAND_VERSION );

	if ( is_search() || is_404() ) {
		wp_enqueue_style( 'scouting-nederland-search', get_template_directory_uri() . '/assets/css/search.css', array( 'scouting-nederland-blocks' ), SCOUTING_NEDERLAND_VERSION );
	}

	if ( is_404() ) {
		wp_enqueue_style( 'scouting-nederland-404', get_template_directory_uri() . '/assets/css/404.css', array( 'scouting-nederland-search' ), SCOUTING_NEDERLAND_VERSION );
	}

	if ( is_page() ) {
		wp_enqueue_style( 'scouting-nederland-page', get_template_directory_uri() . '/assets/css/page.css', array( 'scouting-nederland-blocks' ), SCOUTING_NEDERLAND_VERSION );
	}

	if ( is_singular( 'post' ) ) {
		wp_enqueue_style( 'scouting-nederland-single', get_template_directory_uri() . '/assets/css/single.css', array( 'scouting-nederland-blocks' ), SCOUTING_NEDERLAND_VERSION );
	}

	if ( is_category() ) {
		wp_enqueue_style( 'scouting-nederland-category', get_template_directory_uri() . '/assets/css/category.css', array( 'scouting-nederland-blocks' ), SCOUTING_NEDERLAND_VERSION );
	}

	$buttons_dependency = 'scouting-nederland-blocks';
	if ( is_search() ) {
		$buttons_dependency = 'scouting-nederland-search';
	}
	if ( is_404() ) {
		$buttons_dependency = 'scouting-nederland-404';
	}
	wp_enqueue_style( 'scouting-nederland-buttons', get_template_directory_uri() . '/assets/css/buttons.css', array( $buttons_dependency ), SCOUTING_NEDERLAND_VERSION );
	wp_enqueue_style( 'scouting-nederland-layout', get_template_directory_uri() . '/assets/css/layout.css', array( 'scouting-nederland-buttons' ), SCOUTING_NEDERLAND_VERSION );

	wp_enqueue_script( 'bootstrap-bundle', get_theme_file_uri( '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js' ), array(), '5.3.8', true );
	wp_enqueue_script( 'scouting-nederland-console', get_theme_file_uri( '/assets/js/console.js' ), array(), SCOUTING_NEDERLAND_VERSION, true );
	wp_localize_script(
		'scouting-nederland-console',
		'scoutingNederlandConsoleL10n',
		array(
			'heading'   => __( 'Hey detective! Can you see what is happening under the hood?', 'scouting-nederland' ),
			'message'   => __( 'At Scouting, we build great websites, portals, and systems for more than a hundred thousand members. We are always looking for enthusiastic volunteer IT specialists, developers, and tech enthusiasts to help us build!', 'scouting-nederland' ),
			'vacancies' => __( 'View IT vacancies:', 'scouting-nederland' ),
		)
	);
	wp_enqueue_script( 'scouting-nederland-motion', get_theme_file_uri( '/assets/js/motion.js' ), array(), SCOUTING_NEDERLAND_VERSION, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	if ( scouting_nederland_has_home_media() ) {
		wp_enqueue_script( 'scouting-nederland-home-slider', get_template_directory_uri() . '/assets/js/home-slider.js', array(), SCOUTING_NEDERLAND_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'scouting_nederland_assets' );
