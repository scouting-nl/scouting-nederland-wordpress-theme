<?php
/**
 * Provides Bootstrap-compatible markup for WordPress navigation menus.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Outputs Bootstrap navigation classes for menu items.
 *
 * @package Scouting_Nederland
 */
class ScoutingNederlandBootstrapNavwalker extends Walker_Nav_Menu {
	/**
	 * Starts the submenu level.
	 *
	 * @param string   $output Used to append additional content.
	 * @param int      $depth  Depth of the current menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= "\n<ul class=\"dropdown-menu\">\n";
	}

	/**
	 * Starts rendering a menu item.
	 *
	 * @param string   $output Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of the current menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Menu item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$classes[]    = 'nav-item';

		if ( $has_children ) {
			$classes[] = 'dropdown';
		}

		$class_names   = join( ' ', array_filter( array_map( 'sanitize_html_class', $classes ) ) );
		$output       .= '<li class="' . esc_attr( $class_names ) . '">';
		$atts          = array( 'href' => ! empty( $item->url ) ? $item->url : '#' );
		$atts['class'] = 0 === $depth ? 'nav-link' : 'dropdown-item';

		if ( $has_children && 0 === $depth ) {
			$atts['class']        .= ' dropdown-toggle';
			$atts['aria-haspopup'] = 'true';
		}

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
		}

		$title   = apply_filters( 'the_title', $item->title, $item->ID );
		$output .= '<a' . $attributes . '>' . esc_html( $title ) . '</a>';
	}
}
