<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Extras')) {
    class HVN_AO_Extras extends HVN_AO_Base
    {

        /**
         * HVN_AO_Extras constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $this->load();
        }

        public function load()
        {
            if (parent::check_condition('disable_gutenberg')) {
                $this->disable_gutenberg();
            }
            if (parent::check_condition('enable_custom_login_redirect')) {
                add_filter('login_redirect', [$this, 'custom_login_redirect'], 10, 3);
            }
            if (parent::check_condition('enable_maintenance_mode')) {
                add_action('get_header', [$this, 'maintenance_mode_render']);
            }
//            if (parent::check_condition('disable_connection_to_wordpress_org')) {
//                add_filter( 'pre_http_request', '__return_true', 100 );
//            }

            // Disable move to trash
            #add_action('wp_trash_post', [$this, 'restrict_post_deletion'], 10, 1);

            // Disable Delete Permanently
            #add_action('before_delete_post', [$this, 'restrict_post_deletion'], 10, 1);
        }

        public function disable_gutenberg()
        {
            add_filter('use_block_editor_for_post_type', '__return_false', 100);
            add_filter('after_setup_theme', [$this, 'disable_gutenberg_hooks']);
            add_filter('wp_enqueue_scripts', [$this, 'disable_gutenberg_wp_enqueue_scripts'], 100);
            add_filter('mce_buttons', [$this, 'more_mce_buttons_toolbar_1']);
            add_filter('mce_buttons_2', [$this, 'more_mce_buttons_toolbar_2']);
            add_filter('tiny_mce_before_init', [$this, 'format_tinymce']);
            add_filter('mce_external_plugins', [$this, 'add_the_plugin_to_mce']);
        }

        /**
         * Redirecting users on login
         */
        function custom_login_redirect($url, $request, $user)
        {
            if ($user && is_object($user) && is_a($user, 'WP_User')) {
                $url = home_url();
            }

            return $url;
        }

        public function maintenance_mode_render()
        {
            if (!current_user_can('edit_themes') || !is_user_logged_in()) {
                wp_die(
                    __(
                    /** @lang text */ '<strong>Site offline for maintenance!</strong><br/>We will be back soon. 
														Please comeback later!',
                                      'hvn-ao-lang'
                    ),
                    __('Site Offline For Maintenance'),
                    ''
                );
            }
        }

        public function enable_custom_login_logo()
        {
            add_action('login_head', 'custom_login_logo');
        }

        public function custom_login_logo()
        {
            echo /** @lang text */ '<style>
			        h1 a { background-image:url() !important; }
			    </style>';
        }

        public function disable_gutenberg_hooks()
        {
            remove_action('admin_menu', 'gutenberg_menu');
            remove_action('admin_init', 'gutenberg_redirect_demo');

            remove_filter('wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers');
            remove_filter('get_edit_post_link', 'gutenberg_revisions_link_to_editor');
            remove_filter('wp_prepare_revision_for_js', 'gutenberg_revisions_restore');

            remove_action('rest_api_init', 'gutenberg_register_rest_routes');
            remove_action('rest_api_init', 'gutenberg_add_taxonomy_visibility_field');
            remove_filter('rest_request_after_callbacks', 'gutenberg_filter_oembed_result');
            remove_filter('registered_post_type', 'gutenberg_register_post_prepare_functions');

            remove_action('do_meta_boxes', 'gutenberg_meta_box_save', 1000);
            remove_action('submitpost_box', 'gutenberg_intercept_meta_box_render');
            remove_action('submitpage_box', 'gutenberg_intercept_meta_box_render');
            remove_action('edit_page_form', 'gutenberg_intercept_meta_box_render');
            remove_action('edit_form_advanced', 'gutenberg_intercept_meta_box_render');
            remove_filter('redirect_post_location', 'gutenberg_meta_box_save_redirect');
            remove_filter('filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes');

            remove_action('admin_notices', 'gutenberg_build_files_notice');
            remove_filter('body_class', 'gutenberg_add_responsive_body_class');
            remove_filter('admin_url', 'gutenberg_modify_add_new_button_url'); // old
            remove_action('admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks');
            remove_filter('register_post_type_args', 'gutenberg_filter_post_type_labels');

            remove_action('admin_init', 'gutenberg_add_edit_link_filters');
            remove_action('admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button');
            remove_filter('redirect_post_location', 'gutenberg_redirect_to_classic_editor_when_saving_posts');
            remove_filter('display_post_states', 'gutenberg_add_gutenberg_post_state');
            remove_action('edit_form_top', 'gutenberg_remember_classic_editor_when_saving_posts');
        }

        public function disable_gutenberg_wp_enqueue_scripts()
        {
            wp_dequeue_style('wp-block-library');
        }

        public function more_mce_buttons_toolbar_1($buttons)
        {
            $buttons[] = 'fontselect';
            $buttons[] = 'fontsizeselect';
            $buttons[] = 'styleselect';
            $buttons[] = 'blockquote';
            $buttons[] = 'searchreplace';

            return $buttons;
        }

        public function more_mce_buttons_toolbar_2($buttons)
        {
            $buttons[] = 'subscript';
            $buttons[] = 'superscript';
            $buttons[] = 'hr';
            $buttons[] = 'cut';
            $buttons[] = 'copy';
            $buttons[] = 'paste';
            $buttons[] = 'backcolor';
            $buttons[] = 'newdocument';
            $buttons[] = 'charmap';
            $buttons[] = 'table';

            return $buttons;
        }

        public function add_the_plugin_to_mce($plugins)
        {
            $plugins['table']         = plugins_url('tinymce-plugins/table/plugin.min.js', HVN_AO_FILE);
            $plugins['searchreplace'] = plugins_url('tinymce-plugins/searchreplace/plugin.min.js', HVN_AO_FILE);

            return $plugins;
        }

        public function format_tinymce($ins)
        {
            $ins['wordpress_adv_hidden'] = false;
            $ins['forced_root_block']    = "";
            $in['force_p_newlines']      = true;

            return $ins;
        }

        public function restrict_post_deletion($post_ID){
            $error = new WP_Error( 'broke', __( "You are not authorized to delete this page.", "hostvn-ao-lang" ) );
            if( is_wp_error( $error ) ) {
                wp_die($error->get_error_message(), $error->get_error_message());
            }
        }
    }
}
