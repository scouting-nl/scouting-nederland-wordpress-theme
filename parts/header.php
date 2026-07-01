<?php if (!defined('ABSPATH')) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$navclass  = get_theme_mod('scouting_nederland_hamburger_desktop', 0) ? 'hamburger' : 'navbar-expand-lg';
$show_stamp = get_theme_mod('scouting_nederland_logo_stamp', 1);
$logoclass = $show_stamp ? 'navbar-brand logostamp' : 'navbar-brand';
$custom_logo_id = get_theme_mod('custom_logo');
$custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
?>
<nav id="scouting-nederland-nav" class="navbar navbar-light fixed-top <?php echo esc_attr($navclass); ?>" aria-label="<?php esc_attr_e('Hoofdnavigatie', 'scouting-nederland'); ?>">
    <div class="container-fluid">
        <div id="scouting-nederland-logo" class="navbar-brand mr-auto">
            <a class="<?php echo esc_attr($logoclass); ?>" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php if ($show_stamp) : ?>
                    <img class="scouting-nederland-logostamp-image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/stamp-shadow.png'); ?>" alt="" aria-hidden="true">
                <?php endif; ?>
                <?php if ($custom_logo_url) : ?>
                    <img class="eigenlogo" src="<?php echo esc_url($custom_logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <?php else : ?>
                    <img class="scoutinglogo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/snl-logo.svg'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <?php endif; ?>
            </a>
        </div>

        <div id="scouting-nederland-headermessage" class="flex-grow-1">
            <?php $msg = get_theme_mod('scouting_nederland_header_message', ''); $url = get_theme_mod('scouting_nederland_header_message_url', ''); ?>
            <?php if (get_theme_mod('scouting_nederland_show_header_message', 1) && $msg) : ?>
                <a class="scouting-nederland-header-alert text-danger" href="<?php echo esc_url($url ?: home_url('/')); ?>"><?php echo esc_html($msg); ?> <i class="fas fa-exclamation-triangle" aria-hidden="true"></i></a>
            <?php endif; ?>
        </div>

        <div id="scouting-nederland-buttons">
            <?php if (get_theme_mod('scouting_nederland_show_phone', 1) && ($phone = get_theme_mod('scouting_nederland_phone', ''))) : ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="phone" aria-label="<?php esc_attr_e('Bellen', 'scouting-nederland'); ?>">
                    <i class="fas fa-phone scouting-nederland-fa-icon" aria-hidden="true"></i>
                </a>
            <?php endif; ?>
            <?php if (get_theme_mod('scouting_nederland_show_contact', 1) && ($contact = get_theme_mod('scouting_nederland_contact_url', ''))) : ?>
                <a href="<?php echo esc_url($contact); ?>" class="contact" aria-label="<?php esc_attr_e('Contact', 'scouting-nederland'); ?>">
                    <i class="fas fa-envelope scouting-nederland-fa-icon" aria-hidden="true"></i>
                </a>
            <?php endif; ?>
            <?php if (get_theme_mod('scouting_nederland_show_search', 1)) :
                $search_url = get_theme_mod('scouting_nederland_search_url', ''); ?>
                <a href="<?php echo esc_url($search_url ?: home_url('/?s=')); ?>" class="search" aria-label="<?php esc_attr_e('Zoeken', 'scouting-nederland'); ?>">
                    <i class="fas fa-search scouting-nederland-fa-icon scouting-nederland-fa-icon-search" aria-hidden="true"></i>
                </a>
            <?php endif; ?>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="offcanvas" data-bs-target="#scoutingNederlandMobileOffcanvas" aria-controls="scoutingNederlandMobileOffcanvas" aria-expanded="false" aria-label="<?php esc_attr_e('Navigatie tonen', 'scouting-nederland'); ?>">
                <i id="scouting-nederland-navbar-toggler-icon" class="fa fa-bars scouting-nederland-fa-icon" aria-hidden="true"></i><span class="scouting-nederland-menu-label"><?php esc_html_e('Menu', 'scouting-nederland'); ?></span>
            </button>
        </div>

        <div class="scouting-nederland-menu collapse navbar-collapse" id="navbarSupportedContent">
            <?php wp_nav_menu(array('theme_location'=>'primary', 'container'=>false, 'menu_class'=>'navbar-nav ms-auto mb-2 mb-lg-0', 'fallback_cb'=>'__return_false', 'walker'=>new ScoutingNederlandBootstrapNavwalker())); ?>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-end scouting-nederland-mobile-offcanvas" tabindex="-1" id="scoutingNederlandMobileOffcanvas" aria-labelledby="scoutingNederlandMobileOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="scoutingNederlandMobileOffcanvasLabel"><?php bloginfo('name'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e('Sluiten', 'scouting-nederland'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <?php $mobile_msg = get_theme_mod('scouting_nederland_header_message', ''); $mobile_msg_url = get_theme_mod('scouting_nederland_header_message_url', ''); ?>
        <?php if (get_theme_mod('scouting_nederland_show_header_message', 1) && $mobile_msg) : ?>
            <div class="scouting-nederland-mobile-message">
                <a href="<?php echo esc_url($mobile_msg_url ?: home_url('/')); ?>">
                    <?php echo esc_html($mobile_msg); ?> <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                </a>
            </div>
        <?php endif; ?>

        <nav class="scouting-nederland-mobile-menu" aria-label="<?php esc_attr_e('Mobiele navigatie', 'scouting-nederland'); ?>">
            <?php wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'scouting-nederland-mobile-nav',
                'fallback_cb'    => '__return_false'
            )); ?>
        </nav>

        <div class="scouting-nederland-mobile-icons">
            <?php if (get_theme_mod('scouting_nederland_show_phone', 1) && ($mobile_phone = get_theme_mod('scouting_nederland_phone', ''))) : ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $mobile_phone)); ?>" class="phone" aria-label="<?php esc_attr_e('Bellen', 'scouting-nederland'); ?>">
                    <i class="fas fa-phone" aria-hidden="true"></i>
                    <span><?php esc_html_e('Bellen', 'scouting-nederland'); ?></span>
                </a>
            <?php endif; ?>

            <?php if (get_theme_mod('scouting_nederland_show_contact', 1) && ($mobile_contact = get_theme_mod('scouting_nederland_contact_url', ''))) : ?>
                <a href="<?php echo esc_url($mobile_contact); ?>" class="contact" aria-label="<?php esc_attr_e('Contact', 'scouting-nederland'); ?>">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <span><?php esc_html_e('Contact', 'scouting-nederland'); ?></span>
                </a>
            <?php endif; ?>

            <?php if (get_theme_mod('scouting_nederland_show_search', 1)) :
                $mobile_search_url = get_theme_mod('scouting_nederland_search_url', ''); ?>
                <a href="<?php echo esc_url($mobile_search_url ?: home_url('/?s=')); ?>" class="search" aria-label="<?php esc_attr_e('Zoeken', 'scouting-nederland'); ?>">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span><?php esc_html_e('Zoeken', 'scouting-nederland'); ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
