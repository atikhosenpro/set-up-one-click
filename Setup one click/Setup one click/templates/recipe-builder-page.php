<?php
/**
 * The template for displaying the recipe builder page.
 *
 * FILE: templates/recipe-builder-page.php
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

$popular_plugins = [
    'utilities' => [
        'woocommerce' => 'WooCommerce',
        'easy-digital-downloads' => 'Easy Digital Downloads',
        'google-analytics-for-wordpress' => 'Google Analytics by ExactMetrics',
        'wordfence' => 'Wordfence Security',
        'jetpack' => 'Jetpack',
        'wp-optimize' => 'WP-Optimize',
        'wp-reset' => 'WP Reset',       
    ],
        'code_snippets' => [  
        'code-snippets' => 'Code Snippets',
        'insert-headers-and-footers' => 'Wp Code',
        'header-footer-code-manager' => 'Header Footer Code Manager',    
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
        'litespeed-cache' => 'LiteSpeed Cache',
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
        'wordpress-seo' => 'Yoast SEO',
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

$starter_themes = [
    'astra' => 'Astra',
    'hello-elementor' => 'Hello Elementor',
    'kadence' => 'Kadence',
    'blocksy' => 'Blocksy',
    'generatepress' => 'GeneratePress',
    'oceanwp' => 'OceanWP',
    'storefront' => 'Storefront',
    'neve' => 'Neve',
];

$recipe_data = get_option('set_up_one_click_saved_recipe', []);
?>

<div class="wrap ocs-wrap">
    <div class="atik-pro-ocs-header">   
         <h1><span class="dashicons dashicons-controls-play-icon-5156525 ocs-title-icon"></span> <?php esc_html_e( 'Set up one click', 'set-up-one-click' ); ?></h1>
        <p class="ocs-header-subtitle-655445"><?php esc_html_e( 'Configure your standard setup tasks below. Save the recipe, then deploy it on this site.', 'set-up-one-click' ); ?></p>
    </div>

<div class="wrap ocs-wrap">
    <div class="ocs-header">
        <div class="ocs-logo-section">
            <img src="<?php echo esc_url( SET_UP_ONE_CLICK_PLUGIN_URL . 'assets/Images/plugin-logo.svg' ); ?>" alt="<?php esc_attr_e( 'Set up one click', 'set-up-one-click' ); ?>" class="ocs-logo-image">
        </div>
         <h1><span class="dashicons dashicons-controls-play-icon-5125 ocs-title-icon"></span> <?php esc_html_e( 'Set up one click', 'set-up-one-click' ); ?></h1>
        <p><?php esc_html_e( 'Configure your standard setup tasks below. Save the recipe, then deploy it on this site.', 'set-up-one-click' ); ?></p>
    </div>

    <!-- Search Box -->
    <div class="ocs-search-section">
        <div class="ocs-search-container">
            <span class="dashicons dashicons-search ocs-search-icon"></span>
            <input type="text" id="ocs-plugin-search" class="ocs-search-input" placeholder="<?php esc_attr_e( 'Search plugins...', 'set-up-one-click' ); ?>">
            <button type="button" id="ocs-clear-search" class="ocs-clear-btn" style="display:none;"><span class="dashicons dashicons-no-alt"></span></button>
        </div>
        <div id="ocs-search-results" class="ocs-search-results" style="display:none;"></div>
    </div>

    <div id="ocs-progress-modal" style="display:none;">
        <div id="ocs-progress-container">
            <div id="ocs-progress-bar-wrapper">
                <div id="ocs-progress-bar" style="width: 0%;"></div>
            </div>
            <div id="ocs-progress-text">0%</div>
            <h3 class="ocs-modal-title"><?php esc_html_e( 'Deployment in Progress...', 'set-up-one-click' ); ?></h3>
            <p class="ocs-modal-subtitle"><?php esc_html_e( 'Please do not close this window until the process is complete.', 'set-up-one-click' ); ?></p>
            <pre id="ocs-live-log"></pre>
        </div>
    </div>

    <div id="ocs-ajax-notice" class="notice" style="display:none; padding: 1rem;"></div>

    <form id="ocs-recipe-form" class="ocs-form">
        <div class="ocs-main-content">
            <div class="ocs-recipe-column">
                <!-- Section: Cleanup -->
                <div class="ocs-recipe-section">
                    <h3><span class="dashicons dashicons-trash"></span> <?php esc_html_e( 'Initial Cleanup', 'set-up-one-click' ); ?></h3>
                    <p class="ocs-section-desc"><?php esc_html_e( 'Remove default WordPress content and plugins to start fresh.', 'set-up-one-click' ); ?></p>
                    <label class="ocs-checkbox"><input type="checkbox" name="cleanup[]" value="delete_post" <?php checked(in_array('delete_post', $recipe_data['cleanup'] ?? [])); ?>><span><?php esc_html_e( 'Delete "Hello World" post', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-checkbox"><input type="checkbox" name="cleanup[]" value="delete_page" <?php checked(in_array('delete_page', $recipe_data['cleanup'] ?? [])); ?>><span><?php esc_html_e( 'Delete "Sample Page"', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-checkbox"><input type="checkbox" name="cleanup[]" value="delete_default_comment" <?php checked(in_array('delete_default_comment', $recipe_data['cleanup'] ?? [])); ?>><span><?php esc_html_e( 'Delete default comment', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-checkbox"><input type="checkbox" name="cleanup[]" value="delete_hello_dolly" <?php checked(in_array('delete_hello_dolly', $recipe_data['cleanup'] ?? [])); ?>><span><?php esc_html_e( 'Delete Hello Dolly plugin', 'set-up-one-click' ); ?></span></label>
                </div>
                
                <!-- Section: Settings -->
                <div class="ocs-recipe-section">
                    <h3><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'Core Settings', 'set-up-one-click' ); ?></h3>
                    <p class="ocs-section-desc"><?php esc_html_e( 'Configure essential WordPress settings for your site.', 'set-up-one-click' ); ?></p>
                    <label class="ocs-checkbox"><input type="checkbox" name="settings[]" value="discourage_search" <?php checked(in_array('discourage_search', $recipe_data['settings'] ?? [])); ?>><span><?php esc_html_e( 'Discourage search engines from indexing', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-checkbox"><input type="checkbox" name="settings[]" value="disable_xml_rpc" <?php checked(in_array('disable_xml_rpc', $recipe_data['settings'] ?? [])); ?>><span><?php esc_html_e( 'Disable XML-RPC', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-checkbox"><input type="checkbox" name="content[]" value="create_primary_menu" <?php checked(in_array('create_primary_menu', $recipe_data['content'] ?? [])); ?>><span><?php esc_html_e( 'Create a "Primary" menu', 'set-up-one-click' ); ?></span></label>
                </div>

                <!-- Section: Permalinks -->
                <div class="ocs-recipe-section">
                    <h3><span class="dashicons dashicons-admin-links"></span> <?php esc_html_e( 'Permalinks', 'set-up-one-click' ); ?></h3>
                    <p class="ocs-section-desc"><?php esc_html_e( 'Choose how your site URLs will be structured.', 'set-up-one-click' ); ?></p>
                    <label class="ocs-radio"><input type="radio" name="permalink" value="/%postname%/" <?php checked('/%postname%/' === ($recipe_data['permalink'] ?? '')); ?>><span><?php esc_html_e( 'Post Name', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-radio"><input type="radio" name="permalink" value="/%year%/%monthnum%/%postname%/" <?php checked('/%year%/%monthnum%/%postname%/' === ($recipe_data['permalink'] ?? '')); ?>><span><?php esc_html_e( 'Month and Name', 'set-up-one-click' ); ?></span></label>
                </div>

                <!-- Section: Discussion -->
                <div class="ocs-recipe-section">
                    <h3><span class="dashicons dashicons-admin-comments"></span> <?php esc_html_e( 'Discussion Settings', 'set-up-one-click' ); ?></h3>
                    <p class="ocs-section-desc"><?php esc_html_e( 'Manage comments and engagement on your site.', 'set-up-one-click' ); ?></p>
                    <label class="ocs-checkbox"><input type="checkbox" name="settings[]" value="disable_comments" <?php checked( in_array( 'disable_comments', $recipe_data['settings'] ?? [], true ) ); ?>><span><?php esc_html_e( 'Disable comments globally', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-checkbox"><input type="checkbox" name="settings[]" value="disable_pingbacks" <?php checked(in_array('disable_pingbacks', $recipe_data['settings'] ?? [])); ?>><span><?php esc_html_e( 'Disable pingbacks & trackbacks', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-checkbox"><input type="checkbox" name="settings[]" value="comment_approval" <?php checked(in_array('comment_approval', $recipe_data['settings'] ?? [])); ?>><span><?php esc_html_e( 'Comments must be manually approved', 'set-up-one-click' ); ?></span></label>
                    <label class="ocs-checkbox"><input type="checkbox" name="settings[]" value="comment_moderation" <?php checked(in_array('comment_moderation', $recipe_data['settings'] ?? [])); ?>><span><?php esc_html_e( 'Hold comments with 1+ links', 'set-up-one-click' ); ?></span></label>
                </div>
                <div class="ocs-pro-feature">
                    <span class="ocs-pro-badge">PRO</span>
                    <p><?php esc_html_e( 'Enable content protection & header footer scripts without stacking more plugins.', 'set-up-one-click' ); ?></p>
                </div>
            </div>
            <div class="ocs-recipe-column">
                <!-- Section: Plugins -->
                <div class="ocs-recipe-section">
                    <h3><span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'Install & Activate Plugins', 'set-up-one-click' ); ?></h3>
                    <p class="ocs-section-desc"><?php esc_html_e( 'Search and select plugins from popular categories. Use the search bar above for quick access.', 'set-up-one-click' ); ?></p>
                    <div class="ocs-select-grid">
                    <?php foreach ( $popular_plugins as $group => $plugins ) : ?>
                        <div class="ocs-select-group">
                            <?php
                            // Logic to handle special group names.
                            switch ( $group ) {
                                case 'code_snippets':
                                    $group_name = 'Code Snippets';
                                    break;
                                case 'utilities':
                                    $group_name = 'Utilities';
                                    break;
                                case 'builders':
                                    $group_name = 'Builders';
                                    break;
                                case 'builder_addons':
                                    $group_name = 'Builder Addons';
                                    break;
                                case 'community':
                                    $group_name = 'Community';
                                    break;
                                case 'import_system':
                                    $group_name = 'Import System';
                                    break;
                                case 'forms':
                                    $group_name = 'Forms';
                                    break;
                                case 'performance':
                                    $group_name = 'Performance';
                                    break;
                                case 'filters':
                                    $group_name = 'Filters';
                                    break;
                                case 'miscellaneous':
                                    $group_name = 'Miscellaneous';
                                    break;
                                case 'login_register':
                                    $group_name = 'Login & Register';
                                    break;
                                case 'lms':
                                    $group_name = 'LMS';
                                    break;
                                case 'memberships':
                                    $group_name = 'Memberships';
                                    break;
                                case 'multilingual':
                                    $group_name = 'Multilingual';
                                    break;
                                case 'seo plugins':
                                    $group_name = 'SEO Plugins';
                                    break;
                                case 'Backup plugins':
                                    $group_name = 'Backup Plugins';
                                    break;
                                case 'Booking plugins':
                                    $group_name = 'Booking Plugins';
                                    break;
                                case 'others':
                                    $group_name = 'Others';
                                    break;
                                default:
                                    $group_name = ucfirst( str_replace( '_', ' ', $group ) );
                            }
                            ?>
                            <h4><?php echo esc_html( $group_name ); ?></h4>
                            <?php foreach ( $plugins as $slug => $name ) : ?>
                                <label class="ocs-checkbox"><input type="checkbox" name="<?php echo esc_attr( $group ); ?>[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $recipe_data[ $group ] ?? [], true ) ); ?>><span><?php echo esc_html( $name ); ?></span></label>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Section: Themes -->
                <div class="ocs-recipe-section">
                    <h3><span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e( 'Install & Activate Theme', 'set-up-one-click' ); ?></h3>
                    <p class="ocs-section-desc"><?php esc_html_e( 'Select a professional WordPress theme or skip this step to keep your current theme.', 'set-up-one-click' ); ?></p>
                    <div class="ocs-theme-grid">
                        <!-- The "None" option -->
                        <label class="ocs-radio-image">
                            <input type="radio" name="theme" value="" <?php checked( '', $recipe_data['theme'] ?? '' ); ?>>
                            <div class="ocs-no-theme"><span><?php esc_html_e('None', 'set-up-one-click'); ?></span></div>
                        </label>
                        <!-- The theme options -->
                        <?php foreach ( $starter_themes as $slug => $name ) : ?>
                            <label class="ocs-radio-image">
                                <input type="radio" name="theme" value="<?php echo esc_attr( $slug ); ?>" <?php checked( $slug, $recipe_data['theme'] ?? '' ); ?>>
                                <span><?php echo esc_html( $name ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="ocs-pro-notice_7894564"><?php esc_html_e( 'Pro unlocks full WP.org search, zip installation of plugins &amp; theme simultaneously.', 'set-up-one-click' ); ?></div>
                <!-- Section: Bulk Content -->
                <div class="ocs-recipe-section">
                    <h3><span class="dashicons dashicons-admin-post"></span> <?php esc_html_e( 'Bulk Content Creation', 'set-up-one-click' ); ?></h3>
                    <p class="ocs-section-desc"><?php esc_html_e( 'Create multiple pages, posts, products, courses, memberships, and comments in bulk. Select what you want to create and customize the content.', 'set-up-one-click' ); ?></p>
                    
                    <div class="ocs-bulk-toggles">
                        <!-- Pages Toggle -->
                        <div class="ocs-bulk-toggle">
                            <label class="ocs-toggle-label">
                                <input type="checkbox" class="ocs-bulk-toggle-checkbox" data-type="pages" value="pages">
                                <span class="ocs-toggle-text">
                                    <span class="dashicons dashicons-admin-page"></span>
                                    <?php esc_html_e( 'Bulk Pages', 'set-up-one-click' ); ?>
                                </span>
                            </label>
                        </div>

                        <!-- Posts Toggle -->
                        <div class="ocs-bulk-toggle">
                            <label class="ocs-toggle-label">
                                <input type="checkbox" class="ocs-bulk-toggle-checkbox" data-type="posts" value="posts">
                                <span class="ocs-toggle-text">
                                    <span class="dashicons dashicons-format-aside"></span>
                                    <?php esc_html_e( 'Bulk Posts', 'set-up-one-click' ); ?>
                                </span>
                            </label>
                        </div>

                        <!-- Products Toggle -->
                        <div class="ocs-bulk-toggle">
                            <label class="ocs-toggle-label">
                                <input type="checkbox" class="ocs-bulk-toggle-checkbox" data-type="products" value="products">
                                <span class="ocs-toggle-text">
                                    <span class="dashicons dashicons-cart"></span>
                                    <?php esc_html_e( 'Bulk Products (WooCommerce)', 'set-up-one-click' ); ?>
                                </span>
                            </label>
                        </div>

                        <!-- Comments Toggle -->
                        <div class="ocs-bulk-toggle">
                            <label class="ocs-toggle-label">
                                <input type="checkbox" class="ocs-bulk-toggle-checkbox" data-type="comments" value="comments">
                                <span class="ocs-toggle-text">
                                    <span class="dashicons dashicons-admin-comments"></span>
                                    <?php esc_html_e( 'Bulk Comments', 'set-up-one-click' ); ?>
                                </span>
                            </label>
                        </div>

                        <!-- Courses Toggle -->
                        <div class="ocs-bulk-toggle">
                            <label class="ocs-toggle-label">
                                <input type="checkbox" class="ocs-bulk-toggle-checkbox" data-type="courses" value="courses">
                                <span class="ocs-toggle-text">
                                    <span class="dashicons dashicons-book-alt"></span>
                                    <?php esc_html_e( 'Bulk Courses', 'set-up-one-click' ); ?>
                                </span>
                            </label>
                        </div>

                        <!-- Memberships Toggle -->
                        <div class="ocs-bulk-toggle">
                            <label class="ocs-toggle-label">
                                <input type="checkbox" class="ocs-bulk-toggle-checkbox" data-type="memberships" value="memberships">
                                <span class="ocs-toggle-text">
                                    <span class="dashicons dashicons-groups"></span>
                                    <?php esc_html_e( 'Bulk Memberships', 'set-up-one-click' ); ?>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Bulk Content Selection Fields -->
                    <div id="ocs-bulk-content-fields" style="display: none; margin-top: 20px;">
                        <!-- Standard Content Fields (pages, posts, etc.) -->
                        <div id="ocs-standard-content-fields"></div>
                        
                        <!-- Courses Section -->
                        <div id="ocs-courses-section" style="display: none; margin-top: 20px;">
                            <div class="ocs-bulk-cascade-section">
                                <!-- Step 1: LMS Plugin Selection -->
                                <div class="ocs-cascade-step" id="ocs-lms-plugin-step">
                                    <div class="ocs-bulk-field-title">
                                        <span class="dashicons dashicons-book-alt"></span>
                                        <?php esc_html_e('Step 1: Select Your LMS Plugin', 'set-up-one-click'); ?>
                                    </div>
                                    <p class="ocs-step-description"><?php esc_html_e('Choose the Learning Management System plugin you are using:', 'set-up-one-click'); ?></p>
                                    <div class="ocs-lms-plugins-selection" id="ocs-lms-plugins-list"></div>
                                </div>
                                
                                <!-- Step 2: Course Selection -->
                                <div class="ocs-cascade-step" id="ocs-courses-selection-step" style="display: none; margin-top: 20px;">
                                    <div class="ocs-bulk-field-title">
                                        <span class="dashicons dashicons-list-view"></span>
                                        <span id="ocs-selected-lms-label"></span>
                                    </div>
                                    <p class="ocs-step-description"><?php esc_html_e('Select the courses you want to create:', 'set-up-one-click'); ?></p>
                                    <div class="ocs-courses-selection" id="ocs-lms-courses-list"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Memberships Section -->
                        <div id="ocs-memberships-section" style="display: none; margin-top: 20px;">
                            <div class="ocs-bulk-cascade-section">
                                <!-- Step 1: Membership Plugin Selection -->
                                <div class="ocs-cascade-step" id="ocs-membership-plugin-step">
                                    <div class="ocs-bulk-field-title">
                                        <span class="dashicons dashicons-groups"></span>
                                        <?php esc_html_e('Step 1: Select Your Membership Plugin', 'set-up-one-click'); ?>
                                    </div>
                                    <p class="ocs-step-description"><?php esc_html_e('Choose the membership plugin you are using:', 'set-up-one-click'); ?></p>
                                    <div class="ocs-membership-plugins-selection" id="ocs-membership-plugins-list"></div>
                                </div>
                                
                                <!-- Step 2: Membership Selection -->
                                <div class="ocs-cascade-step" id="ocs-memberships-selection-step" style="display: none; margin-top: 20px;">
                                    <div class="ocs-bulk-field-title">
                                        <span class="dashicons dashicons-list-view"></span>
                                        <span id="ocs-selected-membership-label"></span>
                                    </div>
                                    <p class="ocs-step-description"><?php esc_html_e('Select the memberships you want to create:', 'set-up-one-click'); ?></p>
                                    <div class="ocs-memberships-selection" id="ocs-membership-items-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ocs-sidebar">
            <div class="ocs-actions ocs-recipe-section">
                <h3><?php esc_html_e('Save / Deploy', 'set-up-one-click'); ?></h3>
                <button type="submit" id="ocs-save-recipe-btn" class="button button-large"><?php esc_html_e( 'Save Recipe', 'set-up-one-click' ); ?></button>
                <button type="button" id="ocs-deploy-recipe-btn" class="button button-primary button-large"><?php esc_html_e( 'Deploy This Recipe', 'set-up-one-click' ); ?></button>
            </div>
            <div class="ocs-import-export ocs-recipe-section">
                 <h3><?php esc_html_e('Import / Export', 'set-up-one-click'); ?></h3>
                 <p><?php esc_html_e('Save your recipe to a file or load one from your computer.', 'set-up-one-click'); ?></p>
                 <div class="ocs-import-export-buttons">
                    <a href="<?php echo esc_url(add_query_arg(['action' => 'set_up_one_click_export_recipe', 'nonce' => wp_create_nonce('set_up_one_click_export_nonce')])); ?>" id="ocs-export-btn" class="button button-small"><span class="dashicons dashicons-download"></span> <?php esc_html_e('Export Recipe', 'set-up-one-click'); ?></a>
                    <input type="file" id="ocs-import-file" style="display:none;" accept=".json">
                    <button type="button" id="ocs-import-btn" class="button button-small"><span class="dashicons dashicons-upload"></span> <?php esc_html_e('Import Recipe', 'set-up-one-click'); ?></button>
                </div>
            </div>
             
            <div class="ocs-support ocs-recipe-section">
                <h3><?php esc_html_e('Need Help?', 'set-up-one-click'); ?></h3>
                <p><?php esc_html_e('If you have any issue/question, please check FAQ or visit support forum.', 'set-up-one-click'); ?></p>
                <div class="ocs-support-buttons">
                    <a href="https://github.com/atikhosenpro/set-up-one-click/wiki" target="_blank" class="button button-small" style="width:100%; text-align:center;"><span class="dashicons dashicons-book-alt"></span> <?php esc_html_e('Check FAQ', 'set-up-one-click'); ?></a>
                    <a href="https://github.com/atikhosenpro/set-up-one-click/issues" target="_blank" class="button button-small" style="width:100%; text-align:center;">
                        <span class="dashicons dashicons-editor-help"></span> <?php esc_html_e('Get Help', 'set-up-one-click'); ?>
                    </a>
                </div>
            </div>
            <div class="ocs-support ocs-recipe-section">
                <h3><?php esc_html_e('Love This Plugin?', 'set-up-one-click'); ?></h3>
                <p><?php esc_html_e('Please leave a 5-star review. And save more time with Pro.', 'set-up-one-click'); ?></p>
                <div class="ocs-support-buttons">
                    <a href="https://github.com/atikhosenpro/set-up-one-click" target="_blank" class="button button-small">
                        <span class="dashicons dashicons-star-filled"></span> <?php esc_html_e('Star on GitHub', 'set-up-one-click'); ?>
                    </a>
                    <a href="https://github.com/atikhosenpro/set-up-one-click" target="_blank" class="button button-small">
                        <span class="dashicons dashicons-superhero"></span> <?php esc_html_e('View on GitHub', 'set-up-one-click'); ?>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>