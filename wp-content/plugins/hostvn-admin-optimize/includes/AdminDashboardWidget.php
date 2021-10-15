<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Dashboard_Widget')) {
    class HVN_AO_Dashboard_Widget extends HVN_AO_Base
    {

        /**
         * HVN_AO_Dashboard_Widget constructor.
         */
        public function __construct()
        {
            parent::__construct();
            add_action('wp_dashboard_setup', [$this, 'disable_default_dashboard_widgets'], 999);
            add_action('wp_before_admin_bar_render', [$this, 'remove_logo_and_submenu']);
            add_filter('admin_footer_text', '__return_empty_string', 11);
        }

        /**
         * Disable default dashboard widgets
         */
        public function disable_default_dashboard_widgets()
        {
            global $wp_meta_boxes;
            // wp..
            if ($this->check_condition('disable_wordpress_dashboard_widget')) {
                unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
                unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
                unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
                unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
                unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
                // bbpress
                unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
                // gravity forms
                unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);
                // Yet Another Stars Rating
                unset($wp_meta_boxes['dashboard']['normal']['core']['yasr_widget_log_dashboard']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['yasr_users_dashboard_widget']);
            }

            // yoast seo
            if (parent::check_condition('disable_yoast_dashboard_widget')) {
                unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['rank_math_dashboard_widget']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['rank_math_dashboard_widget']);
            }

            // woocommerce
            if (parent::check_condition('disable_woo_dashboard_widget')) {
                unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_status']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['woocommerce_dashboard_recent_reviews']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['yith_dashboard_products_news']);
                unset($wp_meta_boxes['dashboard']['normal']['core']['yith_dashboard_blog_news']);
            }
        }

        public function remove_logo_and_submenu()
        {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('wp-logo');
            $wp_admin_bar->remove_menu('about');
            $wp_admin_bar->remove_menu('wporg');
            $wp_admin_bar->remove_menu('documentation');
            $wp_admin_bar->remove_menu('support-forums');
            $wp_admin_bar->remove_menu('feedback');
        }
    }
}
