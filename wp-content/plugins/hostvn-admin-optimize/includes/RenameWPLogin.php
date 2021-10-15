<?php

/*
 * Author:      Ella van Durpe
 * Github:  https://github.com/ellatrix/rename-wp-login
 */

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Rename_WP_Login')) {
    class HVN_AO_Rename_WP_Login extends HVN_AO_Base
    {
        private $wp_login_php;

        public function __construct()
        {
            parent::__construct();

            if (parent::check_condition('login_url')) {
                add_action('plugins_loaded', array($this, 'plugins_loaded'), 1);
                add_action('wp_loaded', array($this, 'wp_loaded'));

                add_filter('site_url', array($this, 'site_url'), 10, 4);
                add_filter('wp_redirect', array($this, 'wp_redirect'), 10, 2);

                remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
            }
        }

        private function path()
        {
            return trailingslashit(dirname(__FILE__));
        }

        private function use_trailing_slashes()
        {
            return '/' === substr(get_option('permalink_structure'), -1, 1);
        }

        private function user_trailingslashit($string)
        {
            return $this->use_trailing_slashes() ? trailingslashit($string) : untrailingslashit($string);
        }

        private function wp_template_loader()
        {
            global $pagenow;

            $pagenow = 'index.php';

            if (!defined('WP_USE_THEMES')) {
                define('WP_USE_THEMES', true);
            }

            wp();

            if ($_SERVER['REQUEST_URI'] === $this->user_trailingslashit(str_repeat('-/', 10))) {
                $_SERVER['REQUEST_URI'] = $this->user_trailingslashit('/wp-login-php/');
            }

            require_once(ABSPATH . WPINC . '/template-loader.php');

            die;
        }

        private function new_login_slug()
        {
            if (
                ($slug = $this->options['login_url']) || (
                    is_multisite() &&
                    is_plugin_active_for_network(HVN_AO_BASENAME) &&
                    ($slug = $this->options['login_url'])
                ) ||
                ($slug = 'login')
            ) {
                return $slug;
            }
        }

        public function new_login_url($scheme = null)
        {
            if (get_option('permalink_structure')) {
                return $this->user_trailingslashit(home_url('/', $scheme) . $this->new_login_slug());
            } else {
                return home_url('/', $scheme) . '?' . $this->new_login_slug();
            }
        }

        public function plugins_loaded()
        {
            global $pagenow;

            $request = parse_url($_SERVER['REQUEST_URI']);

            if ((
                    strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false ||
                    untrailingslashit($request['path']) === site_url('wp-login', 'relative')
                ) &&
                !is_admin()
            ) {
                $this->wp_login_php = true;
                $_SERVER['REQUEST_URI'] = $this->user_trailingslashit('/' . str_repeat('-/', 10));
                $pagenow = 'index.php';
            } elseif (
                untrailingslashit($request['path']) === home_url($this->new_login_slug(), 'relative') || (
                    !get_option('permalink_structure') &&
                    isset($_GET[$this->new_login_slug()]) &&
                    empty($_GET[$this->new_login_slug()])
                )) {
                $pagenow = 'wp-login.php';
            }
        }

        public function wp_loaded()
        {
            global $pagenow;

            if (is_admin() && !is_user_logged_in() && !defined('DOING_AJAX')) {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                get_template_part(404);
                exit();
            }

            $request = parse_url($_SERVER['REQUEST_URI']);

            if (
                $pagenow === 'wp-login.php' &&
                $request['path'] !== $this->user_trailingslashit($request['path']) &&
                get_option('permalink_structure')
            ) {
                wp_safe_redirect($this->user_trailingslashit($this->new_login_url()) . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
                die;
            } elseif ($this->wp_login_php) {
                if (
                    ($referer = wp_get_referer()) &&
                    strpos($referer, 'wp-activate.php') !== false &&
                    ($referer = parse_url($referer)) &&
                    !empty($referer['query'])
                ) {
                    parse_str($referer['query'], $referer);

                    if (
                        !empty($referer['key']) &&
                        ($result = wpmu_activate_signup($referer['key'])) &&
                        is_wp_error($result) && (
                            $result->get_error_code() === 'already_active' ||
                            $result->get_error_code() === 'blog_taken'
                        )) {
                        wp_safe_redirect($this->new_login_url() . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
                        die;
                    }
                }

                $this->wp_template_loader();
            } elseif ($pagenow === 'wp-login.php') {
                global $error, $interim_login, $action, $user_login;

                @require_once ABSPATH . 'wp-login.php';

                die;
            }
        }

        public function site_url($url, $path, $scheme, $blog_id)
        {
            return $this->filter_wp_login_php($url, $scheme);
        }

        public function wp_redirect($location, $status)
        {
            return $this->filter_wp_login_php($location);
        }

        public function filter_wp_login_php($url, $scheme = null)
        {
            if (strpos($url, 'wp-login.php') !== false) {
                if (is_ssl()) {
                    $scheme = 'https';
                }

                $args = explode('?', $url);

                if (isset($args[1])) {
                    parse_str($args[1], $args);
                    $url = add_query_arg($args, $this->new_login_url($scheme));
                } else {
                    $url = $this->new_login_url($scheme);
                }
            }

            return $url;
        }

        public function forbidden_slugs()
        {
            $wp = new WP;

            return array_merge($wp->public_query_vars, $wp->private_query_vars);
        }
    }
}
