<?php
if (!defined('ABSPATH')) { exit; }

function scoutingtc4_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? 1 : 0);
}

function scoutingtc4_customize_register($wp_customize) {


    $wp_customize->add_section('scoutingtc4_options', array('title' => __('Scouting TC4 opties', 'scoutingtc4'), 'priority' => 30));
    $colors = array('mgroen'=>'Groen', 'mblauw'=>'Blauw', 'mgeel'=>'Geel', 'mrood'=>'Rood', 'moranje'=>'Oranje', 'mpaars'=>'Paars');
    $subcolors = array('sgroen'=>'Groen', 'sblauw'=>'Blauw', 'sgeel'=>'Geel', 'srood'=>'Rood', 'soranje'=>'Oranje', 'spaars'=>'Paars');
    $wp_customize->add_setting('scoutingtc4_main_color', array('default'=>'mgroen', 'sanitize_callback'=>'sanitize_key'));
    $wp_customize->add_control('scoutingtc4_main_color', array('type'=>'select', 'section'=>'scoutingtc4_options', 'label'=>__('Hoofdkleur', 'scoutingtc4'), 'choices'=>$colors));
    $wp_customize->add_setting('scoutingtc4_sub_color', array('default'=>'sgroen', 'sanitize_callback'=>'sanitize_key'));
    $wp_customize->add_control('scoutingtc4_sub_color', array('type'=>'select', 'section'=>'scoutingtc4_options', 'label'=>__('Steunkleur', 'scoutingtc4'), 'choices'=>$subcolors));
    $wp_customize->add_setting('scoutingtc4_logo_stamp', array('default'=>1, 'sanitize_callback'=>'scoutingtc4_sanitize_checkbox'));
    $wp_customize->add_control('scoutingtc4_logo_stamp', array('type'=>'checkbox', 'section'=>'scoutingtc4_options', 'label'=>__('Logo stempel gebruiken', 'scoutingtc4'), 'description'=>__('Toont de stempel/schaduw onder het logo.', 'scoutingtc4')));
    
    $wp_customize->add_setting('scoutingtc4_header_width', array(
        'default'           => 'narrow',
        'sanitize_callback' => function($value) {
            return in_array($value, array('narrow', 'wide'), true) ? $value : 'narrow';
        },
    ));

    $wp_customize->add_control('scoutingtc4_header_width', array(
        'label'       => __('Header breedte', 'scoutingtc4'),
        'description' => __('Kies Smal voor de scouting.nl-achtige header met standaard hamburgermenu of breed met de mogelijkheid voor een horizontaal menu.', 'scoutingtc4'),
        'section'     => 'scoutingtc4_options',
        'type'        => 'radio',
        'choices'     => array(
            'narrow' => __('Smal', 'scoutingtc4'),
            'wide'   => __('Breed', 'scoutingtc4'),
        ),
    ));
    
    $wp_customize->add_setting('scoutingtc4_default_hero_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'scoutingtc4_default_hero_image', array(
        'label'       => __('Standaard hero-afbeelding', 'scoutingtc4'),
        'description' => __('Deze afbeelding wordt gebruikt als er geen eigen pagina-afbeelding, slider of hero-widget is ingesteld.', 'scoutingtc4'),
        'section'     => 'scoutingtc4_options',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('scoutingtc4_hamburger_desktop', array('default'=>0, 'sanitize_callback'=>'scoutingtc4_sanitize_checkbox'));
    $wp_customize->add_control('scoutingtc4_hamburger_desktop', array('type'=>'checkbox', 'section'=>'scoutingtc4_options', 'label'=>__('Hamburger menu ook op desktop', 'scoutingtc4'), 'description'=>__('Uit = horizontaal menu op desktop. Aan = op desktop hamburger-menu. alleen van toepassing op brede headers.', 'scoutingtc4')));

    $wp_customize->add_setting('scoutingtc4_show_search', array('default'=>1, 'sanitize_callback'=>'scoutingtc4_sanitize_checkbox'));
    $wp_customize->add_control('scoutingtc4_show_search', array('type'=>'checkbox', 'section'=>'scoutingtc4_options', 'label'=>__('Zoek-icoon tonen', 'scoutingtc4')));
    $wp_customize->add_setting('scoutingtc4_search_url', array('default'=>'', 'sanitize_callback'=>'esc_url_raw'));
    $wp_customize->add_control('scoutingtc4_search_url', array('type'=>'url', 'section'=>'scoutingtc4_options', 'label'=>__('Zoekpagina URL', 'scoutingtc4'), 'description'=>__('Leeg laten gebruikt de WordPress zoekpagina.', 'scoutingtc4')));

    $wp_customize->add_setting('scoutingtc4_show_phone', array('default'=>1, 'sanitize_callback'=>'scoutingtc4_sanitize_checkbox'));
    $wp_customize->add_control('scoutingtc4_show_phone', array('type'=>'checkbox', 'section'=>'scoutingtc4_options', 'label'=>__('Telefoon-icoon tonen', 'scoutingtc4')));
    $wp_customize->add_setting('scoutingtc4_phone', array('default'=>'', 'sanitize_callback'=>'sanitize_text_field'));
    $wp_customize->add_control('scoutingtc4_phone', array('type'=>'text', 'section'=>'scoutingtc4_options', 'label'=>__('Telefoonnummer', 'scoutingtc4')));

    $wp_customize->add_setting('scoutingtc4_show_contact', array('default'=>1, 'sanitize_callback'=>'scoutingtc4_sanitize_checkbox'));
    $wp_customize->add_control('scoutingtc4_show_contact', array('type'=>'checkbox', 'section'=>'scoutingtc4_options', 'label'=>__('Contact-icoon tonen', 'scoutingtc4')));
    $wp_customize->add_setting('scoutingtc4_contact_url', array('default'=>'', 'sanitize_callback'=>'esc_url_raw'));
    $wp_customize->add_control('scoutingtc4_contact_url', array('type'=>'url', 'section'=>'scoutingtc4_options', 'label'=>__('Contactpagina URL', 'scoutingtc4')));

    $wp_customize->add_setting('scoutingtc4_show_header_message', array('default'=>1, 'sanitize_callback'=>'scoutingtc4_sanitize_checkbox'));
    $wp_customize->add_control('scoutingtc4_show_header_message', array('type'=>'checkbox', 'section'=>'scoutingtc4_options', 'label'=>__('Melding in header gebruiken', 'scoutingtc4')));
    $wp_customize->add_setting('scoutingtc4_header_message', array('default'=>'', 'sanitize_callback'=>'sanitize_text_field'));
    $wp_customize->add_control('scoutingtc4_header_message', array('type'=>'text', 'section'=>'scoutingtc4_options', 'label'=>__('Melding in de header', 'scoutingtc4')));
    $wp_customize->add_setting('scoutingtc4_header_message_url', array('default'=>'', 'sanitize_callback'=>'esc_url_raw'));
    $wp_customize->add_control('scoutingtc4_header_message_url', array('type'=>'url', 'section'=>'scoutingtc4_options', 'label'=>__('Link header melding', 'scoutingtc4')));
    foreach (array('facebook','twitter','linkedin','youtube','instagram') as $network) {
        $wp_customize->add_setting('scoutingtc4_' . $network . '_url', array('default'=>'', 'sanitize_callback'=>'esc_url_raw'));
        $wp_customize->add_control('scoutingtc4_' . $network . '_url', array('type'=>'url', 'section'=>'scoutingtc4_options', 'label'=>ucfirst($network) . ' URL'));
    }

}
add_action('customize_register', 'scoutingtc4_customize_register');
