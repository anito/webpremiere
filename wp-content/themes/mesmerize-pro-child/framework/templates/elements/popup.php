<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Popup
 */

$atts = us_shortcode_atts( $atts, 'us_popup' );

$popup_classes = ' animation_' . $atts['animation'];

$classes = ' align_' . $atts['align'];
$classes .= ( ! empty( $atts['el_class'] ) ) ? ( ' ' . $atts['el_class'] ) : '';
$el_id = ( ! empty( $atts['el_id'] ) ) ? ( ' id="' . esc_attr( $atts['el_id'] ) . '"' ) : '';
if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}

// Output the trigger
$output = '<div class="w-popup' . $classes . '" ' . $el_id . '>';

// Trigger
if ( $atts['show_on'] == 'image' AND ! empty( $atts['image'] ) AND ( $image_html = wp_get_attachment_image( $atts['image'], $atts['image_size'] ) ) ) {
	$output .= '<a href="javascript:void(0)" class="w-popup-trigger type_image">' . $image_html . '</a>';
} elseif ( $atts['show_on'] == 'load' ) {
	$output .= '<span class="w-popup-trigger type_load" data-delay="' . intval( $atts['show_delay'] ) . '"></span>';
} elseif ( $atts['show_on'] == 'selector' ) {
	$output .= '<span class="w-popup-trigger type_selector" data-selector="' . esc_attr( $atts['trigger_selector'] ) . '"></span>';
} else/*if ( $atts['show_on'] == 'btn' )*/ {
	$output .= '<div class="w-btn-wrapper">';
	$output .= '<a href="javascript:void(0)" class="w-popup-trigger type_btn w-btn us-btn-style_' . $atts['btn_style'] . '">';
	$output .= '<span class="w-btn-label">' . trim( strip_tags( $atts['btn_label'], '<br>' ) ) . '</span>';
	$output .= '</a>';
	$output .= '</div>';
}

// Overlay
$output .= '<div class="w-popup-overlay"';
$output .= us_prepare_inline_css(
	array(
		'background-color' => $atts['overlay_bgcolor'],
	)
);
$output .= '></div>';

// Popup title
$output_title = '';
if ( ! empty( $atts['title'] ) ) {
	$popup_classes .= ' with_title';

	$output_title .= '<div class="w-popup-box-title"';
	$output_title .= us_prepare_inline_css(
		array(
			'color' => $atts['title_textcolor'],
			'background-color' => $atts['title_bgcolor'],
		)
	);
	$output_title .= '>' . esc_html( $atts['title'] ) . '</div>';
} else {
	$popup_classes .= ' without_title';
}

// The Popup itself
$output .= '<div class="w-popup-wrap">';
$output .= '<div class="w-popup-box' . $popup_classes . '"';
$output .= us_prepare_inline_css(
	array(
		'border-radius' => $atts['popup_border_radius'],
		'width' => $atts['popup_width'],
	)
);
$output .= '><div class="w-popup-box-h">';
$output .= $output_title;

// Popup content
$output .= '<div class="w-popup-box-content"';
$output .= us_prepare_inline_css(
	array(
		'padding' => $atts['popup_padding'],
		'background-color' => $atts['content_bgcolor'],
		'color' => $atts['content_textcolor'],
	)
);
$output .= '>';
$output .= do_shortcode( wpautop( $content ) );
$output .= '</div>';
$output .= '</div></div>'; // .w-popup-box

// Popup closer
$output .= '<div class="w-popup-closer"';
$output .= us_prepare_inline_css(
	array(
		'background-color' => $atts['content_bgcolor'],
		'color' => $atts['content_textcolor'],
	)
);
$output .= '></div>';

$output .= '</div>'; // .w-popup-wrap
$output .= '</div>'; // .w-popup

echo $output;
