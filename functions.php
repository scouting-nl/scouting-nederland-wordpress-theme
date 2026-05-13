<?php
if (!defined('ABSPATH')) { exit; }

define('SCOUTINGTC4_VERSION', '1.1');

function scoutingtc4_setup() {
    load_theme_textdomain('scoutingtc4', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
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
    wp_enqueue_style('scoutingtc4-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array('scoutingtc4-blocks'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-colors', get_template_directory_uri() . '/assets/css/colors.css', array('scoutingtc4-responsive'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-404', get_template_directory_uri() . '/assets/css/404.css', array('scoutingtc4-colors'), SCOUTINGTC4_VERSION);
    wp_enqueue_style('scoutingtc4-search', get_template_directory_uri() . '/assets/css/search.css', array('scoutingtc4-colors'), SCOUTINGTC4_VERSION);

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

function scoutingtc4_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? 1 : 0);
}

function scoutingtc4_customize_register($wp_customize) {

    $wp_customize->add_section('scoutingtc4_header_layout', array(
        'title'       => __('Header layout', 'scoutingtc4'),
        'priority'    => 31,
        'description' => __('Instellingen voor de headerweergave.', 'scoutingtc4'),
    ));

    $wp_customize->add_section('scoutingtc4_options', array('title' => __('Scouting TC4 opties', 'scoutingtc4'), 'priority' => 30));
    $colors = array('mgroen'=>'Groen', 'mblauw'=>'Blauw', 'mgeel'=>'Geel', 'mrood'=>'Rood', 'moranje'=>'Oranje', 'mpaars'=>'Paars');
    $subcolors = array('sgroen'=>'Groen', 'sblauw'=>'Blauw', 'sgeel'=>'Geel', 'srood'=>'Rood', 'soranje'=>'Oranje', 'spaars'=>'Paars');
    $wp_customize->add_setting('scoutingtc4_main_color', array('default'=>'mgroen', 'sanitize_callback'=>'sanitize_key'));
    $wp_customize->add_control('scoutingtc4_main_color', array('type'=>'select', 'section'=>'scoutingtc4_options', 'label'=>__('Hoofdkleur', 'scoutingtc4'), 'choices'=>$colors));
    $wp_customize->add_setting('scoutingtc4_sub_color', array('default'=>'sgroen', 'sanitize_callback'=>'sanitize_key'));
    $wp_customize->add_control('scoutingtc4_sub_color', array('type'=>'select', 'section'=>'scoutingtc4_options', 'label'=>__('Steunkleur', 'scoutingtc4'), 'choices'=>$subcolors));
    $wp_customize->add_setting('scoutingtc4_logo_stamp', array('default'=>1, 'sanitize_callback'=>'scoutingtc4_sanitize_checkbox'));
    $wp_customize->add_control('scoutingtc4_logo_stamp', array('type'=>'checkbox', 'section'=>'scoutingtc4_options', 'label'=>__('Logo stempel gebruiken', 'scoutingtc4'), 'description'=>__('Zoals in Joomla: toont de stempel/schaduw onder het logo.', 'scoutingtc4')));

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
    $wp_customize->add_control('scoutingtc4_hamburger_desktop', array('type'=>'checkbox', 'section'=>'scoutingtc4_options', 'label'=>__('Hamburger menu op desktop', 'scoutingtc4'), 'description'=>__('Uit = horizontaal menu op desktop. Aan = ook op desktop hamburger-menu, zoals de Joomla optie.', 'scoutingtc4')));

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

    $wp_customize->add_setting('scoutingtc4_header_width', array(
        'default'           => 'narrow',
        'sanitize_callback' => function($value) {
            return in_array($value, array('narrow', 'wide'), true) ? $value : 'narrow';
        },
    ));

    $wp_customize->add_control('scoutingtc4_header_width', array(
        'label'       => __('Header breedte', 'scoutingtc4'),
        'description' => __('Kies Smal voor de scouting.nl-achtige header of Breed voor de oude template-header.', 'scoutingtc4'),
        'section'     => 'scoutingtc4_header_layout',
        'type'        => 'radio',
        'choices'     => array(
            'narrow' => __('Smal', 'scoutingtc4'),
            'wide'   => __('Breed', 'scoutingtc4'),
        ),
    ));

}
add_action('customize_register', 'scoutingtc4_customize_register');

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
        if ($has_children && $depth === 0) {
            $atts['class'] .= ' dropdown-toggle';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded'] = 'false';
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

function scoutingtc4_posted_on() {
    echo '<p class="text-muted small">' . esc_html(get_the_date()) . '</p>';
}

function scoutingtc4_homepage_features_setup() {
    add_image_size('tc4_home_hero', 1920, 520, true);
    add_image_size('tc4_home_card', 560, 320, true);
    add_theme_support('editor-styles');
    add_editor_style(array('style.css', 'assets/css/base.css', 'assets/css/utilities.css', 'assets/css/blocks.css', 'assets/css/homepage.css', 'assets/css/news.css', 'assets/css/editor.css', 'assets/css/colors.css'));
}
add_action('after_setup_theme', 'scoutingtc4_homepage_features_setup', 20);

function scoutingtc4_add_slider_metabox() {
    add_meta_box(
        'scoutingtc4_slider',
        __('Slider', 'scoutingtc4'),
        'scoutingtc4_render_slider_metabox',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'scoutingtc4_add_slider_metabox');

function scoutingtc4_render_slider_metabox($post) {
    wp_nonce_field('scoutingtc4_save_slider', 'scoutingtc4_slider_nonce');
    $show   = get_post_meta($post->ID, '_tc4_slider_show', true);
    $type   = get_post_meta($post->ID, '_tc4_slider_type', true) ?: 'slider';
    $video  = get_post_meta($post->ID, '_tc4_slider_video', true);
    $video_id = absint(get_post_meta($post->ID, '_tc4_slider_video_id', true));
    $video_heading = get_post_meta($post->ID, '_tc4_slider_video_heading', true);
    $slides = get_post_meta($post->ID, '_tc4_slides', true);
    if (!is_array($slides)) {
        $slides = array();
    }
    ?>
    <div class="tc4-slider-metabox">
        <p>
            <label>
                <input type="checkbox" name="tc4_slider_show" value="1" <?php checked($show, '1'); ?>>
                <?php esc_html_e('Slider tonen', 'scoutingtc4'); ?>
            </label>
        </p>

        <p>
            <strong><?php esc_html_e('Type', 'scoutingtc4'); ?></strong><br>
            <label><input type="radio" name="tc4_slider_type" value="video" <?php checked($type, 'video'); ?>> <?php esc_html_e('video', 'scoutingtc4'); ?></label>
            &nbsp;&nbsp;
            <label><input type="radio" name="tc4_slider_type" value="slider" <?php checked($type, 'slider'); ?>> <?php esc_html_e('slider', 'scoutingtc4'); ?></label>
        </p>

        <div class="tc4-video-field">
            <p><strong><?php esc_html_e('Video', 'scoutingtc4'); ?></strong></p>
            <input type="hidden" id="tc4_slider_video_id" name="tc4_slider_video_id" value="<?php echo esc_attr($video_id); ?>">
            <div id="tc4-slider-video-preview" class="tc4-slider-video-preview">
                <?php if ($video_id) :
                    $video_url = wp_get_attachment_url($video_id);
                    if ($video_url) : ?>
                        <video src="<?php echo esc_url($video_url); ?>" style="max-width:320px;height:auto;" controls muted></video>
                        <p><code><?php echo esc_html(basename($video_url)); ?></code></p>
                    <?php endif;
                endif; ?>
            </div>
            <p>
                <button type="button" class="button" id="tc4-select-video"><?php esc_html_e('Video kiezen uit bibliotheek', 'scoutingtc4'); ?></button>
                <button type="button" class="button-link-delete" id="tc4-remove-video"><?php esc_html_e('Video verwijderen', 'scoutingtc4'); ?></button>
            </p>
            <p>
                <label for="tc4_slider_video_heading"><strong><?php esc_html_e('Kop over video', 'scoutingtc4'); ?></strong></label><br>
                <input type="text" id="tc4_slider_video_heading" name="tc4_slider_video_heading" value="<?php echo esc_attr($video_heading); ?>" class="widefat" placeholder="<?php esc_attr_e('Plaats hier ...', 'scoutingtc4'); ?>">
                <span class="description"><?php esc_html_e('Deze tekst wordt over de videobanner heen getoond.', 'scoutingtc4'); ?></span>
            </p>

            <p>
                <label for="tc4_slider_video"><strong><?php esc_html_e('Of externe video URL', 'scoutingtc4'); ?></strong></label><br>
                <input type="url" id="tc4_slider_video" name="tc4_slider_video" value="<?php echo esc_attr($video); ?>" class="widefat" placeholder="https://...">
                <span class="description"><?php esc_html_e('Gebruik dit alleen voor bijvoorbeeld YouTube/Vimeo. Een gekozen bibliotheekvideo heeft voorrang.', 'scoutingtc4'); ?></span>
            </p>
        </div>

        <table class="widefat striped tc4-slides-table">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th style="width:220px;"><?php esc_html_e('Achtergrondbeeld', 'scoutingtc4'); ?></th>
                    <th><?php esc_html_e('Kop', 'scoutingtc4'); ?></th>
                    <th style="width:90px;"></th>
                </tr>
            </thead>
            <tbody id="tc4-slides-rows">
                <?php foreach ($slides as $index => $slide) :
                    $image_id = isset($slide['image_id']) ? absint($slide['image_id']) : 0;
                    $heading  = isset($slide['heading']) ? $slide['heading'] : '';
                    $preview  = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
                    ?>
                    <tr class="tc4-slide-row">
                        <td class="tc4-slide-number"><?php echo esc_html($index + 1); ?></td>
                        <td>
                            <input type="hidden" class="tc4-slide-image-id" name="tc4_slides[<?php echo esc_attr($index); ?>][image_id]" value="<?php echo esc_attr($image_id); ?>">
                            <div class="tc4-slide-preview">
                                <?php if ($preview) : ?><img src="<?php echo esc_url($preview); ?>" alt="" style="max-width:120px;height:auto;"><?php endif; ?>
                            </div>
                            <button type="button" class="button tc4-select-image"><?php esc_html_e('Afbeelding kiezen', 'scoutingtc4'); ?></button>
                        </td>
                        <td>
                            <input type="text" class="widefat" name="tc4_slides[<?php echo esc_attr($index); ?>][heading]" value="<?php echo esc_attr($heading); ?>" placeholder="<?php esc_attr_e('Plaats hier ...', 'scoutingtc4'); ?>">
                        </td>
                        <td><button type="button" class="button-link-delete tc4-remove-slide"><?php esc_html_e('Verwijder', 'scoutingtc4'); ?></button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>
            <button type="button" class="button button-primary" id="tc4-add-slide"><?php esc_html_e('Slide toevoegen', 'scoutingtc4'); ?></button>
        </p>

        <script type="text/html" id="tc4-slide-template">
            <tr class="tc4-slide-row">
                <td class="tc4-slide-number"></td>
                <td>
                    <input type="hidden" class="tc4-slide-image-id" name="tc4_slides[__INDEX__][image_id]" value="">
                    <div class="tc4-slide-preview"></div>
                    <button type="button" class="button tc4-select-image"><?php esc_html_e('Afbeelding kiezen', 'scoutingtc4'); ?></button>
                </td>
                <td>
                    <input type="text" class="widefat" name="tc4_slides[__INDEX__][heading]" value="" placeholder="<?php esc_attr_e('Plaats hier ...', 'scoutingtc4'); ?>">
                </td>
                <td><button type="button" class="button-link-delete tc4-remove-slide"><?php esc_html_e('Verwijder', 'scoutingtc4'); ?></button></td>
            </tr>
        </script>
    </div>
    <?php
}

function scoutingtc4_save_slider_metabox($post_id) {
    if (!isset($_POST['scoutingtc4_slider_nonce']) || !wp_verify_nonce($_POST['scoutingtc4_slider_nonce'], 'scoutingtc4_save_slider')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    update_post_meta($post_id, '_tc4_slider_show', isset($_POST['tc4_slider_show']) ? '1' : '0');

    $type = isset($_POST['tc4_slider_type']) && $_POST['tc4_slider_type'] === 'video' ? 'video' : 'slider';
    update_post_meta($post_id, '_tc4_slider_type', $type);
    update_post_meta($post_id, '_tc4_slider_video', isset($_POST['tc4_slider_video']) ? esc_url_raw($_POST['tc4_slider_video']) : '');
    update_post_meta($post_id, '_tc4_slider_video_id', isset($_POST['tc4_slider_video_id']) ? absint($_POST['tc4_slider_video_id']) : 0);
    update_post_meta($post_id, '_tc4_slider_video_heading', isset($_POST['tc4_slider_video_heading']) ? sanitize_text_field($_POST['tc4_slider_video_heading']) : '');

    $slides = array();
    if (isset($_POST['tc4_slides']) && is_array($_POST['tc4_slides'])) {
        foreach ($_POST['tc4_slides'] as $slide) {
            $image_id = isset($slide['image_id']) ? absint($slide['image_id']) : 0;
            $heading  = isset($slide['heading']) ? sanitize_text_field($slide['heading']) : '';
            if ($image_id || $heading) {
                $slides[] = array(
                    'image_id' => $image_id,
                    'heading'  => $heading,
                );
            }
        }
    }
    update_post_meta($post_id, '_tc4_slides', $slides);
}
add_action('save_post_page', 'scoutingtc4_save_slider_metabox');

function scoutingtc4_admin_slider_assets($hook) {
    if (!in_array($hook, array('post.php', 'post-new.php'), true)) {
        return;
    }
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'page') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('scoutingtc4-admin-slider', get_template_directory_uri() . '/assets/js/admin-slider.js', array('jquery'), SCOUTINGTC4_VERSION, true);
}
add_action('admin_enqueue_scripts', 'scoutingtc4_admin_slider_assets');

function scoutingtc4_render_home_slider($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    if (get_post_meta($post_id, '_tc4_slider_show', true) !== '1') {
        return false;
    }

    $type = get_post_meta($post_id, '_tc4_slider_type', true) ?: 'slider';

    if ($type === 'video') {
        $video_id = absint(get_post_meta($post_id, '_tc4_slider_video_id', true));
        $video = get_post_meta($post_id, '_tc4_slider_video', true);

        if ($video_id) {
            $video_url = wp_get_attachment_url($video_id);
            if ($video_url) {
                $video_heading = get_post_meta($post_id, '_tc4_slider_video_heading', true);
                echo '<section class="tc4-home-slider tc4-home-video tc4-home-video-library">';
                echo '<video class="tc4-home-video-player" src="' . esc_url($video_url) . '" autoplay muted loop playsinline></video>';
                if ($video_heading) {
                    echo '<div class="tc4-home-video-caption"><h1>' . esc_html($video_heading) . '</h1></div>';
                }
                echo '</section>';
                return true;
            }
        }

        if ($video) {
            $embed = wp_oembed_get($video);
            if ($embed) {
                $video_heading = get_post_meta($post_id, '_tc4_slider_video_heading', true);
                echo '<section class="tc4-home-slider tc4-home-video tc4-home-video-embed"><div class="tc4-video-wrap">' . $embed . '</div>';
                if ($video_heading) {
                    echo '<div class="tc4-home-video-caption"><h1>' . esc_html($video_heading) . '</h1></div>';
                }
                echo '</section>';
                return true;
            }
        }
    }

    $slides = get_post_meta($post_id, '_tc4_slides', true);
    if (!is_array($slides) || empty($slides)) {
        return false;
    }

    echo '<section class="tc4-home-slider" data-tc4-slider>';
    foreach ($slides as $i => $slide) {
        $image_id = isset($slide['image_id']) ? absint($slide['image_id']) : 0;
        $heading  = isset($slide['heading']) ? $slide['heading'] : '';
        $image    = $image_id ? wp_get_attachment_image_url($image_id, 'tc4_home_hero') : '';
        if (!$image) {
            continue;
        }
        echo '<div class="tc4-home-slide' . ($i === 0 ? ' is-active' : '') . '" style="background-image:url(' . esc_url($image) . ')">';
        if ($heading) {
            echo '<div class="tc4-home-slide__caption"><h1>' . esc_html($heading) . '</h1></div>';
        }
        echo '</div>';
    }
    echo '</section>';
    return true;
}

function scoutingtc4_callout_image_options() {
    return array(
        'bijl'    => array('label' => 'bijl', 'file' => 'callout-bijl.png'),
        'boei'    => array('label' => 'boei', 'file' => 'callout-boei.png'),
        'gps'     => array('label' => 'gps', 'file' => 'callout-gps.png'),
        'haken'   => array('label' => 'haken', 'file' => 'callout-haken.png'),
        'hout'    => array('label' => 'hout', 'file' => 'callout-hout.png'),
        'kompas'  => array('label' => 'kompas', 'file' => 'callout-kompas.png'),
        'lamp'    => array('label' => 'lamp', 'file' => 'callout-lamp.png'),
        'touw'    => array('label' => 'touw', 'file' => 'callout-touw.png'),
        'zaklamp' => array('label' => 'zaklamp', 'file' => 'callout-zaklamp.png'),
        'zakmes'  => array('label' => 'zakmes', 'file' => 'callout-zakmes.png'),
    );
}

function scoutingtc4_callout_image_url($preset) {
    $options = scoutingtc4_callout_image_options();
    if (!isset($options[$preset])) {
        $preset = 'kompas';
    }
    return get_template_directory_uri() . '/assets/images/' . $options[$preset]['file'];
}

function scoutingtc4_register_editor_blocks() {
    wp_register_script(
        'scoutingtc4-blocks',
        get_template_directory_uri() . '/assets/js/blocks.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render', 'wp-url', 'jquery', 'wplink', 'quicktags'),
        SCOUTINGTC4_VERSION,
        true
    );
    $callout_options = array();
    foreach (scoutingtc4_callout_image_options() as $key => $option) {
        $callout_options[] = array(
            'label' => $option['label'],
            'value' => $key,
            'url'   => get_template_directory_uri() . '/assets/images/' . $option['file'],
        );
    }

    $news_categories = array();
    $categories = get_categories(array('hide_empty' => false));
    foreach ($categories as $category) {
        $news_categories[] = array(
            'id' => (int) $category->term_id,
            'name' => $category->name,
        );
    }

    $news_posts = array();
    $posts_for_select = get_posts(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => 100,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    foreach ($posts_for_select as $news_post) {
        $news_posts[] = array(
            'id' => (int) $news_post->ID,
            'title' => get_the_title($news_post),
            'categoryIds' => wp_get_post_categories($news_post->ID),
        );
    }

    wp_localize_script('scoutingtc4-blocks', 'scoutingtc4Blocks', array(
        'calloutOptions' => $callout_options,
        'newsCategories' => $news_categories,
        'newsPosts' => $news_posts,
    ));

    $common = array('editor_script' => 'scoutingtc4-blocks');

    register_block_type('scoutingtc4/featured-items', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => ''),
            'items' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => 'scoutingtc4_render_featured_items_block',
    )));

    register_block_type('scoutingtc4/callout', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Vrijwilligers gezocht'),
            'text' => array('type' => 'string', 'default' => ''),
            'buttonText' => array('type' => 'string', 'default' => 'Meld je aan'),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
            'buttonColor' => array('type' => 'string', 'default' => 'blauw'),
            'buttonColor' => array('type' => 'string', 'default' => 'zwart'),
            'useOwnImage' => array('type' => 'boolean', 'default' => false),
            'imageId' => array('type' => 'number', 'default' => 0),
            'imageUrl' => array('type' => 'string', 'default' => ''),
            'calloutPreset' => array('type' => 'string', 'default' => ''),
        ),
        'render_callback' => 'scoutingtc4_render_callout_block',
    )));

    register_block_type('scoutingtc4/news-items', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Nieuws'),
            'categoryIds' => array('type' => 'array', 'default' => array()),
            'postIds' => array('type' => 'array', 'default' => array()),
            'count' => array('type' => 'number', 'default' => 3),
            'buttonText' => array('type' => 'string', 'default' => 'Meer nieuws'),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
            'buttonColor' => array('type' => 'string', 'default' => 'blauw'),
        ),
        'render_callback' => 'scoutingtc4_render_news_items_block',
    )));

    register_block_type('scoutingtc4/two-columns', array_merge($common, array(
        'attributes' => array(
            'leftTitle' => array('type' => 'string', 'default' => 'Gratis sponsoren'),
            'leftText' => array('type' => 'string', 'default' => ''),
            'leftImageUrl' => array('type' => 'string', 'default' => ''),
            'leftButtonText' => array('type' => 'string', 'default' => 'Lees hier meer!'),
            'leftButtonUrl' => array('type' => 'string', 'default' => ''),
            'leftButtonColor' => array('type' => 'string', 'default' => 'blauw'),
            'rightTitle' => array('type' => 'string', 'default' => 'Vacatures'),
            'rightText' => array('type' => 'string', 'default' => ''),
            'rightSecondTitle' => array('type' => 'string', 'default' => 'Maatschappelijke stage'),
            'rightSecondText' => array('type' => 'string', 'default' => ''),
            'rightButtonText' => array('type' => 'string', 'default' => 'Meer vacatures'),
            'rightButtonUrl' => array('type' => 'string', 'default' => ''),
            'rightButtonColor' => array('type' => 'string', 'default' => 'zwart'),
        ),
        'render_callback' => 'scoutingtc4_render_two_columns_block',
    )));
}
add_action('init', 'scoutingtc4_register_editor_blocks');


function scoutingtc4_render_featured_quicklinks($text) {
    $text = trim((string) $text);

    if ($text === '') {
        return '';
    }

    $links = array();

    if (preg_match_all('/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $text, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $label = trim(wp_strip_all_tags($match[2]));
            $href  = trim(html_entity_decode($match[1], ENT_QUOTES, get_bloginfo('charset')));

            if ($label === '') {
                $label = $href;
            }

            if ($href === '') {
                $href = '#';
            }

            $links[] = array(
                'label' => $label,
                'href'  => $href,
            );
        }
    }

    if (empty($links)) {
        $parts = preg_split('/<br\s*\/?>|\r\n|\r|\n|<\/p>\s*<p>/i', $text);

        foreach ($parts as $part) {
            $part = trim(wp_strip_all_tags($part));

            if ($part === '') {
                continue;
            }

            $links[] = array(
                'label' => $part,
                'href'  => '#',
            );
        }
    }

    if (empty($links)) {
        return '';
    }

    $output = '<ul class="tc4-quicklink-list">';

    foreach ($links as $link) {
        $output .= '<li><a href="' . esc_url($link['href']) . '">' . esc_html($link['label']) . '</a></li>';
    }

    $output .= '</ul>';

    return $output;
}


function scoutingtc4_render_featured_items_block($attrs) {
    $items = isset($attrs['items']) && is_array($attrs['items']) ? $attrs['items'] : array();
    if (empty($items)) {
        $items = array(
            array('title' => 'Wie zijn wij?', 'text' => 'Scouting staat voor uitdaging!', 'buttonText' => 'Meer info', 'buttonColor' => 'blauw'),
            array('title' => 'Lid worden', 'text' => 'Laat je uitdagen en ontdek Scouting!', 'buttonText' => 'Kom een keer meedoen', 'buttonColor' => 'blauw'),
            array('title' => 'Snel naar', 'text' => 'Verhuur<br>Stockfoto’s<br>Scouting Nederland', 'buttonText' => '', 'buttonColor' => 'blauw'),
        );
    }

    ob_start();
    echo '<section class="tc4-block-featured-items tc4-featured-style-2020">';
    if (!empty($attrs['title'])) {
        echo '<h2 class="tc4-featured-main-title">' . esc_html($attrs['title']) . '</h2>';
    }
    echo '<div class="tc4-featured-grid">';
    foreach (array_slice($items, 0, 3) as $index => $item) {
        $title = isset($item['title']) ? $item['title'] : '';
        $text = isset($item['text']) ? $item['text'] : '';
        $image = isset($item['imageUrl']) ? $item['imageUrl'] : '';
        $button = isset($item['buttonText']) ? $item['buttonText'] : '';
        $url = isset($item['buttonUrl']) ? $item['buttonUrl'] : '';
        $color = isset($item['buttonColor']) ? $item['buttonColor'] : 'blauw';
        $is_quicklinks = empty($button) && $index === 2;

        echo '<article class="tc4-featured-item' . ($is_quicklinks ? ' tc4-featured-quicklinks' : '') . '">';
        if ($title) echo '<h2>' . wp_kses_post($title) . '</h2>';
        if ($image) echo '<img src="' . esc_url($image) . '" alt="">';

        if ($text) {
            if ($is_quicklinks) {
                echo scoutingtc4_render_featured_quicklinks($text);
            } else {
                echo '<div class="tc4-featured-text">' . wp_kses_post(wpautop($text)) . '</div>';
            }
        }

        if ($button) {
            echo '<a class="tc4-btn ' . esc_attr(scoutingtc4_button_class_from_color($color)) . '" href="' . esc_url($url ?: '#') . '"><span>' . esc_html($button) . '</span></a>';
        }
        echo '</article>';
    }
    echo '</div></section>';
    return ob_get_clean();
}

function scoutingtc4_render_callout_block($attrs) {
    $image = !empty($attrs['useOwnImage']) && !empty($attrs['imageUrl']) ? $attrs['imageUrl'] : '';
    if (empty($image)) {
        $image = scoutingtc4_callout_image_url(!empty($attrs['calloutPreset']) ? $attrs['calloutPreset'] : 'kompas');
    }

    $title = !empty($attrs['title']) ? $attrs['title'] : 'Vrijwilligers gezocht';
    $text = isset($attrs['text']) ? trim((string) $attrs['text']) : '';
    if ($text === '') {
        $text = 'Ben jij enthousiast, geef je goed leiding en vind je het leuk om kinderen en jongeren te begeleiden bij hun persoonlijke ontwikkeling? Of heb je misschien bestuurlijke kwaliteiten die je wilt inzetten? Dan zijn wij op zoek naar jou!';
    }

    ob_start();
    echo '<section class="tc4-block-callout tc4-callout-style-2020">';
    echo '<div class="tc4-callout-inner">';
    echo '<div class="tc4-callout-image">';
    if ($image) {
        echo '<img src="' . esc_url($image) . '" alt="">';
    }
    echo '</div>';
    echo '<div class="tc4-callout-text">';
    if ($title) {
        echo '<h2>' . esc_html($title) . '</h2>';
    }

    echo '<div class="tc4-callout-copy">' . wp_kses_post(wpautop($text)) . '</div>';

    if (!empty($attrs['buttonText'])) {
        echo '<a class="tc4-btn ' . esc_attr(scoutingtc4_button_class_from_color(!empty($attrs['buttonColor']) ? $attrs['buttonColor'] : 'zwart')) . '" href="' . esc_url(!empty($attrs['buttonUrl']) ? $attrs['buttonUrl'] : '#') . '"><span>' . esc_html($attrs['buttonText']) . '</span></a>';
    }
    echo '</div></div></section>';
    return ob_get_clean();
}

function scoutingtc4_render_news_items_block($attrs) {
    $count = !empty($attrs['count']) ? absint($attrs['count']) : 3;
    $post_ids = !empty($attrs['postIds']) && is_array($attrs['postIds']) ? array_values(array_filter(array_map('absint', $attrs['postIds']))) : array();
    $category_ids = !empty($attrs['categoryIds']) && is_array($attrs['categoryIds']) ? array_values(array_filter(array_map('absint', $attrs['categoryIds']))) : array();

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $count,
        'post_status' => 'publish',
    );

    if (!empty($post_ids)) {
        $args['post__in'] = $post_ids;
        $args['orderby'] = 'post__in';
        $args['posts_per_page'] = count($post_ids);
    } elseif (!empty($category_ids)) {
        $args['category__in'] = $category_ids;
    }

    $category_link = '';
    if (!empty($category_ids)) {
        $category_link = get_category_link((int) $category_ids[0]);
    }
    if (empty($category_link)) {
        $posts_page_id = (int) get_option('page_for_posts');
        $category_link = $posts_page_id ? get_permalink($posts_page_id) : home_url('/');
    }

    $query = new WP_Query($args);
    ob_start();
    echo '<section class="tc4-block-news tc4-news-style-2020">';
    echo '<div class="gb-news-items">';
    if (!empty($attrs['title'])) {
        echo '<a class="tc4-news-heading-link" href="' . esc_url($category_link) . '"><h4>' . esc_html($attrs['title']) . '</h4></a>';
    }

    echo '<div class="news-items">';
    while ($query->have_posts()) {
        $query->the_post();
        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'tc4_home_card');
        echo '<div class="news-item">';
            echo '<a class="titel" href="' . esc_url(get_permalink()) . '">';
                echo '<div class="beeld"' . ($thumb ? ' style="background-image:url(' . esc_url($thumb) . ');"' : '') . '>';
                    echo '<span class="datum">' . esc_html(get_the_date('d F Y')) . '</span>';
                echo '</div>';
                echo '<h5>' . esc_html(get_the_title()) . '</h5>';
            echo '</a>';
            echo '<div class="tekst">' . esc_html(wp_trim_words(get_the_excerpt(), 24)) . '</div>';
            echo '<a class="lees-meer" href="' . esc_url(get_permalink()) . '">' . esc_html__('Lees meer', 'scoutingtc4') . '</a>';
        echo '</div>';
    }
    wp_reset_postdata();
    echo '</div>';

    if (!empty($attrs['buttonText'])) {
        echo '<a class="meer-nieuws" href="' . esc_url(!empty($attrs['buttonUrl']) ? $attrs['buttonUrl'] : $category_link) . '">' . esc_html($attrs['buttonText']) . '</a>';
    }

    echo '</div>';
    echo '</section>';
    return ob_get_clean();
}

function scoutingtc4_render_two_columns_block($attrs) {
    ob_start();

    $left_title  = !empty($attrs['leftTitle']) ? $attrs['leftTitle'] : '';
    $left_text   = !empty($attrs['leftText']) ? $attrs['leftText'] : '';
    $left_image  = !empty($attrs['leftImageUrl']) ? $attrs['leftImageUrl'] : '';
    $left_btn_t  = !empty($attrs['leftButtonText']) ? $attrs['leftButtonText'] : '';
    $left_btn_u  = !empty($attrs['leftButtonUrl']) ? $attrs['leftButtonUrl'] : '#';
    $left_btn_c  = !empty($attrs['leftButtonColor']) ? $attrs['leftButtonColor'] : 'blauw';

    $right_title = !empty($attrs['rightTitle']) ? $attrs['rightTitle'] : '';
    $right_text  = isset($attrs['rightText']) ? trim((string) $attrs['rightText']) : '';
    $right_btn_t = !empty($attrs['rightButtonText']) ? $attrs['rightButtonText'] : '';
    $right_btn_u = !empty($attrs['rightButtonUrl']) ? $attrs['rightButtonUrl'] : '#';
    $right_btn_c = !empty($attrs['rightButtonColor']) ? $attrs['rightButtonColor'] : 'zwart';

    if ($right_text === '') {
        $right_text = '<p>Meer weten over vrijwilligerswerk bij Scouting? Laat je verrassen door één van de uitdagende vacatures of lees meer over vrijwilliger worden bij Scouting.</p><ul><li>Welpenleiding</li><li>Secretaris</li></ul><h2>Maatschappelijke stage</h2><p>Iedere scholier krijgt er wel mee te maken: de maatschappelijke stage. Sommige scholieren gaan aan de slag in peuterspeelzalen, bejaardentehuizen of sociale werkplaatsen. Maar je kunt natuurlijk ook bij Scouting terecht.</p><p>Klik hier om je maatschappelijke stage bij onze Scoutinggroep te lopen.</p>';
    }

    echo '<section class="tc4-block-two-columns tc4-two-columns-style-2020">';
    echo '<div class="gb-two-columns">';

    echo '<div class="col-headerimage">';
    echo '<div class="beeld"' . ($left_image ? ' style="background-image:url(' . esc_url($left_image) . ');"' : '') . '>';
    if ($left_title) {
        echo '<h4>' . esc_html($left_title) . '</h4>';
    }
    echo '</div>';
    echo '<div class="tekst">' . wp_kses_post(wpautop($left_text)) . '</div>';
    if ($left_btn_t) {
        echo '<div class="knop"><a class="btn tc4-two-btn ' . esc_attr(scoutingtc4_button_class_from_color($left_btn_c)) . '" href="' . esc_url($left_btn_u) . '"><span class="tc4-two-btn-label">' . esc_html($left_btn_t) . '</span><span class="tc4-two-btn-arrow" aria-hidden="true"></span></a></div>';
    }
    echo '</div>';

    echo '<div class="col-wysiwyg">';
    if ($right_title) {
        echo '<h4>' . esc_html($right_title) . '</h4>';
    }
    echo '<div class="content">' . wp_kses_post(wpautop($right_text)) . '</div>';
    if ($right_btn_t) {
        echo '<div class="knop"><a class="btn tc4-two-btn ' . esc_attr(scoutingtc4_button_class_from_color($right_btn_c)) . '" href="' . esc_url($right_btn_u) . '"><span class="tc4-two-btn-label">' . esc_html($right_btn_t) . '</span><span class="tc4-two-btn-arrow" aria-hidden="true"></span></a></div>';
    }
    echo '</div>';

    echo '</div>';
    echo '</section>';

    return ob_get_clean();
}

function scoutingtc4_home_fields_defaults() {
    return array(
        'enabled' => '0',
        'featured' => array(
            'title' => '',
            'items' => array(
                array('title' => 'Wie zijn wij?', 'image_id' => 0, 'text' => 'Scouting staat voor uitdaging!', 'button_text' => 'Meer info', 'button_url' => '', 'button_color' => 'blauw'),
                array('title' => 'Lid worden', 'image_id' => 0, 'text' => 'Laat je uitdagen en ontdek Scouting!', 'button_text' => 'Kom een keer meedoen', 'button_url' => '', 'button_color' => 'blauw'),
                array('title' => 'Snel naar', 'image_id' => 0, 'text' => 'Verhuur<br>Stockfoto’s<br>Scouting Nederland', 'button_text' => '', 'button_url' => '', 'button_color' => 'blauw'),
            ),
        ),
        'callout' => array(
            'own_image' => '0',
            'image_id' => 0,
            'preset' => 'kompas',
            'title' => 'Vrijwilligers gezocht',
            'text' => 'Ben jij enthousiast, geef je goed leiding en vind je het leuk om kinderen en jongeren te begeleiden bij hun persoonlijke ontwikkeling? Dan zijn wij op zoek naar jou!',
            'button_text' => 'Meld je aan',
            'button_url' => '',
            'button_color' => 'zwart',
        ),
        'intro_text' => '',
        'news' => array(
            'title' => 'Nieuws',
            'mode' => 'latest',
            'count' => 3,
            'post_ids' => '',
            'button_text' => 'Meer nieuws',
            'button_url' => '',
        ),
        'two_columns' => array(
            'left_image_id' => 0,
            'left_title' => 'Gratis sponsoren',
            'left_text' => '',
            'left_button_text' => 'Lees hier meer!',
            'left_button_url' => '',
            'left_button_color' => 'blauw',
            'right_title' => 'Vacatures',
            'right_text' => '',
            'right_button_text' => 'Meer vacatures',
            'right_button_url' => '',
            'right_button_color' => 'zwart',
            'right_second_title' => 'Maatschappelijke stage',
            'right_second_text' => '',
        ),
    );
}

function scoutingtc4_get_home_fields($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $defaults = scoutingtc4_home_fields_defaults();
    $saved = get_post_meta($post_id, '_tc4_home_fields', true);
    if (!is_array($saved)) {
        $saved = array();
    }
    return array_replace_recursive($defaults, $saved);
}

function scoutingtc4_add_home_fields_metaboxes() {
    add_meta_box(
        'scoutingtc4_home_fields',
        __('Homepagina onderdelen', 'scoutingtc4'),
        'scoutingtc4_render_home_fields_metabox',
        'page',
        'normal',
        'default'
    );
}

function scoutingtc4_home_image_field($name, $image_id = 0) {
    $preview = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
    ?>
    <input type="hidden" class="tc4-image-id" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(absint($image_id)); ?>">
    <div class="tc4-image-preview">
        <?php if ($preview) : ?><img src="<?php echo esc_url($preview); ?>" alt="" style="max-width:140px;height:auto;"><?php endif; ?>
    </div>
    <button type="button" class="button tc4-select-home-image"><?php esc_html_e('Afbeelding kiezen', 'scoutingtc4'); ?></button>
    <button type="button" class="button-link-delete tc4-remove-home-image"><?php esc_html_e('Verwijderen', 'scoutingtc4'); ?></button>
    <?php
}

function scoutingtc4_button_fields($base, $data) {
    $text = isset($data['button_text']) ? $data['button_text'] : '';
    $url = isset($data['button_url']) ? $data['button_url'] : '';
    $color = isset($data['button_color']) ? $data['button_color'] : 'blauw';
    ?>
    <div class="tc4-button-fields">
        <label><?php esc_html_e('Tekst', 'scoutingtc4'); ?><br>
            <input type="text" name="<?php echo esc_attr($base); ?>[button_text]" value="<?php echo esc_attr($text); ?>" class="widefat">
        </label>
        <div class="tc4-button-row">
            <label><?php esc_html_e('URL', 'scoutingtc4'); ?><br>
                <input type="url" name="<?php echo esc_attr($base); ?>[button_url]" value="<?php echo esc_attr($url); ?>" class="widefat">
            </label>
            <fieldset>
                <legend><?php esc_html_e('Kleur', 'scoutingtc4'); ?></legend>
                <?php foreach (array('blauw' => 'blauw', 'groen' => 'groen', 'zwart' => 'zwart') as $value => $label) : ?>
                    <label><input type="radio" name="<?php echo esc_attr($base); ?>[button_color]" value="<?php echo esc_attr($value); ?>" <?php checked($color, $value); ?>> <?php echo esc_html($label); ?></label><br>
                <?php endforeach; ?>
            </fieldset>
        </div>
    </div>
    <?php
}

function scoutingtc4_render_home_fields_metabox($post) {
    wp_nonce_field('scoutingtc4_save_home_fields', 'scoutingtc4_home_fields_nonce');
    $fields = scoutingtc4_get_home_fields($post->ID);
    ?>
    <div class="tc4-home-fields">
        <p>
            <label>
                <input type="checkbox" name="tc4_home_fields[enabled]" value="1" <?php checked($fields['enabled'], '1'); ?>>
                <strong><?php esc_html_e('Homepagina onderdelen tonen vanuit deze velden', 'scoutingtc4'); ?></strong>
            </label>
        </p>
        <p class="description"><?php esc_html_e('Zet dit aan als je de homepage wilt vullen zoals in het oude thema, met onderstaande velden in plaats van losse Gutenberg-blokken.', 'scoutingtc4'); ?></p>

        <h2><?php esc_html_e('Featured items', 'scoutingtc4'); ?></h2>
        <table class="widefat striped">
            <thead><tr><th style="width:60px;"></th><th><?php esc_html_e('Item', 'scoutingtc4'); ?></th></tr></thead>
            <tbody>
            <?php for ($i = 0; $i < 3; $i++) :
                $item = isset($fields['featured']['items'][$i]) ? $fields['featured']['items'][$i] : array();
                ?>
                <tr>
                    <td><?php echo esc_html($i + 1); ?></td>
                    <td>
                        <p><label><?php esc_html_e('Kop', 'scoutingtc4'); ?><br>
                            <input type="text" class="widefat" name="tc4_home_fields[featured][items][<?php echo esc_attr($i); ?>][title]" value="<?php echo esc_attr(isset($item['title']) ? $item['title'] : ''); ?>">
                        </label></p>
                        <p><label><?php esc_html_e('Beeld', 'scoutingtc4'); ?></label><br>
                            <?php scoutingtc4_home_image_field("tc4_home_fields[featured][items][$i][image_id]", isset($item['image_id']) ? $item['image_id'] : 0); ?>
                        </p>
                        <p><label><?php esc_html_e('Tekst', 'scoutingtc4'); ?><br>
                            <textarea class="widefat" rows="4" name="tc4_home_fields[featured][items][<?php echo esc_attr($i); ?>][text]"><?php echo esc_textarea(isset($item['text']) ? $item['text'] : ''); ?></textarea>
                        </label></p>
                        <fieldset class="tc4-fieldset"><legend><?php esc_html_e('Knop', 'scoutingtc4'); ?></legend>
                            <?php scoutingtc4_button_fields("tc4_home_fields[featured][items][$i]", $item); ?>
                        </fieldset>
                    </td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <h2><?php esc_html_e('Callout', 'scoutingtc4'); ?></h2>
        <table class="form-table">
            <tr>
                <th><?php esc_html_e('Eigen beeld gebruiken', 'scoutingtc4'); ?></th>
                <td>
                    <label><input type="radio" name="tc4_home_fields[callout][own_image]" value="1" <?php checked($fields['callout']['own_image'], '1'); ?>> ja</label>
                    <label><input type="radio" name="tc4_home_fields[callout][own_image]" value="0" <?php checked($fields['callout']['own_image'], '0'); ?>> nee</label>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e('Beeld [eigen]', 'scoutingtc4'); ?></th>
                <td><?php scoutingtc4_home_image_field('tc4_home_fields[callout][image_id]', $fields['callout']['image_id']); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Beeld [lijst]', 'scoutingtc4'); ?></th>
                <td>
                    <select name="tc4_home_fields[callout][preset]" class="widefat">
                        <option value="kompas" <?php selected($fields['callout']['preset'], 'kompas'); ?>>kompas</option>
                        <option value="bijl" <?php selected($fields['callout']['preset'], 'bijl'); ?>>bijl</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e('Titel', 'scoutingtc4'); ?></th>
                <td><input type="text" class="widefat" name="tc4_home_fields[callout][title]" value="<?php echo esc_attr($fields['callout']['title']); ?>"></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Tekst', 'scoutingtc4'); ?></th>
                <td><?php wp_editor($fields['callout']['text'], 'tc4_callout_text', array('textarea_name' => 'tc4_home_fields[callout][text]', 'textarea_rows' => 8, 'media_buttons' => false)); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Knop', 'scoutingtc4'); ?></th>
                <td><?php scoutingtc4_button_fields('tc4_home_fields[callout]', $fields['callout']); ?></td>
            </tr>
        </table>

        <h2><?php esc_html_e('Intro tekst', 'scoutingtc4'); ?></h2>
        <?php wp_editor($fields['intro_text'], 'tc4_intro_text', array('textarea_name' => 'tc4_home_fields[intro_text]', 'textarea_rows' => 6, 'media_buttons' => false)); ?>

        <h2><?php esc_html_e('Nieuws items', 'scoutingtc4'); ?></h2>
        <table class="form-table">
            <tr><th><?php esc_html_e('Titel', 'scoutingtc4'); ?></th><td><input type="text" class="widefat" name="tc4_home_fields[news][title]" value="<?php echo esc_attr($fields['news']['title']); ?>"></td></tr>
            <tr><th><?php esc_html_e('Bron', 'scoutingtc4'); ?></th><td>
                <label><input type="radio" name="tc4_home_fields[news][mode]" value="latest" <?php checked($fields['news']['mode'], 'latest'); ?>> <?php esc_html_e('Laatste berichten', 'scoutingtc4'); ?></label><br>
                <label><input type="radio" name="tc4_home_fields[news][mode]" value="manual" <?php checked($fields['news']['mode'], 'manual'); ?>> <?php esc_html_e('Handmatig gekozen berichten', 'scoutingtc4'); ?></label>
            </td></tr>
            <tr><th><?php esc_html_e('Aantal', 'scoutingtc4'); ?></th><td><input type="number" min="1" max="6" name="tc4_home_fields[news][count]" value="<?php echo esc_attr($fields['news']['count']); ?>"></td></tr>
            <tr><th><?php esc_html_e('Bericht IDs', 'scoutingtc4'); ?></th><td><input type="text" class="widefat" name="tc4_home_fields[news][post_ids]" value="<?php echo esc_attr($fields['news']['post_ids']); ?>"><p class="description"><?php esc_html_e('Alleen bij handmatig. Vul bericht-ID’s in, gescheiden met komma’s.', 'scoutingtc4'); ?></p></td></tr>
            <tr><th><?php esc_html_e('Knop', 'scoutingtc4'); ?></th><td><?php scoutingtc4_button_fields('tc4_home_fields[news]', $fields['news']); ?></td></tr>
        </table>

        <h2><?php esc_html_e('Twee kolommen', 'scoutingtc4'); ?></h2>
        <div class="tc4-two-admin">
            <div>
                <h3><?php esc_html_e('Kolom 1', 'scoutingtc4'); ?></h3>
                <p><label><?php esc_html_e('Beeld', 'scoutingtc4'); ?></label><br><?php scoutingtc4_home_image_field('tc4_home_fields[two_columns][left_image_id]', $fields['two_columns']['left_image_id']); ?></p>
                <p><label><?php esc_html_e('Kop', 'scoutingtc4'); ?><br><input type="text" class="widefat" name="tc4_home_fields[two_columns][left_title]" value="<?php echo esc_attr($fields['two_columns']['left_title']); ?>"></label></p>
                <p><label><?php esc_html_e('Tekst', 'scoutingtc4'); ?><br><textarea class="widefat" rows="6" name="tc4_home_fields[two_columns][left_text]"><?php echo esc_textarea($fields['two_columns']['left_text']); ?></textarea></label></p>
                <fieldset class="tc4-fieldset"><legend><?php esc_html_e('Knop', 'scoutingtc4'); ?></legend><?php scoutingtc4_button_fields('tc4_home_fields[two_columns]', array('button_text'=>$fields['two_columns']['left_button_text'], 'button_url'=>$fields['two_columns']['left_button_url'], 'button_color'=>$fields['two_columns']['left_button_color'])); ?></fieldset>
            </div>
            <div>
                <h3><?php esc_html_e('Kolom 2', 'scoutingtc4'); ?></h3>
                <p><label><?php esc_html_e('Kop', 'scoutingtc4'); ?><br><input type="text" class="widefat" name="tc4_home_fields[two_columns][right_title]" value="<?php echo esc_attr($fields['two_columns']['right_title']); ?>"></label></p>
                <p><label><?php esc_html_e('Tekst', 'scoutingtc4'); ?><br><textarea class="widefat" rows="5" name="tc4_home_fields[two_columns][right_text]"><?php echo esc_textarea($fields['two_columns']['right_text']); ?></textarea></label></p>
                <p><label><?php esc_html_e('Tweede kop', 'scoutingtc4'); ?><br><input type="text" class="widefat" name="tc4_home_fields[two_columns][right_second_title]" value="<?php echo esc_attr($fields['two_columns']['right_second_title']); ?>"></label></p>
                <p><label><?php esc_html_e('Tweede tekst', 'scoutingtc4'); ?><br><textarea class="widefat" rows="5" name="tc4_home_fields[two_columns][right_second_text]"><?php echo esc_textarea($fields['two_columns']['right_second_text']); ?></textarea></label></p>
                <fieldset class="tc4-fieldset"><legend><?php esc_html_e('Knop', 'scoutingtc4'); ?></legend>
                    <label><?php esc_html_e('Tekst', 'scoutingtc4'); ?><br><input type="text" class="widefat" name="tc4_home_fields[two_columns][right_button_text]" value="<?php echo esc_attr($fields['two_columns']['right_button_text']); ?>"></label>
                    <div class="tc4-button-row">
                        <label><?php esc_html_e('URL', 'scoutingtc4'); ?><br><input type="url" class="widefat" name="tc4_home_fields[two_columns][right_button_url]" value="<?php echo esc_attr($fields['two_columns']['right_button_url']); ?>"></label>
                        <fieldset><legend><?php esc_html_e('Kleur', 'scoutingtc4'); ?></legend>
                            <?php foreach (array('blauw'=>'blauw', 'groen'=>'groen', 'zwart'=>'zwart') as $value=>$label) : ?>
                                <label><input type="radio" name="tc4_home_fields[two_columns][right_button_color]" value="<?php echo esc_attr($value); ?>" <?php checked($fields['two_columns']['right_button_color'], $value); ?>> <?php echo esc_html($label); ?></label><br>
                            <?php endforeach; ?>
                        </fieldset>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <?php
}

function scoutingtc4_sanitize_home_fields($raw) {
    $defaults = scoutingtc4_home_fields_defaults();
    $raw = is_array($raw) ? $raw : array();
    $out = $defaults;
    $out['enabled'] = !empty($raw['enabled']) ? '1' : '0';

    if (isset($raw['featured']['items']) && is_array($raw['featured']['items'])) {
        $out['featured']['items'] = array();
        for ($i = 0; $i < 3; $i++) {
            $item = isset($raw['featured']['items'][$i]) && is_array($raw['featured']['items'][$i]) ? $raw['featured']['items'][$i] : array();
            $out['featured']['items'][$i] = array(
                'title' => sanitize_text_field($item['title'] ?? ''),
                'image_id' => absint($item['image_id'] ?? 0),
                'text' => wp_kses_post($item['text'] ?? ''),
                'button_text' => sanitize_text_field($item['button_text'] ?? ''),
                'button_url' => esc_url_raw($item['button_url'] ?? ''),
                'button_color' => in_array(($item['button_color'] ?? 'blauw'), array('blauw','groen','zwart'), true) ? $item['button_color'] : 'blauw',
            );
        }
    }

    $callout = $raw['callout'] ?? array();
    $out['callout'] = array(
        'own_image' => (($callout['own_image'] ?? '0') === '1') ? '1' : '0',
        'image_id' => absint($callout['image_id'] ?? 0),
        'preset' => in_array(($callout['preset'] ?? 'kompas'), array('kompas','bijl'), true) ? $callout['preset'] : 'kompas',
        'title' => sanitize_text_field($callout['title'] ?? ''),
        'text' => wp_kses_post($callout['text'] ?? ''),
        'button_text' => sanitize_text_field($callout['button_text'] ?? ''),
        'button_url' => esc_url_raw($callout['button_url'] ?? ''),
        'button_color' => in_array(($callout['button_color'] ?? 'zwart'), array('blauw','groen','zwart'), true) ? $callout['button_color'] : 'zwart',
    );

    $out['intro_text'] = wp_kses_post($raw['intro_text'] ?? '');

    $news = $raw['news'] ?? array();
    $out['news'] = array(
        'title' => sanitize_text_field($news['title'] ?? 'Nieuws'),
        'mode' => (($news['mode'] ?? 'latest') === 'manual') ? 'manual' : 'latest',
        'count' => max(1, min(6, absint($news['count'] ?? 3))),
        'post_ids' => sanitize_text_field($news['post_ids'] ?? ''),
        'button_text' => sanitize_text_field($news['button_text'] ?? ''),
        'button_url' => esc_url_raw($news['button_url'] ?? ''),
        'button_color' => in_array(($news['button_color'] ?? 'blauw'), array('blauw','groen','zwart'), true) ? $news['button_color'] : 'blauw',
    );

    $two = $raw['two_columns'] ?? array();
    $out['two_columns'] = array(
        'left_image_id' => absint($two['left_image_id'] ?? 0),
        'left_title' => sanitize_text_field($two['left_title'] ?? ''),
        'left_text' => wp_kses_post($two['left_text'] ?? ''),
        'left_button_text' => sanitize_text_field($two['button_text'] ?? ($two['left_button_text'] ?? '')),
        'left_button_url' => esc_url_raw($two['button_url'] ?? ($two['left_button_url'] ?? '')),
        'left_button_color' => in_array(($two['button_color'] ?? ($two['left_button_color'] ?? 'blauw')), array('blauw','groen','zwart'), true) ? ($two['button_color'] ?? ($two['left_button_color'] ?? 'blauw')) : 'blauw',
        'right_title' => sanitize_text_field($two['right_title'] ?? ''),
        'right_text' => wp_kses_post($two['right_text'] ?? ''),
        'right_second_title' => sanitize_text_field($two['right_second_title'] ?? ''),
        'right_second_text' => wp_kses_post($two['right_second_text'] ?? ''),
        'right_button_text' => sanitize_text_field($two['right_button_text'] ?? ''),
        'right_button_url' => esc_url_raw($two['right_button_url'] ?? ''),
        'right_button_color' => in_array(($two['right_button_color'] ?? 'zwart'), array('blauw','groen','zwart'), true) ? $two['right_button_color'] : 'zwart',
    );

    return $out;
}

function scoutingtc4_save_home_fields_metabox($post_id) {
    if (!isset($_POST['scoutingtc4_home_fields_nonce']) || !wp_verify_nonce($_POST['scoutingtc4_home_fields_nonce'], 'scoutingtc4_save_home_fields')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }
    update_post_meta($post_id, '_tc4_home_fields', scoutingtc4_sanitize_home_fields($_POST['tc4_home_fields'] ?? array()));
}

function scoutingtc4_button_class_from_color($color) {
    if ($color === 'groen') {
        return 'tc4-btn-green';
    }
    if ($color === 'zwart') {
        return 'tc4-btn-black';
    }
    return 'tc4-btn-blue';
}

function scoutingtc4_render_button_from_fields($data, $prefix = '') {
    $text_key = $prefix ? $prefix . '_button_text' : 'button_text';
    $url_key = $prefix ? $prefix . '_button_url' : 'button_url';
    $color_key = $prefix ? $prefix . '_button_color' : 'button_color';
    $text = $data[$text_key] ?? '';
    if (!$text) {
        return;
    }
    $url = $data[$url_key] ?? '#';
    $color = $data[$color_key] ?? 'blauw';
    echo '<a class="tc4-btn ' . esc_attr(scoutingtc4_button_class_from_color($color)) . '" href="' . esc_url($url ?: '#') . '">' . esc_html($text) . '</a>';
}

function scoutingtc4_render_home_fields_frontend($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $fields = scoutingtc4_get_home_fields($post_id);
    if ($fields['enabled'] !== '1') {
        return false;
    }

    echo '<div class="tc4-home-fields-rendered">';

    echo '<section class="tc4-block-featured-items"><div class="tc4-featured-grid">';
    foreach ($fields['featured']['items'] as $item) {
        echo '<article class="tc4-featured-item">';
        if (!empty($item['title'])) echo '<h2>' . esc_html($item['title']) . '</h2>';
        if (!empty($item['image_id'])) echo wp_get_attachment_image(absint($item['image_id']), 'tc4_home_card');
        if (!empty($item['text'])) echo '<p>' . wp_kses_post(nl2br($item['text'])) . '</p>';
        scoutingtc4_render_button_from_fields($item);
        echo '</article>';
    }
    echo '</div></section>';

    $callout = $fields['callout'];
    $callout_image = '';
    if ($callout['own_image'] === '1' && !empty($callout['image_id'])) {
        $callout_image = wp_get_attachment_image_url(absint($callout['image_id']), 'medium');
    } elseif ($callout['preset'] === 'bijl') {
        $callout_image = get_template_directory_uri() . '/assets/images/callout-bijl.png';
    } else {
        $callout_image = get_template_directory_uri() . '/assets/images/callout-kompas.png';
    }
    echo '<section class="tc4-block-callout"><div class="tc4-callout-inner"><div class="tc4-callout-image">';
    if ($callout_image) echo '<img src="' . esc_url($callout_image) . '" alt="">';
    echo '</div><div class="tc4-callout-text">';
    if (!empty($callout['title'])) echo '<h2>' . esc_html($callout['title']) . '</h2>';
    if (!empty($callout['text'])) echo '<div class="tc4-callout-copy">' . wp_kses_post(wpautop($callout['text'])) . '</div>';
    scoutingtc4_render_button_from_fields($callout);
    echo '</div></div></section>';

    if (!empty($fields['intro_text'])) {
        echo '<section class="tc4-home-intro-text">' . wp_kses_post(wpautop($fields['intro_text'])) . '</section>';
    }

    $news = $fields['news'];
    $args = array('post_type'=>'post', 'posts_per_page'=>absint($news['count']), 'post_status'=>'publish');
    if ($news['mode'] === 'manual' && !empty($news['post_ids'])) {
        $ids = array_filter(array_map('absint', explode(',', $news['post_ids'])));
        if ($ids) {
            $args['post__in'] = $ids;
            $args['orderby'] = 'post__in';
            $args['posts_per_page'] = count($ids);
        }
    }
    $q = new WP_Query($args);
    echo '<section class="tc4-block-news">';
    if (!empty($news['title'])) echo '<h2>' . esc_html($news['title']) . '</h2>';
    echo '<div class="tc4-news-grid-real">';
    while ($q->have_posts()) {
        $q->the_post();
        echo '<article class="tc4-news-card">';
        if (has_post_thumbnail()) echo '<a href="' . esc_url(get_permalink()) . '">' . get_the_post_thumbnail(get_the_ID(), 'tc4_home_card') . '</a>';
        echo '<div class="tc4-news-card-title"><small>' . esc_html(get_the_date()) . '</small><h3><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3></div>';
        echo '<p>' . esc_html(wp_trim_words(get_the_excerpt(), 22)) . '</p>';
        echo '<a class="tc4-read-more" href="' . esc_url(get_permalink()) . '">' . esc_html__('Lees meer', 'scoutingtc4') . ' ›</a>';
        echo '</article>';
    }
    wp_reset_postdata();
    echo '</div>';
    scoutingtc4_render_button_from_fields($news);
    echo '</section>';

    $two = $fields['two_columns'];
    echo '<section class="tc4-block-two-columns"><div class="tc4-two-left">';
    if (!empty($two['left_image_id'])) echo wp_get_attachment_image(absint($two['left_image_id']), 'tc4_home_card');
    if (!empty($two['left_title'])) echo '<h2>' . esc_html($two['left_title']) . '</h2>';
    if (!empty($two['left_text'])) echo '<p>' . wp_kses_post(nl2br($two['left_text'])) . '</p>';
    scoutingtc4_render_button_from_fields($two, 'left');
    echo '</div><div class="tc4-two-right">';
    if (!empty($two['right_title'])) echo '<h2>' . esc_html($two['right_title']) . '</h2>';
    if (!empty($two['right_text'])) echo '<p>' . wp_kses_post(nl2br($two['right_text'])) . '</p>';
    if (!empty($two['right_second_title'])) echo '<h2>' . esc_html($two['right_second_title']) . '</h2>';
    if (!empty($two['right_second_text'])) echo '<p>' . wp_kses_post(nl2br($two['right_second_text'])) . '</p>';
    scoutingtc4_render_button_from_fields($two, 'right');
    echo '</div></section>';

    echo '</div>';
    return true;
}

function scoutingtc4_block_categories($categories, $post) {
    return array_merge(
        array(
            array(
                'slug'  => 'scoutingtc4',
                'title' => __('Scouting TC4', 'scoutingtc4'),
                'icon'  => null,
            ),
        ),
        $categories
    );
}
add_filter('block_categories_all', 'scoutingtc4_block_categories', 10, 2);

function scoutingtc4_color_body_classes($classes) {
    $support = get_theme_mod('scoutingtc4_support_color', 'blue');
    $main = get_theme_mod('scoutingtc4_main_color', 'green');

    $allowed = array('green', 'blue', 'red', 'yellow', 'orange', 'purple');
    if (!in_array($support, $allowed, true)) {
        $support = 'blue';
    }
    if (!in_array($main, $allowed, true)) {
        $main = 'green';
    }

    $classes[] = sanitize_html_class($support . '-support');
    $classes[] = sanitize_html_class($main . '-main');
    return $classes;
}
add_filter('body_class', 'scoutingtc4_color_body_classes');

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
add_action('admin_head', 'scoutingtc4_default_favicon', 1);

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

function scoutingtc4_is_scouting_openid_connect_active() {
    if (!function_exists('is_plugin_active')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $active_plugins = (array) get_option('active_plugins', array());

    if (is_multisite()) {
        $active_plugins = array_merge($active_plugins, array_keys((array) get_site_option('active_sitewide_plugins', array())));
    }

    foreach ($active_plugins as $plugin_file) {
        if (strpos($plugin_file, 'scouting-openid-connect/') === 0) {
            return true;
        }
    }

    return false;
}

function scoutingtc4_scouting_openid_connect_notice() {
    if (!is_admin() || !current_user_can('install_plugins')) {
        return;
    }

    if (get_user_meta(get_current_user_id(), 'scoutingtc4_openid_notice_seen', true)) {
        return;
    }

    if (scoutingtc4_is_scouting_openid_connect_active()) {
        update_user_meta(get_current_user_id(), 'scoutingtc4_openid_notice_seen', 1);
        return;
    }

    update_user_meta(get_current_user_id(), 'scoutingtc4_openid_notice_seen', 1);

    $install_url = wp_nonce_url(
        self_admin_url('update.php?action=install-plugin&plugin=scouting-openid-connect'),
        'install-plugin_scouting-openid-connect'
    );
    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <strong><?php esc_html_e('Scouting login beschikbaar', 'scoutingtc4'); ?></strong><br>
            <?php esc_html_e('Je kunt de plugin Scouting OpenID Connect installeren om gebruikers via Scouting te laten inloggen.', 'scoutingtc4'); ?>
        </p>
        <p>
            <a class="button button-primary" href="<?php echo esc_url($install_url); ?>">
                <?php esc_html_e('Scouting OpenID Connect direct installeren', 'scoutingtc4'); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'scoutingtc4_scouting_openid_connect_notice');



function scoutingtc4_enqueue_block_editor_dependencies() {
    wp_enqueue_editor();
}
add_action('enqueue_block_editor_assets', 'scoutingtc4_enqueue_block_editor_dependencies');

