<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_sharing
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['type'] string Type: 'simple' / 'solid' / 'outlined' / 'fixed'
 * @param $atts           ['align'] string Alignment: 'left' / 'center' / 'right'
 * @param $atts           ['color'] string Color Style: 'default' / 'primary' / 'secondary'
 * @param $atts           ['counters'] string Share Counters: 'show' / 'hide'
 * @param $atts           ['email'] bool Is Email button available?
 * @param $atts           ['facebook'] bool Is Facebook button available?
 * @param $atts           ['twitter'] bool Is Twitter button available?
 * @param $atts           ['gplus'] bool Is Google+ button available?
 * @param $atts           ['linkedin'] bool Is LinkedIn button available?
 * @param $atts           ['pinterest'] bool Is Pinterest button available?
 * @param $atts           ['vk'] bool Is VK button available?
 * @param $atts           ['url'] string Sharing URL
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_sharing' );

$classes = ' type_' . $atts['type'] . ' align_' . $atts['align'] . ' color_' . $atts['color'];
if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}
$classes .= ( ! empty( $atts['el_class'] ) ) ? ( ' ' . $atts['el_class'] ) : '';
$el_id = ( ! empty( $atts['el_id'] ) ) ? ( ' id="' . esc_attr( $atts['el_id'] ) . '"' ) : '';

// The list of available sharing providers and additional in-shortcode data
$providers = array(
	'email' => array(
		'title' => __( 'Email this', 'us' ),
	),
	'facebook' => array(
		'title' => __( 'Share this', 'us' ),
	),
	'twitter' => array(
		'title' => __( 'Tweet this', 'us' ),
	),
	'gplus' => array(
		'title' => __( 'Share this', 'us' ),
	),
	'linkedin' => array(
		'title' => __( 'Share this', 'us' ),
	),
	'pinterest' => array(
		'title' => __( 'Pin this', 'us' ),
	),
	'vk' => array(
		'title' => __( 'Share this', 'us' ),
	),
);

$set_providers = explode( ',', $atts['providers'] );

// Keeping only the actually used providers
foreach ( $providers as $provider => $provider_data ) {
	if ( ! in_array( $provider, $set_providers ) ) {
		unset( $providers[$provider] );
	}
}
if ( empty( $providers ) ) {
	return;
}

if ( empty( $atts['url'] ) ) {
	// Using the current page URL
	$atts['url'] = home_url( $_SERVER['REQUEST_URI'] );
}

if ( $atts['counters'] == 'show' ) {
	$counts = us_get_sharing_counts( $atts['url'], array_keys( $providers ) );
}

$post_thumbnail_id = get_post_thumbnail_id();
$post_thumbnail = $post_thumbnail_id ? wp_get_attachment_image_url( $post_thumbnail_id, 'full' ) : '';

// Output the element
$output = '<div class="w-sharing' . $classes . '"' . $el_id . '>';
foreach ( $providers as $provider => $provider_data ) {
	$output .= '<a class="w-sharing-item ' . $provider . '" title="' . esc_attr( $provider_data['title'] ) . '" href="javascript:void(0)" data-sharing-url="' . esc_attr( $atts['url'] ) . '" data-sharing-image="' . esc_attr( $post_thumbnail ) . '">';
	$output .= '<span class="w-sharing-icon"></span>';
	if ( $atts['counters'] == 'show' AND isset( $counts[$provider] ) AND $counts[$provider] != 0 ) {
		$output .= '<span class="w-sharing-count">' . $counts[$provider] . '</span>';
	}
	$output .= '</a>';
}
$output .= '</div>';

echo $output;
