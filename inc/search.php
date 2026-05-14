<?php
if (!defined('ABSPATH')) { exit; }

function scoutingtc4_prevent_empty_search_results($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    $search_term = isset($_GET['s']) ? trim(wp_unslash($_GET['s'])) : '';

    if ($search_term === '') {
        $query->set('post__in', array(0));
    }
}
add_action('pre_get_posts', 'scoutingtc4_prevent_empty_search_results');

function scoutingtc4_search_pages_before_posts($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    $search_term = isset($_GET['s']) ? trim(wp_unslash($_GET['s'])) : '';

    if ($search_term === '') {
        return;
    }

    $query->set('post_type', array('page', 'post'));
    $query->set('scoutingtc4_pages_first', true);
}
add_action('pre_get_posts', 'scoutingtc4_search_pages_before_posts', 20);

function scoutingtc4_search_pages_first_orderby($orderby, $query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search() || !$query->get('scoutingtc4_pages_first')) {
        return $orderby;
    }

    global $wpdb;

    $pages_first = "CASE {$wpdb->posts}.post_type WHEN 'page' THEN 0 WHEN 'post' THEN 1 ELSE 2 END ASC";

    if (!empty($orderby)) {
        return $pages_first . ', ' . $orderby;
    }

    return $pages_first . ", {$wpdb->posts}.post_date DESC";
}
add_filter('posts_orderby', 'scoutingtc4_search_pages_first_orderby', 10, 2);
