<?php
if (!defined('ABSPATH')) { exit; }


function scoutingNederlandGetHeroFontChoices() {
    return array(
        'impact'       => __('Impact', 'scouting-nederland'),
        'roboto-slab'  => __('Roboto Slab', 'scouting-nederland'),
        'poppins'      => __('Poppins', 'scouting-nederland'),
    );
}

function scoutingNederlandSanitizeHeroFont($value) {
    $value = sanitize_key($value);
    $choices = scoutingNederlandGetHeroFontChoices();
    return array_key_exists($value, $choices) ? $value : 'impact';
}

function scoutingNederlandSanitizePageHeroFont($value) {
    $value = sanitize_key($value);
    if ($value === '') {
        return '';
    }
    $choices = scoutingNederlandGetHeroFontChoices();
    return array_key_exists($value, $choices) ? $value : '';
}

function scoutingNederlandGetDefaultHeroTitleFont() {
    return scoutingNederlandSanitizeHeroFont(get_theme_mod('scouting_nederland_hero_title_font', 'impact'));
}

function scoutingNederlandGetHeroTitleFont($post_id = null) {
    $post_id = $post_id ?: get_queried_object_id();
    $page_font = $post_id ? scoutingNederlandSanitizePageHeroFont((string) get_post_meta($post_id, '_scouting_nederland_hero_title_font', true)) : '';
    return $page_font !== '' ? $page_font : scoutingNederlandGetDefaultHeroTitleFont();
}

function scoutingNederlandGetHeroTitleFontClass($post_id = null) {
    return 'scouting-nederland-hero-font-' . scoutingNederlandGetHeroTitleFont($post_id);
}

function scoutingNederlandGetPageDisplaySettings($post_id = null) {
    $post_id = $post_id ?: get_queried_object_id();

    return array(
        'hide_hero' => get_post_meta($post_id, '_scouting_nederland_hide_hero', true) === '1',
        'hide_page_title' => get_post_meta($post_id, '_scouting_nederland_hide_page_title', true) === '1',
        'hide_sidebar' => get_post_meta($post_id, '_scouting_nederland_hide_sidebar', true) === '1',
        'hero_title_font' => scoutingNederlandSanitizePageHeroFont((string) get_post_meta($post_id, '_scouting_nederland_hero_title_font', true)),
    );
}

function scoutingNederlandPageHidesHero($post_id = null) {
    $settings = scoutingNederlandGetPageDisplaySettings($post_id);
    return !empty($settings['hide_hero']);
}

function scoutingNederlandPageHidesTitle($post_id = null) {
    $settings = scoutingNederlandGetPageDisplaySettings($post_id);
    return !empty($settings['hide_page_title']);
}

function scoutingNederlandPageHidesSidebar($post_id = null) {
    $settings = scoutingNederlandGetPageDisplaySettings($post_id);
    return !empty($settings['hide_sidebar']);
}

function scoutingNederlandAddPageDisplayMetabox() {
    add_meta_box(
        'scouting_nederland_page_display_settings',
        __('Pagina weergave', 'scouting-nederland'),
        'scoutingNederlandRenderPageDisplayMetabox',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'scoutingNederlandAddPageDisplayMetabox');

function scoutingNederlandRenderPageDisplayMetabox($post) {
    wp_nonce_field('scouting_nederland_save_page_display_settings', 'scouting_nederland_page_display_nonce');
    $settings = scoutingNederlandGetPageDisplaySettings($post->ID);
    ?>
    <p>
        <label>
            <input type="checkbox" name="scouting_nederland_hide_hero" value="1" <?php checked($settings['hide_hero']); ?>>
            <?php esc_html_e('Standaard hero verbergen', 'scouting-nederland'); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="scouting_nederland_hide_page_title" value="1" <?php checked($settings['hide_page_title']); ?>>
            <?php esc_html_e('Paginatitel in hero verbergen', 'scouting-nederland'); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="scouting_nederland_hide_sidebar" value="1" <?php checked($settings['hide_sidebar']); ?>>
            <?php esc_html_e('Sidebar verbergen', 'scouting-nederland'); ?>
        </label>
    </p>
    <p>
        <label for="scouting_nederland_hero_title_font"><strong><?php esc_html_e('Lettertype hero-koptekst', 'scouting-nederland'); ?></strong></label><br>
        <select name="scouting_nederland_hero_title_font" id="scouting_nederland_hero_title_font" style="width:100%;">
            <option value="" <?php selected($settings['hero_title_font'], ''); ?>><?php printf(esc_html__('Standaard uit Customizer (%s)', 'scouting-nederland'), esc_html(scoutingNederlandGetHeroFontChoices()[scoutingNederlandGetDefaultHeroTitleFont()])); ?></option>
            <?php foreach (scoutingNederlandGetHeroFontChoices() as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($settings['hero_title_font'], $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p class="description">
        <?php esc_html_e('Gebruik deze opties om de standaard hero, de titel-overlay, de sidebar of het lettertype van de hero-koptekst voor deze pagina aan te passen. Een ingestelde slider of video blijft zichtbaar.', 'scouting-nederland'); ?>
    </p>
    <?php
}

function scoutingNederlandSavePageDisplayMetabox($post_id) {
    $nonce = isset($_POST['scouting_nederland_page_display_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['scouting_nederland_page_display_nonce']))
        : '';

    if (
        !wp_verify_nonce($nonce, 'scouting_nederland_save_page_display_settings')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || wp_is_post_revision($post_id)
        || get_post_type($post_id) !== 'page'
        || !current_user_can('edit_page', $post_id)
    ) {
        return;
    }

    update_post_meta($post_id, '_scouting_nederland_hide_hero', !empty($_POST['scouting_nederland_hide_hero']) ? '1' : '0');
    update_post_meta($post_id, '_scouting_nederland_hide_page_title', !empty($_POST['scouting_nederland_hide_page_title']) ? '1' : '0');
    update_post_meta($post_id, '_scouting_nederland_hide_sidebar', !empty($_POST['scouting_nederland_hide_sidebar']) ? '1' : '0');
    update_post_meta($post_id, '_scouting_nederland_hero_title_font', isset($_POST['scouting_nederland_hero_title_font']) ? scoutingNederlandSanitizePageHeroFont(wp_unslash($_POST['scouting_nederland_hero_title_font'])) : '');
}
add_action('save_post_page', 'scoutingNederlandSavePageDisplayMetabox');
