<?php
/**
 * Displays the primary sidebar when it contains widgets.
 *
 * @package Scouting_Nederland
 */

if ( is_active_sidebar( 'sidebar' ) ) : ?>
<aside class="col-md-4 col-lg-3 scouting-nederland-layout-sidebar"><?php dynamic_sidebar( 'sidebar' ); ?></aside>
<?php endif; ?>
