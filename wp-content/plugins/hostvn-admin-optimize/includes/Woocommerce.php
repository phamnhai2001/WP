<?php

defined('ABSPATH') || exit();

if (!class_exists('HVN_AO_Woocommerce')) {
    class HVN_AO_Woocommerce extends HVN_AO_Base
    {
        /**
         * HVN_AO_Woocommerce constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $this->optimize_woocommerce_admin();
        }

        /**
         * optimize woocommerce admin
         */
        public function optimize_woocommerce_admin()
        {
            if (parent::check_condition('optimize_woocommerce')) {
                //			add_filter( 'woocommerce_my_account_my_orders_columns',
//			            array( $this, 'remove_my_account_my_orders_total' ),
//			            10 );
                add_filter('woocommerce_admin_features', array($this, 'disable_woocommerce_admin_features'));
                // Remove order count from admin menu
                add_filter('woocommerce_include_processing_order_count_in_menu', '__return_false');
                // Remove marketplace suggestions
                add_filter('woocommerce_allow_marketplace_suggestions', '__return_false');
                // Remove connect your store to WooCommerce.com admin notice
                add_filter('woocommerce_helper_suppress_admin_notices', '__return_true');
                // Disable the WooCommerce Admin
                add_filter('woocommerce_admin_disabled', '__return_true');
                // Suppress WooCommerce Helper Admin Notices
                add_filter('woocommerce_helper_suppress_admin_notices', '__return_true');
                // Remove header from WooCommerce admin panel
                add_action('admin_head', array($this, 'woo_remove_wc_breadcrumbs'));

                add_action('widgets_init', array($this, 'remove_woo_widgets'));
//
//				add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_woocommerce_styles' ), 99 );
            }
        }

        public function woo_remove_wc_breadcrumbs()
        {
            remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        }

//		public function hide_woocommerce_breadcrumb() {
//			echo /** @lang text */ '<style>
//			    .woocommerce-layout__header {
//			        display: none !important;
//			    }
//			    .woocommerce-layout__activity-panel-tabs {
//			        display: none !important;
//			    }
//			    .woocommerce-layout__header-breadcrumbs {
//			        display: none !important;
//			    }
//			    .woocommerce-embed-page .woocommerce-layout__primary{
//			        display: none !important;
//			    }
//			    .woocommerce-embed-page #screen-meta, .woocommerce-embed-page #screen-meta-links{top:0 !important;}
//			    </style>';
//		}

        /**
         * Remove order total from my account orders
         *
         * @param $order
         *
         * @return mixed
         */
        public function remove_my_account_my_orders_total($order)
        {
            unset($order['order-total']);

            return $order;
        }

        /**
         * Disable the WooCommere Marketing Hub
         *
         * @param $features
         *
         * @return mixed
         */
        public function disable_woocommerce_admin_features($features)
        {
            $marketing = array_search('marketing', $features);
            unset($features[$marketing]);

            return $features;
        }

        public function remove_woo_widgets()
        {
            unregister_widget('WC_Widget_Recent_Products');
            unregister_widget('WC_Widget_Featured_Products');
            unregister_widget('WC_Widget_Product_Categories');
            unregister_widget('WC_Widget_Product_Tag_Cloud');
            unregister_widget('WC_Widget_Cart');
            unregister_widget('WC_Widget_Layered_Nav');
            unregister_widget('WC_Widget_Layered_Nav_Filters');
            unregister_widget('WC_Widget_Price_Filter');
            unregister_widget('WC_Widget_Product_Search');
            unregister_widget('WC_Widget_Top_Rated_Products');
            unregister_widget('WC_Widget_Recent_Reviews');
            unregister_widget('WC_Widget_Recently_Viewed');
            unregister_widget('WC_Widget_Best_Sellers');
            unregister_widget('WC_Widget_Onsale');
            unregister_widget('WC_Widget_Random_Products');
        }

        public function dequeue_woocommerce_styles()
        {
            //remove generator meta tag
            remove_action('wp_head', array($GLOBALS['woocommerce'], 'generator'));

            //first check that woo exists to prevent fatal errors
            if (function_exists('is_woocommerce')) {
                //dequeue scripts and styles
                if (!is_woocommerce() && !is_cart() && !is_checkout()) {
                    wp_dequeue_style('woocommerce_frontend_styles');
                    wp_dequeue_style('woocommerce_fancybox_styles');
                    wp_dequeue_style('woocommerce_chosen_styles');
                    wp_dequeue_style('woocommerce_prettyPhoto_css');
                    wp_dequeue_script('wc_price_slider');
                    wp_dequeue_script('wc-single-product');
                    wp_dequeue_script('wc-add-to-cart');
                    wp_dequeue_script('wc-cart-fragments');
                    wp_dequeue_script('wc-checkout');
                    wp_dequeue_script('wc-add-to-cart-variation');
                    wp_dequeue_script('wc-single-product');
                    wp_dequeue_script('wc-cart');
                    wp_dequeue_script('wc-chosen');
                    wp_dequeue_script('woocommerce');
                    wp_dequeue_script('prettyPhoto');
                    wp_dequeue_script('prettyPhoto-init');
                    wp_dequeue_script('jquery-blockui');
                    wp_dequeue_script('jquery-placeholder');
                    wp_dequeue_script('fancybox');
                    wp_dequeue_script('jqueryui');
                }
            }
        }
    }
}
