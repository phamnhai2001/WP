<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Recaptcha')) {
    class HVN_AO_Recaptcha extends HVN_AO_Base
    {

        /**
         * @var string
         */
        protected $error_message;

        /**
         * HVN_AO_Recaptcha constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $this->error_message = __(/** @lang text */ '<strong>ERROR</strong>: Please verify that you are not a robot.',
                'hostvn-ao-lang');
            $this->enqueue_recaptcha_scripts();
            $this->load();
        }

        public function load()
        {
            if ($this->check_recaptcha_option()) {
                $this->enable_recaptcha_in_login_form();
                $this->enable_recaptcha_in_register_form();
                $this->enable_recaptcha_in_lost_password_form();
            }
        }

        /**
         * Added Google recaptcha to login form
         */
        public function enable_recaptcha_in_login_form()
        {
            add_action("login_form", array($this, "display_captcha"));
            add_action('wp_authenticate_user', array($this, "verify_login_captcha"), 10, 2);
        }

        /**
         * Added Google recaptcha to register form
         */
        public function enable_recaptcha_in_register_form()
        {
            add_action("register_form", array($this, "display_captcha"));
            add_action('registration_errors', array($this, 'verify_register_captcha'), 10, 3);
        }

        /**
         * Added Google recaptcha to lost password form
         */
        public function enable_recaptcha_in_lost_password_form()
        {
            add_action("lostpassword_form", array($this, "display_captcha"));
            add_action("lostpassword_post", array($this, 'verify_lost_password_captcha'));
        }

        /**
         * Enqueue recaptcha scripts
         */
        public function enqueue_recaptcha_scripts()
        {
            if ($this->check_recaptcha_option()) {
                add_action("login_enqueue_scripts", array($this, "recaptcha_script"));
            }
        }

        /**
         * Add recaptcha api script
         */
        public function recaptcha_script()
        {
            wp_register_script("recaptcha_login", "https://www.google.com/recaptcha/api.js");
            wp_enqueue_script("recaptcha_login");
        }

        /**
         * Show captcha in login form
         */
        public function display_captcha()
        { ?>
            <div class="g-recaptcha" data-sitekey="<?php
            echo $this->options['recaptcha_site_key'] ?>"
                 style="transform:scale(0.89);-webkit-transform:scale(0.89);transform-origin:0 0;-webkit-transform-origin:0 0"></div>
            <?php
        }

        /**
         * Verify captcha
         *
         * @return WP_Error
         */
        public function verify_captcha()
        {
            $response = (isset($_POST['g-recaptcha-response'])) ? $_POST['g-recaptcha-response'] : '';
            $remote_ip = $_SERVER["REMOTE_ADDR"];
            $recaptcha_secret = $this->options['recaptcha_secret_key'];

            $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$response}&remoteip={$remote_ip}");
            $response = wp_remote_retrieve_body($response);
            $response = json_decode($response, true);

            return $response["success"];
        }

        /**
         * Verify captcha login form
         *
         * @param $user
         * @param $password
         *
         * @return WP_Error
         */
        public function verify_login_captcha($user, $password)
        {
            if (!isset($_POST['g-recaptcha-response']) || !$this->verify_captcha()) {
                return new WP_Error('recaptcha_error', $this->error_message);
            }

            return $user;
        }

        /**
         * Verify captcha register form
         *
         * @param $errors
         * @param $sanitized_user_login
         * @param $user_email
         *
         * @return mixed
         */
        public function verify_register_captcha($errors, $sanitized_user_login, $user_email)
        {
            if (!isset($_POST['g-recaptcha-response']) || !$this->verify_captcha()) {
                $errors->add('recaptcha_error', $this->error_message);
            }

            return $errors;
        }

        /**
         * @param $errors
         *
         * @return mixed
         */
        public function verify_lost_password_captcha($errors)
        {
            if (!isset($_POST['g-recaptcha-response']) || !$this->verify_captcha()) {
                $errors->add('recaptcha_error', $this->error_message);
            }

            return $errors;
        }

        /**
         * @return bool
         */
        public function check_recaptcha_option()
        {
            if (isset($this->options['enable_recaptcha']) && $this->options['enable_recaptcha'] == true &&
                isset($this->options['recaptcha_site_key']) &&
                isset($this->options['recaptcha_secret_key'])) {
                return true;
            }

            return false;
        }
    }
}
