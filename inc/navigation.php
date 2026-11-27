<?php
/**
 * Adds configured theme classes to the page body.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the selected header colors to the body classes.
 *
 * @param string[] $classes Existing body classes.
 * @return string[] Updated body classes.
 */
function scouting_nederland_body_classes( $classes ) {
	/**
	 * The site body classes.
	 *
	 * @var string[] $classes
	 */
	$main    = get_theme_mod( 'scouting_nederland_main_color', 'mgroen' );
	$support = get_theme_mod( 'scouting_nederland_sub_color', 'sgroen' );

	$main_map = array(
		'mgroen'  => 'scouting-nederland-utilities-main-green',
		'mblauw'  => 'scouting-nederland-utilities-main-blue',
		'mgeel'   => 'scouting-nederland-utilities-main-yellow',
		'mrood'   => 'scouting-nederland-utilities-main-red',
		'moranje' => 'scouting-nederland-utilities-main-orange',
		'mpaars'  => 'scouting-nederland-utilities-main-purple',
	);

	$support_map = array(
		'sgroen'  => 'scouting-nederland-utilities-support-green',
		'sblauw'  => 'scouting-nederland-utilities-support-blue',
		'sgeel'   => 'scouting-nederland-utilities-support-yellow',
		'srood'   => 'scouting-nederland-utilities-support-red',
		'soranje' => 'scouting-nederland-utilities-support-orange',
		'spaars'  => 'scouting-nederland-utilities-support-purple',
	);

	if ( isset( $main_map[ $main ] ) ) {
		$classes[] = $main_map[ $main ];
	}

	if ( isset( $support_map[ $support ] ) ) {
		$classes[] = $support_map[ $support ];
	}

	return array_unique( $classes );
}
add_filter( 'body_class', 'scouting_nederland_body_classes' );
