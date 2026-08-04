<?php
if (!defined('ABSPATH')) { exit; }

class ScoutingNederlandBootstrapNavwalker extends Walker_Nav_Menu {
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
        if ($has_children && $depth === 0) {
            $atts['class'] .= ' dropdown-toggle';
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

function scoutingNederlandBodyClasses($classes) {
    $main = get_theme_mod('scouting_nederland_main_color', 'mgroen');
    $support = get_theme_mod('scouting_nederland_sub_color', 'sgroen');

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

    if (isset($main_map[$main])) {
        $classes[] = $main_map[$main];
    }

    if (isset($support_map[$support])) {
        $classes[] = $support_map[$support];
    }

    return array_unique($classes);
}
add_filter('body_class', 'scoutingNederlandBodyClasses');
