<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Contact_Button')) {
    class HVN_AO_Contact_Button extends HVN_AO_Base
    {
        /**
         * HVN_CDN_Action constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $this->load();
        }

        public function load()
        {
            if (parent::check_condition('enable_contact_button')) {
                add_action('wp_enqueue_scripts', [$this, 'plugin_enqueue_scripts']);
                add_action('wp_footer', [$this, 'render_button']);
            }
        }

        /**
         * Add Css, JS
         */
        public function plugin_enqueue_scripts()
        {
            wp_register_style('hvn.contact.button',
                HVN_AO_DIRECTORY_URL . '/assets/css/hvn.contact.button.css',
                [],
                null,
                'all');

            wp_register_script('hvn.contact.button',
                HVN_AO_DIRECTORY_URL . '/assets/js/hvn.contact.button.js',
                [],
                null,
                true);
            wp_enqueue_style('hvn.contact.button');
            wp_enqueue_script('hvn.contact.button');
        }

        /**
         * Render button
         */
        public function render_button()
        {
            if (parent::check_condition('cb_position') && $this->options['cb_position'] == 'left') {
                $css = $this->left_position_css();
            } else {
                $css = $this->right_position_css();
            }

            $html
                = /** @lang text */
                '' . $css . '
				<div class="fixed-action-btn"><a class="btn-floating btn-large red">
				        <i class="fa fa-commenting"></i>
				    </a><ul>';
            if (parent::check_condition('zalo')) {
                $html
                    .= /** @lang text */
                    '<li>
			            <a href="https://zalo.me/' . $this->options['zalo'] . '" target="_blank" rel="noopener noreferrer nofollow"
			                class="btn-floating zalo-color" style="opacity: 0; transform: scale(0.4) translateY(40px) translateX(0px);">
			                <img src="' . HVN_AO_DIRECTORY_URL . '/assets/img/zalo-min-150x150.png" alt="zalo">
			            </a>
			            <div class="hvn-cb-i-title true hide-it zalo-hover hvn-cb-i-title-right">Zalo</div>
			        </li>';
            }
            if (parent::check_condition('facebook')) {
                $html
                    .= /** @lang text */
                    '<li>
			            <a class="btn-floating facebook-color" href="https://www.facebook.com/' . $this->options['facebook'] . '"
			                target="_blank" rel="noopener noreferrer nofollow"
			                style="opacity: 0; transform: scale(0.4) translateY(40px) translateX(0px);">
			                <i class="fa fa-facebook-square"></i>
			            </a>
			            <div class="hvn-cb-i-title true hide-it facebook-hover hvn-cb-i-title-right">Facebook</div>
			        </li>';
            }
            if (parent::check_condition('skype')) {
                $html
                    .= /** @lang text */
                    '<li>
			            <a class="btn-floating skype-color" href="skype:' . $this->options['skype'] . '?chat"
			                style="opacity: 0; transform: scale(0.4) translateY(40px) translateX(0px);">
			                <i class="fa fa-skype"></i>
			            </a>
			            <div class="hvn-cb-i-title true hide-it skype-hover hvn-cb-i-title-right">Skype</div>
			        </li>';
            }
            if (parent::check_condition('facebook')) {
                $html
                    .= /** @lang text */
                    '<li>
			            <a class="btn-floating fb-message-color darken-1" href="https://m.me/' . $this->options['facebook'] . '"
			                target="_blank" rel="noopener noreferrer nofollow"
			                style="opacity: 0; transform: scale(0.4) translateY(40px) translateX(0px);">
			                <img src="' . HVN_AO_DIRECTORY_URL . '/assets/img/messenger.png" alt="facebook messenger">
			            </a>
			            <div class="hvn-cb-i-title true hide-it message-hover hvn-cb-i-title-right">Facebook messenger</div>
			        </li>';
            }
            if (parent::check_condition('phone')) {
                $html
                    .= /** @lang text */
                    '<li>
			            <a class="btn-floating phone-color green" href="tel:' . $this->options['phone'] . '"
			                style="opacity: 0; transform: scale(0.4) translateY(40px) translateX(0px);">
			                <i class="fa fa-phone"></i>
			            </a>
			            <div class="hvn-cb-i-title true hide-it phone-hover hvn-cb-i-title-right">Phone</div>
			        </li>';
            }
            if (parent::check_condition('email')) {
                $html
                    .= /** @lang text */
                    '<li>
			            <a class="btn-floating email-color" href="mailto:' . antispambot($this->options['email'], 1) . '"
		                    style="opacity: 0; transform: scale(0.4) translateY(40px) translateX(0px);">
			                <i class="fa fa-envelope-o"></i>
			            </a>
			            <div class="hvn-cb-i-title true hide-it email-hover hvn-cb-i-title-right">Email</div>
			        </li>';
            }

            $html
                .= /** @lang text */
                '</ul>';
            if (parent::check_condition('show_hotline')) {
                $hotline = (isset($this->options['phone'])) ? $this->options['phone'] : '';
                $html .= '<div class="hvn-cb-i-title true hide-it hvn-cb-i-title-right"> Hotline: ' . $hotline . '</div>';
            }
            $html
                .= /** @lang text */
                '</div>
				<script> document.addEventListener(\'DOMContentLoaded\', function() {
				        let elements = document.querySelectorAll(\'.fixed-action-btn\');
				        let instances = M.FloatingActionButton.init(elements, {
				            direction: \'top\',
				            hoverEnabled: false
				        });
				    });
				</script>';

            echo $html;
        }

        public function left_position_css()
        {
            return $right_css = /** @lang text */ '<style>
				.fixed-action-btn{position:fixed;left:23px;bottom:45px;padding-top:15px;margin-bottom:0;z-index:997}
				.hvn-cb-i-title{text-align:center;font-size:17px;top:50%;transform:translate(calc(-100% - 13px),-50%);white-space:nowrap;padding:5px 15px;line-height:21px;color:#333;background-color:#fff;box-shadow:0 1.93465px 7.73859px rgba(0,0,0,.15);border-radius:10px;right:0;left:300px;width:198px}
				.hvn-cb-i-title.true:before{content:"";position:absolute;left:-9px;top:51%;transform:translateY(-51%);z-index:10;width:0;height:0;border-top:6px solid transparent;border-bottom:6px solid transparent;border-right:10px solid #f3b112}
			</style>';
        }

        public function right_position_css()
        {
            return $left_css = /** @lang text */ '<style>
				.fixed-action-btn{position:fixed;right:23px;bottom:45px;padding-top:15px;margin-bottom:0;z-index:997}
				.hvn-cb-i-title{text-align:center;font-size:17px;top:50%;transform:translate(calc(-100% - 13px),-50%);white-space:nowrap;line-height:21px;box-shadow:0 1.93465px 7.73859px rgba(0,0,0,.15);border-radius:10px;right:0;left:10px;width:198px}
				.hvn-cb-i-title.true:before{content:"";position:absolute;right:-9px;top:51%;transform:translateY(-51%);z-index:10;width:0;height:0;border-top:6px solid transparent;border-bottom:6px solid transparent;border-right:0;border-left:10px solid #f3b112}
			</style>';
        }
    }
}
