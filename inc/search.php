<?php
/**
 * Customizes WordPress search behavior.
 *
 * @package Scouting_Nederland
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

/**
 * Prevents an empty search from returning every post.
 *
 * @param WP_Query $query The current query.
 * @return void
 */
function scouting_nederland_prevent_empty_search_results( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public search URLs use GET and do not require a form nonce.
	$search_term = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

	if ( '' === $search_term ) {
		$query->set( 'post__in', array( 0 ) );
	}
}
add_action( 'pre_get_posts', 'scouting_nederland_prevent_empty_search_results' );

/**
 * Includes pages in search results and marks them for ordering first.
 *
 * @param WP_Query $query The current query.
 * @return void
 */
function scouting_nederland_search_pages_before_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public search URLs use GET and do not require a form nonce.
	$search_term = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

	if ( '' === $search_term ) {
		return;
	}

	$query->set( 'post_type', array( 'page', 'post' ) );
	$query->set( 'scouting_nederland_pages_first', true );
}
add_action( 'pre_get_posts', 'scouting_nederland_search_pages_before_posts', 20 );

/**
 * Sorts matching pages before posts in search results.
 *
 * @param string   $orderby The existing SQL order clause.
 * @param WP_Query $query   The current query.
 * @return string The updated SQL order clause.
 */
function scouting_nederland_search_pages_first_orderby( $orderby, $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() || ! $query->get( 'scouting_nederland_pages_first' ) ) {
		return $orderby;
	}

	global $wpdb;

	$pages_first = "CASE {$wpdb->posts}.post_type WHEN 'page' THEN 0 WHEN 'post' THEN 1 ELSE 2 END ASC";

	if ( ! empty( $orderby ) ) {
		return $pages_first . ', ' . $orderby;
	}

	return $pages_first . ", {$wpdb->posts}.post_date DESC";
}
add_filter( 'posts_orderby', 'scouting_nederland_search_pages_first_orderby', 10, 2 );
