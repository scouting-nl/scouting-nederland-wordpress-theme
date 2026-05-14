<?php
if (!defined('ABSPATH')) { exit; }

function scoutingtc4_get_page_display_settings($post_id = null) {
    $post_id = $post_id ?: get_queried_object_id();

    return array(
        'hide_hero' => get_post_meta($post_id, '_tc4_hide_hero', true) === '1',
        'hide_page_title' => get_post_meta($post_id, '_tc4_hide_page_title', true) === '1',
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
    <p class="description">
        <?php esc_html_e('Gebruik deze opties om de standaard hero of alleen de titel-overlay voor deze pagina uit te zetten. Een ingestelde slider of video blijft zichtbaar.', 'scoutingtc4'); ?>
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
}
add_action('save_post_page', 'scoutingtc4_save_page_display_metabox');
