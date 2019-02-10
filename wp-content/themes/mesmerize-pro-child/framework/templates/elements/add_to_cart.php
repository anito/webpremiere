<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Add to cart button element
 */

// Button wrapper
$wrapper_classes = isset( $classes ) ? $classes : '';

// Output WooCommerce Add to cart button
if ( class_exists( 'woocommerce' ) ) {

	add_filter( 'woocommerce_product_add_to_cart_text', 'us_add_to_cart_text', 99, 2 );
	add_filter( 'woocommerce_loop_add_to_cart_link', 'us_add_to_cart_text_replace', 99, 3 );

	if ( empty( $view_cart_link ) ) {
		$wrapper_classes .= ' no_view_cart_link';
	}
	echo '<div class="w-btn-wrapper woocommerce' . $wrapper_classes . '">';
	woocommerce_template_loop_add_to_cart();
	echo '</div>';

	remove_filter( 'woocommerce_product_add_to_cart_text', 'us_add_to_cart_text', 99 );
	remove_filter( 'woocommerce_loop_add_to_cart_link', 'us_add_to_cart_text_replace', 99 );
}
