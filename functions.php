<?php
/**
 * Loads the theme setup and feature modules.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

define( 'SCOUTING_NEDERLAND_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/class-scoutingnederlandbootstrapnavwalker.php';
require_once get_template_directory() . '/inc/slider.php';
require_once get_template_directory() . '/inc/page-settings.php';
require_once get_template_directory() . '/inc/search.php';
require_once get_template_directory() . '/inc/admin-notices.php';
