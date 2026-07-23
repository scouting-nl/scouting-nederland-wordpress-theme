<?php
if (!defined('ABSPATH')) { exit; }

class ScoutingTC4_Bootstrap_Navwalker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= "\n<ul class=\"dropdown-menu\">\n";
    }
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes, true);
        $classes[] = 'nav-item';
        if ($has_children) { $classes[] = 'dropdown'; }
        $class_names = join(' ', array_filter(array_map('sanitize_html_class', $classes)));
        $output .= '<li class="' . esc_attr($class_names) . '">';
        $atts = array('href' => !empty($item->url) ? $item->url : '#');
        $atts['class'] = $depth === 0 ? 'nav-link' : 'dropdown-item';
        if ($has_children) {
            $atts['class'] .= $depth === 0 ? ' dropdown-toggle' : ' submenu-link';
            $atts['aria-haspopup'] = 'true';
        }
        $attributes = '';
        foreach ($atts as $attr => $value) {
            $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
        }
        $title = apply_filters('the_title', $item->title, $item->ID);
        $output .= '<a' . $attributes . '>' . esc_html($title) . '</a>';
    }
}

function scoutingtc4_body_classes($classes) {
    $main = get_theme_mod('scoutingtc4_main_color', 'mgroen');
    $support = get_theme_mod('scoutingtc4_sub_color', 'sgroen');

    $classes[] = $main;
    $classes[] = $support;

    $main_map = array(
        'mgroen'  => 'green-main',
        'mblauw'  => 'blue-main',
        'mgeel'   => 'yellow-main',
        'mrood'   => 'red-main',
        'moranje' => 'orange-main',
        'mpaars'  => 'purple-main',
    );

    $support_map = array(
        'sgroen'  => 'green-support',
        'sblauw'  => 'blue-support',
        'sgeel'   => 'yellow-support',
        'srood'   => 'red-support',
        'soranje' => 'orange-support',
        'spaars'  => 'purple-support',
    );

    if (isset($main_map[$main])) {
        $classes[] = $main_map[$main];
    }

    if (isset($support_map[$support])) {
        $classes[] = $support_map[$support];
    }

    return array_unique($classes);
}
add_filter('body_class', 'scoutingtc4_body_classes');
