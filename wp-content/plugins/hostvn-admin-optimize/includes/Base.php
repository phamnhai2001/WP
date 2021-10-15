<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Base')) {
    class HVN_AO_Base
    {

        protected $options;

        /**
         * Hostvn_AO_Base constructor.
         */
        public function __construct()
        {
            $this->options = get_option('hostvn_admin_optimize');
        }

        /**
         * @param $key
         *
         * @return bool
         */
        public function check_condition($key)
        {
            if (isset($this->options[$key]) && $this->options[$key]) {
                return true;
            }

            return false;
        }

        /**
         * @param $className
         *
         * @return mixed|null
         */
        public static function init($className)
        {
            static $instance = null;

            if (!$instance) {
                $instance = new $className();
            }

            return $instance;
        }
    }
}
