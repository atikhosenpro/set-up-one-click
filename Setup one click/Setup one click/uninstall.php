<?php
/**
 * Fired when the plugin is uninstalled.
 * Cleans up the database option.
 *
 * FILE: uninstall.php
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete all plugin-related options to ensure clean database
delete_option( 'set_up_one_click_saved_recipe' );
delete_option( 'set_up_one_click_show_review_prompt' );