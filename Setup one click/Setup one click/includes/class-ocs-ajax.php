<?php
/**
 * Handles all AJAX requests for the plugin.
 *
 * @since      1.0.0
 * @package    Set_Up_One_Click
 * @author     _atikhosen
 *
 * FILE: includes/class-ocs-ajax.php
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AJAX Handler class for Set Up One Click plugin.
 * 
 * All AJAX actions use 'set_up_one_click_' prefix to avoid conflicts.
 * All requests are verified with nonce and capability checks.
 */
class Set_Up_One_Click_Ajax {

    public function save_recipe(): void {
        // Verify nonce with proper error handling
        check_ajax_referer( 'set_up_one_click_ajax_nonce', 'nonce' );
        
        // Check capability
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'Permission denied.', 'set-up-one-click' ) ] );
        }

        // Validate and get form data
        if ( ! isset( $_POST['form_data'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Missing form data.', 'set-up-one-click' ) ] );
        }

        $form_data_raw = is_string( $_POST['form_data'] ) ? wp_unslash( $_POST['form_data'] ) : '';
        if ( empty( $form_data_raw ) ) {
            wp_send_json_error( [ 'message' => __( 'Missing form data.', 'set-up-one-click' ) ] );
        }

        parse_str( $form_data_raw, $form_data );

        $sanitized_data = [
            'cleanup'                => isset( $form_data['cleanup'] ) && is_array( $form_data['cleanup'] ) ? array_map( 'sanitize_text_field', array_slice( $form_data['cleanup'], 0, 20 ) ) : [],
            'permalink'              => isset( $form_data['permalink'] ) ? sanitize_text_field( $form_data['permalink'] ) : '',
            'settings'               => isset( $form_data['settings'] ) && is_array( $form_data['settings'] ) ? array_map( 'sanitize_text_field', array_slice( $form_data['settings'], 0, 50 ) ) : [],
            'content'                => isset( $form_data['content'] ) && is_array( $form_data['content'] ) ? array_map( 'sanitize_text_field', array_slice( $form_data['content'], 0, 50 ) ) : [],
            'plugins'                => isset( $form_data['plugins'] ) && is_array( $form_data['plugins'] ) ? array_map( 'sanitize_text_field', array_slice( $form_data['plugins'], 0, 100 ) ) : [],
            'code_snippets'          => isset( $form_data['code_snippets'] ) && is_array( $form_data['code_snippets'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['code_snippets'], 0, 100 ) ) : [],
            'utilities'              => isset( $form_data['utilities'] ) && is_array( $form_data['utilities'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['utilities'], 0, 100 ) ) : [],
            'builders'               => isset( $form_data['builders'] ) && is_array( $form_data['builders'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['builders'], 0, 100 ) ) : [],
            'builder_addons'         => isset( $form_data['builder_addons'] ) && is_array( $form_data['builder_addons'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['builder_addons'], 0, 100 ) ) : [],
            'community'              => isset( $form_data['community'] ) && is_array( $form_data['community'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['community'], 0, 100 ) ) : [],
            'import_system'          => isset( $form_data['import_system'] ) && is_array( $form_data['import_system'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['import_system'], 0, 100 ) ) : [],
            'forms'                  => isset( $form_data['forms'] ) && is_array( $form_data['forms'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['forms'], 0, 100 ) ) : [],
            'performance'            => isset( $form_data['performance'] ) && is_array( $form_data['performance'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['performance'], 0, 100 ) ) : [],
            'filters'                => isset( $form_data['filters'] ) && is_array( $form_data['filters'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['filters'], 0, 100 ) ) : [],
            'miscellaneous'          => isset( $form_data['miscellaneous'] ) && is_array( $form_data['miscellaneous'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['miscellaneous'], 0, 100 ) ) : [],
            'login_register'         => isset( $form_data['login_register'] ) && is_array( $form_data['login_register'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['login_register'], 0, 100 ) ) : [],
            'lms'                    => isset( $form_data['lms'] ) && is_array( $form_data['lms'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['lms'], 0, 100 ) ) : [],
            'memberships'            => isset( $form_data['memberships'] ) && is_array( $form_data['memberships'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['memberships'], 0, 100 ) ) : [],
            'multilingual'           => isset( $form_data['multilingual'] ) && is_array( $form_data['multilingual'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['multilingual'], 0, 100 ) ) : [],
            'seo plugins'            => isset( $form_data['seo plugins'] ) && is_array( $form_data['seo plugins'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['seo plugins'], 0, 100 ) ) : [],
            'Backup plugins'         => isset( $form_data['Backup plugins'] ) && is_array( $form_data['Backup plugins'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['Backup plugins'], 0, 100 ) ) : [],
            'Booking plugins'        => isset( $form_data['Booking plugins'] ) && is_array( $form_data['Booking plugins'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['Booking plugins'], 0, 100 ) ) : [],
            'others'                 => isset( $form_data['others'] ) && is_array( $form_data['others'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['others'], 0, 100 ) ) : [],
            'theme'                  => isset( $form_data['theme'] ) ? sanitize_text_field( $form_data['theme'] ) : '',
            'bulk_pages'             => isset( $form_data['bulk_pages'] ) && is_array( $form_data['bulk_pages'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['bulk_pages'], 0, 100 ) ) : [],
            'bulk_posts'             => isset( $form_data['bulk_posts'] ) && is_array( $form_data['bulk_posts'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['bulk_posts'], 0, 100 ) ) : [],
            'bulk_products'          => isset( $form_data['bulk_products'] ) && is_array( $form_data['bulk_products'] ) ? array_map( 'sanitize_text_field', array_slice( (array) $form_data['bulk_products'], 0, 100 ) ) : [],
            'bulk_comments_count'    => isset( $form_data['bulk_comments_count'] ) ? (int) $form_data['bulk_comments_count'] : 0,
            'ocs_selected_lms_plugin' => isset( $form_data['ocs_selected_lms_plugin'] ) ? sanitize_key( $form_data['ocs_selected_lms_plugin'] ) : '',
            'ocs_selected_courses'   => isset( $form_data['ocs_selected_courses'] ) && is_array( $form_data['ocs_selected_courses'] ) ? array_map( 'sanitize_key', array_slice( (array) $form_data['ocs_selected_courses'], 0, 100 ) ) : [],
            'ocs_selected_membership_plugin' => isset( $form_data['ocs_selected_membership_plugin'] ) ? sanitize_key( $form_data['ocs_selected_membership_plugin'] ) : '',
            'ocs_selected_memberships' => isset( $form_data['ocs_selected_memberships'] ) && is_array( $form_data['ocs_selected_memberships'] ) ? array_map( 'sanitize_key', array_slice( (array) $form_data['ocs_selected_memberships'], 0, 100 ) ) : [],
        ];

        update_option( 'set_up_one_click_saved_recipe', $sanitized_data, 'no' );
        wp_send_json_success( [ 'message' => __( 'Recipe saved successfully!', 'set-up-one-click' ) ] );
    }

    public function execute_task(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');

        $raw_task_details = isset( $_POST['task_details'] ) && is_array( $_POST['task_details'] )
        ? array_map( 'sanitize_text_field', wp_unslash( $_POST['task_details'] ) )
        : [];
        
        // Validation and Sanitization.
        if ( empty($raw_task_details) || !is_array($raw_task_details) || !isset($raw_task_details['task']) || !isset($raw_task_details['value']) ) {
            wp_send_json_error(['message' => __( 'Invalid task specified.', 'set-up-one-click' )]);
        }

        $task_details = [
            'task'  => sanitize_key($raw_task_details['task']),
            'value' => sanitize_text_field($raw_task_details['value']),
        ];

        $capability_map = [
            'install_plugin' => 'install_plugins',
            'activate_plugin' => 'activate_plugins',
            'install_theme' => 'install_themes',
        ];
        $required_cap = $capability_map[$task_details['task']] ?? 'manage_options';

        if (!current_user_can($required_cap)) {
            /* translators: %s: The required user capability (e.g., 'manage_options'). */
            wp_send_json_error(['message' => sprintf(__( "Permission denied. Requires '%s' capability.", 'set-up-one-click' ), $required_cap)]);
        }

        if (!class_exists('Set_Up_One_Click_Recipe_Handler')) {
            wp_send_json_error(['message' => __( 'Recipe handler class missing.', 'set-up-one-click' )]);
        }

        $handler = new Set_Up_One_Click_Recipe_Handler();
        $result = $handler->execute_single_task($task_details['task'], $task_details['value']);

        if ($result['success']) {
            wp_send_json_success(['message' => $result['message']]);
        } else {
            wp_send_json_error(['message' => $result['message']]);
        }
    }
    
    public function import_recipe(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __( 'Permission denied.', 'set-up-one-click' )]);
        }


        if ( ! isset( $_FILES['import_file'], $_FILES['import_file']['error'], $_FILES['import_file']['tmp_name'], $_FILES['import_file']['name'] ) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK ) {
            wp_send_json_error(['message' => __( 'File upload error.', 'set-up-one-click' )]);
        }


        $sanitized_filename = sanitize_file_name( $_FILES['import_file']['name'] );
        $file_type          = wp_check_filetype( $sanitized_filename, ['json' => 'application/json'] );

        if ( 'json' !== $file_type['ext'] ) {
            wp_send_json_error(['message' => __( 'Invalid file type. Only .json files are allowed.', 'set-up-one-click')]);
        }

        // Size cap: 5 KB (import JSON should be tiny)
        if ( isset( $_FILES['import_file']['size'] ) && (int) $_FILES['import_file']['size'] > 5 * 1024 ) {
            wp_send_json_error( ['message' => __( 'Import file too large. Max size is 5 KB.', 'set-up-one-click' )] );
        }

        // The 'tmp_name' is now safe to use because it passed the isset and UPLOAD_ERR_OK checks.
        // Validate that the temp file is a legitimate upload before getting its contents.
        $tmp_name = $_FILES['import_file']['tmp_name'];
        if ( ! is_uploaded_file( $tmp_name ) ) {
            wp_send_json_error( ['message' => __( 'Invalid file upload.', 'set-up-one-click' )] );
        }
        
        $file_content = file_get_contents( $tmp_name );
        if ( false === $file_content ) {
            wp_send_json_error( ['message' => __( 'Could not read uploaded file.', 'set-up-one-click' )] );
        }
        $data         = json_decode( $file_content, true );

        if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $data ) ) {
            wp_send_json_error( ['message' => __( 'Invalid JSON file.', 'set-up-one-click' )] );
        }

        // Schema validation: only allow expected keys
        $allowed_keys = [ 'cleanup', 'permalink', 'settings', 'content', 'plugins', 'code_snippets', 'utilities', 'builders', 'builder_addons', 'community', 'import_system', 'forms', 'performance', 'filters', 'miscellaneous', 'login_register', 'lms', 'memberships', 'multilingual', 'seo plugins', 'Backup plugins', 'Booking plugins', 'others', 'theme' ];
        foreach ( $data as $k => $_ ) {
            if ( ! in_array( $k, $allowed_keys, true ) ) {
                /* translators: %s: Unexpected JSON key name found in the imported recipe. */
                wp_send_json_error( [ 'message' => sprintf( __( 'Unexpected key: %s', 'set-up-one-click' ), esc_html( (string) $k ) ) ] );
            }
        }

        // Type checks
        $must_be_arrays  = [ 'cleanup', 'settings', 'content', 'plugins', 'code_snippets', 'utilities', 'builders', 'builder_addons', 'community', 'import_system', 'forms', 'performance', 'filters', 'miscellaneous', 'login_register', 'lms', 'memberships', 'multilingual', 'seo plugins', 'Backup plugins', 'Booking plugins', 'others' ];
        foreach ( $must_be_arrays as $k ) {
            if ( isset( $data[ $k ] ) && ! is_array( $data[ $k ] ) ) {
                /* translators: %s: JSON key name that should contain an array in the imported recipe. */
                wp_send_json_error( [ 'message' => sprintf( __( 'Key %s must be an array.', 'set-up-one-click' ), esc_html( $k ) ) ] );
            }
        }
        $must_be_strings = [ 'permalink', 'theme' ];
        foreach ( $must_be_strings as $k ) {
            if ( isset( $data[ $k ] ) && ! is_string( $data[ $k ] ) ) {
                /* translators: %s: JSON key name that should contain a string in the imported recipe. */
                wp_send_json_error( [ 'message' => sprintf( __( 'Key %s must be a string.', 'set-up-one-click' ), esc_html( $k ) ) ] );
            }
        }

        // Bound arrays to reasonable sizes
        $bounded = [ 'cleanup' => 20, 'settings' => 50, 'content' => 50, 'plugins' => 100, 'code_snippets' => 100, 'utilities' => 100, 'builders' => 100, 'builder_addons' => 100, 'community' => 100, 'import_system' => 100, 'forms' => 100, 'performance' => 100, 'filters' => 100, 'miscellaneous' => 100, 'login_register' => 100, 'lms' => 100, 'memberships' => 100, 'multilingual' => 100, 'seo plugins' => 100, 'Backup plugins' => 100, 'Booking plugins' => 100, 'others' => 100 ];
        foreach ( $bounded as $k => $limit ) {
            if ( isset( $data[ $k ] ) && is_array( $data[ $k ] ) && count( $data[ $k ] ) > $limit ) {
                /* translators: %s: JSON section name that exceeded the allowed number of items. */
                wp_send_json_error( [ 'message' => sprintf( __( 'Too many entries for %s.', 'set-up-one-click' ), esc_html( $k ) ) ] );
            }
        }

        // Sanitize values before saving
        $sanitized_data = [
            'cleanup'   => isset( $data['cleanup'] ) ? array_map( 'sanitize_text_field', $data['cleanup'] ) : [],
            'permalink' => isset( $data['permalink'] ) ? sanitize_text_field( $data['permalink'] ) : '',
            'settings'  => isset( $data['settings'] ) ? array_map( 'sanitize_text_field', $data['settings'] ) : [],
            'content'   => isset( $data['content'] ) ? array_map( 'sanitize_text_field', $data['content'] ) : [],
            'plugins'   => isset( $data['plugins'] ) ? array_map( 'sanitize_text_field', $data['plugins'] ) : [],
            'code_snippets' => isset( $data['code_snippets'] ) ? array_map( 'sanitize_text_field', $data['code_snippets'] ) : [],
            'utilities' => isset( $data['utilities'] ) ? array_map( 'sanitize_text_field', $data['utilities'] ) : [],
            'builders' => isset( $data['builders'] ) ? array_map( 'sanitize_text_field', $data['builders'] ) : [],
            'builder_addons' => isset( $data['builder_addons'] ) ? array_map( 'sanitize_text_field', $data['builder_addons'] ) : [],
            'community' => isset( $data['community'] ) ? array_map( 'sanitize_text_field', $data['community'] ) : [],
            'import_system' => isset( $data['import_system'] ) ? array_map( 'sanitize_text_field', $data['import_system'] ) : [],
            'forms' => isset( $data['forms'] ) ? array_map( 'sanitize_text_field', $data['forms'] ) : [],
            'performance' => isset( $data['performance'] ) ? array_map( 'sanitize_text_field', $data['performance'] ) : [],
            'filters' => isset( $data['filters'] ) ? array_map( 'sanitize_text_field', $data['filters'] ) : [],
            'miscellaneous' => isset( $data['miscellaneous'] ) ? array_map( 'sanitize_text_field', $data['miscellaneous'] ) : [],
            'login_register' => isset( $data['login_register'] ) ? array_map( 'sanitize_text_field', $data['login_register'] ) : [],
            'lms' => isset( $data['lms'] ) ? array_map( 'sanitize_text_field', $data['lms'] ) : [],
            'memberships' => isset( $data['memberships'] ) ? array_map( 'sanitize_text_field', $data['memberships'] ) : [],
            'multilingual' => isset( $data['multilingual'] ) ? array_map( 'sanitize_text_field', $data['multilingual'] ) : [],
            'seo plugins' => isset( $data['seo plugins'] ) ? array_map( 'sanitize_text_field', $data['seo plugins'] ) : [],
            'Backup plugins' => isset( $data['Backup plugins'] ) ? array_map( 'sanitize_text_field', $data['Backup plugins'] ) : [],
            'Booking plugins' => isset( $data['Booking plugins'] ) ? array_map( 'sanitize_text_field', $data['Booking plugins'] ) : [],
            'others' => isset( $data['others'] ) ? array_map( 'sanitize_text_field', $data['others'] ) : [],
            'theme'     => isset( $data['theme'] ) ? sanitize_text_field( $data['theme'] ) : '',
        ];
        update_option( 'set_up_one_click_saved_recipe', $sanitized_data );
        wp_send_json_success();
    }

    /**
     * Set a flag to show the review prompt on next admin page load.
     */
    public function set_review_prompt(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __( 'Permission denied.', 'set-up-one-click' )]);
        }
        update_option('set_up_one_click_show_review_prompt', '1');
        wp_send_json_success();
    }

    /**
     * Dismiss the review prompt.
     */
    public function dismiss_review_prompt(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __( 'Permission denied.', 'set-up-one-click' )]);
        }
        delete_option('set_up_one_click_show_review_prompt');
        wp_send_json_success();
    }

    /**
     * Search plugins by keyword.
     */
    public function search_plugins(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __( 'Permission denied.', 'set-up-one-click' )]);
        }

        $search_term = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        if (empty($search_term) || strlen($search_term) < 1) {
            wp_send_json_success(['results' => []]);
        }

        // Get plugin categories
        $popular_plugins = self::get_plugin_categories();

        $results = [];
        $search_term_lower = strtolower($search_term);

        foreach ($popular_plugins as $category => $plugins) {
            foreach ($plugins as $slug => $name) {
                // Search in both slug and name
                if (stripos($slug, $search_term_lower) !== false || stripos($name, $search_term_lower) !== false) {
                    switch ( $category ) {
                        case 'code_snippets':
                            $category_display = 'Code Snippets';
                            break;
                        case 'utilities':
                            $category_display = 'Utilities';
                            break;
                        case 'builders':
                            $category_display = 'Builders';
                            break;
                        case 'builder_addons':
                            $category_display = 'Builder Addons';
                            break;
                        case 'community':
                            $category_display = 'Community';
                            break;
                        case 'import_system':
                            $category_display = 'Import System';
                            break;
                        case 'forms':
                            $category_display = 'Forms';
                            break;
                        case 'performance':
                            $category_display = 'Performance';
                            break;
                        case 'filters':
                            $category_display = 'Filters';
                            break;
                        case 'miscellaneous':
                            $category_display = 'Miscellaneous';
                            break;
                        case 'login_register':
                            $category_display = 'Login & Register';
                            break;
                        case 'lms':
                            $category_display = 'LMS';
                            break;
                        case 'memberships':
                            $category_display = 'Memberships';
                            break;
                        case 'multilingual':
                            $category_display = 'Multilingual';
                            break;
                        case 'seo plugins':
                            $category_display = 'SEO Plugins';
                            break;
                        case 'Backup plugins':
                            $category_display = 'Backup Plugins';
                            break;
                        case 'Booking plugins':
                            $category_display = 'Booking Plugins';
                            break;
                        case 'others':
                            $category_display = 'Others';
                            break;
                        default:
                            $category_display = ucfirst( str_replace( '_', ' ', $category ) );
                    }

                    $results[] = [
                        'slug' => $slug,
                        'name' => $name,
                        'category' => $category,
                        'category_display' => $category_display,
                    ];
                }
            }
        }

        wp_send_json_success(['results' => array_slice($results, 0, 15)]);
    }

    /**
     * Get bulk content templates.
     */
    public function get_bulk_templates(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied.', 'set-up-one-click')]);
        }

        if (!class_exists('Set_Up_One_Click_Bulk_Content')) {
            $bulk_file = SET_UP_ONE_CLICK_PLUGIN_DIR . 'includes/class-ocs-bulk-content.php';
            if (file_exists($bulk_file)) {
                require_once $bulk_file;
            }
        }

        if (!class_exists('Set_Up_One_Click_Bulk_Content')) {
            wp_send_json_error(['message' => __('Bulk content handler not found.', 'set-up-one-click')]);
        }

        $types = isset($_POST['types']) ? json_decode(wp_unslash($_POST['types']), true) : [];
        $templates_data = [];

        // If types is a string (for backward compatibility), convert to array
        if (is_string($types)) {
            $types = [$types];
        }

        foreach ($types as $type) {
            $type = sanitize_text_field($type);
            $templates = [];

            switch ($type) {
                case 'pages':
                    $templates = Set_Up_One_Click_Bulk_Content::get_page_templates();
                    break;
                case 'posts':
                    $templates = Set_Up_One_Click_Bulk_Content::get_post_templates();
                    break;
                case 'products':
                    $templates = Set_Up_One_Click_Bulk_Content::get_product_templates();
                    break;
                case 'comments':
                    $templates = Set_Up_One_Click_Bulk_Content::get_comment_templates();
                    break;
            }

            if (!empty($templates)) {
                $templates_data[$type] = $templates;
            }
        }

        wp_send_json_success([
            'templates' => $templates_data,
        ]);
    }

    /**
     * Get available LMS plugins for course selection.
     */
    public function get_lms_plugins(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied.', 'set-up-one-click')]);
        }

        if (!class_exists('Set_Up_One_Click_Bulk_Content')) {
            $bulk_file = SET_UP_ONE_CLICK_PLUGIN_DIR . 'includes/class-ocs-bulk-content.php';
            if (file_exists($bulk_file)) {
                require_once $bulk_file;
            }
        }

        $available_lms = Set_Up_One_Click_Bulk_Content::get_available_lms_plugins();
        $active_lms = Set_Up_One_Click_Bulk_Content::get_active_lms_plugins();

        wp_send_json_success([
            'all_plugins' => $available_lms,
            'active_plugins' => $active_lms,
        ]);
    }

    /**
     * Get courses for a specific LMS plugin.
     */
    public function get_courses_for_lms(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied.', 'set-up-one-click')]);
        }

        $plugin_key = isset($_POST['plugin_key']) ? sanitize_text_field($_POST['plugin_key']) : '';

        if (empty($plugin_key)) {
            wp_send_json_error(['message' => __('Plugin key required.', 'set-up-one-click')]);
        }

        if (!class_exists('Set_Up_One_Click_Bulk_Content')) {
            $bulk_file = SET_UP_ONE_CLICK_PLUGIN_DIR . 'includes/class-ocs-bulk-content.php';
            if (file_exists($bulk_file)) {
                require_once $bulk_file;
            }
        }

        $courses_data = Set_Up_One_Click_Bulk_Content::get_courses_for_plugin($plugin_key);

        if (empty($courses_data) || !isset($courses_data['courses']) || empty($courses_data['courses'])) {
            wp_send_json_error(['message' => __('No courses found for this plugin.', 'set-up-one-click')]);
        }

        wp_send_json_success([
            'plugin_key' => $plugin_key,
            'plugin_name' => $courses_data['plugin_name'] ?? '',
            'courses' => $courses_data['courses'] ?? [],
        ]);
    }

    /**
     * Get available Membership plugins.
     */
    public function get_membership_plugins(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied.', 'set-up-one-click')]);
        }

        if (!class_exists('Set_Up_One_Click_Bulk_Content')) {
            $bulk_file = SET_UP_ONE_CLICK_PLUGIN_DIR . 'includes/class-ocs-bulk-content.php';
            if (file_exists($bulk_file)) {
                require_once $bulk_file;
            }
        }

        $available = Set_Up_One_Click_Bulk_Content::get_available_membership_plugins();
        $active = Set_Up_One_Click_Bulk_Content::get_active_membership_plugins();

        wp_send_json_success([
            'all_plugins' => $available,
            'active_plugins' => $active,
        ]);
    }

    /**
     * Get memberships for a specific membership plugin.
     */
    public function get_memberships_for_plugin_ajax(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied.', 'set-up-one-click')]);
        }

        $plugin_key = isset($_POST['plugin_key']) ? sanitize_text_field($_POST['plugin_key']) : '';

        if (empty($plugin_key)) {
            wp_send_json_error(['message' => __('Plugin key required.', 'set-up-one-click')]);
        }

        if (!class_exists('Set_Up_One_Click_Bulk_Content')) {
            $bulk_file = SET_UP_ONE_CLICK_PLUGIN_DIR . 'includes/class-ocs-bulk-content.php';
            if (file_exists($bulk_file)) {
                require_once $bulk_file;
            }
        }

        $memberships_data = Set_Up_One_Click_Bulk_Content::get_memberships_for_plugin($plugin_key);

        if (empty($memberships_data) || !isset($memberships_data['memberships']) || empty($memberships_data['memberships'])) {
            wp_send_json_error(['message' => __('No memberships found for this plugin.', 'set-up-one-click')]);
        }

        wp_send_json_success([
            'plugin_key' => $plugin_key,
            'plugin_name' => $memberships_data['plugin_name'] ?? '',
            'memberships' => $memberships_data['memberships'] ?? [],
        ]);
    }

    /**
     * Create bulk content.
     */
    public function create_bulk_content(): void {
        check_ajax_referer('set_up_one_click_ajax_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied.', 'set-up-one-click')]);
        }

        if (!class_exists('Set_Up_One_Click_Bulk_Content')) {
            $bulk_file = SET_UP_ONE_CLICK_PLUGIN_DIR . 'includes/class-ocs-bulk-content.php';
            if (file_exists($bulk_file)) {
                require_once $bulk_file;
            }
        }

        $bulk_data = isset($_POST['bulk_data']) ? json_decode(wp_unslash($_POST['bulk_data']), true) : [];
        $results = [];

        if (isset($bulk_data['pages']) && !empty($bulk_data['pages'])) {
            $results['pages'] = Set_Up_One_Click_Bulk_Content::create_pages($bulk_data['pages']);
        }

        if (isset($bulk_data['posts']) && !empty($bulk_data['posts'])) {
            $results['posts'] = Set_Up_One_Click_Bulk_Content::create_posts($bulk_data['posts']);
        }

        if (isset($bulk_data['products']) && !empty($bulk_data['products'])) {
            $results['products'] = Set_Up_One_Click_Bulk_Content::create_products($bulk_data['products']);
        }

        if (isset($bulk_data['comments']) && !empty($bulk_data['comments'])) {
            // Process comments for posts
            $posts = get_posts(['numberposts' => 5, 'post_type' => 'post']);
            foreach ($posts as $post) {
                $results['comments_' . $post->ID] = Set_Up_One_Click_Bulk_Content::add_comments_to_posts(
                    $post->ID,
                    (int)$bulk_data['comments']
                );
            }
        }

        // Handle courses with cascading plugin selection
        if (isset($bulk_data['courses']) && is_array($bulk_data['courses'])) {
            $lms_plugin = sanitize_text_field($bulk_data['courses']['plugin'] ?? '');
            $course_ids = is_array($bulk_data['courses']['course_ids'] ?? []) ? $bulk_data['courses']['course_ids'] : [];
            if (!empty($lms_plugin) && !empty($course_ids)) {
                $results['courses'] = Set_Up_One_Click_Bulk_Content::create_courses_for_plugin($lms_plugin, $course_ids);
            }
        }

        // Handle memberships with cascading plugin selection
        if (isset($bulk_data['memberships']) && is_array($bulk_data['memberships'])) {
            $membership_plugin = sanitize_text_field($bulk_data['memberships']['plugin'] ?? '');
            $membership_ids = is_array($bulk_data['memberships']['membership_ids'] ?? []) ? $bulk_data['memberships']['membership_ids'] : [];
            if (!empty($membership_plugin) && !empty($membership_ids)) {
                $results['memberships'] = Set_Up_One_Click_Bulk_Content::create_memberships_for_plugin($membership_plugin, $membership_ids);
            }
        }

        wp_send_json_success([
            'message' => __('Bulk content created successfully!', 'set-up-one-click'),
            'results' => $results,
        ]);
    }

    /**
     * Get plugins organized by category.
     * 
     * @return array Plugins organized by category.
     */
    private static function get_plugin_categories(): array {
        return [
            'code_snippets' => [
                'code-snippets' => 'Code Snippets',
                'insert-headers-and-footers' => 'WP Code',
                'header-footer-code-manager' => 'Header Footer Code Manager',
            ],
            'utilities' => [
                'woocommerce' => 'WooCommerce',
                'easy-digital-downloads' => 'Easy Digital Downloads',
                'google-analytics-for-wordpress' => 'Google Analytics by ExactMetrics',
                'wordfence' => 'Wordfence Security',
                'jetpack' => 'Jetpack',
                'wp-optimize' => 'WP-Optimize',
                'wp-reset' => 'WP Reset',
            ],
            'builders' => [
                'elementor' => 'Elementor',
                'beaver-builder-lite-version' => 'Beaver Builder',
                'brizy' => 'Brizy',
                'kadence-blocks' => 'Kadence Blocks',
                'coming-soon' => 'SeedProd',
            ],
            'builder_addons' => [
                'royal-elementor-addons' => 'Royal Elementor Addons',
                'unlimited-elements-for-elementor' => 'Unlimited Elements for Elementor',
                'essential-addons-for-elementor-lite' => 'Essential Addons for Elementor Lite',
                'elementskit-lite' => 'ElementsKit Lite',
                'header-footer-elementor' => 'Header Footer Elementor',
                'astra-sites' => 'Astra Sites',
                'premium-addons-for-elementor' => 'Premium Addons for Elementor',
                'happy-elementor-addons' => 'Happy Elementor Addons',
            ],
            'community' => [
                'buddypress' => 'BuddyPress',
                'youzify' => 'Youzify',
                'gamipress' => 'GamiPress',
                'simple-membership-custom-messages' => 'Simple Membership Custom Messages',
                'bp-better-messages' => 'BP Better Messages',
            ],
            'import_system' => [
                'template-kit-import' => 'Template Kit Import',
                'duplicate-page' => 'Duplicate Page',
                'duplicate-post' => 'Duplicate Post',
                'one-click-demo-import' => 'One Click Demo Import',
            ],
            'forms' => [
                'cartflows' => 'CartFlows',
                'jetformbuilder' => 'JetFormBuilder',
                'forminator' => 'Forminator',
                'ninja-forms' => 'Ninja Forms',
                'contact-form-7' => 'Contact Form 7',
                'weforms' => 'weForms',
            ],
            'performance' => [
                'wp-fastest-cache' => 'WP Fastest Cache',
                'w3-total-cache' => 'W3 Total Cache',
                'autoptimize' => 'Autoptimize',
                'lite-speed-cache' => 'LiteSpeed Cache',
                'seraphinite-accelerator' => 'Seraphinite Accelerator',
            ],
            'filters' => [
                'filter-everything' => 'Filter Everything',
                'woo-product-filter' => 'Woo Product Filter',
                'advanced-woo-search' => 'Advanced Woo Search',
                'searchwp-live-ajax-search' => 'SearchWP Live Ajax Search',
                'woocommerce-ajax-filters' => 'WooCommerce Ajax Filters',
            ],
            'miscellaneous' => [
                'wp-mail-smtp' => 'WP Mail SMTP',
                'post-smtp' => 'Post SMTP',
                'wordfence' => 'Wordfence Security',
                'sucuri-scanner' => 'Sucuri Security',
                'really-simple-ssl' => 'Really Simple SSL',
            ],
            'login_register' => [
                'nextend-facebook-connect' => 'Nextend Facebook Connect',
                'change-wp-admin-login' => 'Change WP Admin Login',
                'wps-hide-login' => 'WPS Hide Login',
                'custom-login-page-customizer' => 'Custom Login Page Customizer',
                'loginpress' => 'LoginPress',
            ],
            'lms' => [
                'learnpress' => 'LearnPress',
                'academy' => 'Academy LMS',
                'tutor' => 'Tutor LMS',
                'masterstudy-lms-learning-management-system' => 'MasterStudy LMS',
                'lifterlms' => 'LifterLMS',
                'sensei-lms' => 'Sensei LMS',
            ],
            'memberships' => [
                'ultimate-member' => 'Ultimate Member',
                'user-registration' => 'User Registration',
                'members' => 'Members',
                'restrict-content' => 'Restrict Content',
            ],
            'multilingual' => [
                'translatepress-multilingual' => 'TranslatePress Multilingual',
                'polylang' => 'Polylang',
                'loco-translate' => 'Loco Translate',
                'gtranslate' => 'GTranslate',
            ],
            'seo plugins' => [
                'yoast-seo' => 'Yoast SEO',
                'all-in-one-seo-pack' => 'All in One SEO Pack',
                'rank-math-seo' => 'Rank Math SEO',
            ],
            'Backup plugins' => [
                'updraftplus' => 'UpdraftPlus',
                'all-in-one-wp-migration' => 'All-in-One WP Migration',
                'wpvivid-backuprestore' => 'WP Vivid',
                'backwpup' => 'BackWPup',
                'duplicator' => 'Duplicator',
            ],
            'Booking plugins' => [
                'booking' => 'Booking',
                'bookly-responsive-appointment-booking-tool' => 'Bookly Responsive Appointment Booking Tool',
                'wp-simple-booking-calendar' => 'WP Simple Booking Calendar',
                'ameliabooking' => 'Amelia',
                'booking-calendar' => 'Booking Calendar',
            ],
            'others' => [
                'user-role-editor' => 'User Role Editor',
                'pods' => 'Pods',
                'advanced-custom-fields' => 'Advanced Custom Fields',
            ],
        ];
    }
}