<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Verify_Site')) {
    class HVN_AO_Verify_Site extends HVN_AO_Base
    {
        protected $google_verify_string, $bing_verify_string, $yandex_verify_string, $pinterest_verify_string;

        /**
         * HVN_AO_Verify_Site constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $this->google_verify_string = (parent::check_condition('google_verify_string')) ? $this->options['google_verify_string'] : '';
            $this->bing_verify_string = (parent::check_condition('bing_verify_string')) ? $this->options['bing_verify_string'] : '';
            $this->yandex_verify_string = (parent::check_condition('yandex_verify_string')) ? $this->options['yandex_verify_string'] : '';
            $this->pinterest_verify_string = (parent::check_condition('pinterest_verify_string')) ? $this->options['pinterest_verify_string'] : '';
            $this->load();
        }

        public function load()
        {
            if ($this->google_verify_string) {
                $this->verify_google();
            }
            if ($this->bing_verify_string) {
                $this->verify_bing();
            }
            if ($this->yandex_verify_string) {
                $this->verify_yandex();
            }
            if ($this->pinterest_verify_string) {
                $this->verify_pinterest();
            }
        }

        public function verify_google()
        {
            echo /** @lang text */ '<meta name="google-site-verification" content="' . $this->google_verify_string . '">';
        }

        public function verify_bing()
        {
            echo /** @lang text */ '<meta name="msvalidate.01" content="' . $this->bing_verify_string . '">';
        }

        public function verify_yandex()
        {
            echo /** @lang text */ '<meta name="yandex-verification" content="' . $this->yandex_verify_string . '">';
        }

        public function verify_pinterest()
        {
            echo /** @lang text */ '<meta name="p:domain_verify" content="' . $this->pinterest_verify_string . '">';
        }
    }
}
