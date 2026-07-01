<?php
if (!defined('ABSPATH')) { exit; }

function scoutingNederlandIsScoutingOpenidConnectActive() {
    if (!function_exists('is_plugin_active')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $active_plugins = (array) get_option('active_plugins', array());

    if (is_multisite()) {
        $active_plugins = array_merge($active_plugins, array_keys((array) get_site_option('active_sitewide_plugins', array())));
    }

    foreach ($active_plugins as $plugin_file) {
        if (strpos($plugin_file, 'scouting-openid-connect/') === 0) {
            return true;
        }
    }

    return false;
}

function scoutingNederlandScoutingOpenidConnectNotice() {
    if (!is_admin() || !current_user_can('install_plugins')) {
        return;
    }

    if (get_user_meta(get_current_user_id(), 'scouting_nederland_openid_notice_dismissed', true)) {
        return;
    }

    if (scoutingNederlandIsScoutingOpenidConnectActive()) {
        update_user_meta(get_current_user_id(), 'scouting_nederland_openid_notice_dismissed', 1);
        return;
    }

    $install_url = wp_nonce_url(
        self_admin_url('update.php?action=install-plugin&plugin=scouting-openid-connect'),
        'install-plugin_scouting-openid-connect'
    );
    $dismiss_url = wp_nonce_url(
        self_admin_url('admin-post.php?action=scouting_nederland_dismiss_openid_notice'),
        'scouting_nederland_dismiss_openid_notice'
    );
    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <strong><?php esc_html_e('Scouting login beschikbaar', 'scouting-nederland'); ?></strong><br>
            <?php esc_html_e('Installeer optioneel de plugin Scouting OpenID Connect om gebruikers via Scouting te laten inloggen.', 'scouting-nederland'); ?>
        </p>
        <p>
            <a class="button button-primary" href="<?php echo esc_url($install_url); ?>">
                <?php esc_html_e('Scouting OpenID Connect direct installeren', 'scouting-nederland'); ?>
            </a>
            <a class="button button-link" href="<?php echo esc_url($dismiss_url); ?>">
                <?php esc_html_e('Niet meer tonen', 'scouting-nederland'); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'scoutingNederlandScoutingOpenidConnectNotice');

function scoutingNederlandDismissOpenidNotice() {
    if (!current_user_can('install_plugins')) {
        wp_die(esc_html__('Je hebt geen rechten om deze melding te verbergen.', 'scouting-nederland'));
    }

    check_admin_referer('scouting_nederland_dismiss_openid_notice');
    update_user_meta(get_current_user_id(), 'scouting_nederland_openid_notice_dismissed', 1);

    wp_safe_redirect(wp_get_referer() ?: self_admin_url('themes.php'));
    exit;
}
add_action('admin_post_scouting_nederland_dismiss_openid_notice', 'scoutingNederlandDismissOpenidNotice');
