<?php
if (!defined('ABSPATH')) { exit; }

function scoutingNederlandHomepageFeaturesSetup() {
    add_image_size('scouting_nederland_home_hero', 1920, 520, true);
    add_image_size('scouting_nederland_home_card', 560, 320, true);
    add_theme_support('editor-styles');
    add_editor_style(array('style.css', 'assets/css/base.css', 'assets/css/utilities.css', 'assets/css/blocks.css', 'assets/css/homepage.css', 'assets/css/news.css', 'assets/css/editor.css', 'assets/css/colors.css', 'assets/css/buttons.css'));
}
add_action('after_setup_theme', 'scoutingNederlandHomepageFeaturesSetup', 20);

function scoutingNederlandAddSliderMetabox() {
    add_meta_box(
        'scouting_nederland_slider',
        __('Slider', 'scouting-nederland'),
        'scoutingNederlandRenderSliderMetabox',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'scoutingNederlandAddSliderMetabox');

function scoutingNederlandRenderSliderMetabox($post) {
    wp_nonce_field('scouting_nederland_save_slider', 'scouting_nederland_slider_nonce');
    $show   = get_post_meta($post->ID, '_scouting_nederland_slider_show', true);
    $type   = get_post_meta($post->ID, '_scouting_nederland_slider_type', true) ?: 'slider';
    $video  = get_post_meta($post->ID, '_scouting_nederland_slider_video', true);
    $video_id = absint(get_post_meta($post->ID, '_scouting_nederland_slider_video_id', true));
    $video_heading = get_post_meta($post->ID, '_scouting_nederland_slider_video_heading', true);
    $slides = get_post_meta($post->ID, '_scouting_nederland_slides', true);
    if (!is_array($slides)) {
        $slides = array();
    }
    ?>
    <div class="scouting-nederland-slider-metabox">
        <p>
            <label>
                <input type="checkbox" name="scouting_nederland_slider_show" value="1" <?php checked($show, '1'); ?>>
                <?php esc_html_e('Slider tonen', 'scouting-nederland'); ?>
            </label>
        </p>

        <p>
            <strong><?php esc_html_e('Type', 'scouting-nederland'); ?></strong><br>
            <label><input type="radio" name="scouting_nederland_slider_type" value="video" <?php checked($type, 'video'); ?>> <?php esc_html_e('video', 'scouting-nederland'); ?></label>
            &nbsp;&nbsp;
            <label><input type="radio" name="scouting_nederland_slider_type" value="slider" <?php checked($type, 'slider'); ?>> <?php esc_html_e('slider', 'scouting-nederland'); ?></label>
        </p>

        <div class="scouting-nederland-video-field">
            <p><strong><?php esc_html_e('Video', 'scouting-nederland'); ?></strong></p>
            <input type="hidden" id="scouting_nederland_slider_video_id" name="scouting_nederland_slider_video_id" value="<?php echo esc_attr($video_id); ?>">
            <div id="scouting-nederland-slider-video-preview" class="scouting-nederland-slider-video-preview">
                <?php if ($video_id) :
                    $video_url = wp_get_attachment_url($video_id);
                    if ($video_url) : ?>
                        <video src="<?php echo esc_url($video_url); ?>" style="max-width:320px;height:auto;" controls muted></video>
                        <p><code><?php echo esc_html(basename($video_url)); ?></code></p>
                    <?php endif;
                endif; ?>
            </div>
            <p>
                <button type="button" class="button" id="scouting-nederland-select-video"><?php esc_html_e('Video kiezen uit bibliotheek', 'scouting-nederland'); ?></button>
                <button type="button" class="button-link-delete" id="scouting-nederland-remove-video"><?php esc_html_e('Video verwijderen', 'scouting-nederland'); ?></button>
            </p>
            <p>
                <label for="scouting_nederland_slider_video_heading"><strong><?php esc_html_e('Kop over video', 'scouting-nederland'); ?></strong></label><br>
                <input type="text" id="scouting_nederland_slider_video_heading" name="scouting_nederland_slider_video_heading" value="<?php echo esc_attr($video_heading); ?>" class="widefat" placeholder="<?php esc_attr_e('Plaats hier ...', 'scouting-nederland'); ?>">
                <span class="description"><?php esc_html_e('Deze tekst wordt over de videobanner heen getoond.', 'scouting-nederland'); ?></span>
            </p>

            <p>
                <label for="scouting_nederland_slider_video"><strong><?php esc_html_e('Of externe video URL', 'scouting-nederland'); ?></strong></label><br>
                <input type="url" id="scouting_nederland_slider_video" name="scouting_nederland_slider_video" value="<?php echo esc_attr($video); ?>" class="widefat" placeholder="https://...">
                <span class="description"><?php esc_html_e('Gebruik dit alleen voor bijvoorbeeld YouTube/Vimeo. Een gekozen bibliotheekvideo heeft voorrang.', 'scouting-nederland'); ?></span>
            </p>
        </div>

        <table class="widefat striped scouting-nederland-slides-table">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th style="width:220px;"><?php esc_html_e('Achtergrondbeeld', 'scouting-nederland'); ?></th>
                    <th><?php esc_html_e('Kop', 'scouting-nederland'); ?></th>
                    <th style="width:90px;"></th>
                </tr>
            </thead>
            <tbody id="scouting-nederland-slides-rows">
                <?php foreach ($slides as $index => $slide) :
                    $image_id = isset($slide['image_id']) ? absint($slide['image_id']) : 0;
                    $heading  = isset($slide['heading']) ? $slide['heading'] : '';
                    $preview  = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
                    ?>
                    <tr class="scouting-nederland-slide-row">
                        <td class="scouting-nederland-slide-number"><?php echo esc_html($index + 1); ?></td>
                        <td>
                            <input type="hidden" class="scouting-nederland-slide-image-id" name="scouting_nederland_slides[<?php echo esc_attr($index); ?>][image_id]" value="<?php echo esc_attr($image_id); ?>">
                            <div class="scouting-nederland-slide-preview">
                                <?php if ($preview) : ?><img src="<?php echo esc_url($preview); ?>" alt="" style="max-width:120px;height:auto;"><?php endif; ?>
                            </div>
                            <button type="button" class="button scouting-nederland-select-image"><?php esc_html_e('Afbeelding kiezen', 'scouting-nederland'); ?></button>
                        </td>
                        <td>
                            <input type="text" class="widefat" name="scouting_nederland_slides[<?php echo esc_attr($index); ?>][heading]" value="<?php echo esc_attr($heading); ?>" placeholder="<?php esc_attr_e('Plaats hier ...', 'scouting-nederland'); ?>">
                        </td>
                        <td><button type="button" class="button-link-delete scouting-nederland-remove-slide"><?php esc_html_e('Verwijder', 'scouting-nederland'); ?></button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>
            <button type="button" class="button button-primary" id="scouting-nederland-add-slide"><?php esc_html_e('Slide toevoegen', 'scouting-nederland'); ?></button>
        </p>

        <script type="text/html" id="scouting-nederland-slide-template">
            <tr class="scouting-nederland-slide-row">
                <td class="scouting-nederland-slide-number"></td>
                <td>
                    <input type="hidden" class="scouting-nederland-slide-image-id" name="scouting_nederland_slides[__INDEX__][image_id]" value="">
                    <div class="scouting-nederland-slide-preview"></div>
                    <button type="button" class="button scouting-nederland-select-image"><?php esc_html_e('Afbeelding kiezen', 'scouting-nederland'); ?></button>
                </td>
                <td>
                    <input type="text" class="widefat" name="scouting_nederland_slides[__INDEX__][heading]" value="" placeholder="<?php esc_attr_e('Plaats hier ...', 'scouting-nederland'); ?>">
                </td>
                <td><button type="button" class="button-link-delete scouting-nederland-remove-slide"><?php esc_html_e('Verwijder', 'scouting-nederland'); ?></button></td>
            </tr>
        </script>
    </div>
    <?php
}

function scoutingNederlandSaveSliderMetabox($post_id) {
    if (!isset($_POST['scouting_nederland_slider_nonce']) || !wp_verify_nonce($_POST['scouting_nederland_slider_nonce'], 'scouting_nederland_save_slider')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    update_post_meta($post_id, '_scouting_nederland_slider_show', isset($_POST['scouting_nederland_slider_show']) ? '1' : '0');

    $type = isset($_POST['scouting_nederland_slider_type']) && $_POST['scouting_nederland_slider_type'] === 'video' ? 'video' : 'slider';
    update_post_meta($post_id, '_scouting_nederland_slider_type', $type);
    update_post_meta($post_id, '_scouting_nederland_slider_video', isset($_POST['scouting_nederland_slider_video']) ? esc_url_raw($_POST['scouting_nederland_slider_video']) : '');
    update_post_meta($post_id, '_scouting_nederland_slider_video_id', isset($_POST['scouting_nederland_slider_video_id']) ? absint($_POST['scouting_nederland_slider_video_id']) : 0);
    update_post_meta($post_id, '_scouting_nederland_slider_video_heading', isset($_POST['scouting_nederland_slider_video_heading']) ? sanitize_text_field($_POST['scouting_nederland_slider_video_heading']) : '');

    $slides = array();
    if (isset($_POST['scouting_nederland_slides']) && is_array($_POST['scouting_nederland_slides'])) {
        foreach ($_POST['scouting_nederland_slides'] as $slide) {
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
    update_post_meta($post_id, '_scouting_nederland_slides', $slides);
}
add_action('save_post_page', 'scoutingNederlandSaveSliderMetabox');

function scoutingNederlandAdminSliderAssets($hook) {
    if (!in_array($hook, array('post.php', 'post-new.php'), true)) {
        return;
    }
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'page') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('scouting-nederland-admin-slider', get_template_directory_uri() . '/assets/js/admin-slider.js', array('jquery'), SCOUTING_NEDERLAND_VERSION, true);
}
add_action('admin_enqueue_scripts', 'scoutingNederlandAdminSliderAssets');

function scoutingNederlandRenderHomeSlider($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    if (get_post_meta($post_id, '_scouting_nederland_slider_show', true) !== '1') {
        return false;
    }

    $type = get_post_meta($post_id, '_scouting_nederland_slider_type', true) ?: 'slider';
    $scouting_nederland_hero_font_class = scoutingNederlandGetHeroTitleFontClass($post_id);

    if ($type === 'video') {
        $video_id = absint(get_post_meta($post_id, '_scouting_nederland_slider_video_id', true));
        $video = get_post_meta($post_id, '_scouting_nederland_slider_video', true);

        if ($video_id) {
            $video_url = wp_get_attachment_url($video_id);
            if ($video_url) {
                $video_heading = get_post_meta($post_id, '_scouting_nederland_slider_video_heading', true);
                echo '<section class="scouting-nederland-home-slider scouting-nederland-home-video scouting-nederland-home-video-library ' . esc_attr($scouting_nederland_hero_font_class) . '">';
                echo '<video class="scouting-nederland-home-video-player" src="' . esc_url($video_url) . '" autoplay muted loop playsinline></video>';
                if ($video_heading) {
                    echo '<div class="scouting-nederland-home-video-caption ' . esc_attr($scouting_nederland_hero_font_class) . '"><h1 class="' . esc_attr($scouting_nederland_hero_font_class) . '">' . esc_html($video_heading) . '</h1></div>';
                }
                echo '</section>';
                return true;
            }
        }

        if ($video) {
            $embed = wp_oembed_get($video);
            if ($embed) {
                $video_heading = get_post_meta($post_id, '_scouting_nederland_slider_video_heading', true);
                echo '<section class="scouting-nederland-home-slider scouting-nederland-home-video scouting-nederland-home-video-embed ' . esc_attr($scouting_nederland_hero_font_class) . '"><div class="scouting-nederland-video-wrap">' . $embed . '</div>';
                if ($video_heading) {
                    echo '<div class="scouting-nederland-home-video-caption ' . esc_attr($scouting_nederland_hero_font_class) . '"><h1 class="' . esc_attr($scouting_nederland_hero_font_class) . '">' . esc_html($video_heading) . '</h1></div>';
                }
                echo '</section>';
                return true;
            }
        }
    }

    $slides = get_post_meta($post_id, '_scouting_nederland_slides', true);
    if (!is_array($slides) || empty($slides)) {
        return false;
    }

    echo '<section class="scouting-nederland-home-slider ' . esc_attr($scouting_nederland_hero_font_class) . '" data-scouting-nederland-slider>';
    foreach ($slides as $i => $slide) {
        $image_id = isset($slide['image_id']) ? absint($slide['image_id']) : 0;
        $heading  = isset($slide['heading']) ? $slide['heading'] : '';
        $image    = $image_id ? wp_get_attachment_image_url($image_id, 'scouting_nederland_home_hero') : '';
        if (!$image) {
            continue;
        }
        echo '<div class="scouting-nederland-home-slide' . ($i === 0 ? ' is-active' : '') . '" style="background-image:url(' . esc_url($image) . ')">';
        if ($heading) {
            echo '<div class="scouting-nederland-home-slide__caption ' . esc_attr($scouting_nederland_hero_font_class) . '"><h1 class="' . esc_attr($scouting_nederland_hero_font_class) . '">' . esc_html($heading) . '</h1></div>';
        }
        echo '</div>';
    }
    echo '</section>';
    return true;
}
