<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Activation')) {
    class HVN_AO_Activation
    {
        protected $file, $autoload = 'yes';

        /**
         * HVN_AO_Activation constructor.
         *
         * @param $file
         */
        public function __construct($file)
        {
            $this->file = $file;
            register_activation_hook($file, [$this, 'activate']);
            register_deactivation_hook($file, [$this, 'deactivate']);
            add_action('plugins_loaded', [$this, 'load_plugin_text_domain']);
        }

        /**
         * Activate plugins
         */
        public function activate()
        {
            $this->create_option();
        }

        /**
         * Deactivate plugins
         */
        public function deactivate()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'options';
            $data       = ['autoload' => 'no'];
            $wpdb->update($table_name, $data, array('option_name' => 'hostvn_admin_optimize'), "%s", "%s");
        }

        /**
         * Create wp-options row
         */
        public function create_option()
        {
            global $wpdb;

            $hostvn_options = $this->option_value();

            if (!get_option('hostvn_admin_optimize')) {
                add_option(
                    $option = "hostvn_admin_optimize",
                    $value = $hostvn_options,
                    $deprecated = '',
                    $this->autoload
                );
            } else {
                $wpdb->update(
                    "{$wpdb->prefix}options",
                    array('autoload' => $this->autoload),
                    array('option_name' => 'hostvn_admin_optimize'),
                    "%s",
                    "%s"
                );
            }
        }

        /**
         * @return array
         */
        public function option_value()
        {
            return [
                'disable_wordpress_dashboard_widget'  => true,
                'disable_yoast_dashboard_widget'      => true,
                'disable_woo_dashboard_widget'        => true,
                'disable_plugins_update'              => false,
                'disable_theme_update'                => false,
                'disable_core_update'                 => false,
                'disable_wp_generator'                => true,
                'disable_login_errors'                => false,
                'enable_recaptcha'                    => false,
                'recaptcha_site_key'                  => '',
                'recaptcha_secret_key'                => '',
                'enable_custom_login_redirect'        => false,
                'disable_xmlrpc'                      => false,
                'disable_user_api'                    => false,
                'disable_rest_api'                    => false,
                'enable_maintenance_mode'             => false,
                'disable_wp_embed'                    => false,
                'disable_json_link'                   => false,
                'disable_feeds'                       => false,
                'disable_rsd'                         => false,
                'disable_contact_form_7_js_css'       => false,
                'remove_scripts_version'              => false,
                'optimize_woocommerce'                => false,
                'jquery_to_footer'                    => false,
                'slow_heartbeat'                      => false,
                'disable_wlw_manifest'                => false,
                'limit_revisions'                     => false,
                'disable_gutenberg'                   => false,
                'disable_emoji'                       => false,
                'enable_smtp'                         => false,
                'smtp_host'                           => '',
                'smtp_port'                           => '',
                'smtp_auth'                           => '',
                'smtp_user'                           => '',
                'smtp_pass'                           => '',
                'smtp_secure'                         => '',
                'smtp_from'                           => '',
                'smtp_name'                           => '',
                'enable_contact_button'               => false,
                'show_hotline'                        => false,
                'facebook'                            => '',
                'zalo'                                => '',
                'skype'                               => '',
                'email'                               => '',
                'phone'                               => '',
                'cb_position'                         => 'left',
                'remove_posts_menu'                   => false,
                'remove_media_menu'                   => false,
                'remove_comments_menu'                => false,
                'remove_appearance_menu'              => false,
                'remove_plugins_menu'                 => false,
                'remove_users_menu'                   => false,
                'remove_tools_menu'                   => false,
                'remove_settings_menu'                => false,
                'remove_pages_menu'                   => false,
                'remove_wc_menu'                      => false,
                'remove_product_menu'                 => false,
                'remove_wpcf7_menu'                   => false,
                'remove_flatsome_menu'                => false,
                'wpb_stop_update_emails'              => false,
                'login_url'                           => ''
            ];
        }

        /**
         * Load Language
         *
         * @access  public
         * @return  void
         * @since   1.0.0
         */
        function load_plugin_text_domain()
        {
            $path = dirname(plugin_basename($this->file)) . '/languages/';

            load_plugin_textdomain(
                'hostvn-ao-lang',
                false,
                $path
            );
        }

        /**
         * @param null $file
         *
         * @return HVN_AO_Activation|null
         */
        public static function init($file = null)
        {
            static $instance = null;

            if (!$instance) {
                $instance = new HVN_AO_Activation($file);
            }

            return $instance;
        }
    }
}
