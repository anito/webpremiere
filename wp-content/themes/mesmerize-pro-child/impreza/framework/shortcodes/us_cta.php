<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_cta
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['title'] string ActionBox title
 * @param $atts           ['title_tag'] string Title HTML tag: 'div' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'p'
 * @param $atts           ['title_size'] string Title Size
 * @param $atts           ['color'] string ActionBox color style: 'primary' / 'secondary' / 'light' / 'custom'
 * @param $atts           ['bg_color'] string Background color
 * @param $atts           ['text_color'] string Text color
 * @param $atts           ['controls'] string Button(s) location: 'right' / 'bottom'
 * @param $atts           ['btn_label'] string Button 1 label
 * @param $atts           ['btn_link'] string Button 1 link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $atts           ['btn_style'] string Button 2 Style: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white'
 * @param $atts           ['btn_size'] string Button 1 size
 * @param $atts           ['btn_icon'] string Button 1 icon
 * @param $atts           ['btn_iconpos'] string Button 1 icon position: 'left' / 'right'
 * @param $atts           ['second_button'] bool Has second button?
 * @param $atts           ['btn2_label'] string Button 2 label
 * @param $atts           ['btn2_link'] string Button 2 link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $atts           ['btn2_style'] string Button 2 Style: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white'
 * @param $atts           ['btn2_size'] string Button 2 size
 * @param $atts           ['btn2_icon'] string Button 2 icon
 * @param $atts           ['btn2_iconpos'] string Button 2 icon position: 'left' / 'right'
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_cta' );

$classes = ' color_' . $atts['color'];
$classes .= ' controls_' . $atts['controls'];
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$inline_css = us_prepare_inline_css(
	array(
		'background-color' => $atts['bg_color'],
		'color' => $atts['text_color'],
	)
);

// Button keys that will be parsed
$btn_prefixes = array( 'btn' );
if ( $atts['second_button'] ) {
	$btn_prefixes[] = 'btn2';
}

// Preparing buttons
$buttons = array();
foreach ( $btn_prefixes as $prefix ) {
	if ( empty( $atts[$prefix . '_label'] ) ) {
		continue;
	}
	$icon_html = '';
	$btn_classes = ' us-btn-style_' . $atts[$prefix . '_style'];

	if ( ! isset( $atts[$prefix . '_size'] ) OR trim( $atts[$prefix . '_size'] ) == ( us_get_option( 'body_fontsize' ) . 'px' ) ) {
		$atts[$prefix . '_size'] = '';
	}
	$btn_inline_css = us_prepare_inline_css( array(
		'font-size' => $atts[$prefix . '_size'],
		// 'background-color' => $atts[$prefix . '_bg_color'],
		// 'color' => $atts[$prefix . '_text_color'],
	));

	if ( ! empty( $atts[$prefix . '_icon'] ) ) {
		$btn_classes .= ' icon_at' . $atts[$prefix . '_iconpos'];
		$icon_html = us_prepare_icon_tag( $atts[$prefix . '_icon'] );
	} else {
		$btn_classes .= ' icon_none';
	}

	$link = us_vc_build_link( $atts[$prefix . '_link'] );

	$buttons[$prefix] = '<a class="w-btn' . $btn_classes . '" href="' . esc_url( $link['url'] ) . '"';
	$buttons[$prefix] .= ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$buttons[$prefix] .= ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
	$buttons[$prefix] .= empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
	$buttons[$prefix] .= $btn_inline_css . '>';
	$buttons[$prefix] .= ( $atts[$prefix . '_iconpos'] == 'left' ) ? $icon_html : '';
	$buttons[$prefix] .= '<span class="w-btn-label">' . strip_tags( $atts[$prefix . '_label'], '<br>' ) . '</span>';
	$buttons[$prefix] .= ( $atts[$prefix . '_iconpos'] == 'right' ) ? $icon_html : '';
	$buttons[$prefix] .= '</a>';
}

// Output the element
$output = '<div class="w-actionbox' . $classes . '"' . $inline_css . '><div class="w-actionbox-text">';
if ( ! empty( $atts['title'] ) ) {
	$title_inline_css = us_prepare_inline_css( array(
		'font-size' => $atts['title_size'],
	));
	$output .= '<' . $atts['title_tag'] . $title_inline_css . '>' . html_entity_decode( $atts['title'] ) . '</' . $atts['title_tag'] . '>';
}
if ( ! empty( $content ) ) {
	$output .= '<p>' . do_shortcode( $content ) . '</p>';
}
$output .= '</div>';
if ( ! empty( $buttons ) ) {
	$output .= '<div class="w-actionbox-controls">' . implode( '', $buttons ) . '</div>';
}
$output .= '</div>';

echo $output;
