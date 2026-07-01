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

function scoutingNederlandAssets() {
    wp_enqueue_style('scouting-nederland-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');

    wp_enqueue_style('scouting-nederland-template', get_template_directory_uri() . '/assets/css/template.css', array('scouting-nederland-fontawesome'), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-base', get_template_directory_uri() . '/assets/css/base.css', array('scouting-nederland-template'), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-utilities', get_template_directory_uri() . '/assets/css/utilities.css', array('scouting-nederland-base'), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-header', get_template_directory_uri() . '/assets/css/header.css', array('scouting-nederland-utilities'), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-footer', get_template_directory_uri() . '/assets/css/footer.css', array('scouting-nederland-header'), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-homepage', get_template_directory_uri() . '/assets/css/homepage.css', array('scouting-nederland-footer'), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-news', get_template_directory_uri() . '/assets/css/news.css', array('scouting-nederland-homepage'), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-blocks', get_template_directory_uri() . '/assets/css/blocks.css', array('scouting-nederland-news'), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-colors', get_template_directory_uri() . '/assets/css/colors.css', array('scouting-nederland-blocks'), SCOUTING_NEDERLAND_VERSION);

    if (is_search() || is_404()) {
        wp_enqueue_style('scouting-nederland-search', get_template_directory_uri() . '/assets/css/search.css', array('scouting-nederland-colors'), SCOUTING_NEDERLAND_VERSION);
    }

    if (is_404()) {
        wp_enqueue_style('scouting-nederland-404', get_template_directory_uri() . '/assets/css/404.css', array('scouting-nederland-search'), SCOUTING_NEDERLAND_VERSION);
    }

    if (is_page()) {
        wp_enqueue_style('scouting-nederland-page', get_template_directory_uri() . '/assets/css/page.css', array('scouting-nederland-colors'), SCOUTING_NEDERLAND_VERSION);
    }

    if (is_singular('post')) {
        wp_enqueue_style('scouting-nederland-single', get_template_directory_uri() . '/assets/css/single.css', array('scouting-nederland-colors'), SCOUTING_NEDERLAND_VERSION);
    }

    if (is_category()) {
        wp_enqueue_style('scouting-nederland-category', get_template_directory_uri() . '/assets/css/category.css', array('scouting-nederland-colors'), SCOUTING_NEDERLAND_VERSION);
    }

    $buttons_dependency = 'scouting-nederland-colors';
    if (is_search()) {
        $buttons_dependency = 'scouting-nederland-search';
    }
    if (is_404()) {
        $buttons_dependency = 'scouting-nederland-404';
    }
    wp_enqueue_style('scouting-nederland-buttons', get_template_directory_uri() . '/assets/css/buttons.css', array($buttons_dependency), SCOUTING_NEDERLAND_VERSION);
    wp_enqueue_style('scouting-nederland-layout', get_template_directory_uri() . '/assets/css/layout.css', array('scouting-nederland-buttons'), SCOUTING_NEDERLAND_VERSION);

    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array(), '5.1.3', true);
    wp_enqueue_script('scouting-nederland-template', get_template_directory_uri() . '/assets/js/template.js', array('bootstrap-bundle'), SCOUTING_NEDERLAND_VERSION, true);
    wp_enqueue_script('scouting-nederland-home-slider', get_template_directory_uri() . '/assets/js/home-slider.js', array(), SCOUTING_NEDERLAND_VERSION, true);
}
add_action('wp_enqueue_scripts', 'scoutingNederlandAssets');

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

function scoutingNederlandRemoveDefaultSidebarWidgets() {
    $cleanup_option = 'scouting_nederland_default_sidebar_widgets_removed_154';

    if (get_option($cleanup_option)) {
        return;
    }

    $sidebars_widgets = get_option('sidebars_widgets');
    if (!is_array($sidebars_widgets) || empty($sidebars_widgets['sidebar']) || !is_array($sidebars_widgets['sidebar'])) {
        update_option($cleanup_option, 1, false);
        return;
    }

    $default_widget_patterns = array(
        '/^recent-posts-\d+$/',
        '/^recent-comments-\d+$/',
        '/^archives-\d+$/',
        '/^categories-\d+$/',
    );

    $sidebars_widgets['sidebar'] = array_values(array_filter(
        $sidebars_widgets['sidebar'],
        function($widget_id) use ($default_widget_patterns) {
            foreach ($default_widget_patterns as $pattern) {
                if (preg_match($pattern, $widget_id)) {
                    return false;
                }
            }
            return true;
        }
    ));

    update_option('sidebars_widgets', $sidebars_widgets);
    update_option($cleanup_option, 1, false);
}
add_action('after_switch_theme', 'scoutingNederlandRemoveDefaultSidebarWidgets');
add_action('admin_init', 'scoutingNederlandRemoveDefaultSidebarWidgets');
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

function scoutingNederlandElementorBodyClass($classes) {
    if (scoutingNederlandIsElementorPage()) {
        $classes[] = 'scouting-nederland-is-elementor-page';
    }

    return $classes;
}
add_filter('body_class', 'scoutingNederlandElementorBodyClass');

function scoutingNederlandHeaderWidthBodyClass($classes) {
    $mode = get_theme_mod('scouting_nederland_header_width', 'narrow');
    $classes[] = ($mode === 'narrow') ? 'scouting-nederland-header-wide' : 'scouting-nederland-header-narrow';
    return $classes;
}
add_filter('body_class', 'scoutingNederlandHeaderWidthBodyClass');

function scoutingNederlandDefaultFavicon() {
    if (function_exists('has_site_icon') && has_site_icon()) {
        return;
    }

    $favicon_png = get_template_directory_uri() . '/assets/images/favicon.png';
    $favicon_ico = get_template_directory_uri() . '/assets/images/favicon.ico';
    echo '<link rel="icon" type="image/png" sizes="512x512" href="' . esc_url($favicon_png) . '">' . "\n";
    echo '<link rel="shortcut icon" href="' . esc_url($favicon_ico) . '">' . "\n";
}
add_action('wp_head', 'scoutingNederlandDefaultFavicon', 1);
