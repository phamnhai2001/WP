<?php

/*
Plugin Name: Hostvn Admin Optimize
Description: Speed up wp-admin access
Version: 1.0.7
Author: Hostvn.net
Author URI: https://hostvn.net
License: GPL2
*/

defined( 'ABSPATH' ) || exit();

define( 'HVN_AO_FILE', __FILE__ );
define( "HVN_AO_DIRECTORY", dirname( __FILE__ ) );
define( "HVN_AO_SETTING_PAGE", HVN_AO_DIRECTORY . '/admin/view' );
define( "HVN_AO_DIRECTORY_URL", plugins_url( null, __FILE__ ) );
define( 'HVN_AO_BASENAME', plugin_basename( __FILE__ ) );

require_once( HVN_AO_DIRECTORY . '/includes/Activation.php' );
require_once( HVN_AO_DIRECTORY . '/includes/Base.php' );
require_once( HVN_AO_DIRECTORY . '/includes/AdminDashboardWidget.php' );
require_once( HVN_AO_DIRECTORY . '/includes/DisableUpdate.php' );
require_once( HVN_AO_DIRECTORY . '/includes/Extras.php' );
require_once( HVN_AO_DIRECTORY . '/includes/Recaptcha.php' );
require_once( HVN_AO_DIRECTORY . '/includes/Security.php' );
require_once( HVN_AO_DIRECTORY . '/includes/Optimize.php' );
require_once( HVN_AO_DIRECTORY . '/includes/Woocommerce.php' );
require_once( HVN_AO_DIRECTORY . '/includes/WPAdminCustom.php' );
require_once( HVN_AO_DIRECTORY . '/includes/SMTP.php' );
require_once( HVN_AO_DIRECTORY . '/includes/CDN.php' );
require_once( HVN_AO_DIRECTORY . '/includes/ContactButton.php' );
require_once( HVN_AO_DIRECTORY . '/includes/RenameWPLogin.php' );
require_once( HVN_AO_DIRECTORY . '/admin/Admin.php' );

HVN_AO_Activation::init( __FILE__ );

HVN_AO_Recaptcha::init( 'HVN_AO_Recaptcha' );
HVN_AO_Extras::init( 'HVN_AO_Extras' );
HVN_AO_Disable_Update::init( 'HVN_AO_Disable_Update' );
HVN_AO_Optimize::init( 'HVN_AO_Optimize' );
HVN_AO_Security::init( 'HVN_AO_Security' );
HVN_AO_Woocommerce::init( 'HVN_AO_Woocommerce' );
HVN_AO_SMTP::init( 'HVN_AO_SMTP' );
HVN_AO_Contact_Button::init( 'HVN_AO_Contact_Button' );
HVN_AO_CDN::init( 'HVN_AO_CDN' );
HVN_AO_Rename_WP_Login::init( 'HVN_AO_Rename_WP_Login' );

if ( is_admin() ) {
	HVN_AO_Dashboard_Widget::init( 'HVN_AO_Dashboard_Widget' );
	HVN_AO_Custom_WPAdmin::init( 'HVN_AO_Custom_WPAdmin' );
	HVN_AO_Admin::init( 'HVN_AO_Admin' );
}
