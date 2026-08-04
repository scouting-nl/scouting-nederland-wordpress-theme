<?php
if (!defined('ABSPATH')) { exit; }

const SCOUTING_NEDERLAND_BLOCKS_NEW_TAB_ATTRIBUTES = ' target="_blank" rel="noopener noreferrer"';
const SCOUTING_NEDERLAND_CALLOUT_DEFAULT_TEXT = 'Ben jij enthousiast, geef je goed leiding en vind je het leuk om kinderen en jongeren te begeleiden bij hun persoonlijke ontwikkeling? Of heb je misschien bestuurlijke kwaliteiten die je wilt inzetten? Dan zijn wij op zoek naar jou!';
const SCOUTING_NEDERLAND_TWO_COLUMNS_LEFT_TEXT = 'Binnen onze Scoutinggroep hebben we sponsoracties om op die manier Scouting betaalbaar te maken voor iedereen.';
const SCOUTING_NEDERLAND_TWO_COLUMNS_RIGHT_TEXT = 'Meer weten over vrijwilligerswerk bij Scouting? Laat je verrassen door één van de uitdagende vacatures of lees meer over vrijwilliger worden bij Scouting.<ul><li>Welpenleiding</li><li>Secretaris</li></ul><h2>Maatschappelijke stage</h2><p>Iedere scholier krijgt er wel mee te maken: de maatschappelijke stage.</p>';

function scoutingNederlandFeaturedItemsDefaults() {
    return array(
        array(
            'title' => 'Wie zijn wij?',
            'text' => 'Scouting staat voor uitdaging! Scouting biedt leuke en spannende activiteiten waarmee meiden en jongens worden uitgedaagd zich persoonlijk te ontwikkelen.',
            'imageUrl' => '',
            'buttonText' => 'Meer informatie',
            'buttonUrl' => '',
            'buttonColor' => 'blauw',
        ),
        array(
            'title' => 'Lid worden',
            'text' => 'Laat je uitdagen en ontdek Scouting!',
            'imageUrl' => '',
            'buttonText' => 'Kom een keer meedoen',
            'buttonUrl' => '',
            'buttonColor' => 'blauw',
        ),
        array(
            'title' => 'Snel naar',
            'text' => 'Verhuur<br>Stockfoto’s<br>Scouting Nederland',
            'imageUrl' => '',
            'buttonText' => '',
            'buttonUrl' => '',
            'buttonColor' => 'blauw',
        ),
    );
}

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


function scoutingNederlandBlocksButtonClassFromColor($component, $color) {
    $modifier = 'blue';

    if ($color === 'groen') {
        $modifier = 'green';
    } elseif ($color === 'zwart') {
        $modifier = 'black';
    }

    return 'scouting-nederland-blocks-' . $component . '-button-' . $modifier;
}

function scoutingNederlandIsScoutingOidcAvailable() {
    return shortcode_exists('scouting_oidc_button') || shortcode_exists('scouting_oidc_link') || defined('SCOUTING_OIDC_VERSION');
}

function scoutingNederlandBlockEditorAssetVersion($filename) {
    $asset_path = get_template_directory() . '/assets/js/blocks/' . $filename;

    return file_exists($asset_path) ? (string) filemtime($asset_path) : SCOUTING_NEDERLAND_VERSION;
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
        scoutingNederlandBlockEditorAssetVersion('common.js'),
        true
    );

    foreach ($block_scripts as $handle => $file) {
        wp_register_script(
            $handle,
            get_template_directory_uri() . '/assets/js/blocks/' . $file,
            array('scouting-nederland-blocks-common'),
            scoutingNederlandBlockEditorAssetVersion($file),
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

    $common = array('editor_script' => 'scouting-nederland-blocks');

    register_block_type('scouting-nederland/featured-items', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => ''),
            'items' => array('type' => 'array', 'default' => scoutingNederlandFeaturedItemsDefaults()),
        ),
        'render_callback' => 'scoutingNederlandRenderFeaturedItemsBlock',
    )));

    register_block_type('scouting-nederland/callout', array_merge($common, array(
        'attributes' => array(
            'title' => array('type' => 'string', 'default' => 'Vrijwilligers gezocht'),
            'text' => array('type' => 'string', 'default' => SCOUTING_NEDERLAND_CALLOUT_DEFAULT_TEXT),
            'buttonText' => array('type' => 'string', 'default' => 'Meld je aan'),
            'buttonUrl' => array('type' => 'string', 'default' => ''),
            'buttonTarget' => array('type' => 'string', 'default' => ''),
            'buttonColor' => array('type' => 'string', 'default' => 'zwart'),
            'useOwnImage' => array('type' => 'boolean', 'default' => false),
            'imageUrl' => array('type' => 'string', 'default' => ''),
            'calloutPreset' => array('type' => 'string', 'default' => 'kompas'),
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
            'buttonTarget' => array('type' => 'string', 'default' => ''),
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
            'leftText' => array('type' => 'string', 'default' => SCOUTING_NEDERLAND_TWO_COLUMNS_LEFT_TEXT),
            'leftImageUrl' => array('type' => 'string', 'default' => ''),
            'leftButtonText' => array('type' => 'string', 'default' => 'Lees hier meer!'),
            'leftButtonUrl' => array('type' => 'string', 'default' => ''),
            'leftButtonTarget' => array('type' => 'string', 'default' => ''),
            'leftButtonColor' => array('type' => 'string', 'default' => 'blauw'),
            'rightTitle' => array('type' => 'string', 'default' => 'Vacatures'),
            'rightText' => array('type' => 'string', 'default' => SCOUTING_NEDERLAND_TWO_COLUMNS_RIGHT_TEXT),
            'rightButtonText' => array('type' => 'string', 'default' => 'Meer vacatures'),
            'rightButtonUrl' => array('type' => 'string', 'default' => ''),
            'rightButtonTarget' => array('type' => 'string', 'default' => ''),
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

    $output = '<ul class="scouting-nederland-blocks-featured-items-quicklinks">';

    foreach ($links as $link) {
        $output .= '<li><a href="' . esc_url($link['href']) . '"><i class="fas fa-chevron-right" aria-hidden="true"></i>' . esc_html($link['label']) . '</a></li>';
    }

    $output .= '</ul>';

    return $output;
}


function scoutingNederlandRenderFeaturedItemsBlock($attrs) {
    $items = isset($attrs['items']) && is_array($attrs['items']) ? $attrs['items'] : array();
    if (empty($items)) {
        $items = scoutingNederlandFeaturedItemsDefaults();
    }

    ob_start();
    echo '<section class="scouting-nederland-blocks-featured-items">';
    if (!empty($attrs['title'])) {
        echo '<h2 class="scouting-nederland-blocks-featured-items-title">' . esc_html($attrs['title']) . '</h2>';
    }
    echo '<div class="scouting-nederland-blocks-featured-items-grid">';
    foreach (array_slice($items, 0, 3) as $index => $item) {
        $title = isset($item['title']) ? $item['title'] : '';
        $text = isset($item['text']) ? $item['text'] : '';
        $image = isset($item['imageUrl']) ? $item['imageUrl'] : '';
        $button = isset($item['buttonText']) ? $item['buttonText'] : '';
        $url = isset($item['buttonUrl']) ? $item['buttonUrl'] : '';
        $target = isset($item['buttonTarget']) ? $item['buttonTarget'] : '';
        $color = isset($item['buttonColor']) ? $item['buttonColor'] : 'blauw';
        $is_quicklinks = empty($button) && $index === 2;

        echo '<article class="scouting-nederland-blocks-featured-items-item' . ($is_quicklinks ? ' scouting-nederland-blocks-featured-items-item-quicklinks' : '') . '">';
        if ($title) echo '<h2>' . wp_kses_post($title) . '</h2>';
        if ($image) echo '<img src="' . esc_url($image) . '" alt="">';

        if ($text) {
            if ($is_quicklinks) {
                echo scoutingNederlandRenderFeaturedQuicklinks($text);
            } else {
                echo '<div class="scouting-nederland-blocks-featured-items-text">' . wp_kses_post(wpautop($text)) . '</div>';
            }
        }

        if ($button) {
            $target_attr = $target === '_blank' ? SCOUTING_NEDERLAND_BLOCKS_NEW_TAB_ATTRIBUTES : '';
            echo '<a class="scouting-nederland-blocks-featured-items-button ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor('featured-items', $color)) . '" href="' . esc_url($url ?: '#') . '"' . $target_attr . '><span>' . esc_html($button) . '</span><i class="fas fa-chevron-right" aria-hidden="true"></i></a>';
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
        $text = SCOUTING_NEDERLAND_CALLOUT_DEFAULT_TEXT;
    }

    ob_start();
    echo '<section class="scouting-nederland-blocks-callout">';
    echo '<div class="scouting-nederland-blocks-callout-inner">';
    echo '<div class="scouting-nederland-blocks-callout-image">';
    if ($image) {
        echo '<img src="' . esc_url($image) . '" alt="">';
    }
    echo '</div>';
    echo '<div class="scouting-nederland-blocks-callout-content">';
    if ($title) {
        echo '<h2>' . esc_html($title) . '</h2>';
    }

    echo '<div class="scouting-nederland-blocks-callout-copy">' . wp_kses_post(wpautop($text)) . '</div>';

    if (!empty($attrs['buttonText'])) {
        $button_target = !empty($attrs['buttonTarget']) ? $attrs['buttonTarget'] : '';
        $target_attr = $button_target === '_blank' ? SCOUTING_NEDERLAND_BLOCKS_NEW_TAB_ATTRIBUTES : '';
        echo '<a class="scouting-nederland-blocks-callout-button ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor('callout', !empty($attrs['buttonColor']) ? $attrs['buttonColor'] : 'zwart')) . '" href="' . esc_url(!empty($attrs['buttonUrl']) ? $attrs['buttonUrl'] : '#') . '"' . $target_attr . '><span>' . esc_html($attrs['buttonText']) . '</span></a>';
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
    echo '<section class="scouting-nederland-blocks-news-items">';
    echo '<div class="scouting-nederland-blocks-news-items-inner">';
    if (!empty($attrs['title'])) {
        echo '<a class="scouting-nederland-blocks-news-items-heading-link" href="' . esc_url($category_link) . '"><h4>' . esc_html($attrs['title']) . '</h4></a>';
    }

    echo '<div class="scouting-nederland-blocks-news-items-grid">';
    while ($query->have_posts()) {
        $query->the_post();
        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'scouting_nederland_home_card');
        echo '<div class="scouting-nederland-blocks-news-items-item">';
            echo '<a class="scouting-nederland-blocks-news-items-item-link" href="' . esc_url(get_permalink()) . '">';
                echo '<div class="scouting-nederland-blocks-news-items-image"' . ($thumb ? ' style="background-image:url(' . esc_url($thumb) . ');"' : '') . '>';
                    echo '<span class="scouting-nederland-blocks-news-items-date">' . esc_html(get_the_date('d F Y')) . '</span>';
                echo '</div>';
                echo '<h5>' . esc_html(get_the_title()) . '</h5>';
            echo '</a>';
            echo '<div class="scouting-nederland-blocks-news-items-excerpt">' . esc_html(scoutingNederlandTrimTextChars(get_the_excerpt(), 142)) . '</div>';
            echo '<a class="scouting-nederland-blocks-news-items-read-more" href="' . esc_url(get_permalink()) . '">' . esc_html__('Lees meer', 'scouting-nederland') . ' <i class="fas fa-chevron-right" aria-hidden="true"></i></a>';
        echo '</div>';
    }
    wp_reset_postdata();
    echo '</div>';

    if (!empty($attrs['buttonText'])) {
        $button_url = !empty($attrs['buttonUrl']) ? $attrs['buttonUrl'] : $category_link;
        $button_color = !empty($attrs['buttonColor']) ? $attrs['buttonColor'] : 'blauw';
        $button_target = !empty($attrs['buttonTarget']) ? $attrs['buttonTarget'] : '';
        $target_attr = $button_target === '_blank' ? SCOUTING_NEDERLAND_BLOCKS_NEW_TAB_ATTRIBUTES : '';
        echo '<a class="scouting-nederland-blocks-news-items-button ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor('news-items', $button_color)) . '" href="' . esc_url($button_url) . '"' . $target_attr . '><span>' . esc_html($attrs['buttonText']) . '</span><i class="fas fa-chevron-right" aria-hidden="true"></i></a>';
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
    echo '<section class="scouting-nederland-blocks-speltakken-grid">';
    if (!empty($attrs['title'])) {
        echo '<h2 class="scouting-nederland-blocks-speltakken-grid-title">' . esc_html($attrs['title']) . '</h2>';
    }
    echo '<div class="scouting-nederland-blocks-speltakken-grid-grid">';

    foreach ($items as $item) {
        $title = isset($item['title']) ? $item['title'] : '';
        $text = isset($item['text']) ? $item['text'] : '';
        $image = isset($item['imageUrl']) ? $item['imageUrl'] : '';
        $button_url = isset($item['buttonUrl']) ? $item['buttonUrl'] : '';
        $button_text = $button_url ? 'Meer informatie' : '';
        $button_target = isset($item['buttonTarget']) ? $item['buttonTarget'] : '';

        echo '<article class="scouting-nederland-blocks-speltakken-grid-card">';
        if ($image) {
            echo '<div class="scouting-nederland-blocks-speltakken-grid-image"><img src="' . esc_url($image) . '" alt=""></div>';
        } else {
            echo '<div class="scouting-nederland-blocks-speltakken-grid-image scouting-nederland-blocks-speltakken-grid-image-placeholder" aria-hidden="true"></div>';
        }
        echo '<div class="scouting-nederland-blocks-speltakken-grid-body">';
        if ($title) {
            echo '<h3>' . esc_html($title) . '</h3>';
        }
        if ($text) {
            echo '<div class="scouting-nederland-blocks-speltakken-grid-text">' . wp_kses_post(wpautop($text)) . '</div>';
        }
        if ($button_text && $button_url) {
            $target_attr = $button_target === '_blank' ? SCOUTING_NEDERLAND_BLOCKS_NEW_TAB_ATTRIBUTES : '';
            echo '<div class="scouting-nederland-blocks-speltakken-grid-button-wrap"><a class="scouting-nederland-blocks-speltakken-grid-button ' . esc_attr(scoutingNederlandBlocksButtonClassFromColor('speltakken-grid', 'blauw')) . '" href="' . esc_url($button_url) . '"' . $target_attr . '><span>' . esc_html($button_text) . '</span></a></div>';
        }
        echo '</div>';
        echo '</article>';
    }

    echo '</div></section>';
    return ob_get_clean();
}

function scoutingNederlandGetTwoColumnsBlockValues($attrs) {
    $left_text = array_key_exists('leftText', $attrs)
        ? (string) $attrs['leftText']
        : SCOUTING_NEDERLAND_TWO_COLUMNS_LEFT_TEXT;
    $right_text = array_key_exists('rightText', $attrs)
        ? trim((string) $attrs['rightText'])
        : SCOUTING_NEDERLAND_TWO_COLUMNS_RIGHT_TEXT;

    return array(
        'leftTitle' => !empty($attrs['leftTitle']) ? $attrs['leftTitle'] : '',
        'leftText' => $left_text,
        'leftImageUrl' => !empty($attrs['leftImageUrl']) ? $attrs['leftImageUrl'] : '',
        'leftButtonText' => !empty($attrs['leftButtonText']) ? $attrs['leftButtonText'] : '',
        'leftButtonUrl' => !empty($attrs['leftButtonUrl']) ? $attrs['leftButtonUrl'] : '#',
        'leftButtonTarget' => !empty($attrs['leftButtonTarget']) ? $attrs['leftButtonTarget'] : '',
        'leftButtonColor' => !empty($attrs['leftButtonColor']) ? $attrs['leftButtonColor'] : 'blauw',
        'rightTitle' => !empty($attrs['rightTitle']) ? $attrs['rightTitle'] : '',
        'rightText' => $right_text,
        'rightButtonText' => !empty($attrs['rightButtonText']) ? $attrs['rightButtonText'] : '',
        'rightButtonUrl' => !empty($attrs['rightButtonUrl']) ? $attrs['rightButtonUrl'] : '#',
        'rightButtonTarget' => !empty($attrs['rightButtonTarget']) ? $attrs['rightButtonTarget'] : '',
        'rightButtonColor' => !empty($attrs['rightButtonColor']) ? $attrs['rightButtonColor'] : 'zwart',
    );
}

function scoutingNederlandRenderTwoColumnsButton($text, $url, $target, $color) {
    if ($text === '') {
        return '';
    }

    $target_attr = $target === '_blank' ? SCOUTING_NEDERLAND_BLOCKS_NEW_TAB_ATTRIBUTES : '';
    $classes = 'scouting-nederland-blocks-two-columns-button '
        . scoutingNederlandBlocksButtonClassFromColor('two-columns', $color);

    return '<div class="scouting-nederland-blocks-two-columns-button-wrap">'
        . '<a class="' . esc_attr($classes) . '" href="' . esc_url($url) . '"' . $target_attr . '>'
        . '<span>' . esc_html($text) . '</span>'
        . '<span class="scouting-nederland-blocks-two-columns-button-arrow" aria-hidden="true"></span>'
        . '</a></div>';
}

function scoutingNederlandRenderTwoColumnsImageColumn($values) {
    $style = $values['leftImageUrl']
        ? ' style="background-image:url(' . esc_url($values['leftImageUrl']) . ');"'
        : '';
    $title = $values['leftTitle'] ? '<h4>' . esc_html($values['leftTitle']) . '</h4>' : '';
    $button = scoutingNederlandRenderTwoColumnsButton(
        $values['leftButtonText'],
        $values['leftButtonUrl'],
        $values['leftButtonTarget'],
        $values['leftButtonColor']
    );

    return '<div class="scouting-nederland-blocks-two-columns-image-column">'
        . '<div class="scouting-nederland-blocks-two-columns-image"' . $style . '>' . $title . '</div>'
        . '<div class="scouting-nederland-blocks-two-columns-image-column-text">'
        . wp_kses_post(wpautop($values['leftText'])) . '</div>'
        . $button
        . '</div>';
}

function scoutingNederlandRenderTwoColumnsContentColumn($values) {
    $title = $values['rightTitle'] ? '<h4>' . esc_html($values['rightTitle']) . '</h4>' : '';
    $button = scoutingNederlandRenderTwoColumnsButton(
        $values['rightButtonText'],
        $values['rightButtonUrl'],
        $values['rightButtonTarget'],
        $values['rightButtonColor']
    );

    return '<div class="scouting-nederland-blocks-two-columns-content-column">'
        . $title
        . '<div class="scouting-nederland-blocks-two-columns-content">'
        . wp_kses_post(wpautop($values['rightText'])) . '</div>'
        . $button
        . '</div>';
}

function scoutingNederlandRenderTwoColumnsBlock($attrs) {
    $values = scoutingNederlandGetTwoColumnsBlockValues($attrs);

    return '<section class="scouting-nederland-blocks-two-columns">'
        . '<div class="scouting-nederland-blocks-two-columns-grid">'
        . scoutingNederlandRenderTwoColumnsImageColumn($values)
        . scoutingNederlandRenderTwoColumnsContentColumn($values)
        . '</div></section>';
}

function scoutingNederlandRenderOidcBlock($attrs) {
    if (!scoutingNederlandIsScoutingOidcAvailable()) {
        return '';
    }

    $display_type = !empty($attrs['displayType']) ? sanitize_key($attrs['displayType']) : 'button';
    $align_center = !empty($attrs['alignCenter']);
    $classes = 'scouting-nederland-blocks-oidc' . ($align_center ? ' scouting-nederland-blocks-oidc-center' : '');

    if ($display_type === 'link') {
        $login_url = shortcode_exists('scouting_oidc_link') ? trim(do_shortcode('[scouting_oidc_link]')) : '';
        $button_text = !empty($attrs['buttonText']) ? $attrs['buttonText'] : 'Inloggen met Scouting';

        if ($login_url === '') {
            return '';
        }

        return '<div class="' . esc_attr($classes . ' scouting-nederland-blocks-oidc-link') . '"><a class="scouting-nederland-blocks-oidc-text-link" href="' . esc_url($login_url) . '">' . esc_html($button_text) . '</a></div>';
    }

    if (!shortcode_exists('scouting_oidc_button')) {
        return '';
    }

    return '<div class="' . esc_attr($classes . ' scouting-nederland-blocks-oidc-button') . '">' . do_shortcode('[scouting_oidc_button]') . '</div>';
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
