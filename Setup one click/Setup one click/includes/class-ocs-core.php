<?php
/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Set_Up_One_Click
 * @author     _atikhosen
 *
 * FILE: includes/class-ocs-core.php
 */
declare(strict_types=1);

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main plugin class to prevent conflicts with other plugins.
 * 
 * All hooks, options, and CSS/JS handles use 'set_up_one_click' prefix.
 * All CSS classes and IDs use 'ocs-' prefix to avoid conflicts with themes.
 */
class Set_Up_One_Click_Core {

    protected string $plugin_name;
    protected string $version;

    public function __construct() {
        $this->plugin_name = 'set-up-one-click';
        $this->version = SET_UP_ONE_CLICK_VERSION;
    }

    public function run(): void {
        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    private function load_dependencies(): void {
        $ajax_file = SET_UP_ONE_CLICK_PLUGIN_DIR . 'includes/class-ocs-ajax.php';
        $recipe_handler_file = SET_UP_ONE_CLICK_PLUGIN_DIR . 'includes/class-ocs-recipe-handler.php';

        if (file_exists($ajax_file)) {
            require_once $ajax_file;
        }
        if (file_exists($recipe_handler_file)) {
            require_once $recipe_handler_file;
        }
    }

    private function define_admin_hooks(): void {
        add_action( 'admin_menu', [$this, 'add_plugin_admin_menu'] );
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_styles_and_scripts'] );
        add_action( 'admin_init', [$this, 'handle_export'] );
        add_action( 'admin_notices', [$this, 'hide_other_admin_notices'], 1 );
        add_action( 'admin_notices', [$this, 'maybe_show_review_prompt'], 999 );

        if (class_exists('Set_Up_One_Click_Ajax')) {
            $ajax_handler = new Set_Up_One_Click_Ajax();
            add_action('wp_ajax_set_up_one_click_save_recipe', [$ajax_handler, 'save_recipe']);
            add_action('wp_ajax_set_up_one_click_execute_task', [$ajax_handler, 'execute_task']);
            add_action('wp_ajax_set_up_one_click_import_recipe', [$ajax_handler, 'import_recipe']);
            add_action('wp_ajax_set_up_one_click_set_review_prompt', [$ajax_handler, 'set_review_prompt']);
            add_action('wp_ajax_set_up_one_click_dismiss_review_prompt', [$ajax_handler, 'dismiss_review_prompt']);
            add_action('wp_ajax_set_up_one_click_search_plugins', [$ajax_handler, 'search_plugins']);
            add_action('wp_ajax_set_up_one_click_get_bulk_templates', [$ajax_handler, 'get_bulk_templates']);
            add_action('wp_ajax_set_up_one_click_create_bulk_content', [$ajax_handler, 'create_bulk_content']);
            add_action('wp_ajax_set_up_one_click_get_lms_plugins', [$ajax_handler, 'get_lms_plugins']);
            add_action('wp_ajax_set_up_one_click_get_courses_for_lms', [$ajax_handler, 'get_courses_for_lms']);
            add_action('wp_ajax_set_up_one_click_get_membership_plugins', [$ajax_handler, 'get_membership_plugins']);
            add_action('wp_ajax_set_up_one_click_get_memberships_for_plugin', [$ajax_handler, 'get_memberships_for_plugin_ajax']);
        }
    }

    public function add_plugin_admin_menu(): void {
        add_menu_page(
            esc_html__( 'Set up one click', 'set-up-one-click' ),
            esc_html__( 'Set up one click', 'set-up-one-click' ),
            'manage_options',
            $this->plugin_name,
            [$this, 'display_plugin_setup_page'],
            'dashicons-controls-play',
            25
        );
    }
    
    public function handle_export(): void {
        // Verify request method
        if ( 'GET' !== $_SERVER['REQUEST_METHOD'] ) {
            return;
        }

        // Check for both action and nonce before processing
        if ( ! isset( $_GET['action'], $_GET['nonce'] ) ) {
            return;
        }

        $action = sanitize_text_field( wp_unslash( $_GET['action'] ) );
        $nonce  = sanitize_text_field( wp_unslash( $_GET['nonce'] ) );

        // Verify action
        if ( 'set_up_one_click_export_recipe' !== $action ) {
            return;
        }

        // Check capability BEFORE verifying nonce to prevent timing attacks
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Permission Denied', 'set-up-one-click' ) );
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $nonce, 'set_up_one_click_export_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed', 'set-up-one-click' ) );
        }

        // Get recipe data
        $recipe_data = get_option( 'set_up_one_click_saved_recipe', [] );

        // Set proper headers for JSON file download
        header( 'Content-Type: application/json; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="set-up-one-click-recipe.json"' );
        header( 'Expires: 0' );
        header( 'Cache-Control: must-revalidate' );
        header( 'Pragma: public' );

        // Output JSON-encoded data
        echo wp_json_encode( $recipe_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
        exit;
    }

    public function display_plugin_setup_page(): void {
        $saved_recipe = get_option('set_up_one_click_saved_recipe', []);
        $defaults = [
            'cleanup'   => ['delete_post', 'delete_page', 'delete_hello_dolly', 'delete_default_comment'],
            'permalink' => '/%postname%/',
            'settings'  => ['disable_pingbacks', 'comment_moderation', 'comment_approval', 'disable_xml_rpc'],
            'content'   => ['create_primary_menu'],
            'plugins'   => [],
            'utilities' => [],
            'builders' => [],
            'builder_addons' => [],
            'community' => [],
            'import_system' => [],
            'forms' => [],
            'performance' => [],
            'filters' => [],
            'miscellaneous' => [],
            'login_register' => [],
            'lms' => [],
            'memberships' => [],
            'multilingual' => [],
            'seo plugins' => [],
            'Backup plugins' => [],
            'others' => [],
            'theme'     => ''
        ];
        $recipe_data = wp_parse_args($saved_recipe, $defaults);
        update_option('set_up_one_click_saved_recipe', $recipe_data);

        require_once SET_UP_ONE_CLICK_PLUGIN_DIR . 'templates/recipe-builder-page.php';
    }

    public function enqueue_styles_and_scripts( string $hook ): void {
        if ( 'toplevel_page_' . $this->plugin_name !== $hook ) {
            return;
        }
        wp_enqueue_style( $this->plugin_name, SET_UP_ONE_CLICK_PLUGIN_URL . 'assets/css/ocs-admin-styles.css', [], $this->version, 'all' );
        wp_enqueue_script( $this->plugin_name, SET_UP_ONE_CLICK_PLUGIN_URL . 'assets/js/ocs-admin-scripts.js', [ 'jquery' ], $this->version, true );
        
        wp_localize_script( $this->plugin_name, 'set_up_one_click_ajax_object', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'set_up_one_click_ajax_nonce' ),
            'i18n'     => [
                'confirm_deploy'              => esc_html__( 'Are you sure you want to deploy this recipe? This will perform the saved actions on your site.', 'set-up-one-click' ),
                'saving'                      => esc_html__( 'Saving...', 'set-up-one-click' ),
                'error_prefix'                => esc_html__( 'Error: ', 'set-up-one-click' ),
                'no_tasks'                    => esc_html__( 'No tasks selected in the recipe!', 'set-up-one-click' ),
                'plugin_limit_exceeded'       => esc_html__( 'You can select a maximum of 5 plugins to install.', 'set-up-one-click' ),
                'all_tasks_complete'          => esc_html__( 'All tasks completed successfully!', 'set-up-one-click' ),
                'deployment_halted_error'     => esc_html__( 'Deployment halted due to an error.', 'set-up-one-click' ),
                'ajax_error_prefix'           => esc_html__( 'AJAX ERROR: ', 'set-up-one-click' ),
                'server_error'                => esc_html__( 'Deployment halted due to a server error.', 'set-up-one-click' ),
                'import_error'                => esc_html__( 'Could not import settings. The file may be invalid.', 'set-up-one-click' ),
                'import_success'              => esc_html__( 'Settings imported successfully! The page will now reload.', 'set-up-one-click' ),
                'you_may_close'               => esc_html__( 'You may now close this window.', 'set-up-one-click' ),
                'review_thanks'               => esc_html__( 'Thanks! If you enjoy Set up one click, please consider leaving a 5-star review.', 'set-up-one-click' ),
                'found'                       => esc_html__( 'Found', 'set-up-one-click' ),
                'no_plugins_found'            => esc_html__( 'No plugins found. Try another search.', 'set-up-one-click' ),
                'search_error'                => esc_html__( 'An error occurred while searching. Please try again.', 'set-up-one-click' ),
                'no_lms_plugins'              => esc_html__( 'No active LMS plugins found. Please install and activate an LMS plugin first.', 'set-up-one-click' ),
                'no_membership_plugins'       => esc_html__( 'No active membership plugins found. Please install and activate a membership plugin first.', 'set-up-one-click' ),
            ],
        ] );

        // Review prompt detection via MutationObserver (no inline PHP/JS mixing)
        wp_add_inline_script(
            $this->plugin_name,
            <<<'JAVASCRIPT'
(function() {
    'use strict';
    if (window.ocsReviewPromptHooked) { return; }
    window.ocsReviewPromptHooked = true;
    
    var cfg = window.set_up_one_click_ajax_object || {};
    var i18n = (cfg && cfg.i18n) || {};
    var ajaxUrl = cfg.ajax_url;
    var nonce = cfg.nonce;
    
    if (!ajaxUrl || !nonce || !i18n || !i18n.all_tasks_complete) { return; }
    
    var fired = false;
    var checkDelay = null;
    
    var check = function() {
        clearTimeout(checkDelay);
        checkDelay = setTimeout(function() {
            var titleEl = document.querySelector('.ocs-modal-title');
            if (!titleEl) { return; }
            var txt = (titleEl.textContent || '').trim();
            if (!fired && txt.indexOf(i18n.all_tasks_complete) !== -1) {
                fired = true;
                var fd = new FormData();
                fd.append('action', 'set_up_one_click_set_review_prompt');
                fd.append('nonce', nonce);
                fetch(ajaxUrl, { method: 'POST', credentials: 'same-origin', body: fd });
            }
        }, 100);
    };
    
    var startObserve = function() {
        var mo = new MutationObserver(check);
        mo.observe(document.body, { subtree: true, childList: true });
        check();
    };
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startObserve, { once: true });
    } else {
        startObserve();
    }
})();
JAVASCRIPT
            ,
            'after'
        );
    }

    /**
     * Hide all other admin notices on the plugin page.
     * Only show Set Up One Click notices.
     * 
     * Uses proper WordPress hooks instead of direct global manipulation.
     */
    public function hide_other_admin_notices(): void {
        $screen = get_current_screen();
        // Only on our plugin page
        if ( ! isset( $screen->id ) || $screen->id !== 'toplevel_page_set-up-one-click' ) {
            return;
        }
        
        // Remove all non-plugin notices using proper WordPress functions
        remove_all_actions( 'admin_notices' );
        add_action( 'admin_notices', [ $this, 'maybe_show_review_prompt' ], 999 );
    }

    /**
     * Show a friendly review prompt once after a successful run.
     */
    public function maybe_show_review_prompt(): void {
        $screen = get_current_screen();
        // Only show on our plugin page
        if ( isset($screen->id) && $screen->id !== 'toplevel_page_set-up-one-click' ) {
            return;
        }
        
        if ( get_option('set_up_one_click_show_review_prompt') ) {
            ?>
            <div class="notice notice-success is-dismissible ocs-review-notice">
                <p>
                    <strong><?php esc_html_e('Congratulations! You are ready to start building!', 'set-up-one-click'); ?></strong>
                    <?php esc_html_e('If Set up one click saved you time, drop a quick 5-star review — it helps us keep improving.', 'set-up-one-click'); ?>
                    <a href="https://github.com/atikhosenpro/set-up-one-click" target="_blank" rel="noopener noreferrer" class="" style="margin-left:8px;">
                        <?php esc_html_e('Star on GitHub', 'set-up-one-click'); ?>
                    </a>
                </p>
            </div>
            <script>
            (function(){
                // Auto-dismiss flag when the notice is dismissed by the user
                document.addEventListener('click', function(e){
                    if ( e.target && e.target.closest('.notice.is-dismissible .notice-dismiss') ) {
                        var fd = new FormData();
                        fd.append('action','set_up_one_click_dismiss_review_prompt');
                        fd.append('nonce','<?php echo esc_js( wp_create_nonce('set_up_one_click_ajax_nonce') ); ?>');
                        fetch('<?php echo esc_url( admin_url('admin-ajax.php') ); ?>', { method: 'POST', credentials: 'same-origin', body: fd });
                    }
                }, { passive: true });
            })();
            </script>
            <?php
        }
    }
}