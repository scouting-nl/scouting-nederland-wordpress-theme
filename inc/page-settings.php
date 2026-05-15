<?php
if (!defined('ABSPATH')) { exit; }


function scoutingtc4_get_hero_font_choices() {
    return array(
        'urban-jungle' => __('Urban Jungle', 'scoutingtc4'),
        'roboto-slab'  => __('Roboto Slab', 'scoutingtc4'),
        'poppins'      => __('Poppins', 'scoutingtc4'),
    );
}

function scoutingtc4_sanitize_hero_font($value) {
    $value = sanitize_key($value);
    $choices = scoutingtc4_get_hero_font_choices();
    return array_key_exists($value, $choices) ? $value : 'urban-jungle';
}

function scoutingtc4_sanitize_page_hero_font($value) {
    $value = sanitize_key($value);
    if ($value === '') {
        return '';
    }
    $choices = scoutingtc4_get_hero_font_choices();
    return array_key_exists($value, $choices) ? $value : '';
}

function scoutingtc4_get_default_hero_title_font() {
    return scoutingtc4_sanitize_hero_font(get_theme_mod('scoutingtc4_hero_title_font', 'urban-jungle'));
}

function scoutingtc4_get_hero_title_font($post_id = null) {
    $post_id = $post_id ?: get_queried_object_id();
    $page_font = $post_id ? scoutingtc4_sanitize_page_hero_font((string) get_post_meta($post_id, '_tc4_hero_title_font', true)) : '';
    return $page_font !== '' ? $page_font : scoutingtc4_get_default_hero_title_font();
}

function scoutingtc4_get_hero_title_font_class($post_id = null) {
    return 'tc4-hero-font-' . scoutingtc4_get_hero_title_font($post_id);
}

function scoutingtc4_get_page_display_settings($post_id = null) {
    $post_id = $post_id ?: get_queried_object_id();

    return array(
        'hide_hero' => get_post_meta($post_id, '_tc4_hide_hero', true) === '1',
        'hide_page_title' => get_post_meta($post_id, '_tc4_hide_page_title', true) === '1',
        'hide_sidebar' => get_post_meta($post_id, '_tc4_hide_sidebar', true) === '1',
        'hero_title_font' => scoutingtc4_sanitize_page_hero_font((string) get_post_meta($post_id, '_tc4_hero_title_font', true)),
    );
}

function scoutingtc4_page_hides_hero($post_id = null) {
    $settings = scoutingtc4_get_page_display_settings($post_id);
    return !empty($settings['hide_hero']);
}

function scoutingtc4_page_hides_title($post_id = null) {
    $settings = scoutingtc4_get_page_display_settings($post_id);
    return !empty($settings['hide_page_title']);
}

function scoutingtc4_page_hides_sidebar($post_id = null) {
    $settings = scoutingtc4_get_page_display_settings($post_id);
    return !empty($settings['hide_sidebar']);
}

function scoutingtc4_add_page_display_metabox() {
    add_meta_box(
        'scoutingtc4_page_display_settings',
        __('Pagina weergave', 'scoutingtc4'),
        'scoutingtc4_render_page_display_metabox',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'scoutingtc4_add_page_display_metabox');

function scoutingtc4_render_page_display_metabox($post) {
    wp_nonce_field('scoutingtc4_save_page_display_settings', 'scoutingtc4_page_display_nonce');
    $settings = scoutingtc4_get_page_display_settings($post->ID);
    ?>
    <p>
        <label>
            <input type="checkbox" name="tc4_hide_hero" value="1" <?php checked($settings['hide_hero']); ?>>
            <?php esc_html_e('Standaard hero verbergen', 'scoutingtc4'); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="tc4_hide_page_title" value="1" <?php checked($settings['hide_page_title']); ?>>
            <?php esc_html_e('Paginatitel in hero verbergen', 'scoutingtc4'); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="tc4_hide_sidebar" value="1" <?php checked($settings['hide_sidebar']); ?>>
            <?php esc_html_e('Sidebar verbergen', 'scoutingtc4'); ?>
        </label>
    </p>
    <p>
        <label for="tc4_hero_title_font"><strong><?php esc_html_e('Lettertype hero-koptekst', 'scoutingtc4'); ?></strong></label><br>
        <select name="tc4_hero_title_font" id="tc4_hero_title_font" style="width:100%;">
            <option value="" <?php selected($settings['hero_title_font'], ''); ?>><?php printf(esc_html__('Standaard uit Customizer (%s)', 'scoutingtc4'), esc_html(scoutingtc4_get_hero_font_choices()[scoutingtc4_get_default_hero_title_font()])); ?></option>
            <?php foreach (scoutingtc4_get_hero_font_choices() as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($settings['hero_title_font'], $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p class="description">
        <?php esc_html_e('Gebruik deze opties om de standaard hero, de titel-overlay, de sidebar of het lettertype van de hero-koptekst voor deze pagina aan te passen. Een ingestelde slider of video blijft zichtbaar.', 'scoutingtc4'); ?>
    </p>
    <?php
}

function scoutingtc4_save_page_display_metabox($post_id) {
    if (!isset($_POST['scoutingtc4_page_display_nonce']) || !wp_verify_nonce($_POST['scoutingtc4_page_display_nonce'], 'scoutingtc4_save_page_display_settings')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_revision($post_id)) {
        return;
    }
    if (get_post_type($post_id) !== 'page') {
        return;
    }
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    update_post_meta($post_id, '_tc4_hide_hero', !empty($_POST['tc4_hide_hero']) ? '1' : '0');
    update_post_meta($post_id, '_tc4_hide_page_title', !empty($_POST['tc4_hide_page_title']) ? '1' : '0');
    update_post_meta($post_id, '_tc4_hide_sidebar', !empty($_POST['tc4_hide_sidebar']) ? '1' : '0');
    update_post_meta($post_id, '_tc4_hero_title_font', isset($_POST['tc4_hero_title_font']) ? scoutingtc4_sanitize_page_hero_font(wp_unslash($_POST['tc4_hero_title_font'])) : '');
}
add_action('save_post_page', 'scoutingtc4_save_page_display_metabox');
