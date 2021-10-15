<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Custom_WPAdmin')) {
    class HVN_AO_Custom_WPAdmin extends HVN_AO_Base
    {
        public function __construct()
        {
            parent::__construct();
            $this->load();
        }

        public function load()
        {
            $actions = $this->action_name();

            foreach ($actions as $value) {
                if (parent::check_condition($value)) {
                    add_action('admin_menu', array($this, $value));
                }
            }
        }

        /**
         * @return array
         */
        public function action_name()
        {
            return array(
                'remove_posts_menu',
                'remove_media_menu',
                'remove_comments_menu',
                'remove_appearance_menu',
                'remove_plugins_menu',
                'remove_users_menu',
                'remove_tools_menu',
                'remove_settings_menu',
                'remove_pages_menu',
                'remove_wc_menu',
                'remove_product_menu',
                'remove_wpcf7_menu'
            );
        }

        public function remove_posts_menu()
        {
            remove_menu_page('edit.php');
        }

        public function remove_pages_menu()
        {
            remove_menu_page('edit.php?post_type=page');
        }

        public function remove_wc_menu()
        {
            remove_menu_page('woocommerce');
        }

        public function remove_wpcf7_menu()
        {
            remove_menu_page('wpcf7');
        }

        public function remove_product_menu()
        {
            remove_menu_page('edit.php?post_type=product');
        }

        public function remove_media_menu()
        {
            remove_menu_page('upload.php');
        }

        public function remove_comments_menu()
        {
            remove_menu_page('edit-comments.php');
        }

        public function remove_appearance_menu()
        {
            remove_menu_page('themes.php');
        }

        public function remove_plugins_menu()
        {
            remove_menu_page('plugins.php');
        }

        public function remove_users_menu()
        {
            remove_menu_page('users.php');
        }

        public function remove_tools_menu()
        {
            remove_menu_page('tools.php');
        }

        public function remove_settings_menu()
        {
            remove_menu_page('options-general.php');
        }

        public function remove_flatsome_menu()
        {
            remove_menu_page('flatsome-panel');
        }

        public function remove_flatsome_in_admin_bar($wp_admin_bar)
        {
            $wp_admin_bar->remove_node('flatsome_panel');
            $wp_admin_bar->remove_node('flatsome-activate');
        }
    }
}
