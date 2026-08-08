<?php
/**
 * Search form template.
 *
 * @package Scouting_Nederland
 */

if (!defined('ABSPATH')) { exit; }
?>
<?php $field_id = wp_unique_id('scouting-nederland-search-field-'); ?>
<form role="search" method="get" class="scouting-nederland-search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="<?php echo esc_attr($field_id); ?>"><?php esc_html_e('Zoeken naar:', 'scouting-nederland'); ?></label>
    <input
        id="<?php echo esc_attr($field_id); ?>"
        type="search"
        class="scouting-nederland-search-form-input"
        placeholder="<?php echo esc_attr__('Waar ben je naar op zoek?', 'scouting-nederland'); ?>"
        value="<?php echo esc_attr(get_search_query()); ?>"
        name="s"
    />
    <button type="submit" class="scouting-nederland-search-form-button">
        <i class="fas fa-search" aria-hidden="true"></i>
        <span><?php esc_html_e('Zoeken', 'scouting-nederland'); ?></span>
    </button>
</form>
