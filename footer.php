</div></div>

<footer id="tc4-footer" role="contentinfo">
    <div class="tc4-footer-grass" aria-hidden="true"></div>
                    <div class="wing">
                        <div class="bit"></div>
                        <div class="bit"></div>
                    </div>
                </div>
                <div class="shadow"></div>
            </div>
        </div>
    </div>

    <div class="tc4-footer-main">
        <div class="tc4-footer-inner">
            <div class="tc4-footer-left">
                <div id="social-media" class="tc4-footer-social">
                    <h5><?php esc_html_e('Vind Scouting op:', 'scoutingtc4'); ?></h5>
                    <ul>
                        <?php
                        $socials = array(
                            'facebook'  => array('icon' => 'fab fa-facebook-f', 'label' => 'Facebook'),
                            'linkedin'  => array('icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'),
                            'youtube'   => array('icon' => 'fab fa-youtube', 'label' => 'YouTube'),
                            'instagram' => array('icon' => 'fab fa-instagram', 'label' => 'Instagram'),
                        );
                        foreach ($socials as $network => $data) :
                            $url = get_theme_mod('scoutingtc4_' . $network . '_url', '');
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

                <div id="copyright" class="tc4-footer-copyright">
                    <p>Copyright &copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?></p>
                    <p><?php printf(esc_html__('Dit is de officiële website van de vereniging %s.', 'scoutingtc4'), esc_html(get_bloginfo('name'))); ?></p>
                    <p><?php esc_html_e('Nederland.', 'scoutingtc4'); ?></p>
                </div>
            </div>

            <?php if (is_active_sidebar('footer-partners')) : ?>
                <div class="tc4-footer-right tc4-footer-partners-widget-area">
                    <?php dynamic_sidebar('footer-partners'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (has_nav_menu('footer')) : ?>
        <div class="tc4-footer-bottom">
            <div class="tc4-footer-bottom-inner">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'tc4-footer-menu',
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
