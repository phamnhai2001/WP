<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

update_option( get_template() . '_wup_purchase_code', '*******' );
update_option( get_template() . '_wup_supported_until', '01.01.2030' );
update_option( get_template() . '_wup_buyer', 'Hotrowordpress' );

require get_template_directory() . '/inc/init.php';

/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */
function my_custom_translations( $strings ) {
$text = array(
'Quick View' => 'Xem chi tiết',
'SHOPPING CART' => 'Giỏ hàng',
'CHECKOUT DETAILS' => 'Thanh toán',
'ORDER COMPLETE' => 'Hoàn thành'
);
 
$strings = str_ireplace( array_keys( $text ), $text, $strings );
return $strings;
}
add_filter( 'gettext', 'my_custom_translations', 20 );

add_filter( 'woocommerce_product_tabs', 'dieuhau_remove_product_tabs', 98 );
function dieuhau_remove_product_tabs( $tabs ) {
   unset( $tabs['additional_information'] );
   return $tabs;
}

