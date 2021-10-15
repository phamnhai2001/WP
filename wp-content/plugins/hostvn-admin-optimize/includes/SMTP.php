<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_SMTP')) {
    class HVN_AO_SMTP extends HVN_AO_Base
    {
        protected $phpmailer;

        public function __construct()
        {
            parent::__construct();
            if (parent::check_condition('enable_smtp')) {
                add_action('phpmailer_init', [$this, 'setup_phpmailer_init']);
            }
        }

        public function setup_phpmailer_init($phpmailer)
        {
            $phpmailer->isSMTP();
            $phpmailer->Host = (isset($this->options['smtp_host'])) ? sanitize_text_field($this->options['smtp_host']) : '';
            $phpmailer->Port = (isset($this->options['smtp_port'])) ? sanitize_text_field($this->options['smtp_port']) : '';
            $phpmailer->SMTPAuth = (isset($this->options['smtp_auth'])) ? sanitize_text_field($this->options['smtp_auth']) : '';
            $phpmailer->Username = (isset($this->options['smtp_user'])) ? sanitize_text_field($this->options['smtp_user']) : '';
            $phpmailer->Password = (isset($this->options['smtp_pass'])) ? sanitize_text_field($this->options['smtp_pass']) : '';
            $phpmailer->SMTPSecure = (isset($this->options['smtp_secure'])) ? sanitize_text_field($this->options['smtp_secure']) : '';
            $phpmailer->From = (isset($this->options['smtp_from'])) ? sanitize_text_field($this->options['smtp_from']) : '';
            $phpmailer->FromName = (isset($this->options['smtp_name'])) ? sanitize_text_field($this->options['smtp_name']) : '';
        }
    }
}
