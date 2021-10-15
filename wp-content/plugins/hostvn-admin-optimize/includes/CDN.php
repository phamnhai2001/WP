<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_CDN')) {
    class HVN_AO_CDN extends HVN_AO_Base
    {
        protected $cdn_url, $option, $excludes;

        /**
         * HVN_AO_CDN constructor.
         */
        public function __construct()
        {
            parent::__construct();
            if (is_admin() || (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG)) {
                return;
            }

            $this->excludes = apply_filters('hostvn_cdn_excludes', '#\.php#');
            $this->cdn_url = (isset($this->options['cdn_url'])) ? $this->options['cdn_url'] : "";
            $this->load();
        }

        public function load()
        {
            if (parent::check_condition('enable_cdn') && parent::check_condition('cdn_url')) {
                $this->css_js_cdn();
                $this->uploads_cdn();
            }
        }

        /**
         * Change link css, js
         */
        public function css_js_cdn()
        {
            add_filter('theme_root_uri', [$this, 'rewrite_content'], 99, 1);
            add_filter('plugins_url', [$this, 'rewrite_content'], 99, 1);
            add_filter('script_loader_src', [$this, 'rewrite'], 99, 1);
            add_filter('style_loader_src', [$this, 'rewrite'], 99, 1);
            add_filter('upload_dir', [$this, 'uploads'], 9999);
        }

        /**
         * Change image url
         */
        public function uploads_cdn()
        {
            add_filter('the_content', [$this, 'images']);
            add_filter('wp_get_attachment_image_src', [$this, 'thumbnail'], 9999);
            add_filter('widget_text', [$this, 'images'], 9999);
        }

        /**
         * Rewrite both includes URL and content URL.
         *
         * @param string $url Any URL.
         *
         * @return string
         */
        public function rewrite($url)
        {
            if (1 === preg_match($this->excludes, $url)) {
                return $url;
            }

            $url = $this->replace_includes($url);
            $url = $this->replace_content($url);

            return $url;
        }

        /**
         * Rewrite content URL.
         *
         * @param string $url Any URL.
         *
         * @return string
         */
        public function rewrite_content($url)
        {
            if (1 === preg_match($this->excludes, $url)) {
                return $url;
            }

            $url = $this->replace_content($url);

            return $url;
        }

        /**
         * Replace includes URL if the given constant is present.
         *
         * @param string $url
         *
         * @return string
         */
        private function replace_includes($url)
        {
            $includes_url = site_url('/' . WPINC, null);
            $url = str_replace($includes_url, $this->cdn_url . '/wp-includes', $url);

            return $url;
        }

        /**
         * Replace content URL if the given constant is present.
         *
         * @param string $url
         *
         * @return string
         */
        private function replace_content($url)
        {
            $url = str_replace(WP_CONTENT_URL, $this->cdn_url . '/wp-content', $url);

            return $url;
        }

        /**
         * Rewrite uploads URL.
         *
         * @param array $upload_data
         *
         * @return array
         */
        public function uploads($upload_data)
        {
            $upload_data['url'] = $this->rewrite_content($upload_data['url']);
            $upload_data['baseurl'] = $this->rewrite_content($upload_data['baseurl']);

            return $upload_data;
        }

        /**
         * Rewrite image URL-s in post content.
         *
         * @param string $content
         *
         * @return string
         */
        public function images($content)
        {
            $pattern = '|(<img [^>]*\bsrc=")([^"]+)(" [^>]*\balt="[^"]*")|';

            $content = preg_replace_callback(
                $pattern,
                function ($matches) {
                    $url = $this->rewrite_content($matches[2]);

                    return $matches[1] . $url . $matches[3];
                },
                $content
            );

            return $content;
        }

        /**
         * Rewrite image URL of post thumbnail.
         *
         * @param array|false $image
         *
         * @return array|false
         */
        public function thumbnail($image)
        {
            if (is_array($image) && array_key_exists('src', $image)) {
                $image['src'] = $this->rewrite_content($image['src']);
            }

            return $image;
        }
    }
}
