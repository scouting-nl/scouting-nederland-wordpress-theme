<?php
if (!defined('ABSPATH')) { exit; }

function scoutingNederlandPreventEmptySearchResults($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    $search_term = isset($_GET['s']) ? trim(wp_unslash($_GET['s'])) : '';

    if ($search_term === '') {
        $query->set('post__in', array(0));
    }
}
add_action('pre_get_posts', 'scoutingNederlandPreventEmptySearchResults');

function scoutingNederlandSearchPagesBeforePosts($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    $search_term = isset($_GET['s']) ? trim(wp_unslash($_GET['s'])) : '';

    if ($search_term === '') {
        return;
    }

    $query->set('post_type', array('page', 'post'));
    $query->set('scouting_nederland_pages_first', true);
}
add_action('pre_get_posts', 'scoutingNederlandSearchPagesBeforePosts', 20);

function scoutingNederlandSearchPagesFirstOrderby($orderby, $query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search() || !$query->get('scouting_nederland_pages_first')) {
        return $orderby;
    }

    global $wpdb;

    $pages_first = "CASE {$wpdb->posts}.post_type WHEN 'page' THEN 0 WHEN 'post' THEN 1 ELSE 2 END ASC";

    if (!empty($orderby)) {
        return $pages_first . ', ' . $orderby;
    }

    return $pages_first . ", {$wpdb->posts}.post_date DESC";
}
add_filter('posts_orderby', 'scoutingNederlandSearchPagesFirstOrderby', 10, 2);
