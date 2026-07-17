<footer id="scouting-nederland-footer" role="contentinfo">
    <div class="scouting-nederland-footer-grass" aria-hidden="true"></div>

    <div class="scouting-nederland-footer-main">
        <div class="scouting-nederland-footer-inner">
            <div class="scouting-nederland-footer-left">
                <div id="social-media" class="scouting-nederland-footer-social">
                    <h5><?php esc_html_e('Vind Scouting op:', 'scouting-nederland'); ?></h5>
                    <ul>
                        <?php
                        $socials = array(
                            'facebook'  => array('icon' => 'fab fa-facebook-f', 'label' => 'Facebook'),
                            'linkedin'  => array('icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'),
                            'youtube'   => array('icon' => 'fab fa-youtube', 'label' => 'YouTube'),
                            'instagram' => array('icon' => 'fab fa-instagram', 'label' => 'Instagram'),
                            'tiktok'    => array('icon' => 'fab fa-tiktok', 'label' => 'TikTok'),
                        );
                        foreach ($socials as $network => $data) :
                            $url = get_theme_mod('scouting_nederland_' . $network . '_url', '');
                            if (!$url) {
                                continue;
                            }
                        ?>
                            <li>
                                <a href="<?php echo esc_url($url); ?>" aria-label="<?php echo esc_attr($data['label']); ?>">
                                    <i class="<?php echo esc_attr($data['icon']); ?>" aria-hidden="true"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div id="copyright" class="scouting-nederland-footer-copyright">
                    <p>Copyright &copy; <?php echo esc_html(wp_date('Y')); ?> <?php bloginfo('name'); ?></p>
                    <p><?php printf(esc_html__('Dit is de officiële website van de vereniging %s.', 'scouting-nederland'), esc_html(get_bloginfo('name'))); ?></p>
                </div>
            </div>

            <?php if (is_active_sidebar('footer-partners')) : ?>
                <div class="scouting-nederland-footer-right scouting-nederland-footer-partners-widget-area">
                    <?php dynamic_sidebar('footer-partners'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (has_nav_menu('footer')) : ?>
        <div class="scouting-nederland-footer-bottom">
            <div class="scouting-nederland-footer-bottom-inner">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'scouting-nederland-footer-menu',
                    'fallback_cb'    => '__return_false',
                    'depth'          => 1,
                ));
                ?>
            </div>
        </div>
    <?php endif; ?>
</footer>

<?php wp_footer(); ?>
</body></html>

