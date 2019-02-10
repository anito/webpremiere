<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_separator
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['size'] string Separator Height: 'small' / 'medium' / 'large' / 'huge' / 'custom'
 * @param $atts           ['height'] string Separator custom height
 * @param $atts           ['show_line'] bool Show the line in the middle?
 * @param $atts           ['line_width'] string Separator type: 'default' / 'fullwidth' / 'short'
 * @param $atts           ['thick'] string Line thickness: '1' / '2' / '3' / '4' / '5'
 * @param $atts           ['style'] string Line style: 'solid' / 'dashed' / 'dotted' / 'double'
 * @param $atts           ['color'] string Color style: 'border' / 'primary' / 'secondary' / 'custom'
 * @param $atts           ['bdcolor'] string Border color value
 * @param $atts           ['icon'] string Icon
 * @param $atts           ['text'] string Title
 * @param $atts           ['title_tag'] string Title HTML tag: 'h1' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'div'
 * @param $atts           ['title_size'] string Font Size
 * @param $atts           ['align'] string Alignment
 * @param $atts           ['link'] string Link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $atts           ['el_class'] string Extra class name
 * @param $atts           ['breakpoint_1_width'] string Screen Width breakpoint 1
 * @param $atts           ['breakpoint_1_height'] string Separator custom height 1
 * @param $atts           ['breakpoint_2_width'] string Screen Width breakpoint 2
 * @param $atts           ['breakpoint_2_height'] string Separator custom height 2
 */

$atts = us_shortcode_atts( $atts, 'us_separator' );

$classes = $inner_html = $inline_css = $link_opener = $link_closer = $responsive_styles = '';

// Set element index to apply <style> for responsive CSS
if ( $atts['size'] == 'custom' AND $atts['breakpoint_1_height'] != '' OR $atts['breakpoint_2_height'] != '' ) {
	global $us_separator_index;
	$us_separator_index = isset( $us_separator_index ) ? ( $us_separator_index + 1 ) : 1;
	$classes .= ' us_separator_' . $us_separator_index;

	$responsive_styles = '<style>';
	if ( $atts['breakpoint_1_height'] != '' AND $atts['breakpoint_1_height'] != $atts['height'] ) {
		$responsive_styles .= '@media(max-width:' . esc_attr( $atts['breakpoint_1_width'] ) . '){.us_separator_' . $us_separator_index . '{height:' . esc_attr( $atts['breakpoint_1_height'] ) . '!important}}';
	}
	if ( $atts['breakpoint_2_height'] != '' AND $atts['breakpoint_2_height'] != $atts['height'] ) {
		$responsive_styles .= '@media(max-width:' . esc_attr( $atts['breakpoint_2_width'] ) . '){.us_separator_' . $us_separator_index . '{height:' . esc_attr( $atts['breakpoint_2_height'] ) . '!important}}';
	}
	$responsive_styles .= '</style>';
}

$classes .= ' size_' . $atts['size'];
if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}
$classes .= ( ! empty( $atts['el_class'] ) ) ? ( ' ' . $atts['el_class'] ) : '';
$el_id = ( ! empty( $atts['el_id'] ) ) ? ( ' id="' . esc_attr( $atts['el_id'] ) . '"' ) : '';

// Generate link semantics
$link = us_vc_build_link( $atts['link'] );
if ( ! empty( $link['url'] ) ) {
	$link_target = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$link_rel = ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
	$link_title = empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
	$link_opener = '<a href="' . esc_url( $link['url'] ) . '"' . $link_target . $link_rel . $link_title . '>';
	$link_closer = '</a>';
}

// Generate separator icon and title
if ( $atts['show_line'] ) {
	$classes .= ' with_line';
	$classes .= ' width_' . $atts['line_width'];
	$classes .= ' thick_' . $atts['thick'];
	$classes .= ' style_' . $atts['style'];
	$classes .= ' color_' . $atts['color'];
	$classes .= ' align_' . $atts['align'];

	if ( ! empty( $atts['text'] ) ) {
		$inner_html .= '<' . $atts['title_tag'] . ' class="w-separator-text">';
		$inner_html .= $link_opener;
		$inner_html .= us_prepare_icon_tag( $atts['icon'] );
		$inner_html .= '<span>' . $atts['text'] . '</span>';
		$inner_html .= $link_closer;
		$inner_html .= '</' . $atts['title_tag'] . '>';
	} else {
		$inner_html .= us_prepare_icon_tag( $atts['icon'] );
	}

	if ( $inner_html != '' ) {
		$classes .= ' with_content';
	}

	$inline_css = us_prepare_inline_css(
		array(
			'height' => $atts['height'],
			'border-color' => $atts['bdcolor'],
			'color' => $atts['bdcolor'],
			'font-size' => $atts['title_size'],
		)
	);
} else {
	$inline_css = us_prepare_inline_css(
		array(
			'height' => $atts['height'],
		)
	);
}

// Output the element
$output = '<div class="w-separator' . $classes . '"' . $el_id . $inline_css . '>';
$output .= $responsive_styles;
if ( $atts['show_line'] ) {
	$output .= '<div class="w-separator-h">' . $inner_html . '</div>';
}
$output .= '</div>';

echo $output;
