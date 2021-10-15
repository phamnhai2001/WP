<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Admin')) {
    class HVN_AO_Admin extends HVN_AO_Base
    {
        protected $menuSlug                  = 'hvn-admin-optimize-settings';
        protected $hvn_ao_widget_section     = 'hvn_ao_widget_section',
                  $hvn_ao_update_section     = 'hvn_ao_update_section',
                  $hvn_ao_recaptcha_section  = 'hvn_ao_recaptcha_section',
                  $hvn_ao_extra_section      = 'hvn_ao_extra_section',
                  $hvn_ao_smtp_section       = 'hvn_ao_smtp_section',
                  $hvn_ao_admin_menu_section = 'hvn_ao_admin_menu_section',
                  $hvn_ao_cb_section         = 'hvn_ao_cb_section',
                  $hvn_ao_optimize_section   = 'hvn_ao_optimize_section ',
                  $hvn_ao_security_section   = 'hvn_ao_security_section';

        /**
         * HVN_AO_Admin constructor.
         */
        public function __construct()
        {
            parent::__construct();
            add_action('admin_menu', [$this, 'setting_menu']);
            add_action('admin_init', [$this, 'register_setting_and_fields']);
        }

        public function setting_menu()
        {
            add_submenu_page(
                'options-general.php',
                __('Hostvn Admin Optimize Settings', 'hostvn-ao-lang'),
                __('Hostvn AO Settings', 'hostvn-ao-lang'),
                'manage_options',
                $this->menuSlug,
                [$this, 'setting_page']
            );
        }

        public function setting_page()
        {
            require_once(HVN_AO_SETTING_PAGE . '/setting-page.php');
        }

        public function register_setting_and_fields()
        {
            register_setting('hostvn_ao_option', 'hostvn_admin_optimize', [$this, 'validate']);
            $option_value = $this->option_value();

            foreach ($option_value as $key => $value) {
                switch ($key) {
                    case 'disable_plugins_update':
                    case 'disable_theme_update':
                    case 'disable_core_update':
                    case 'wpb_stop_update_emails':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_update_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => ''
                            ]
                        );
                        break;
                    case 'enable_recaptcha':
                    case 'recaptcha_site_key':
                    case 'recaptcha_secret_key':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_recaptcha_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => ''
                            ]
                        );
                        break;
                    case 'disable_gutenberg':
                    case 'enable_custom_login_redirect':
                    case 'enable_maintenance_mode':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_extra_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => ''
                            ]
                        );
                        break;
                    case 'disable_wp_generator':
                    case 'disable_xmlrpc':
                    case 'disable_rest_api':
                    case 'disable_user_api':
                    case 'disable_login_errors':
                    case 'login_url':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_security_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => __('Ex: login', 'hostvn-ao-lang')
                            ]
                        );
                        break;
                    case 'disable_wp_embed':
                    case 'disable_json_link':
                    case 'disable_feeds':
                    case 'disable_rsd':
                    case 'disable_wlw_manifest':
                    case 'limit_revisions':
                    case 'slow_heartbeat':
                    case 'disable_emoji':
                    case 'disable_contact_form_7_js_css':
                    case 'jquery_to_footer':
                    case 'remove_scripts_version':
                    case 'optimize_woocommerce':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_optimize_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => ''
                            ]
                        );
                        break;
                    case 'disable_wordpress_dashboard_widget':
                    case 'disable_yoast_dashboard_widget':
                    case 'disable_woo_dashboard_widget':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_widget_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => ''
                            ]
                        );
                        break;
                    case 'enable_smtp':
                    case 'smtp_host':
                    case 'smtp_port':
                    case 'smtp_auth':
                    case 'smtp_secure':
                    case 'smtp_user':
                    case 'smtp_pass':
                    case 'smtp_from':
                    case 'smtp_name':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_smtp_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => ''
                            ]
                        );
                        break;
                    case 'remove_posts_menu':
                    case 'remove_media_menu':
                    case 'remove_comments_menu':
                    case 'remove_appearance_menu':
                    case 'remove_plugins_menu':
                    case 'remove_users_menu':
                    case 'remove_tools_menu':
                    case 'remove_settings_menu':
                    case 'remove_pages_menu':
                    case 'remove_wpcf7_menu':
                    case 'remove_product_menu':
                    case 'remove_wc_menu':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_admin_menu_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => ''
                            ]
                        );
                        break;
                    case 'enable_contact_button':
                    case 'show_hotline':
                    case 'facebook':
                    case 'zalo':
                    case 'skype':
                    case 'email':
                    case 'phone':
                    case 'cb_position':
                        add_settings_field(
                            $key,
                            '',
                            [$this, 'create_form'],
                            $this->menuSlug,
                            $this->hvn_ao_cb_section,
                            $args = [
                                'name'        => $key,
                                'label'       => __($value, 'hostvn-ao-lang'),
                                'placeholder' => ''
                            ]
                        );
                        break;
                }
            }
        }

        /**
         * Create setting input
         *
         * @param $args
         */
        public function create_form($args)
        {
            switch ($args['name']) {
                case 'recaptcha_site_key':
                case 'recaptcha_secret_key':
                case 'smtp_name':
                case 'smtp_from':
                case 'smtp_user':
                case 'smtp_port':
                case 'smtp_host':
                case 'facebook':
                case 'zalo':
                case 'skype':
                case 'email':
                case 'phone':
                case 'login_url':
                    $value = (isset($this->options[$args['name']])) ? $this->options[$args['name']] : '';
                    printf(
                    /** @lang text */ '<div class="group-label"><label for="%s" class="ao-label">%s</label>
						<input type="text" name="hostvn_admin_optimize[%s]" id="%s" value="%s" class="regular-text code" 
							placeholder="%s"></div>',
                                      $args['name'],
                                      $args['label'],
                                      $args['name'],
                                      $args['name'],
                                      $value,
                                      $args['placeholder']
                    );
                    break;
                case 'cb_position':
                    $html
                        = /** @lang text */
                        '<div class="group-label"><label for="' . $args['name'] . '" class="ao-label">' . $args['label'] . '</label>
					<select name="hostvn_admin_optimize[' . $args['name'] . ']" id="' . $args['name'] . '" 
						class="postform">';
                    if (isset($this->options[$args['name']]) && $this->options[$args['name']] == 'left') {
                        $html
                            .= /** @lang text */
                            '<option value="left" selected>' . esc_html__('Left', 'hostvn-ao-lang') . '</option>
							<option value="right">' . esc_html__('Right', 'hostvn-ao-lang') . '</option>';
                    } else {
                        $html
                            .= /** @lang text */
                            '<option value="left">' . esc_html__('Left', 'hostvn-ao-lang') . '</option>
							<option value="right" selected>' . esc_html__('Right', 'hostvn-ao-lang') . '</option>';
                    }
                    $html
                        .= /** @lang text */
                        '</select></div>';
                    echo $html;
                    break;
                case 'smtp_pass':
                    $value = '';
                    if (parent::check_condition('smtp_pass')) {
                        $value = '*******************';
                    }
                    printf(
                    /** @lang text */ '<div class="group-label"><label for="%s" class="ao-label">%s</label>
						<input type="password" name="hostvn_admin_optimize[%s]" id="%s" value="%s" 
							class="regular-text code"></div>',
                                      $args['name'],
                                      $args['label'],
                                      $args['name'],
                                      $args['name'],
                                      $value
                    );
                    break;
                case 'smtp_secure':
                    $html
                        = /** @lang text */
                        '<div class="group-label"><label for="' . $args['name'] . '" class="ao-label">' . $args['label'] . '</label>
					<select name="hostvn_admin_optimize[' . $args['name'] . ']" id="' . $args['name'] . '" 
						class="postform">';
                    if (isset($this->options[$args['name']]) && ($this->options[$args['name']] == 'none' || $this->options[$args['name']] == '')) {
                        $html
                            .= /** @lang text */
                            '<option value="none" selected>' . esc_html__('None', 'hostvn-ao-lang') . '</option>
							<option value="tls" >' . esc_html__('TLS', 'hostvn-ao-lang') . '</option>
							<option value="ssl">' . esc_html__('SSL', 'hostvn-ao-lang') . '</option>';
                    } elseif (isset($this->options[$args['name']]) && $this->options[$args['name']] == 'tls') {
                        $html
                            .= /** @lang text */
                            '<option value="none">' . esc_html__('None', 'hostvn-ao-lang') . '</option>
							<option value="tls" selected>' . esc_html__('TLS', 'hostvn-ao-lang') . '</option>
							<option value="ssl">' . esc_html__('SSL', 'hostvn-ao-lang') . '</option>';
                    } else {
                        $html
                            .= /** @lang text */
                            '<option value="none">' . esc_html__('None', 'hostvn-ao-lang') . '</option>
							<option value="tls">' . esc_html__('TLS', 'hostvn-ao-lang') . '</option>
							<option value="ssl" selected>' . esc_html__('SSL', 'hostvn-ao-lang') . '</option>';
                    }
                    $html
                        .= /** @lang text */
                        '</select></div>';
                    echo $html;
                    break;
                default:
                    $html
                        = /** @lang text */
                        '<div class="group-label"><label for="' . $args['name'] . '" class="ao-label">' . $args['label'] . '</label>
						<select name="hostvn_admin_optimize[' . $args['name'] . ']" id="' . $args['name'] . '" 
							class="postform">';
                    if (isset($this->options[$args['name']]) && $this->options[$args['name']] == true) {
                        $html
                            .= /** @lang text */
                            '<option value="1" selected>' . esc_html__('Yes', 'hostvn-ao-lang') . '</option>
							<option value="0">' . esc_html__('No', 'hostvn-ao-lang') . '</option>';
                    } else {
                        $html
                            .= /** @lang text */
                            '<option value="1">' . esc_html__('Yes', 'hostvn-ao-lang') . '</option>
							<option value="0" selected>' . esc_html__('No', 'hostvn-ao-lang') . '</option>';
                    }
                    $html
                        .= /** @lang text */
                        '</select></div>';
                    echo $html;
                    break;
            }
        }

        /**
         * Validate input
         *
         * @param $input
         *
         * @return array
         */
        public function validate($input)
        {
            $new_input   = $this->option_default_value();
            $valid_array = ['0', '1'];

            foreach ($input as $key => $value) {
                switch ($key) {
                    case 'enable_recaptcha':
                    case 'recaptcha_site_key':
                    case 'recaptcha_secret_key':
                    case 'smtp_host':
                    case 'smtp_auth':
                    case 'smtp_user':
                    case 'smtp_pass':
                    case 'smtp_from':
                    case 'smtp_name':
                    case 'facebook':
                    case 'skype':
                    case 'login_url':
                        if (isset($input[$key])) {
                            $new_input[$key] = sanitize_text_field($input[$key]);
                        }
                        break;
                    case 'smtp_port':
                    case 'zalo':
                    case 'phone':
                        if (isset($input[$key]) && is_numeric($input[$key])) {
                            $new_input[$key] = sanitize_text_field($input[$key]);
                        }
                        break;
                    case 'smtp_secure':
                        if (isset($input['smtp_secure']) && in_array(
                                $input['smtp_secure'],
                                ['tls', 'ssl']
                            )) {
                            $new_input['smtp_secure'] = sanitize_text_field($input['smtp_secure']);
                        }
                        break;
                    case 'cb_position':
                        if (isset($input['cb_position']) && in_array(
                                $input['cb_position'],
                                ['left', 'right']
                            )) {
                            $new_input['cb_position'] = sanitize_text_field($input['cb_position']);
                        }
                        break;
                    case 'email':
                        if (isset($input['email']) && is_email($input['email'])) {
                            $new_input['email'] = sanitize_text_field($input['email']);
                        }
                        break;
                    default:
                        if (isset($input[$key]) && in_array(
                                $input[$key],
                                $valid_array
                            )) {
                            $new_input[$key] = sanitize_text_field($input[$key]);
                        }
                        break;
                }
            }

            return $new_input;
        }

        /**
         * Option default Value
         *
         * @return array
         */
        public function option_default_value()
        {
            return [
                'disable_wordpress_dashboard_widget'  => (parent::check_condition(
                    'disable_wordpress_dashboard_widget'
                )) ? $this->options['disable_wordpress_dashboard_widget'] : true,
                'disable_yoast_dashboard_widget'      => (parent::check_condition(
                    'disable_yoast_dashboard_widget'
                )) ? $this->options['disable_yoast_dashboard_widget'] : true,
                'disable_woo_dashboard_widget'        => (parent::check_condition(
                    'disable_woo_dashboard_widget'
                )) ? $this->options['disable_woo_dashboard_widget'] : true,
                'disable_plugins_update'              => (parent::check_condition(
                    'disable_plugins_update'
                )) ? $this->options['disable_plugins_update'] : false,
                'disable_theme_update'                => (parent::check_condition(
                    'disable_theme_update'
                )) ? $this->options['disable_theme_update'] : false,
                'disable_core_update'                 => (parent::check_condition(
                    'disable_core_update'
                )) ? $this->options['disable_core_update'] : false,
                'disable_wp_generator'                => (parent::check_condition(
                    'disable_wp_generator'
                )) ? $this->options['disable_wp_generator'] : true,
                'disable_login_errors'                => (parent::check_condition(
                    'disable_login_errors'
                )) ? $this->options['disable_login_errors'] : false,
                'enable_recaptcha'                    => (parent::check_condition(
                    'enable_recaptcha'
                )) ? $this->options['enable_recaptcha'] : false,
                'recaptcha_site_key'                  => (parent::check_condition(
                    'recaptcha_site_key'
                )) ? $this->options['recaptcha_site_key'] : false,
                'recaptcha_secret_key'                => (parent::check_condition(
                    'recaptcha_secret_key'
                )) ? $this->options['recaptcha_secret_key'] : false,
                'enable_custom_login_redirect'        => (parent::check_condition(
                    'enable_custom_login_redirect'
                )) ? $this->options['enable_custom_login_redirect'] : false,
                'disable_xmlrpc'                      => (parent::check_condition(
                    'disable_xmlrpc'
                )) ? $this->options['disable_xmlrpc'] : false,
                'disable_user_api'                    => (parent::check_condition(
                    'disable_user_api'
                )) ? $this->options['disable_user_api'] : false,
                'enable_maintenance_mode'             => (parent::check_condition(
                    'enable_maintenance_mode'
                )) ? $this->options['enable_maintenance_mode'] : false,
                'disable_wp_embed'                    => (parent::check_condition(
                    'disable_wp_embed'
                )) ? $this->options['disable_wp_embed'] : false,
                'disable_rest_api'                    => (parent::check_condition(
                    'disable_rest_api'
                )) ? $this->options['disable_rest_api'] : false,
                'disable_json_link'                   => (parent::check_condition(
                    'disable_json_link'
                )) ? $this->options['disable_json_link'] : false,
                'disable_feeds'                       => (parent::check_condition(
                    'disable_feeds'
                )) ? $this->options['disable_feeds'] : false,
                'disable_rsd'                         => (parent::check_condition(
                    'disable_rsd'
                )) ? $this->options['disable_rsd'] : false,
                'disable_contact_form_7_js_css'       => (parent::check_condition(
                    'disable_contact_form_7_js_css'
                )) ? $this->options['disable_contact_form_7_js_css'] : false,
                'jquery_to_footer'                    => (parent::check_condition(
                    'jquery_to_footer'
                )) ? $this->options['jquery_to_footer'] : false,
                'slow_heartbeat'                      => (parent::check_condition(
                    'slow_heartbeat'
                )) ? $this->options['slow_heartbeat'] : false,
                'remove_scripts_version'              => (parent::check_condition(
                    'remove_scripts_version'
                )) ? $this->options['remove_scripts_version'] : false,
                'disable_wlw_manifest'                => (parent::check_condition(
                    'disable_wlw_manifest'
                )) ? $this->options['disable_wlw_manifest'] : false,
                'limit_revisions'                     => (parent::check_condition(
                    'limit_revisions'
                )) ? $this->options['limit_revisions'] : false,
                'disable_gutenberg'                   => (parent::check_condition(
                    'disable_gutenberg'
                )) ? $this->options['disable_gutenberg'] : false,
                'disable_emoji'                       => (parent::check_condition(
                    'disable_emoji'
                )) ? $this->options['disable_emoji'] : false,
                'optimize_woocommerce'                => (parent::check_condition(
                    'optimize_woocommerce'
                )) ? $this->options['optimize_woocommerce'] : false,
                'enable_smtp'                         => (parent::check_condition(
                    'enable_smtp'
                )) ? $this->options['enable_smtp'] : '',
                'smtp_host'                           => (parent::check_condition(
                    'smtp_host'
                )) ? $this->options['smtp_host'] : '',
                'smtp_port'                           => (parent::check_condition(
                    'smtp_port'
                )) ? $this->options['smtp_port'] : '',
                'smtp_auth'                           => (parent::check_condition(
                    'smtp_auth'
                )) ? $this->options['smtp_auth'] : '',
                'smtp_user'                           => (parent::check_condition(
                    'smtp_user'
                )) ? $this->options['smtp_user'] : '',
                'smtp_pass'                           => (parent::check_condition(
                    'smtp_pass'
                )) ? $this->options['smtp_pass'] : '',
                'smtp_secure'                         => (parent::check_condition(
                    'smtp_secure'
                )) ? $this->options['smtp_secure'] : '',
                'smtp_from'                           => (parent::check_condition(
                    'smtp_from'
                )) ? $this->options['smtp_from'] : '',
                'smtp_name'                           => (parent::check_condition(
                    'smtp_name'
                )) ? $this->options['smtp_name'] : '',
                'enable_contact_button'               => (parent::check_condition(
                    'enable_contact_button'
                )) ? $this->options['enable_contact_button'] : false,
                'show_hotline'                        => (parent::check_condition(
                    'show_hotline'
                )) ? $this->options['show_hotline'] : false,
                'facebook'                            => (parent::check_condition(
                    'facebook'
                )) ? $this->options['facebook'] : '',
                'zalo'                                => (parent::check_condition(
                    'zalo'
                )) ? $this->options['zalo'] : '',
                'skype'                               => (parent::check_condition(
                    'skype'
                )) ? $this->options['skype'] : '',
                'email'                               => (parent::check_condition(
                    'email'
                )) ? $this->options['email'] : '',
                'phone'                               => (parent::check_condition(
                    'phone'
                )) ? $this->options['phone'] : '',
                'cb_position'                         => (parent::check_condition(
                    'cb_position'
                )) ? $this->options['cb_position'] : 'left',
                'remove_posts_menu'                   => (parent::check_condition(
                    'remove_posts_menu'
                )) ? $this->options['remove_posts_menu'] : false,
                'remove_media_menu'                   => (parent::check_condition(
                    'remove_media_menu'
                )) ? $this->options['remove_media_menu'] : false,
                'remove_comments_menu'                => (parent::check_condition(
                    'remove_comments_menu'
                )) ? $this->options['remove_comments_menu'] : false,
                'remove_appearance_menu'              => (parent::check_condition(
                    'remove_appearance_menu'
                )) ? $this->options['remove_appearance_menu'] : false,
                'remove_plugins_menu'                 => (parent::check_condition(
                    'remove_plugins_menu'
                )) ? $this->options['remove_plugins_menu'] : false,
                'remove_users_menu'                   => (parent::check_condition(
                    'remove_users_menu'
                )) ? $this->options['remove_users_menu'] : false,
                'remove_tools_menu'                   => (parent::check_condition(
                    'remove_tools_menu'
                )) ? $this->options['remove_tools_menu'] : false,
                'remove_settings_menu'                => (parent::check_condition(
                    'remove_settings_menu'
                )) ? $this->options['remove_settings_menu'] : false,
                'remove_pages_menu'                   => (parent::check_condition(
                    'remove_pages_menu'
                )) ? $this->options['remove_pages_menu'] : false,
                'remove_wpcf7_menu'                   => (parent::check_condition(
                    'remove_wpcf7_menu'
                )) ? $this->options['remove_wpcf7_menu'] : false,
                'remove_product_menu'                 => (parent::check_condition(
                    'remove_product_menu'
                )) ? $this->options['remove_product_menu'] : false,
                'remove_wc_menu'                      => (parent::check_condition(
                    'remove_wc_menu'
                )) ? $this->options['remove_wc_menu'] : false,
                'remove_flatsome_menu'                => (parent::check_condition(
                    'remove_flatsome_menu'
                )) ? $this->options['remove_flatsome_menu'] : false,
                'login_url'                           => (parent::check_condition(
                    'login_url'
                )) ? $this->options['login_url'] : '',
                'wpb_stop_update_emails'              => (parent::check_condition(
                    'wpb_stop_update_emails'
                )) ? $this->options['wpb_stop_update_emails'] : false,
            ];
        }

        /**
         * Option Value
         *
         * @return array
         */
        public function option_value()
        {
            return [
                'disable_wordpress_dashboard_widget'  => esc_html__(
                    'Disable Wordpress dashboard widget',
                    'hostvn-ao-lang'
                ),
                'disable_yoast_dashboard_widget'      => esc_html__(
                    'Disable Yoast Seo dashboard widget',
                    'hostvn-ao-lang'
                ),
                'disable_woo_dashboard_widget'        => esc_html__(
                    'Disable Woocommerce dashboard widget',
                    'hostvn-ao-lang'
                ),
                'disable_plugins_update'              => esc_html__('Disable plugins update', 'hostvn-ao-lang'),
                'disable_theme_update'                => esc_html__('Disable theme update', 'hostvn-ao-lang'),
                'disable_core_update'                 => esc_html__('Disable Wordpress core update', 'hostvn-ao-lang'),
                'disable_wp_generator'                => esc_html__('Disable Wordpress generator', 'hostvn-ao-lang'),
                'disable_login_errors'                => esc_html__('Disable login errors message', 'hostvn-ao-lang'),
                'enable_recaptcha'                    => esc_html__('Enable reCaptcha in login form', 'hostvn-ao-lang'),
                'recaptcha_site_key'                  => esc_html__('reCaptcha site key (V2)', 'hostvn-ao-lang'),
                'recaptcha_secret_key'                => esc_html__('reCaptcha secret key (V2)', 'hostvn-ao-lang'),
                'enable_custom_login_redirect'        => esc_html__(
                    'Enable redirect to home page after login',
                    'hostvn-ao-lang'
                ),
                'disable_xmlrpc'                      => esc_html__('Disable XMLRPC', 'hostvn-ao-lang'),
                'disable_user_api'                    => esc_html__('Disable User API', 'hostvn-ao-lang'),
                'disable_rest_api'                    => esc_html__(
                    'Require login to access rest api',
                    'hostvn-ao-lang'
                ),
                'enable_maintenance_mode'             => esc_html__('Enable maintenance mode', 'hostvn-ao-lang'),
                'disable_wp_embed'                    => esc_html__('Disable Wordpress embed', 'hostvn-ao-lang'),
                'disable_json_link'                   => esc_html__('Disable Json Url', 'hostvn-ao-lang'),
                'disable_contact_form_7_js_css'       => esc_html__('Disable Contact Form 7 JS/CSS', 'hostvn-ao-lang'),
                'remove_scripts_version'              => esc_html__('Remove scripts version', 'hostvn-ao-lang'),
                'slow_heartbeat'                      => esc_html__('Slow heartbeat', 'hostvn-ao-lang'),
                'disable_feeds'                       => esc_html__('Disable Wordpress feed', 'hostvn-ao-lang'),
                'disable_rsd'                         => esc_html__('Disable Wordpress RSD', 'hostvn-ao-lang'),
                'disable_wlw_manifest'                => esc_html__('Disable WLW manifest', 'hostvn-ao-lang'),
                'jquery_to_footer'                    => esc_html__('Move Jquery to footer', 'hostvn-ao-lang'),
                'optimize_woocommerce'                => esc_html__('Optimize Woocommerce', 'hostvn-ao-lang'),
                'limit_revisions'                     => esc_html__('Limit post revisions', 'hostvn-ao-lang'),
                'disable_gutenberg'                   => esc_html__('Disable Gutenberg editor', 'hostvn-ao-lang'),
                'disable_emoji'                       => esc_html__('Disable emoji', 'hostvn-ao-lang'),
                'enable_smtp'                         => esc_html__('Enable SMTP', 'hostvn-ao-lang'),
                'smtp_host'                           => esc_html__('SMTP host', 'hostvn-ao-lang'),
                'smtp_port'                           => esc_html__('SMTP port', 'hostvn-ao-lang'),
                'smtp_auth'                           => esc_html__('SMTP Authenticate', 'hostvn-ao-lang'),
                'smtp_user'                           => esc_html__('SMTP username', 'hostvn-ao-lang'),
                'smtp_pass'                           => esc_html__('SMTP password', 'hostvn-ao-lang'),
                'smtp_secure'                         => esc_html__('SMTP secure', 'hostvn-ao-lang'),
                'smtp_from'                           => esc_html__('SMTP from address', 'hostvn-ao-lang'),
                'smtp_name'                           => esc_html__('SMTP from name', 'hostvn-ao-lang'),
                'enable_contact_button'               => esc_html__('Enable floating contact button', 'hostvn-ao-lang'),
                'show_hotline'                        => esc_html__('Show hotline', 'hostvn-ao-lang'),
                'facebook'                            => esc_html__('Facebook', 'hostvn-ao-lang'),
                'zalo'                                => esc_html__('Zalo', 'hostvn-ao-lang'),
                'skype'                               => esc_html__('Skype', 'hostvn-ao-lang'),
                'email'                               => esc_html__('Email', 'hostvn-ao-lang'),
                'phone'                               => esc_html__('Hotline', 'hostvn-ao-lang'),
                'cb_position'                         => esc_html__('Position', 'hostvn-ao-lang'),
                'remove_posts_menu'                   => esc_html__('Remove posts menu', 'hostvn-ao-lang'),
                'remove_media_menu'                   => esc_html__('Remove media menu', 'hostvn-ao-lang'),
                'remove_comments_menu'                => esc_html__('Remove comments menu', 'hostvn-ao-lang'),
                'remove_appearance_menu'              => esc_html__('Remove appearance menu', 'hostvn-ao-lang'),
                'remove_plugins_menu'                 => esc_html__('Remove plugins menu', 'hostvn-ao-lang'),
                'remove_users_menu'                   => esc_html__('Remove users menu', 'hostvn-ao-lang'),
                'remove_tools_menu'                   => esc_html__('Remove tools menu', 'hostvn-ao-lang'),
                'remove_settings_menu'                => esc_html__('Remove settings menu', 'hostvn-ao-lang'),
                'remove_pages_menu'                   => esc_html__('Remove pages menu', 'hostvn-ao-lang'),
                'remove_wc_menu'                      => esc_html__('Remove WooCommerce menu', 'hostvn-ao-lang'),
                'remove_product_menu'                 => esc_html__('Remove products menu', 'hostvn-ao-lang'),
                'remove_wpcf7_menu'                   => esc_html__('Remove Contact form 7 menu', 'hostvn-ao-lang'),
                'remove_flatsome_menu'                => esc_html__('Remove Flatsome menu', 'hostvn-ao-lang'),
                'login_url'                           => esc_html__('New Login URL', 'hostvn-ao-lang'),
                'wpb_stop_update_emails'              => esc_html__(
                    'Disable Automatic Update Email Notification',
                    'hostvn-ao-lang'
                )
            ];
        }
    }
}
