<?php
if (!defined('ABSPATH')) { exit; }

function scoutingtc4_trim_text_chars($text, $limit = 142) {
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

function scoutingtc4_setup() {
    load_theme_textdomain('scoutingtc4', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_post_type_support('page', 'excerpt');
    add_theme_support('custom-logo', array('height' => 120, 'width' => 240, 'flex-height' => true, 'flex-width' => true));
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    register_nav_menus(array(
        'primary' => __('Hoofdmenu', 'scoutingtc4'),
        'footer'  => __('Footermenu', 'scoutingtc4'),
    ));
}
add_action('after_setup_theme', 'scoutingtc4_setup');

function scoutingtc4_get_default_hero_url() {
    $hero_id = absint(get_theme_mod('scoutingtc4_default_hero_image', 0));

    if ($hero_id) {
        $hero_url = wp_get_attachment_image_url($hero_id, 'full');
        if ($hero_url) {
            return $hero_url;
        }
    }

    return get_template_directory_uri() . '/assets/images/default-slide.jpg';
}

function scoutingtc4_assets() {
    wp_enqueue_style('scoutingtc4-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&family=Roboto+Slab:wght@700&display=swap', array(), null);
    wp_enqueue_style('scoutingtc4-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');

    wp_enqueue_style('scoutingtc4-template', get_template_directory_uri() . '/assets/css/template.css', array('scoutingtc4-google-fonts', 'scoutingtc4-fontawesome'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-base', get_template_directory_uri() . '/assets/css/base.css', array('scoutingtc4-template'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-utilities', get_template_directory_uri() . '/assets/css/utilities.css', array('scoutingtc4-base'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-header', get_template_directory_uri() . '/assets/css/header.css', array('scoutingtc4-utilities'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-footer', get_template_directory_uri() . '/assets/css/footer.css', array('scoutingtc4-header'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-homepage', get_template_directory_uri() . '/assets/css/homepage.css', array('scoutingtc4-footer'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-news', get_template_directory_uri() . '/assets/css/news.css', array('scoutingtc4-homepage'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-blocks', get_template_directory_uri() . '/assets/css/blocks.css', array('scoutingtc4-news'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-colors', get_template_directory_uri() . '/assets/css/colors.css', array('scoutingtc4-blocks'), SCOUTINGTC4_VERSION);

    if (is_search() || is_404()) {
        wp_enqueue_style('scoutingtc4-search', get_template_directory_uri() . '/assets/css/search.css', array('scoutingtc4-colors'), SCOUTINGTC4_VERSION);
    }

    if (is_404()) {
        wp_enqueue_style('scoutingtc4-404', get_template_directory_uri() . '/assets/css/404.css', array('scoutingtc4-search'), SCOUTINGTC4_VERSION);
    }

    if (is_page()) {
        wp_enqueue_style('scoutingtc4-page', get_template_directory_uri() . '/assets/css/page.css', array('scoutingtc4-colors'), SCOUTINGTC4_VERSION);
    }

    if (is_singular('post')) {
        wp_enqueue_style('scoutingtc4-single', get_template_directory_uri() . '/assets/css/single.css', array('scoutingtc4-colors'), SCOUTINGTC4_VERSION);
    }

    if (is_category()) {
        wp_enqueue_style('scoutingtc4-category', get_template_directory_uri() . '/assets/css/category.css', array('scoutingtc4-colors'), SCOUTINGTC4_VERSION);
    }

    $buttons_dependency = 'scoutingtc4-colors';
    if (is_search()) {
        $buttons_dependency = 'scoutingtc4-search';
    }
    if (is_404()) {
        $buttons_dependency = 'scoutingtc4-404';
    }
    wp_enqueue_style('scoutingtc4-buttons', get_template_directory_uri() . '/assets/css/buttons.css', array($buttons_dependency), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-layout', get_template_directory_uri() . '/assets/css/layout.css', array('scoutingtc4-buttons'), SCOUTINGTC4_VERSION);

    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array(), '5.1.3', true);
    wp_enqueue_script('scoutingtc4-template', get_template_directory_uri() . '/assets/js/template.js', array('bootstrap-bundle'), SCOUTINGTC4_VERSION, true);
    wp_enqueue_script('scoutingtc4-home-slider', get_template_directory_uri() . '/assets/js/home-slider.js', array(), SCOUTINGTC4_VERSION, true);
}
add_action('wp_enqueue_scripts', 'scoutingtc4_assets');

function scoutingtc4_widgets_init() {
    $areas = array(
        'hero' => __('Hero', 'scoutingtc4'),
        'featured-a' => __('Featured A', 'scoutingtc4'),
        'featured-b' => __('Featured B', 'scoutingtc4'),
        'featured-c' => __('Featured C', 'scoutingtc4'),
        'showcase' => __('Showcase', 'scoutingtc4'),
        'news' => __('Nieuws', 'scoutingtc4'),
        'breadcrumbs' => __('Breadcrumbs', 'scoutingtc4'),
        'sidebar' => __('Sidebar', 'scoutingtc4'),
        'bottom-a' => __('Bottom A', 'scoutingtc4'),
        'bottom-b' => __('Bottom B', 'scoutingtc4'),
        'footer' => __('Footer', 'scoutingtc4'),
        'footer-partners' => __('Footer - Partners van Scouting', 'scoutingtc4'),
    );
    foreach ($areas as $id => $name) {
        register_sidebar(array(
            'name' => $name,
            'id' => $id,
            'description' => sprintf(__('Widgetgebied %s', 'scoutingtc4'), $name),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'scoutingtc4_widgets_init');

function scoutingtc4_remove_default_sidebar_widgets() {
    $cleanup_option = 'scoutingtc4_default_sidebar_widgets_removed_154';

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
add_action('after_switch_theme', 'scoutingtc4_remove_default_sidebar_widgets');
add_action('admin_init', 'scoutingtc4_remove_default_sidebar_widgets');
function scoutingtc4_posted_on() {
    echo '<p class="text-muted small">' . esc_html(get_the_date()) . '</p>';
}
function scoutingtc4_header_width_body_class($classes) {
    $mode = get_theme_mod('scoutingtc4_header_width', 'narrow');
    $classes[] = ($mode === 'narrow') ? 'scoutingtc4-header-wide' : 'scoutingtc4-header-narrow';
    return $classes;
}
add_filter('body_class', 'scoutingtc4_header_width_body_class');

function scoutingtc4_default_favicon() {
    if (function_exists('has_site_icon') && has_site_icon()) {
        return;
    }

    $favicon_png = get_template_directory_uri() . '/assets/images/favicon.png';
    $favicon_ico = get_template_directory_uri() . '/assets/images/favicon.ico';
    echo '<link rel="icon" type="image/png" sizes="512x512" href="' . esc_url($favicon_png) . '">' . "\n";
    echo '<link rel="shortcut icon" href="' . esc_url($favicon_ico) . '">' . "\n";
}
add_action('wp_head', 'scoutingtc4_default_favicon', 1);
