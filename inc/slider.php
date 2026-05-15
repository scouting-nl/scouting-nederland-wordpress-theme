<?php
if (!defined('ABSPATH')) { exit; }

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
    $tc4_hero_font_class = scoutingtc4_get_hero_title_font_class($post_id);

    if ($type === 'video') {
        $video_id = absint(get_post_meta($post_id, '_tc4_slider_video_id', true));
        $video = get_post_meta($post_id, '_tc4_slider_video', true);

        if ($video_id) {
            $video_url = wp_get_attachment_url($video_id);
            if ($video_url) {
                $video_heading = get_post_meta($post_id, '_tc4_slider_video_heading', true);
                echo '<section class="tc4-home-slider tc4-home-video tc4-home-video-library ' . esc_attr($tc4_hero_font_class) . '">';
                echo '<video class="tc4-home-video-player" src="' . esc_url($video_url) . '" autoplay muted loop playsinline></video>';
                if ($video_heading) {
                    echo '<div class="tc4-home-video-caption ' . esc_attr($tc4_hero_font_class) . '"><h1 class="' . esc_attr($tc4_hero_font_class) . '">' . esc_html($video_heading) . '</h1></div>';
                }
                echo '</section>';
                return true;
            }
        }

        if ($video) {
            $embed = wp_oembed_get($video);
            if ($embed) {
                $video_heading = get_post_meta($post_id, '_tc4_slider_video_heading', true);
                echo '<section class="tc4-home-slider tc4-home-video tc4-home-video-embed ' . esc_attr($tc4_hero_font_class) . '"><div class="tc4-video-wrap">' . $embed . '</div>';
                if ($video_heading) {
                    echo '<div class="tc4-home-video-caption ' . esc_attr($tc4_hero_font_class) . '"><h1 class="' . esc_attr($tc4_hero_font_class) . '">' . esc_html($video_heading) . '</h1></div>';
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

    echo '<section class="tc4-home-slider ' . esc_attr($tc4_hero_font_class) . '" data-tc4-slider>';
    foreach ($slides as $i => $slide) {
        $image_id = isset($slide['image_id']) ? absint($slide['image_id']) : 0;
        $heading  = isset($slide['heading']) ? $slide['heading'] : '';
        $image    = $image_id ? wp_get_attachment_image_url($image_id, 'tc4_home_hero') : '';
        if (!$image) {
            continue;
        }
        echo '<div class="tc4-home-slide' . ($i === 0 ? ' is-active' : '') . '" style="background-image:url(' . esc_url($image) . ')">';
        if ($heading) {
            echo '<div class="tc4-home-slide__caption ' . esc_attr($tc4_hero_font_class) . '"><h1 class="' . esc_attr($tc4_hero_font_class) . '">' . esc_html($heading) . '</h1></div>';
        }
        echo '</div>';
    }
    echo '</section>';
    return true;
}
