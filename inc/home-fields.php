<?php
if (!defined('ABSPATH')) { exit; }

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
        echo '<p>' . esc_html(scoutingtc4_trim_text_chars(get_the_excerpt(), 142)) . '</p>';
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
