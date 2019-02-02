<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Button element. Used in Header Builder & Grid builder
 *
 * @var $add_to_cart      string Used in Grid builder
 * @var $view_cart_link   string Used in Grid builder
 * @var $label            string
 * @var $link_type        string Used in Grid builder
 * @var $link             string
 * @var $style            string Button Style
 * @var $icon             string
 * @var $iconpos          string
 * @var $size             string
 * @var $size_tablets     string
 * @var $size_mobiles     string
 * @var $design_options   array
 * @var $classes          string
 * @var $id               string
 */

$classes = isset( $classes ) ? $classes : '';
$btn_classes = 'w-btn us-btn-style_' . $style;

$icon_html = '';

if ( ! empty( $icon ) ) {
	$icon_html = us_prepare_icon_tag( $icon );
	$btn_classes .= ' icon_at' . $iconpos;
} else {
	$btn_classes .= ' icon_none';
}

$text = trim( strip_tags( $label, '<br>' ) );
if ( $text == '' ) {
	$btn_classes .= ' text_none';
}

// Generate anchor semantics
if ( isset( $link_type ) AND $link_type === 'post' ) {
	$link_atts['href'] = apply_filters( 'the_permalink', get_permalink() );
} elseif ( empty( $link_type ) OR $link_type === 'custom' ) {
	$link_atts = us_grid_get_custom_link( $link );
} else { //elseif ( $link_type == 'none' )
	$link_atts['href'] = '';
}

// Output the element
$output = '<div class="w-btn-wrapper' . $classes . '">';
$output .= '<a class="' . $btn_classes . '" href="' . esc_url( $link_atts['href'] ) . '"';
if ( ! empty( $link_atts['target'] ) ) {
	$output .= ' target="' . esc_attr( $link_atts['target'] ) . '"';
}
if ( ! empty( $link_atts['meta'] ) ) {
	$output .= $link_atts['meta'];
}
$output .= '>';
if ( $iconpos == 'left' ) {
	$output .= $icon_html;
}
if ( $text != '' ) {
	$output .= '<span class="w-btn-label">' . $text . '</span>';
}
if ( $iconpos == 'right' ) {
	$output .= $icon_html;
}
$output .= '</a>';
$output .= '</div>';

// Output WooCommerce Add to cart button
if ( class_exists( 'woocommerce' ) AND isset( $add_to_cart ) AND $add_to_cart ) {

	add_filter( 'woocommerce_product_add_to_cart_text', 'us_add_to_cart_text', 99, 2 );
	add_filter( 'woocommerce_loop_add_to_cart_link', 'us_add_to_cart_text_replace', 99, 3 );

	if ( empty( $view_cart_link ) ) {
		$classes .= ' no_view_cart_link';
	}
	echo '<div class="w-btn-wrapper' . $classes . '">';
	woocommerce_template_loop_add_to_cart();
	echo '</div>';

	remove_filter( 'woocommerce_product_add_to_cart_text', 'us_add_to_cart_text', 99 );
	remove_filter( 'woocommerce_loop_add_to_cart_link', 'us_add_to_cart_text_replace', 99 );

} else {
	echo $output;
}
