<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Security')) {
    class HVN_AO_Security extends HVN_AO_Base
    {

        /**
         * HVN_AO_Security constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $this->load();
        }

        public function load()
        {
            if (parent::check_condition('disable_login_errors')) {
                $this->change_wordpress_login_errors();
            }
            if (parent::check_condition('disable_xmlrpc')) {
                $this->disable_xmlrpc();
            }
            if (parent::check_condition('disable_wp_generator')) {
                $this->remove_wp_generator();
            }
            if (parent::check_condition('disable_user_api')) {
                $this->disable_user_api();
            }
            if (parent::check_condition('disable_rest_api')) {
                $this->rest_authentication_errors();
            }
        }

        /**
         * Remove Wordpress generator meta
         */
        public function remove_wp_generator()
        {
            remove_action('wp_head', 'wp_generator');
            add_filter('the_generator', '__return_null');
        }

        /**
         * Login required to access res api
         */
        public function rest_authentication_errors()
        {
            add_filter('rest_authentication_errors',
                function ($result) {
                    if (!empty($result)) {
                        return $result;
                    }
                    if (!is_user_logged_in()) {
                        return new WP_Error('restx_logged_out',
                            esc_html__('Sorry, you must be logged in to make a request.',
                                'hostvn_ao_lang'),
                            array('status' => 401));
                    }

                    return $result;
                });
        }

        /**
         * Block Get User API: /wp-json/wp/v2/users/
         */
        public function disable_user_api()
        {
            add_filter('rest_endpoints',
                function ($endpoints) {
                    if (isset($endpoints['/wp/v2/users'])) {
                        unset($endpoints['/wp/v2/users']);
                    }
                    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
                        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
                    }

                    return $endpoints;
                });
        }

        /**
         * Change login error message
         */
        public function change_wordpress_login_errors()
        {
            add_filter('login_errors', array($this, 'wordpress_login_errors_message'));
        }

        /**
         * @return string
         */
        public function wordpress_login_errors_message()
        {
            return __('Something is wrong!', 'hostvn-ao-lang');
        }

        /**
         * Disables XML RPC. Warning, makes some functions unavailable!
         */
        public function disable_xmlrpc()
        {
            if (is_admin()) {
                update_option('default_ping_status', 'closed');
            }

            add_filter('xmlrpc_enabled', '__return_false');
            add_filter('pre_update_option_enable_xmlrpc', '__return_false');
            add_filter('pre_option_enable_xmlrpc', '__return_zero');

            add_filter('wp_headers',
                function ($headers) {
                    if (isset($headers['X-Pingback'])) {
                        unset($headers['X-Pingback']);
                    }

                    return $headers;
                },
                10,
                1);

            add_filter('xmlrpc_methods',
                function ($methods) {
                    unset($methods['pingback.ping']);
                    unset($methods['pingback.extensions.getPingbacks']);

                    return $methods;
                },
                10,
                1);
        }
    }
}
