<?php
/**
 * Handles bulk content creation with plugin-specific organization.
 * Courses and memberships organized by their required LMS/Membership plugins.
 *
 * @since      1.0.0
 * @package    Set_Up_One_Click
 * @author     _atikhosen
 *
 * FILE: includes/class-ocs-bulk-content-v2.php
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Set_Up_One_Click_Bulk_Content {

    /**
     * Get available LMS (Learning Management System) plugins.
     * These are the course plugins that users can choose from.
     */
    public static function get_available_lms_plugins(): array {
        return [
            'learndash' => [
                'name' => 'LearnDash',
                'plugin_file' => 'sfwd-lms/sfwd_lms.php',
                'post_type' => 'sfwd-courses',
                'icon' => 'dashicons-book-alt',
                'description' => 'Professional course platform with certificates and drip content',
            ],
            'tutor' => [
                'name' => 'Tutor LMS',
                'plugin_file' => 'tutor/tutor.php',
                'post_type' => 'tutor_course',
                'icon' => 'dashicons-book-alt',
                'description' => 'Full-featured learning management system',
            ],
            'learnpress' => [
                'name' => 'LearnPress',
                'plugin_file' => 'learnpress/learnpress.php',
                'post_type' => 'lp_course',
                'icon' => 'dashicons-book-alt',
                'description' => 'WordPress LMS plugin with online courses',
            ],
            'lifterLMS' => [
                'name' => 'LifterLMS',
                'plugin_file' => 'lifterlms/lifterlms.php',
                'post_type' => 'course',
                'icon' => 'dashicons-book-alt',
                'description' => 'Complete learning platform and community',
            ],
            'masterstudy' => [
                'name' => 'MasterStudy LMS',
                'plugin_file' => 'masterstudy-lms-learning-management-system/masterstudy-lms.php',
                'post_type' => 'stm-courses',
                'icon' => 'dashicons-book-alt',
                'description' => 'Powerful course builder and LMS',
            ],
        ];
    }

    /**
     * Get available Membership plugins.
     */
    public static function get_available_membership_plugins(): array {
        return [
            'pmpro' => [
                'name' => 'Paid Memberships Pro',
                'plugin_file' => 'paid-memberships-pro/paid-memberships-pro.php',
                'post_type' => 'pmpro_membership_level',
                'icon' => 'dashicons-groups',
                'description' => 'Complete membership solution for WordPress',
            ],
            'memberpress' => [
                'name' => 'MemberPress',
                'plugin_file' => 'memberpress/memberpress.php',
                'post_type' => 'memberships',
                'icon' => 'dashicons-groups',
                'description' => 'Membership and content protection plugin',
            ],
            'wishlist_member' => [
                'name' => 'Wishlist Member',
                'plugin_file' => 'wishlist-member/wishlist-member.php',
                'post_type' => 'wlm_membership',
                'icon' => 'dashicons-groups',
                'description' => 'WordPress membership plugin',
            ],
        ];
    }

    /**
     * Get courses organized by LMS plugin.
     */
    public static function get_courses_by_plugin(): array {
        return [
            'learndash' => [
                'plugin_name' => 'LearnDash',
                'courses' => [
                    'ld-wordpress-basics' => [
                        'title' => 'WordPress Basics for Beginners',
                        'description' => 'Learn the fundamentals of WordPress',
                        'content' => 'Complete guide to WordPress basics including installation, setup, and basic navigation.',
                        'price' => '49.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=WordPress+Basics',
                        'lessons' => 8,
                    ],
                    'ld-plugin-development' => [
                        'title' => 'WordPress Plugin Development',
                        'description' => 'Create powerful WordPress plugins',
                        'content' => 'Master plugin development with hooks, filters, and best practices.',
                        'price' => '99.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Plugin+Dev',
                        'lessons' => 15,
                    ],
                    'ld-theme-design' => [
                        'title' => 'WordPress Theme Design',
                        'description' => 'Design and develop custom themes',
                        'content' => 'Create beautiful, responsive WordPress themes from scratch.',
                        'price' => '89.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Theme+Design',
                        'lessons' => 12,
                    ],
                    'ld-woocommerce' => [
                        'title' => 'WooCommerce Mastery',
                        'description' => 'Build online stores with WooCommerce',
                        'content' => 'Complete e-commerce course with WooCommerce setup and management.',
                        'price' => '79.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=WooCommerce',
                        'lessons' => 14,
                    ],
                    'ld-seo' => [
                        'title' => 'WordPress SEO Mastery',
                        'description' => 'Optimize your site for search engines',
                        'content' => 'Learn SEO best practices and rank higher in search results.',
                        'price' => '69.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=SEO+Mastery',
                        'lessons' => 10,
                    ],
                    'ld-security' => [
                        'title' => 'WordPress Security Course',
                        'description' => 'Secure your WordPress installation',
                        'content' => 'Comprehensive security practices for WordPress websites.',
                        'price' => '59.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Security',
                        'lessons' => 9,
                    ],
                ],
            ],
            'tutor' => [
                'plugin_name' => 'Tutor LMS',
                'courses' => [
                    'tutor-web-design' => [
                        'title' => 'Web Design Fundamentals',
                        'description' => 'Design beautiful websites',
                        'content' => 'Learn web design principles, layout, typography, and user experience.',
                        'price' => '79.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Web+Design',
                        'lessons' => 11,
                    ],
                    'tutor-html-css' => [
                        'title' => 'HTML & CSS Mastery',
                        'description' => 'Master frontend web development',
                        'content' => 'Complete HTML and CSS course for web development.',
                        'price' => '89.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=HTML+CSS',
                        'lessons' => 16,
                    ],
                    'tutor-javascript' => [
                        'title' => 'JavaScript Programming',
                        'description' => 'Learn JavaScript fundamentals',
                        'content' => 'From basics to advanced JavaScript concepts and frameworks.',
                        'price' => '99.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=JavaScript',
                        'lessons' => 18,
                    ],
                    'tutor-react' => [
                        'title' => 'React JS Framework',
                        'description' => 'Build dynamic web applications',
                        'content' => 'Learn React for building modern user interfaces.',
                        'price' => '109.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=React',
                        'lessons' => 16,
                    ],
                ],
            ],
            'learnpress' => [
                'plugin_name' => 'LearnPress',
                'courses' => [
                    'lp-digital-marketing' => [
                        'title' => 'Digital Marketing Fundamentals',
                        'description' => 'Learn digital marketing strategies',
                        'content' => 'Complete digital marketing course including social media and SEO.',
                        'price' => '79.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Digital+Marketing',
                        'lessons' => 12,
                    ],
                    'lp-social-media' => [
                        'title' => 'Social Media Marketing',
                        'description' => 'Master social media strategies',
                        'content' => 'Learn to create engaging content and grow your social presence.',
                        'price' => '69.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Social+Media',
                        'lessons' => 10,
                    ],
                    'lp-content-marketing' => [
                        'title' => 'Content Marketing Strategy',
                        'description' => 'Create compelling content',
                        'content' => 'Learn content strategy, creation, and distribution techniques.',
                        'price' => '74.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Content+Marketing',
                        'lessons' => 11,
                    ],
                ],
            ],
            'lifterLMS' => [
                'plugin_name' => 'LifterLMS',
                'courses' => [
                    'llms-business-basics' => [
                        'title' => 'Business Fundamentals',
                        'description' => 'Learn business essentials',
                        'content' => 'Essential business principles and entrepreneurship guide.',
                        'price' => '69.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Business',
                        'lessons' => 10,
                    ],
                    'llms-accounting' => [
                        'title' => 'Small Business Accounting',
                        'description' => 'Manage business finances',
                        'content' => 'Learn accounting and financial management for small businesses.',
                        'price' => '79.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Accounting',
                        'lessons' => 12,
                    ],
                    'llms-marketing' => [
                        'title' => 'Marketing Strategies for Business',
                        'description' => 'Grow your business through marketing',
                        'content' => 'Comprehensive marketing course for business growth.',
                        'price' => '89.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Marketing',
                        'lessons' => 14,
                    ],
                ],
            ],
            'masterstudy' => [
                'plugin_name' => 'MasterStudy LMS',
                'courses' => [
                    'ms-graphic-design' => [
                        'title' => 'Graphic Design Fundamentals',
                        'description' => 'Learn design principles',
                        'content' => 'Complete guide to graphic design using industry-standard tools.',
                        'price' => '84.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Graphic+Design',
                        'lessons' => 13,
                    ],
                    'ms-photoshop' => [
                        'title' => 'Adobe Photoshop Mastery',
                        'description' => 'Master Photoshop for design',
                        'content' => 'Professional Photoshop course for designers and photographers.',
                        'price' => '89.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Photoshop',
                        'lessons' => 15,
                    ],
                    'ms-figma' => [
                        'title' => 'Figma UI/UX Design',
                        'description' => 'Design user interfaces with Figma',
                        'content' => 'Modern UI/UX design course using Figma.',
                        'price' => '79.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Figma',
                        'lessons' => 12,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get memberships organized by Membership plugin.
     */
    public static function get_memberships_by_plugin(): array {
        return [
            'pmpro' => [
                'plugin_name' => 'Paid Memberships Pro',
                'memberships' => [
                    'pmpro-starter' => [
                        'title' => 'Starter Membership',
                        'description' => 'Perfect for getting started',
                        'content' => 'Access to basic content and community features.',
                        'price' => '19.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Starter',
                        'duration' => 'Monthly',
                        'features' => 'Basic access, Community forum, Monthly email',
                    ],
                    'pmpro-professional' => [
                        'title' => 'Professional Membership',
                        'description' => 'For professionals and experts',
                        'content' => 'Full access to all courses and exclusive content.',
                        'price' => '79.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Professional',
                        'duration' => 'Monthly',
                        'features' => 'All courses, Priority support, Exclusive content',
                    ],
                    'pmpro-premium' => [
                        'title' => 'Premium Membership',
                        'description' => 'VIP experience',
                        'content' => 'Everything plus 1-on-1 mentoring and priority access.',
                        'price' => '199.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Premium',
                        'duration' => 'Monthly',
                        'features' => 'VIP support, 1-on-1 mentoring, Source code access',
                    ],
                    'pmpro-yearly' => [
                        'title' => 'Premium Yearly Plan',
                        'description' => 'Save with annual billing',
                        'content' => 'All Premium features with 20% annual savings.',
                        'price' => '1799.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Yearly+Deal',
                        'duration' => 'Yearly',
                        'features' => 'All premium features, Annual billing discount',
                    ],
                ],
            ],
            'memberpress' => [
                'plugin_name' => 'MemberPress',
                'memberships' => [
                    'mp-basic' => [
                        'title' => 'Basic Membership',
                        'description' => 'Essential membership access',
                        'content' => 'Access to basic member content.',
                        'price' => '29.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Basic',
                        'duration' => 'Monthly',
                        'features' => 'Member content, Basic support',
                    ],
                    'mp-business' => [
                        'title' => 'Business Membership',
                        'description' => 'For business owners',
                        'content' => 'Complete business resources and networking.',
                        'price' => '99.99',
                        'image_url' => 'https://via.placeholder.com/400x225?text=Business',
                        'duration' => 'Monthly',
                        'features' => 'All content, Networking events, Business resources',
                    ],
                ],
            ],
        ];
    }

    /**
     * Check if a plugin is installed and active.
     */
    public static function is_plugin_active( string $plugin_file ): bool {
        return is_plugin_active( $plugin_file );
    }

    /**
     * Get active LMS plugins on the website.
     */
    public static function get_active_lms_plugins(): array {
        $all_lms = self::get_available_lms_plugins();
        $active = [];

        foreach ( $all_lms as $key => $plugin ) {
            if ( self::is_plugin_active( $plugin['plugin_file'] ) ) {
                $active[ $key ] = $plugin;
            }
        }

        return $active;
    }

    /**
     * Get active Membership plugins on the website.
     */
    public static function get_active_membership_plugins(): array {
        $all_membership = self::get_available_membership_plugins();
        $active = [];

        foreach ( $all_membership as $key => $plugin ) {
            if ( self::is_plugin_active( $plugin['plugin_file'] ) ) {
                $active[ $key ] = $plugin;
            }
        }

        return $active;
    }

    /**
     * Get courses for a specific LMS plugin.
     */
    public static function get_courses_for_plugin( string $plugin_key ): array {
        $all_courses = self::get_courses_by_plugin();
        return $all_courses[ $plugin_key ] ?? [];
    }

    /**
     * Get memberships for a specific membership plugin.
     */
    public static function get_memberships_for_plugin( string $plugin_key ): array {
        $all_memberships = self::get_memberships_by_plugin();
        return $all_memberships[ $plugin_key ] ?? [];
    }

    /**
     * Create courses for a specific LMS plugin.
     */
    public static function create_courses_for_plugin( string $plugin_key, array $course_ids ): array {
        $courses_data = self::get_courses_for_plugin( $plugin_key );
        $lms_plugins = self::get_available_lms_plugins();
        
        if ( empty( $courses_data ) || ! isset( $lms_plugins[ $plugin_key ] ) ) {
            return [ 'error' => 'Invalid LMS plugin or courses not found' ];
        }

        $plugin_info = $lms_plugins[ $plugin_key ];
        $courses = $courses_data['courses'] ?? [];
        $results = [];

        foreach ( $course_ids as $course_id ) {
            if ( ! isset( $courses[ $course_id ] ) ) {
                continue;
            }

            $course_data = $courses[ $course_id ];
            $post_data = [
                'post_title'   => $course_data['title'],
                'post_content' => $course_data['content'],
                'post_type'    => $plugin_info['post_type'],
                'post_status'  => 'publish',
            ];

            $post_id = wp_insert_post( $post_data );

            if ( is_wp_error( $post_id ) ) {
                $results[ $course_id ] = [
                    'success' => false,
                    'message' => $post_id->get_error_message(),
                ];
            } else {
                // Add course metadata
                update_post_meta( $post_id, '_course_price', $course_data['price'] );
                update_post_meta( $post_id, '_course_description', $course_data['description'] );
                update_post_meta( $post_id, '_course_lessons', $course_data['lessons'] );
                update_post_meta( $post_id, '_lms_plugin', $plugin_key );

                $results[ $course_id ] = [
                    'success' => true,
                    'id'      => $post_id,
                    'title'   => $course_data['title'],
                    'plugin'  => $plugin_info['name'],
                ];
            }
        }

        return $results;
    }

    /**
     * Create memberships for a specific membership plugin.
     */
    public static function create_memberships_for_plugin( string $plugin_key, array $membership_ids ): array {
        $memberships_data = self::get_memberships_for_plugin( $plugin_key );
        $membership_plugins = self::get_available_membership_plugins();
        
        if ( empty( $memberships_data ) || ! isset( $membership_plugins[ $plugin_key ] ) ) {
            return [ 'error' => 'Invalid membership plugin or memberships not found' ];
        }

        $plugin_info = $membership_plugins[ $plugin_key ];
        $memberships = $memberships_data['memberships'] ?? [];
        $results = [];

        foreach ( $membership_ids as $membership_id ) {
            if ( ! isset( $memberships[ $membership_id ] ) ) {
                continue;
            }

            $membership_data = $memberships[ $membership_id ];
            $post_data = [
                'post_title'   => $membership_data['title'],
                'post_content' => $membership_data['content'],
                'post_type'    => $plugin_info['post_type'],
                'post_status'  => 'publish',
            ];

            $post_id = wp_insert_post( $post_data );

            if ( is_wp_error( $post_id ) ) {
                $results[ $membership_id ] = [
                    'success' => false,
                    'message' => $post_id->get_error_message(),
                ];
            } else {
                // Add membership metadata
                update_post_meta( $post_id, '_membership_price', $membership_data['price'] );
                update_post_meta( $post_id, '_membership_duration', $membership_data['duration'] );
                update_post_meta( $post_id, '_membership_features', $membership_data['features'] );
                update_post_meta( $post_id, '_membership_plugin', $plugin_key );

                $results[ $membership_id ] = [
                    'success' => true,
                    'id'      => $post_id,
                    'title'   => $membership_data['title'],
                    'price'   => $membership_data['price'],
                    'plugin'  => $plugin_info['name'],
                ];
            }
        }

        return $results;
    }

    /**
     * Get pages templates (unchanged).
     */
    public static function get_page_templates(): array {
        return [
            'home' => [
                'title' => 'Home',
                'content' => 'Welcome to our website. This is the home page where you can showcase your business, services, or products.',
            ],
            'about' => [
                'title' => 'About Us',
                'content' => 'Learn more about our company, mission, and values. We are committed to providing excellent service to our customers.',
            ],
            'contact' => [
                'title' => 'Contact Us',
                'content' => 'Get in touch with us. We would love to hear from you. Fill out the contact form below and we will respond promptly.',
            ],
            'services' => [
                'title' => 'Services',
                'content' => 'Discover our wide range of services designed to meet your needs. Each service is tailored to provide maximum value.',
            ],
            'shop' => [
                'title' => 'Shop',
                'content' => 'Browse our collection of products. Find exactly what you need from our carefully curated selection.',
            ],
            'faq' => [
                'title' => 'FAQ',
                'content' => 'Frequently Asked Questions - Find answers to common questions about our products and services.',
            ],
            'privacy' => [
                'title' => 'Privacy Policy',
                'content' => 'Read our privacy policy to understand how we collect, use, and protect your personal information.',
            ],
            'terms' => [
                'title' => 'Terms of Service',
                'content' => 'Terms and conditions for using our website and services. Please read carefully before using our platform.',
            ],
        ];
    }

    /**
     * Get posts templates (unchanged).
     */
    public static function get_post_templates(): array {
        return [
            'wordpress-dev-1' => [
                'title' => 'Getting Started with WordPress Development',
                'content' => 'Learn the basics of WordPress development. This guide covers the fundamentals of custom theme and plugin development.',
            ],
            'wordpress-dev-2' => [
                'title' => 'Advanced WordPress Plugin Development',
                'content' => 'Master advanced concepts in WordPress plugin development. Learn about hooks, filters, and best practices.',
            ],
            'wordpress-dev-3' => [
                'title' => 'WordPress Performance Optimization',
                'content' => 'Optimize your WordPress site for speed and performance. Discover caching strategies and performance tips.',
            ],
            'wordpress-dev-4' => [
                'title' => 'WordPress Security Best Practices',
                'content' => 'Secure your WordPress installation. Learn about security measures and protection strategies.',
            ],
            'wordpress-dev-5' => [
                'title' => 'Custom Post Types and Taxonomies',
                'content' => 'Create custom post types and taxonomies to extend WordPress functionality for your specific needs.',
            ],
            'wordpress-dev-6' => [
                'title' => 'WordPress REST API Guide',
                'content' => 'Build powerful applications using the WordPress REST API. Learn how to consume and extend the API.',
            ],
        ];
    }

    /**
     * Get product templates (unchanged).
     */
    public static function get_product_templates(): array {
        return [
            'digital' => [
                'title' => 'Premium Digital Product',
                'description' => 'High-quality digital product with instant delivery.',
                'price' => '29.99',
                'sale_price' => '19.99',
                'category' => 'Digital Products',
                'demo_link' => 'https://example.com/demo',
            ],
            'computer' => [
                'title' => 'Professional Desktop Computer',
                'description' => 'Powerful computer for professionals and content creators.',
                'price' => '1299.99',
                'sale_price' => '999.99',
                'category' => 'Computers',
                'demo_link' => 'https://example.com/demo',
            ],
            'laptop' => [
                'title' => 'Portable Laptop',
                'description' => 'Lightweight and powerful laptop for on-the-go professionals.',
                'price' => '999.99',
                'sale_price' => '799.99',
                'category' => 'Laptops',
                'demo_link' => 'https://example.com/demo',
            ],
            'tablet' => [
                'title' => 'Tablet Device',
                'description' => 'Perfect for entertainment and productivity on the move.',
                'price' => '499.99',
                'sale_price' => '399.99',
                'category' => 'Tablets',
                'demo_link' => 'https://example.com/demo',
            ],
            'phone' => [
                'title' => 'Smartphone',
                'description' => 'Latest smartphone with advanced features and technology.',
                'price' => '799.99',
                'sale_price' => '699.99',
                'category' => 'Smartphones',
                'demo_link' => 'https://example.com/demo',
            ],
            'accessory' => [
                'title' => 'Tech Accessory',
                'description' => 'Essential accessory to enhance your device experience.',
                'price' => '49.99',
                'sale_price' => '39.99',
                'category' => 'Accessories',
                'demo_link' => 'https://example.com/demo',
            ],
        ];
    }

    /**
     * Get comment templates (unchanged).
     */
    public static function get_comment_templates(): array {
        return [
            'positive-1' => 'Excellent quality! Highly recommended.',
            'positive-2' => 'Amazing service and great customer support!',
            'positive-3' => 'Best purchase I\'ve made. Very satisfied!',
            'positive-4' => 'Great product and fast delivery. Thank you!',
            'positive-5' => 'Exceeded my expectations. Will buy again!',
        ];
    }

    /**
     * Create pages from templates (unchanged).
     */
    public static function create_pages( array $page_ids ): array {
        $results = [];
        $templates = self::get_page_templates();

        foreach ( $page_ids as $page_id ) {
            if ( ! isset( $templates[ $page_id ] ) ) {
                continue;
            }

            $template = $templates[ $page_id ];
            $page_data = [
                'post_title'   => $template['title'],
                'post_content' => $template['content'],
                'post_type'    => 'page',
                'post_status'  => 'publish',
            ];

            $post_id = wp_insert_post( $page_data );

            if ( is_wp_error( $post_id ) ) {
                $results[ $page_id ] = [
                    'success' => false,
                    'message' => $post_id->get_error_message(),
                ];
            } else {
                $results[ $page_id ] = [
                    'success' => true,
                    'id'      => $post_id,
                    'title'   => $template['title'],
                ];
            }
        }

        return $results;
    }

    /**
     * Create posts from templates (unchanged).
     */
    public static function create_posts( array $post_ids ): array {
        $results = [];
        $templates = self::get_post_templates();

        foreach ( $post_ids as $post_id_key ) {
            if ( ! isset( $templates[ $post_id_key ] ) ) {
                continue;
            }

            $template = $templates[ $post_id_key ];
            $post_data = [
                'post_title'   => $template['title'],
                'post_content' => $template['content'],
                'post_type'    => 'post',
                'post_status'  => 'publish',
            ];

            $post_id = wp_insert_post( $post_data );

            if ( is_wp_error( $post_id ) ) {
                $results[ $post_id_key ] = [
                    'success' => false,
                    'message' => $post_id->get_error_message(),
                ];
            } else {
                $results[ $post_id_key ] = [
                    'success' => true,
                    'id'      => $post_id,
                    'title'   => $template['title'],
                ];
            }
        }

        return $results;
    }

    /**
     * Create WooCommerce products from templates (unchanged).
     */
    public static function create_products( array $product_ids ): array {
        $results = [];

        if ( ! class_exists( 'WC_Product_Simple' ) ) {
            return [ 'error' => 'WooCommerce is not installed or activated.' ];
        }

        $templates = self::get_product_templates();

        foreach ( $product_ids as $product_id_key ) {
            if ( ! isset( $templates[ $product_id_key ] ) ) {
                continue;
            }

            $template = $templates[ $product_id_key ];
            $product = new \WC_Product_Simple();

            $product->set_name( $template['title'] );
            $product->set_description( $template['description'] );
            $product->set_price( (float) $template['price'] );
            $product->set_sale_price( (float) $template['sale_price'] );
            $product->set_status( 'publish' );

            // Add category
            $category_name = $template['category'];
            $category = get_term_by( 'name', $category_name, 'product_cat' );

            if ( ! $category ) {
                $category = wp_insert_term( $category_name, 'product_cat' );
                if ( ! is_wp_error( $category ) ) {
                    $product->set_category_ids( [ $category['term_id'] ] );
                }
            } else {
                $product->set_category_ids( [ $category->term_id ] );
            }

            // Save product
            $product_id = $product->save();

            if ( is_wp_error( $product_id ) ) {
                $results[ $product_id_key ] = [
                    'success' => false,
                    'message' => $product_id->get_error_message(),
                ];
            } else {
                // Add product meta
                if ( ! empty( $template['demo_link'] ) ) {
                    update_post_meta( $product_id, '_demo_link', esc_url_raw( $template['demo_link'] ) );
                }

                $results[ $product_id_key ] = [
                    'success' => true,
                    'id'      => $product_id,
                    'title'   => $template['title'],
                ];
            }
        }

        return $results;
    }

    /**
     * Add comments to posts (unchanged).
     */
    public static function add_comments_to_posts( int $post_id, int $comment_count ): array {
        $results = [];
        $comment_templates = self::get_comment_templates();

        $comment_ids = wp_list_pluck(
            get_comments( [ 'post_id' => $post_id, 'number' => 999 ] ),
            'comment_ID'
        );

        $existing_count = count( $comment_ids );

        if ( $existing_count >= $comment_count ) {
            return [
                'success'  => true,
                'existing' => $existing_count,
                'message'  => 'Post already has sufficient comments.',
            ];
        }

        $comments_to_add = $comment_count - $existing_count;

        for ( $i = 0; $i < $comments_to_add; $i++ ) {
            $template = $comment_templates[ $i % count( $comment_templates ) ];

            $comment_data = [
                'comment_post_ID'      => $post_id,
                'comment_content'      => $template,
                'comment_author'       => 'Guest User ' . ( $i + 1 ),
                'comment_author_email' => 'guest' . ( $i + 1 ) . '@example.com',
                'comment_author_url'   => 'https://example.com',
                'comment_approved'     => 1,
                'comment_type'         => 'comment',
            ];

            $comment_id = wp_insert_comment( $comment_data );

            if ( is_wp_error( $comment_id ) ) {
                $results[ $i ] = [
                    'success' => false,
                    'message' => $comment_id->get_error_message(),
                ];
            } else {
                $results[ $i ] = [
                    'success' => true,
                    'id'      => $comment_id,
                ];
            }
        }

        return [
            'success'   => true,
            'created'   => count( array_filter( $results, fn( $r ) => $r['success'] ?? false ) ),
            'total'     => $comments_to_add,
            'details'   => $results,
        ];
    }
}
