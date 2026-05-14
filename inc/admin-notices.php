<?php
if (!defined('ABSPATH')) { exit; }

function scoutingtc4_is_scouting_openid_connect_active() {
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

function scoutingtc4_scouting_openid_connect_notice() {
    if (!is_admin() || !current_user_can('install_plugins')) {
        return;
    }

    if (get_user_meta(get_current_user_id(), 'scoutingtc4_openid_notice_seen', true)) {
        return;
    }

    if (scoutingtc4_is_scouting_openid_connect_active()) {
        update_user_meta(get_current_user_id(), 'scoutingtc4_openid_notice_seen', 1);
        return;
    }

    update_user_meta(get_current_user_id(), 'scoutingtc4_openid_notice_seen', 1);

    $install_url = wp_nonce_url(
        self_admin_url('update.php?action=install-plugin&plugin=scouting-openid-connect'),
        'install-plugin_scouting-openid-connect'
    );
    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <strong><?php esc_html_e('Scouting login beschikbaar', 'scoutingtc4'); ?></strong><br>
            <?php esc_html_e('Je kunt de plugin Scouting OpenID Connect installeren om gebruikers via Scouting te laten inloggen.', 'scoutingtc4'); ?>
        </p>
        <p>
            <a class="button button-primary" href="<?php echo esc_url($install_url); ?>">
                <?php esc_html_e('Scouting OpenID Connect direct installeren', 'scoutingtc4'); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'scoutingtc4_scouting_openid_connect_notice');
