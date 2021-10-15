<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Disable_Update')) {
    class HVN_AO_Disable_Update extends HVN_AO_Base
    {

        /**
         * HVN_AO_Disable_Update constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $this->disable_update();
        }

        /**
         * Disable update
         */
        public function disable_update()
        {
            if (parent::check_condition('disable_plugins_update')) {
                add_filter('auto_update_plugin', '__return_false');
                remove_action('load-update-core.php','wp_update_plugins');
                add_filter('pre_site_transient_update_plugins','__return_null');
            }
            if (parent::check_condition('disable_theme_update')) {
                add_filter('auto_update_theme', '__return_false');
            }
            if (parent::check_condition('disable_core_update')) {
                add_filter('auto_update_core', '__return_false');
                add_filter('pre_site_transient_update_core', [$this, 'remove_core_updates']);
                add_filter('pre_site_transient_update_plugins', [$this, 'remove_core_updates']);
                add_filter('pre_site_transient_update_themes', [$this, 'remove_core_updates']);
            }
            if (parent::check_condition('wpb_stop_update_emails')) {
                $this->disable_automatic_update_email();
            }
        }

        public function remove_core_updates()
        {
            global $wp_version;
            return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
        }

        public function disable_automatic_update_email()
        {
            add_filter('auto_core_update_send_email', [$this, 'wpb_stop_auto_update_emails'], 10, 4);
            add_filter('auto_plugin_update_send_email', '__return_false');
            add_filter('auto_theme_update_send_email', '__return_false');
        }

        public function wpb_stop_update_emails($send, $type, $core_update, $result)
        {
            if (!empty($type) && $type == 'success') {
                return false;
            }
            return true;
        }
    }
}
