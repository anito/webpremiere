<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_btn
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['text'] string Button label
 * @param $atts           ['link'] string Button link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $atts           ['style'] string Button Style: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white' / 'custom'
 * @param $atts           ['size'] string Button size
 * @param $atts           ['hover_style'] string Hover Style: 'fade' / 'slide'
 * @param $atts           ['align'] string Button alignment: 'left' / 'center' / 'right'
 * @param $atts           ['icon'] string Button icon
 * @param $atts           ['iconpos'] string Icon position: 'left' / 'right'
 * @param $atts           ['el_id'] string Element ID
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_btn' );
$classes = $wrapper_classes = $icon_html = '';

// Button indexes starting from 1
global $us_btn_index;
$us_btn_index = isset( $us_btn_index ) ? ( $us_btn_index + 1 ) : 1;

$classes .= ' us_btn_' . $us_btn_index;
$classes .= ' us-btn-style_' . $atts['style'];
if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( $atts['icon'] != '' ) {
	$icon_html = us_prepare_icon_tag( $atts['icon'] );
	$classes .= ' icon_at' . $atts['iconpos'];
} else {
	$classes .= ' icon_none';
}

$text = trim( strip_tags( $atts['text'], '<br>' ) );
if ( $text == '' ) {
	$classes .= ' text_none';
}

$wrapper_classes .= ' width_' . $atts['width_type'];

// Set alignment class for non fullwidth button only
if ( $atts['width_type'] != 'full' ) {
	$wrapper_classes .= ' align_' . $atts['align'];
}

// Set link attributes
$link = us_vc_build_link( $atts['link'] );
$_link_attr = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
$_link_attr .= ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
$_link_attr .= empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );

if ( ! isset( $atts['size'] ) OR trim( $atts['size'] ) == ( us_get_option( 'body_fontsize' ) . 'px' ) ) {
	$atts['size'] = '';
}

$inline_css = us_prepare_inline_css(
	array(
		'font-size' => $atts['size'],
		'width' => ( $atts['width_type'] == 'custom' AND $atts['align'] == 'center' ) ? $atts['width'] : NULL,
		'max-width' => ( $atts['width_type'] == 'max' AND $atts['align'] == 'center' ) ? $atts['width'] : NULL,
	)
);
$wrapper_inline_css = us_prepare_inline_css(
	array(
		'width' => ( $atts['width_type'] == 'custom' AND $atts['align'] != 'center' ) ? $atts['width'] : NULL,
		'max-width' => ( $atts['width_type'] == 'max' AND $atts['align'] != 'center' ) ? $atts['width'] : NULL,
	)
);

$el_id = ( isset( $atts['el_id'] ) AND ! empty( $atts['el_id'] ) ) ? ( ' id="' . $atts['el_id'] . '"' ) : '';

// Output the element
$output = '<div class="w-btn-wrapper' . $wrapper_classes . '"' . $wrapper_inline_css . '>';
// Add Size on mobiles as inline style tag
if ( ! empty( $atts['size_mobiles'] ) ) {
	$output .= '<style>@media(max-width:600px){.us_btn_' . $us_btn_index . '{font-size:' . $atts['size_mobiles'] . '!important}}</style>';
}
$output .= '<a class="w-btn' . $classes . '" href="' . esc_url( $link['url'] ) . '"';
$output .= $_link_attr . $inline_css . $el_id;
$output .= '>';
if ( $atts['iconpos'] == 'left' ) {
	$output .= $icon_html;
}
if ( $text != '' ) {
	$output .= '<span class="w-btn-label">' . $text . '</span>';
}
if ( $atts['iconpos'] == 'right' ) {
	$output .= $icon_html;
}
$output .= '</a></div>';

echo $output;
