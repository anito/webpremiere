<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output WooCommerce Product Field
 *
 * @var $type string custom field
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( ! class_exists( 'woocommerce' ) ) {
	return FALSE;
}

global $product;
if ( ! $product ) {
	return FALSE;
}

$value = '';

// Get product data value
if ( $type == 'price' ) {
	$value .= $product->get_price_html();
} elseif ( $type == 'sku' ) {
	$value .= $product->get_sku();
} elseif ( $type == 'rating' ) {
	$value .= wc_get_rating_html( $product->get_average_rating() );
} elseif ( $type == 'sale_badge' AND $product->is_on_sale() ) {
	$value .= $sale_text;
} elseif ( $type == 'weight' AND $product->has_weight() ) {
	$value .= '<span>' . us_translate( 'Weight', 'woocommerce' ) . ': </span>';
	$value .= esc_html( wc_format_weight( $product->get_weight() ) );
} elseif ( $type == 'dimensions' AND $product->has_dimensions()  ) {
	$value .= '<span>' . us_translate( 'Dimensions', 'woocommerce' ) . ': </span>';
	$value .= esc_html( wc_format_dimensions( $product->get_dimensions( FALSE ) ) );
} elseif ( $product_attribute_values = wc_get_product_terms( $product->get_id(), $type, array( 'fields' => 'names' ) ) ) {
	$value .= '<span>' . wc_attribute_label( $type ) . ': </span>';
	$value .= implode( ', ', $product_attribute_values );
}

$classes = isset( $classes ) ? $classes : '';
$classes .= isset( $type ) ? ( ' ' . $type ) : '';

// Output the element
$output = '<div class="w-grid-item-elm' . $classes . '">';
$output .= $value;
$output .= '</div>';

if ( $value != '' ) {
	echo $output;
}
