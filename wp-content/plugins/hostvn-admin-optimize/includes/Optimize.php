<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Optimize')) {
    class HVN_AO_Optimize extends HVN_AO_Base
    {
        /**
         * HVN_AO_Optimize constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $this->load();
        }

        public function load()
        {
            $conditions = [
                'disable_wp_embed',
                'disable_json_link',
                'disable_feeds',
                'disable_rsd',
                'disable_wlw_manifest',
                'limit_revisions',
                'disable_contact_form_7_js_css',
                'remove_scripts_version',
                'disable_emoji',
                'jquery_to_footer',
            ];
            foreach ($conditions as $value) {
                if (parent::check_condition($value)) {
                    $this->$value();
                }
            }
        }

        /**
         * Contact Form 7 is one of the most popular contact form plugins available for WordPress. If you use Contact Form 7,
         * then you should know that their CSS and JS files are loaded with each page of your website,
         * whether that includes a form or not. It’s an unnecessary waste of resources that you should avoid.
         */
        public function disable_contact_form_7_js_css()
        {
            add_filter('wpcf7_load_js', '__return_false');
            add_filter('wpcf7_load_css', '__return_false');
        }

        /**
         * Block plugins connect to external http
         */
        public function block_external_http()
        {
            if (!is_admin()) {
                add_filter('pre_http_request',
                    function () {
                        return new WP_Error('http_request_failed', __('Request blocked.'));
                    },
                    100);
            }
        }

        /**
         * Removes the Embed Javascript and References
         */
        public function disable_wp_embed()
        {
            add_action('wp_enqueue_scripts',
                function () {
                    wp_deregister_script('wp-embed');
                },
                100);

            add_action('init',
                function () {
                    remove_action('wp_head', 'wp_oembed_add_host_js');
                    remove_action('wp_head', 'wp_oembed_add_discovery_links');
                    remove_action('rest_api_init', 'wp_oembed_register_route');
                    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
                    add_filter('embed_oembed_discover', '__return_false');
                });
        }

        /**
         * Disables the access to Rest API
         */
        public function disable_json_link()
        {
            remove_action('wp_head', 'rest_output_link_wp_head', 10);
            remove_action('template_redirect', 'rest_output_link_header', 11);
            add_filter('json_enabled', '__return_false');
            add_filter('json_jsonp_enabled', '__return_false');
            add_filter('rest_jsonp_enabled', '__return_false');
        }

        /**
         * Removes RSS feeds
         */
        public function disable_feeds()
        {
            remove_action('wp_head', 'feed_links_extra', 3);
            remove_action('wp_head', 'feed_links', 2);
            add_action('do_feed', [$this, 'disable_feeds_hook'], 1);
            add_action('do_feed_rdf', [$this, 'disable_feeds_hook'], 1);
            add_action('do_feed_rss', [$this, 'disable_feeds_hook'], 1);
            add_action('do_feed_rss2', [$this, 'disable_feeds_hook'], 1);
            add_action('do_feed_atom', [$this, 'disable_feeds_hook'], 1);
        }

        /**
         * Removes the actual feed links
         */
        public function disable_feeds_hook()
        {
            wp_die(/** @lang text */ '<p>' . __('Feed disabled.') . '</p>');
        }

        /**
         * Disables RSD Links, used by pingbacks
         */
        public function disable_rsd()
        {
            remove_action('wp_head', 'rsd_link');
        }

        /**
         * Removes WLW manifest bloat
         */
        public function disable_wlw_manifest()
        {
            remove_action('wp_head', 'wlwmanifest_link');
        }

        /**
         * Limits post revisions
         */
        public function limit_revisions()
        {
            if (defined('WP_POST_REVISIONS') && (WP_POST_REVISIONS != false)) {
                add_filter('wp_revisions_to_keep',
                    function ($num, $post) {
                        return 3;
                    },
                    10,
                    2);
            }
        }

        public function disable_emoji()
        {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
            add_filter('tiny_mce_plugins', [$this, 'wp_disable_emoji_tinymce']);
        }

        public function wp_disable_emoji_tinymce($plugins)
        {
            unset($plugins['wpemoji']);

            return $plugins;
        }

        /**
         * Slows heartbeat to 1 minute
         */
        public function slow_heartbeat()
        {
            add_filter('heartbeat_settings',
                function ($settings) {
                    $settings['interval'] = 60;

                    return $settings;
                });
        }

        /**
         * Removes the WP Short link
         */
        public function disable_short_link()
        {
            remove_action('wp_head', 'wp_shortlink_wp_head', 10);
        }

        /**
         * Removes the version hook on scripts and styles
         */
        public function remove_scripts_version()
        {
            add_filter('style_loader_src', [$this, 'disable_script_version'], 9999);
            add_filter('script_loader_src', [$this, 'disable_script_version'], 9999);
        }

        public function disable_script_version($target_url = '')
        {
            if (strpos($target_url, 'ver=')) {
                $target_url = remove_query_arg('ver', $target_url);
            }

            return $target_url;
        }

        /**
         * Puts jquery inside the footer
         */
        public function jquery_to_footer()
        {
            add_action('wp_enqueue_scripts',
                function () {
                    wp_deregister_script('jquery');
                    wp_register_script('jquery', includes_url('/js/jquery/jquery.js'), false, null, true);
                    wp_enqueue_script('jquery');
                });
        }

        public function enable_queries_and_page_load_time()
        {
            add_action('wp_footer', [$this, 'show_queries_and_page_load_time']);
        }

        public function show_queries_and_page_load_time()
        {
            if (current_user_can('administrator')) {
                echo get_num_queries() . ' ' . esc_html__('queries in') . ' ' . timer_stop(1) . ' ' . esc_html__('seconds');
            }
        }
    }
}
