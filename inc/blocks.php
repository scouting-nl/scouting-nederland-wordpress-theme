<?php
if (!defined('ABSPATH')) { exit; }

function scoutingNederlandCalloutImageOptions() {
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

function scoutingNederlandCalloutImageUrl($preset) {
    $options = scoutingNederlandCalloutImageOptions();
    if (!isset($options[$preset])) {
        $preset = 'kompas';
    }
    return get_template_directory_uri() . '/assets/images/' . $options[$preset]['file'];
}


function scoutingNederlandBlocksButtonClassFromColor($color) {
    if ($color === 'groen') {
        return 'scouting-nederland-btn-green';
    }
    if ($color === 'zwart') {
        return 'scouting-nederland-btn-black';
    }
    return 'scouting-nederland-btn-blue';
}

function scoutingNederlandIsScoutingOidcAvailable() {
    return shortcode_exists('scouting_oidc_button') || shortcode_exists('scouting_oidc_link') || defined('SCOUTING_OIDC_VERSION');
}

function scoutingNederlandRegisterEditorBlocks() {
    $block_editor_dependencies = array('wp-blocks', 'wp-element', 'wp-editor', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render', 'wp-url', 'jquery', 'wplink', 'quicktags');
    $block_scripts = array(
        'scouting-nederland-blocks-featured-items' => 'featured-items.js',
        'scouting-nederland-blocks-callout' => 'callout.js',
        'scouting-nederland-blocks-news-items' => 'news-items.js',
        'scouting-nederland-blocks-speltakken-grid' => 'speltakken-grid.js',
        'scouting-nederland-blocks-two-columns' => 'two-columns.js',
    );

    if (scoutingNederlandIsScoutingOidcAvailable()) {
        $block_scripts['scouting-nederland-blocks-oidc'] = 'oidc.js';
    }

    wp_register_script(
        'scouting-nederland-blocks-common',
        get_template_directory_uri() . '/assets/js/blocks/common.js',
        $block_editor_dependencies,
        SCOUTING_NEDERLAND_VERSION,
        true
    );

    foreach ($block_scripts as $handle => $file) {
        wp_register_script(
            $handle,
            get_template_directory_uri() . '/assets/js/blocks/' . $file,
            array('scouting-nederland-blocks-common'),
            SCOUTING_NEDERLAND_VERSION,
            true
        );
    }

    wp_register_script(
        'scouting-nederland-blocks',
        false,
        array_keys($block_scripts),
        SCOUTING_NEDERLAND_VERSION,
        true
    );
    $callout_options = array();
    foreach (scoutingNederlandCalloutImageOptions() as $key => $option) {
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

    wp_localize_script('scouting-nederland-blocks-common', 'scoutingNederlandBlocks', array(
        'calloutOptions' => $callout_options,
        'newsCategories' => $news_categories,
        'newsPosts' => $news_posts,
        'oidcAvailable' => scoutingNederlandIsScoutingOidcAvailable(),
    ));

    $common = array(
        'editor_script' => 'scouting-nederland-blocks',
        'supports' => array(
            'anchor' => true,
        ),
    );

    register_block_type('scouting-nederland/featured-items', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => ''),
            'items' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => 'scoutingNederlandRenderFeaturedItemsBlock',
    )));

    register_block_type('scouting-nederland/callout', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Vrijwilligers gezocht'),
            'text' => array('type' => 'string', 'default' => ''),
            'buttonText' => array('type' => 'string', 'default' => 'Meld je aan'),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
            'buttonTarget' => array('type' => 'string', 'default' => ''),
            'buttonColor' => array('type' => 'string', 'default' => 'blauw'),
            'useOwnImage' => array('type' => 'boolean', 'default' => false),
            'imageId' => array('type' => 'number', 'default' => 0),
            'imageUrl' => array('type' => 'string', 'default' => ''),
            'calloutPreset' => array('type' => 'string', 'default' => ''),
        ),
        'render_callback' => 'scoutingNederlandRenderCalloutBlock',
    )));

    register_block_type('scouting-nederland/news-items', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Nieuws'),
            'categoryIds' => array('type' => 'array', 'default' => array()),
            'postIds' => array('type' => 'array', 'default' => array()),
            'count' => array('type' => 'number', 'default' => 3),
            'buttonText' => array('type' => 'string', 'default' => 'Meer nieuws'),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
            'buttonColor' => array('type' => 'string', 'default' => 'blauw'),
        ),
        'render_callback' => 'scoutingNederlandRenderNewsItemsBlock',
    )));

    register_block_type('scouting-nederland/speltakken-grid', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => ''),
            'items' => array('type' => 'array', 'default' => array()),
        ),
        'render_callback' => 'scoutingNederlandRenderSpeltakkenGridBlock',
    )));

    register_block_type('scouting-nederland/two-columns', array_merge($common, array(
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
        'render_callback' => 'scoutingNederlandRenderTwoColumnsBlock',
    )));


    if (scoutingNederlandIsScoutingOidcAvailable()) {
        register_block_type('scouting-nederland/oidc', array_merge($common, array(
            'attributes' => array(
                'displayType' => array('type' => 'string', 'default' => 'button'),
                'buttonText' => array('type' => 'string', 'default' => 'Inloggen met Scouting'),
                'alignCenter' => array('type' => 'boolean', 'default' => false),
            ),
            'render_callback' => 'scoutingNederlandRenderOidcBlock',
        )));
    }
}
add_action('init', 'scoutingNederlandRegisterEditorBlocks');
function scoutingNederlandRenderFeaturedQuicklinks($text) {
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

    $output = '<ul class="scouting-nederland-quicklink-list">';

    foreach ($links as $link) {
        $output .= '<li><a href="' . esc_url($link['href']) . '">' . esc_html($link['label']) . '</a></li>';
    }

    $output .= '</ul>';

    return $output;
}


function scoutingNederlandBlockAnchorAttribute($attrs) {
    if (empty($attrs['anchor'])) {
        return '';
    }

    $anchor = sanitize_title((string) $attrs['anchor']);
    return $anchor !== '' ? ' id="' . esc_attr($anchor) . '"' : '';
}

function scoutingNederlandLinkTargetAttribute($target) {
    return $target === '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '';
}

function scoutingNederlandRenderFeaturedItemsBlock($attrs) {
    $items = isset($attrs['items']) && is_array($attrs['items']) ? $attrs['items'] : array();
    if (empty($items)) {
        $items = array(
            array('title' => 'Wie zijn wij?', 'text' => 'Scouting staat voor uitdaging!', 'buttonText' => 'Meer informatie', 'buttonColor' => 'blauw'),
            array('title' => 'Lid worden', 'text' => 'Laat je uitdagen en ontdek Scouting!', 'buttonText' => 'Kom een keer meedoen', 'buttonColor' => 'blauw'),
            array('title' => 'Snel naar', 'text' => 'Verhuur<br>Stockfoto’s<br>Scouting Nederland', 'buttonText' => '', 'buttonColor' => 'blauw'),
        );
    }

    ob_start();
    echo '<section' . scoutingNederlandBlockAnchorAttribute($attrs) . ' class="scouting-nederland-block-featured-items scouting-nederland-featured-style">';
    if (!empty($attrs['title'])) {
        echo '<h2 class="scouting-nederland-featured-main-title">' . esc_html($attrs['title']) . '</h2>';
    }
    echo '<div class="scouting-nederland-featured-grid">';
    foreach (array_slice($items, 0, 3) as $index => $item) {
        $title = isset($item['title']) ? $item['title'] : '';
        $text = isset($item['text']) ? $item['text'] : '';
        $image = isset($item['imageUrl']) ? $item['imageUrl'] : '';
        $button = isset($item['buttonText']) ? $item['buttonText'] : '';
        $url = isset($item['buttonUrl']) ? $item['buttonUrl'] : '';
        $target = isset($item['buttonTarget']) && $item['buttonTarget'] === '_blank' ? '_blank' : '';
        $color = isset($item['buttonColor']) ? $item['buttonColor'] : 'blauw';
        $is_quicklinks = empty($button) && $index === 2;

        echo '<article class="scouting-nederland-featured-item' . ($is_quicklinks ? ' scouting-nederland-featured-quicklinks' : '') . '">';
        if ($title) echo '<h2>' . wp_kses_post($title) . '</h2>';
        if ($image) echo '<img src="' . esc_url($image) . '" alt="">';

        if ($text) {
            if ($is_quicklinks) {
                echo scoutingNederlandRenderFeaturedQuicklinks($text);
            } else {
                echo '<div class="scouting-nederland-featured-text">' . wp_kses_post(wpautop($text)) . '</div>';
            }
        }

        if ($button) {
            $target_attr = scoutingNederlandLinkTargetAttribute($target);
            echo '<a class="scouting-nederland-btn ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor($color)) . '" href="' . esc_url($url ?: '#') . '"' . $target_attr . '><span>' . esc_html($button) . '</span></a>';
        }
        echo '</article>';
    }
    echo '</div></section>';
    return ob_get_clean();
}

function scoutingNederlandRenderCalloutBlock($attrs) {
    $image = !empty($attrs['useOwnImage']) && !empty($attrs['imageUrl']) ? $attrs['imageUrl'] : '';
    if (empty($image)) {
        $image = scoutingNederlandCalloutImageUrl(!empty($attrs['calloutPreset']) ? $attrs['calloutPreset'] : 'kompas');
    }

    $title = !empty($attrs['title']) ? $attrs['title'] : 'Vrijwilligers gezocht';
    $text = isset($attrs['text']) ? trim((string) $attrs['text']) : '';
    if ($text === '') {
        $text = 'Ben jij enthousiast, geef je goed leiding en vind je het leuk om kinderen en jongeren te begeleiden bij hun persoonlijke ontwikkeling? Of heb je misschien bestuurlijke kwaliteiten die je wilt inzetten? Dan zijn wij op zoek naar jou!';
    }

    ob_start();
    echo '<section' . scoutingNederlandBlockAnchorAttribute($attrs) . ' class="scouting-nederland-block-callout scouting-nederland-callout-style">';
    echo '<div class="scouting-nederland-callout-inner">';
    echo '<div class="scouting-nederland-callout-image">';
    if ($image) {
        echo '<img src="' . esc_url($image) . '" alt="">';
    }
    echo '</div>';
    echo '<div class="scouting-nederland-callout-text">';
    if ($title) {
        echo '<h2>' . esc_html($title) . '</h2>';
    }

    echo '<div class="scouting-nederland-callout-copy">' . wp_kses_post(wpautop($text)) . '</div>';

    if (!empty($attrs['buttonText'])) {
        echo '<a class="scouting-nederland-btn ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor(!empty($attrs['buttonColor']) ? $attrs['buttonColor'] : 'zwart')) . '" href="' . esc_url(!empty($attrs['buttonUrl']) ? $attrs['buttonUrl'] : '#') . '"><span>' . esc_html($attrs['buttonText']) . '</span></a>';
    }
    echo '</div></div></section>';
    return ob_get_clean();
}

function scoutingNederlandRenderNewsItemsBlock($attrs) {
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
    echo '<section' . scoutingNederlandBlockAnchorAttribute($attrs) . ' class="scouting-nederland-block-news scouting-nederland-news-style">';
    echo '<div class="gb-news-items">';
    if (!empty($attrs['title'])) {
        echo '<a class="scouting-nederland-news-heading-link" href="' . esc_url($category_link) . '"><h4>' . esc_html($attrs['title']) . '</h4></a>';
    }

    echo '<div class="news-items">';
    while ($query->have_posts()) {
        $query->the_post();
        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'scouting_nederland_home_card');
        echo '<div class="news-item">';
            echo '<a class="titel" href="' . esc_url(get_permalink()) . '">';
                echo '<div class="beeld"' . ($thumb ? ' style="background-image:url(' . esc_url($thumb) . ');"' : '') . '>';
                    echo '<span class="datum">' . esc_html(get_the_date('d F Y')) . '</span>';
                echo '</div>';
                echo '<h5>' . esc_html(get_the_title()) . '</h5>';
            echo '</a>';
            echo '<div class="tekst">' . esc_html(scoutingNederlandTrimTextChars(get_the_excerpt(), 142)) . '</div>';
            echo '<a class="lees-meer" href="' . esc_url(get_permalink()) . '">' . esc_html__('Lees meer', 'scouting-nederland') . '</a>';
        echo '</div>';
    }
    wp_reset_postdata();
    echo '</div>';

    if (!empty($attrs['buttonText'])) {
        $button_url = !empty($attrs['buttonUrl']) ? $attrs['buttonUrl'] : $category_link;
        $button_color = !empty($attrs['buttonColor']) ? $attrs['buttonColor'] : 'blauw';
        $button_target = !empty($attrs['buttonTarget']) ? $attrs['buttonTarget'] : '';
        $target_attr = scoutingNederlandLinkTargetAttribute($button_target);
        echo '<a class="meer-nieuws scouting-nederland-btn ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor($button_color)) . '" href="' . esc_url($button_url) . '"' . $target_attr . '><span>' . esc_html($attrs['buttonText']) . '</span></a>';
    }

    echo '</div>';
    echo '</section>';
    return ob_get_clean();
}

function scoutingNederlandRenderSpeltakkenGridBlock($attrs) {
    $items = isset($attrs['items']) && is_array($attrs['items']) ? $attrs['items'] : array();

    if (empty($items)) {
        $items = array(
            array('title' => 'Bevers', 'text' => 'Voor kinderen van 5 tot 7 jaar: samen spelen, fantaseren en ontdekken.', 'imageUrl' => '', 'buttonText' => 'Meer informatie', 'buttonUrl' => '', 'buttonTarget' => ''),
            array('title' => 'Welpen', 'text' => 'Voor kinderen van 7 tot 11 jaar: avonturen beleven in de jungle.', 'imageUrl' => '', 'buttonText' => 'Meer informatie', 'buttonUrl' => '', 'buttonTarget' => ''),
            array('title' => 'Scouts', 'text' => 'Voor jongeren van 11 tot 15 jaar: buitenactiviteiten, samenwerken en nieuwe skills leren.', 'imageUrl' => '', 'buttonText' => 'Meer informatie', 'buttonUrl' => '', 'buttonTarget' => ''),
        );
    }

    ob_start();
    echo '<section' . scoutingNederlandBlockAnchorAttribute($attrs) . ' class="scouting-nederland-block-speltakken-grid scouting-nederland-speltakken-grid-style">';
    if (!empty($attrs['title'])) {
        echo '<h2 class="scouting-nederland-speltakken-main-title">' . esc_html($attrs['title']) . '</h2>';
    }
    echo '<div class="scouting-nederland-speltakken-grid">';

    foreach ($items as $item) {
        $title = isset($item['title']) ? $item['title'] : '';
        $text = isset($item['text']) ? $item['text'] : '';
        $image = isset($item['imageUrl']) ? $item['imageUrl'] : '';
        $button_url = isset($item['buttonUrl']) ? $item['buttonUrl'] : '';
        $button_text = $button_url ? 'Meer informatie' : '';
        $button_target = isset($item['buttonTarget']) ? $item['buttonTarget'] : '';

        echo '<article class="scouting-nederland-speltak-card">';
        if ($image) {
            echo '<div class="scouting-nederland-speltak-card__image"><img src="' . esc_url($image) . '" alt=""></div>';
        } else {
            echo '<div class="scouting-nederland-speltak-card__image scouting-nederland-speltak-card__image--placeholder" aria-hidden="true"></div>';
        }
        echo '<div class="scouting-nederland-speltak-card__body">';
        if ($title) {
            echo '<h3>' . esc_html($title) . '</h3>';
        }
        if ($text) {
            echo '<div class="scouting-nederland-speltak-card__text">' . wp_kses_post(wpautop($text)) . '</div>';
        }
        if ($button_text && $button_url) {
            $target_attr = scoutingNederlandLinkTargetAttribute($button_target);
            echo '<div class="scouting-nederland-speltak-card__button"><a class="scouting-nederland-btn scouting-nederland-btn-blue" href="' . esc_url($button_url) . '"' . $target_attr . '><span>' . esc_html($button_text) . '</span></a></div>';
        }
        echo '</div>';
        echo '</article>';
    }

    echo '</div></section>';
    return ob_get_clean();
}

function scoutingNederlandRenderTwoColumnsBlock($attrs) {
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

    echo '<section' . scoutingNederlandBlockAnchorAttribute($attrs) . ' class="scouting-nederland-block-two-columns scouting-nederland-two-columns-style">';
    echo '<div class="gb-two-columns">';

    echo '<div class="col-headerimage">';
    echo '<div class="beeld"' . ($left_image ? ' style="background-image:url(' . esc_url($left_image) . ');"' : '') . '>';
    if ($left_title) {
        echo '<h4>' . esc_html($left_title) . '</h4>';
    }
    echo '</div>';
    echo '<div class="tekst">' . wp_kses_post(wpautop($left_text)) . '</div>';
    if ($left_btn_t) {
        echo '<div class="knop"><a class="btn scouting-nederland-two-btn ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor($left_btn_c)) . '" href="' . esc_url($left_btn_u) . '"><span class="scouting-nederland-two-btn-label">' . esc_html($left_btn_t) . '</span><span class="scouting-nederland-two-btn-arrow" aria-hidden="true"></span></a></div>';
    }
    echo '</div>';

    echo '<div class="col-wysiwyg">';
    if ($right_title) {
        echo '<h4>' . esc_html($right_title) . '</h4>';
    }
    echo '<div class="content">' . wp_kses_post(wpautop($right_text)) . '</div>';
    if ($right_btn_t) {
        echo '<div class="knop"><a class="btn scouting-nederland-two-btn ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor($right_btn_c)) . '" href="' . esc_url($right_btn_u) . '"><span class="scouting-nederland-two-btn-label">' . esc_html($right_btn_t) . '</span><span class="scouting-nederland-two-btn-arrow" aria-hidden="true"></span></a></div>';
    }
    echo '</div>';

    echo '</div>';
    echo '</section>';

    return ob_get_clean();
}

function scoutingNederlandRenderOidcBlock($attrs) {
    if (!scoutingNederlandIsScoutingOidcAvailable()) {
        return current_user_can('edit_posts') ? '<p class="scouting-nederland-oidc-unavailable">Scouting OpenID Connect is niet actief.</p>' : '';
    }

    $display_type = !empty($attrs['displayType']) ? sanitize_key($attrs['displayType']) : 'button';
    $align_center = !empty($attrs['alignCenter']);
    $classes = 'scouting-nederland-block-oidc' . ($align_center ? ' scouting-nederland-block-oidc--center' : '');

    if ($display_type === 'link') {
        $login_url = shortcode_exists('scouting_oidc_link') ? trim(do_shortcode('[scouting_oidc_link]')) : '';
        $button_text = !empty($attrs['buttonText']) ? $attrs['buttonText'] : 'Inloggen met Scouting';

        if ($login_url === '') {
            return '';
        }

    return '<div' . scoutingNederlandBlockAnchorAttribute($attrs) . ' class="' . esc_attr($classes . ' scouting-nederland-block-oidc--link') . '"><a class="scouting-nederland-oidc-text-link" href="' . esc_url($login_url) . '">' . esc_html($button_text) . '</a></div>';
    }

    if (!shortcode_exists('scouting_oidc_button')) {
        return '';
    }

    return '<div' . scoutingNederlandBlockAnchorAttribute($attrs) . ' class="' . esc_attr($classes . ' scouting-nederland-block-oidc--button') . '">' . do_shortcode('[scouting_oidc_button]') . '</div>';
}

function scoutingNederlandBlockCategories($categories, $post) {
    return array_merge(
        array(
            array(
                'slug'  => 'scouting-nederland',
                'title' => __('Scouting Nederland', 'scouting-nederland'),
                'icon'  => null,
            ),
        ),
        $categories
    );
}
add_filter('block_categories_all', 'scoutingNederlandBlockCategories', 10, 2);
function scoutingNederlandEnqueueBlockEditorDependencies() {
    wp_enqueue_editor();
}
add_action('enqueue_block_editor_assets', 'scoutingNederlandEnqueueBlockEditorDependencies');
