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
$navclass  = get_theme_mod('scoutingtc4_hamburger_desktop', 0) ? 'hamburger' : 'navbar-expand-lg';
$show_stamp = get_theme_mod('scoutingtc4_logo_stamp', 1);
$logoclass = $show_stamp ? 'navbar-brand logostamp' : 'navbar-brand';
$custom_logo_id = get_theme_mod('custom_logo');
$custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';
?>
<nav id="tc4-nav" class="navbar navbar-light fixed-top <?php echo esc_attr($navclass); ?>">
    <div class="container-fluid">
        <div id="tc4-logo" class="navbar-brand mr-auto">
            <a class="<?php echo esc_attr($logoclass); ?>" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <?php if ($show_stamp) : ?>
                    <img class="tc4-logostamp-image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/stamp-shadow.png'); ?>" alt="" aria-hidden="true">
                <?php endif; ?>
                <?php if ($custom_logo_url) : ?>
                    <img class="eigenlogo" src="<?php echo esc_url($custom_logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <?php else : ?>
                    <img class="scoutinglogo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/snl-logo.svg'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <?php endif; ?>
            </a>
        </div>

        <div id="tc4-headermessage" class="flex-grow-1">
            <?php $msg = get_theme_mod('scoutingtc4_header_message', ''); $url = get_theme_mod('scoutingtc4_header_message_url', ''); ?>
            <?php if (get_theme_mod('scoutingtc4_show_header_message', 1) && $msg) : ?>
                <a class="tc4-header-alert text-danger" href="<?php echo esc_url($url ?: home_url('/')); ?>"><?php echo esc_html($msg); ?> <i class="fas fa-exclamation-triangle" aria-hidden="true"></i></a>
            <?php endif; ?>
        </div>

        <div id="tc4-buttons">
            <?php if (get_theme_mod('scoutingtc4_show_phone', 1) && ($phone = get_theme_mod('scoutingtc4_phone', ''))) : ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="phone" aria-label="<?php esc_attr_e('Bellen', 'scoutingtc4'); ?>">
                    <i class="fas fa-phone tc4-fa-icon" aria-hidden="true"></i>
                </a>
            <?php endif; ?>
            <?php if (get_theme_mod('scoutingtc4_show_contact', 1) && ($contact = get_theme_mod('scoutingtc4_contact_url', ''))) : ?>
                <a href="<?php echo esc_url($contact); ?>" class="contact" aria-label="<?php esc_attr_e('Contact', 'scoutingtc4'); ?>">
                    <i class="fas fa-envelope tc4-fa-icon" aria-hidden="true"></i>
                </a>
            <?php endif; ?>
            <?php if (get_theme_mod('scoutingtc4_show_search', 1)) :
                $search_url = get_theme_mod('scoutingtc4_search_url', ''); ?>
                <a href="<?php echo esc_url($search_url ?: home_url('/?s=')); ?>" class="search" aria-label="<?php esc_attr_e('Zoeken', 'scoutingtc4'); ?>">
                    <i class="fas fa-search tc4-fa-icon tc4-fa-icon-search" aria-hidden="true"></i>
                </a>
            <?php endif; ?>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="offcanvas" data-bs-target="#tc4MobileOffcanvas" aria-controls="tc4MobileOffcanvas" aria-expanded="false" aria-label="<?php esc_attr_e('Navigatie tonen', 'scoutingtc4'); ?>">
                <i id="tc4-navbar-toggler-icon" class="fa fa-bars tc4-fa-icon" aria-hidden="true"></i><span class="tc4-menu-label"><?php esc_html_e('Menu', 'scoutingtc4'); ?></span>
            </button>
        </div>

        <div class="tc4-menu collapse navbar-collapse" id="navbarSupportedContent">
            <?php wp_nav_menu(array('theme_location'=>'primary', 'container'=>false, 'menu_class'=>'navbar-nav ms-auto mb-2 mb-lg-0', 'fallback_cb'=>'__return_false', 'walker'=>new ScoutingTC4_Bootstrap_Navwalker())); ?>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-end tc4-mobile-offcanvas" tabindex="-1" id="tc4MobileOffcanvas" aria-labelledby="tc4MobileOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="tc4MobileOffcanvasLabel"><?php bloginfo('name'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e('Sluiten', 'scoutingtc4'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <?php $mobile_msg = get_theme_mod('scoutingtc4_header_message', ''); $mobile_msg_url = get_theme_mod('scoutingtc4_header_message_url', ''); ?>
        <?php if (get_theme_mod('scoutingtc4_show_header_message', 1) && $mobile_msg) : ?>
            <div class="tc4-mobile-message">
                <a href="<?php echo esc_url($mobile_msg_url ?: home_url('/')); ?>">
                    <?php echo esc_html($mobile_msg); ?> <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                </a>
            </div>
        <?php endif; ?>

        <nav class="tc4-mobile-menu" aria-label="<?php esc_attr_e('Mobiele navigatie', 'scoutingtc4'); ?>">
            <?php wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'tc4-mobile-nav',
                'fallback_cb'    => '__return_false'
            )); ?>
        </nav>

        <div class="tc4-mobile-icons">
            <?php if (get_theme_mod('scoutingtc4_show_phone', 1) && ($mobile_phone = get_theme_mod('scoutingtc4_phone', ''))) : ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $mobile_phone)); ?>" class="phone" aria-label="<?php esc_attr_e('Bellen', 'scoutingtc4'); ?>">
                    <i class="fas fa-phone" aria-hidden="true"></i>
                    <span><?php esc_html_e('Bellen', 'scoutingtc4'); ?></span>
                </a>
            <?php endif; ?>

            <?php if (get_theme_mod('scoutingtc4_show_contact', 1) && ($mobile_contact = get_theme_mod('scoutingtc4_contact_url', ''))) : ?>
                <a href="<?php echo esc_url($mobile_contact); ?>" class="contact" aria-label="<?php esc_attr_e('Contact', 'scoutingtc4'); ?>">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <span><?php esc_html_e('Contact', 'scoutingtc4'); ?></span>
                </a>
            <?php endif; ?>

            <?php if (get_theme_mod('scoutingtc4_show_search', 1)) :
                $mobile_search_url = get_theme_mod('scoutingtc4_search_url', ''); ?>
                <a href="<?php echo esc_url($mobile_search_url ?: home_url('/?s=')); ?>" class="search" aria-label="<?php esc_attr_e('Zoeken', 'scoutingtc4'); ?>">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <span><?php esc_html_e('Zoeken', 'scoutingtc4'); ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="mainbody"><div class="content">
