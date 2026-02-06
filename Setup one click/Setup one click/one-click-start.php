<?php
/**
 * Plugin Name:       Set up one click
 * Plugin URI:        https://github.com/atikhosenpro/set-up-one-click/
 * Description:       A simple, reliable tool to automate your initial WordPress setup tasks.
 * Version:           1.0.2
 * Author:            Atik Hosen
 * Author URI:        https://github.com/atikhosenpro/
 * License:           GPL v2 or later
 * Text Domain:       set-up-one-click
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP:      7.4
 *
 * FILE: one-click-start.php (Main Plugin File)
 * 
 * CONFLICT PREVENTION:
 * - PHP Classes: Set_Up_One_Click_* prefix
 * - WordPress Hooks: set_up_one_click_* prefix  
 * - Options: set_up_one_click_* prefix
 * - CSS Classes/IDs: ocs-* prefix
 * - JavaScript: Wrapped in IIFE, uses wp_localize_script for AJAX
 * - Database: Uses unique option names with plugin prefix
 *
 * This plugin is designed to work alongside any theme or other plugins
 * without conflicts or interference.
 * 
 * Version 1.0.2 - Security & Performance Release
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Early version/compatibility check
if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
    add_action(
        'admin_notices',
        static function() {
            printf(
                '<div class="notice notice-error"><p>%s</p></div>',
                esc_html__( 'Set up one click requires PHP 7.4 or later. Please upgrade your PHP version.', 'set-up-one-click' )
            );
        }
    );
    return;
}

define( 'SET_UP_ONE_CLICK_VERSION', '1.0.2' );
define( 'SET_UP_ONE_CLICK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SET_UP_ONE_CLICK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SET_UP_ONE_CLICK_PLUGIN_FILE', __FILE__ );

/**
 * The code that runs during plugin activation.
 * Sets default options with proper autoload settings for performance.
 * 
 * @since 1.0.0
 */
function set_up_one_click_activate() {
    // Migrate old option names to new ones
    $old_recipe = get_option( 'one_click_start_saved_recipe' );
    if ( $old_recipe && false === get_option( 'set_up_one_click_saved_recipe' ) ) {
        add_option( 'set_up_one_click_saved_recipe', $old_recipe, '', 'no' );
    }
    
    $old_prompt = get_option( 'one_click_start_show_review_prompt' );
    if ( $old_prompt && false === get_option( 'set_up_one_click_show_review_prompt' ) ) {
        add_option( 'set_up_one_click_show_review_prompt', $old_prompt, '', 'no' );
    }
    
    if ( false === get_option( 'set_up_one_click_saved_recipe' ) ) {
        $defaults = [
            'cleanup'            => [ 'delete_post', 'delete_page', 'delete_hello_dolly', 'delete_default_comment' ],
            'permalink'          => '/%postname%/',
            'settings'           => [ 'disable_pingbacks', 'comment_moderation', 'comment_approval', 'disable_xml_rpc' ],
            'content'            => [ 'create_primary_menu' ],
            'plugins'            => [],
            'utilities'          => [],
            'code_snippets'      => [],
            'builders'           => [],
            'builder_addons'     => [],
            'community'          => [],
            'import_system'      => [],
            'forms'              => [],
            'performance'        => [],
            'filters'            => [],
            'miscellaneous'      => [],
            'login_register'     => [],
            'lms'                => [],
            'memberships'        => [],
            'multilingual'       => [],
            'seo plugins'        => [],
            'Backup plugins'     => [],
            'Booking plugins'    => [],
            'others'             => [],
            'theme'              => '',
        ];
        // Use autoload=no since recipe is only needed in admin
        add_option( 'set_up_one_click_saved_recipe', $defaults, '', 'no' );
    }
}
register_activation_hook( __FILE__, 'set_up_one_click_activate' );

$core_file = plugin_dir_path( __FILE__ ) . 'includes/class-ocs-core.php';
if ( file_exists( $core_file ) ) {
    require_once $core_file;
}

/**
 * Initialize the plugin.
 * 
 * @since 1.0.0
 */
function set_up_one_click_run() {
    if ( class_exists( 'Set_Up_One_Click_Core' ) ) {
        $plugin = new Set_Up_One_Click_Core();
        $plugin->run();
    }
}
set_up_one_click_run();

/**
 * Displays an admin notice on the plugin page in a Multisite environment for non-Super Admins.
 * 
 * @since 1.0.0
 */
function set_up_one_click_multisite_admin_notice() {
    if ( is_multisite() && ! is_network_admin() ) {
        $screen = get_current_screen();
        if ( isset( $screen->id ) && $screen->id === 'toplevel_page_set-up-one-click' ) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><strong><?php esc_html_e( 'Set up one click Notice:', 'set-up-one-click' ); ?></strong> <?php esc_html_e( 'This plugin has limited functionality on a Multisite sub-site. Plugin and theme installation/deletion can only be performed by a Super Admin.', 'set-up-one-click' ); ?></p>
            </div>
            <?php
        }
    }
}
add_action( 'admin_notices', 'set_up_one_click_multisite_admin_notice', 999 );
