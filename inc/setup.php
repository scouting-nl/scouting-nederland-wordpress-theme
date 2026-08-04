<?php
if (!defined('ABSPATH')) { exit; }

function scoutingNederlandTrimTextChars($text, $limit = 142) {
    $limit = max(1, absint($limit));
    $text = wp_strip_all_tags((string) $text, true);
    $text = html_entity_decode($text, ENT_QUOTES, get_bloginfo('charset'));
    $text = preg_replace('/\s+/u', ' ', trim($text));

    if ($text === '') {
        return '';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }
        $slice = mb_substr($text, 0, max(1, $limit - 3));
    } else {
        if (strlen($text) <= $limit) {
            return $text;
        }
        $slice = substr($text, 0, max(1, $limit - 3));
    }

    $slice = preg_replace('/\s+\S*$/u', '', $slice);
    $slice = trim($slice);

    if ($slice === '') {
        $slice = function_exists('mb_substr') ? mb_substr($text, 0, max(1, $limit - 3)) : substr($text, 0, max(1, $limit - 3));
    }

    return rtrim($slice, '.,;:!?') . '...';
}

function scoutingNederlandSetup() {
    load_theme_textdomain('scouting-nederland', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('customize-selective-refresh-widgets');
    add_post_type_support('page', 'excerpt');
    add_theme_support('custom-logo', array('height' => 120, 'width' => 240, 'flex-height' => true, 'flex-width' => true));
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    register_nav_menus(array(
        'primary' => __('Hoofdmenu', 'scouting-nederland'),
        'footer'  => __('Footermenu', 'scouting-nederland'),
    ));
}
add_action('after_setup_theme', 'scoutingNederlandSetup');

function scoutingNederlandRegisterBlockStyles() {
    register_block_style('core/button', array(
        'name'  => 'scouting-nederland-blue',
        'label' => __('Scouting blauw', 'scouting-nederland'),
    ));
    register_block_style('core/button', array(
        'name'  => 'scouting-nederland-black',
        'label' => __('Scouting zwart', 'scouting-nederland'),
    ));
}
add_action('init', 'scoutingNederlandRegisterBlockStyles');

function scoutingNederlandGetDefaultHeroUrl() {
    $hero_id = absint(get_theme_mod('scouting_nederland_default_hero_image', 0));

    if ($hero_id) {
        $hero_url = wp_get_attachment_image_url($hero_id, 'full');
        if ($hero_url) {
            return $hero_url;
        }
    }

    return get_template_directory_uri() . '/assets/images/default-slide.jpg';
}

function scoutingNederlandWidgetsInit() {
    $areas = array(
        'hero' => __('Hero', 'scouting-nederland'),
        'featured-a' => __('Featured A', 'scouting-nederland'),
        'featured-b' => __('Featured B', 'scouting-nederland'),
        'featured-c' => __('Featured C', 'scouting-nederland'),
        'showcase' => __('Showcase', 'scouting-nederland'),
        'news' => __('Nieuws', 'scouting-nederland'),
        'breadcrumbs' => __('Breadcrumbs', 'scouting-nederland'),
        'sidebar' => __('Sidebar', 'scouting-nederland'),
        'bottom-a' => __('Bottom A', 'scouting-nederland'),
        'bottom-b' => __('Bottom B', 'scouting-nederland'),
        'footer' => __('Footer', 'scouting-nederland'),
        'footer-partners' => __('Footer - Partners van Scouting', 'scouting-nederland'),
    );
    foreach ($areas as $id => $name) {
        register_sidebar(array(
            'name' => $name,
            'id' => $id,
            'description' => sprintf(__('Widgetgebied %s', 'scouting-nederland'), $name),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'scoutingNederlandWidgetsInit');

function scoutingNederlandPostedOn() {
    echo '<p class="text-muted small">' . esc_html(get_the_date()) . '</p>';
}

function scoutingNederlandIsElementorPage($post_id = null) {
    $post_id = $post_id ? absint($post_id) : absint(get_queried_object_id());

    if (!$post_id || get_post_type($post_id) !== 'page') {
        return false;
    }

    return get_post_meta($post_id, '_elementor_edit_mode', true) === 'builder'
        || get_post_meta($post_id, '_elementor_data', true) !== '';
}

function scoutingNederlandHeaderWidthBodyClass($classes) {
    $mode = get_theme_mod('scouting_nederland_header_width', 'narrow');
    $classes[] = ($mode === 'narrow')
        ? 'scouting-nederland-header-mode-narrow'
        : 'scouting-nederland-header-mode-wide';
    return $classes;
}
add_filter('body_class', 'scoutingNederlandHeaderWidthBodyClass');

function scoutingNederlandDefaultFavicon() {
    if (function_exists('has_site_icon') && has_site_icon()) {
        return;
    }

    $favicon_png = get_template_directory_uri() . '/assets/images/favicon.png';
    echo '<link rel="icon" type="image/png" sizes="512x512" href="' . esc_url($favicon_png) . '">' . "\n";
}
add_action('wp_head', 'scoutingNederlandDefaultFavicon', 1);
