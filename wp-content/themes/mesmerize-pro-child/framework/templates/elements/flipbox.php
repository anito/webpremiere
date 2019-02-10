<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Flipbox
 */

$atts = us_shortcode_atts( $atts, 'us_flipbox' );

$front_inline_css = $back_inline_css = array();

// When rotating cubetilt in diagonal direction, we're actually doing a cube flip animation instead
if ( in_array( $atts['direction'], array( 'ne', 'se', 'sw', 'nw' ) ) ) {
	if ( $atts['animation'] == 'cubetilt' ) {
		$atts['animation'] = 'cubeflip';
	}
	if ( $atts['animation'] == 'cubeflip' AND $atts['link_type'] == 'btn' ) {
		$atts['direction'] = 'n'; // disable diagonal directions, when back side has a button
	}
}

// Main element classes
$classes = ' animation_' . $atts['animation'];
$classes .= ' direction_' . $atts['direction'];

if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}

// Extract "padding" properties from "css" attribute to use them inside flipbox
if ( ! empty( $atts['css'] ) AND preg_match( '~\{([^\}]+?)\;?\}~', $atts['css'], $matches ) ) {
	$css_rules = array_map( 'trim', explode( ';', $matches[1] ) );
	$padding_params = array(
		'border',
		'border-radius',
		'padding',
		'padding-top',
		'padding-left',
		'padding-right',
		'padding-bottom',
	);
	foreach ( $css_rules as $css_rule ) {
		$css_rule = explode( ':', $css_rule );
		if ( count( $css_rule ) == 2 AND in_array( $css_rule[0], $padding_params ) ) {
			$front_inline_css[ $css_rule[0] ] = $css_rule[1];
			$back_inline_css[ $css_rule[0] ] = $css_rule[1];
		}
	}
}

$classes .= ( ! empty( $atts['el_class'] ) ) ? ( ' ' . $atts['el_class'] ) : '';
$el_id = ( ! empty( $atts['el_id'] ) ) ? ( ' id="' . esc_attr( $atts['el_id'] ) . '"' ) : '';

// Link
$tag = 'div';
$link_atts = $btn_html = '';
$link_array = us_vc_build_link( $atts['link'] );

if ( isset( $link_array['url'] ) AND $link_array['url'] != '' ) {
	$link_atts .= ' href="' . esc_url( $link_array['url'] ) . '"';
	$link_atts .= ( ! empty( $link_array['title'] ) ) ? ( ' title="' . esc_attr( $link_array['title'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['target'] ) ) ? ( ' target="' . esc_attr( $link_array['target'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['rel'] ) ) ? ( ' rel="' . esc_attr( $link_array['rel'] ) . '"' ) : '';

	if ( $atts['link_type'] == 'container' ) {
		$tag = 'a';
	} elseif ( $atts['link_type'] == 'btn' ) {
		$btn_html .= '<a class="w-btn us-btn-style_' . $atts['btn_style'] . '"';
		$btn_html .= $link_atts;
		$btn_html .= us_prepare_inline_css(	array( 'font-size' => $atts['btn_size'] ) );
		$btn_html .= '>';
		$btn_html .= '<span>' . strip_tags( $atts['btn_label'] ) . '</span>';
		$btn_html .= '</a>';
	}
}

$inline_css = us_prepare_inline_css(
	array(
		'width' => $atts['custom_width'],
	)
);

// Output the element
$output = '<' . $tag . ' class="w-flipbox' . $classes . '"' . $inline_css . $el_id . $link_atts . '>';
$helper_classes = ' easing_' . $atts['easing'];
$helper_inline_css = us_prepare_inline_css(
	array(
		'transition-duration' => floatval( $atts['duration'] ) . 's',
	)
);
$output .= '<div class="w-flipbox-h' . $helper_classes . '"' . $helper_inline_css . '>';
$output .= '<div class="w-flipbox-hh">';

if ( $atts['animation'] == 'cubeflip' AND in_array( $atts['direction'], array( 'ne', 'se', 'sw', 'nw' ) ) ) {
	$output .= '<div class="w-flipbox-hhh">';
}

// Front Side
$front_inline_css['height'] = $atts['custom_height'];
$front_inline_css['background-color'] = $atts['front_bgcolor'];
$front_inline_css['color'] = $atts['front_textcolor'];

if ( $front_bgimage_src = wp_get_attachment_image_src( $atts['front_bgimage'], $atts['front_bgimage_size'] ) ) {
	$front_inline_css['background-image'] = $front_bgimage_src[0];
}

$output .= '<div class="w-flipbox-front"' . us_prepare_inline_css( $front_inline_css ) . '><div class="w-flipbox-front-h">';
$output_front_icon = '';
if ( $atts['front_icon_type'] == 'font' ) {
	$icon_inline_css = array(
		'font-size' => $atts['front_icon_size'],
		'background-color' => $atts['front_icon_bgcolor'],
		'color' => $atts['front_icon_color'],
	);
	$output_front_icon .= '<div class="w-flipbox-front-icon style_' . $atts['front_icon_style'] . '"' . us_prepare_inline_css( $icon_inline_css ) . '>';
	$output_front_icon .= us_prepare_icon_tag( $atts['front_icon_name'] );
	$output_front_icon .= '</div>';
} elseif ( $atts['front_icon_type'] == 'image' ) {
	$icon_inline_css = array(
		'width' => $atts['front_icon_image_width'],
	);
	$output_front_icon .= '<div class="w-flipbox-front-icon type_image"' . us_prepare_inline_css( $icon_inline_css ) . '>';
	$output_front_icon .= wp_get_attachment_image( $atts['front_icon_image'], 'medium' );
	$output_front_icon .= '</div>';
}
$output_front_title = '';
if ( ! empty( $atts['front_title'] ) ) {
	$output_front_title .= '<' . $atts['front_title_tag'] . ' class="w-flipbox-front-title"';
	$output_front_title .= us_prepare_inline_css(
		array(
			'font-size' => $atts['front_title_size'],
			'color' => $atts['front_textcolor'],
		)
	);
	$output_front_title .= '>' . esc_html( $atts['front_title'] ) . '</' . $atts['front_title_tag'] . '>';
}
$output_front_desc = '';
if ( ! empty( $atts['front_desc'] ) ) {
	$output_front_desc .= '<div class="w-flipbox-front-desc">' . wpautop( $atts['front_desc'] ) . '</div>';
}
if ( $atts['front_icon_pos'] == 'below_title' ) {
	$output .= $output_front_title . $output_front_icon . $output_front_desc;
} elseif ( $atts['front_icon_pos'] == 'below_desc' ) {
	$output .= $output_front_title . $output_front_desc . $output_front_icon;
} else/*if ( $atts['front_icon_pos'] == 'above_title' )*/ {
	$output .= $output_front_icon . $output_front_title . $output_front_desc;
}
$output .= '</div></div>';

// Back Side
$back_inline_css['display'] = 'none';
$back_inline_css['background-color'] = $atts['back_bgcolor'];
$back_inline_css['color'] = $atts['back_textcolor'];

if ( $back_bgimage_src = wp_get_attachment_image_src( $atts['back_bgimage'], $atts['back_bgimage_size'] ) ) {
	$back_inline_css['background-image'] = $back_bgimage_src[0];
}

$output .= '<div class="w-flipbox-back"' . us_prepare_inline_css( $back_inline_css ) . '><div class="w-flipbox-back-h">';
if ( ! empty( $atts['back_title'] ) ) {
	$output .= '<' . $atts['back_title_tag'] . ' class="w-flipbox-back-title"';
	$output .= us_prepare_inline_css(
		array(
			'font-size' => $atts['back_title_size'],
			'color' => $atts['back_textcolor'],
		)
	);
	$output .= '>' . esc_html( $atts['back_title'] ) . '</' . $atts['back_title_tag'] . '>';
}
if ( ! empty( $atts['back_desc'] ) ) {
	$output .= '<div class="w-flipbox-back-desc">' . wpautop( $atts['back_desc'] ) . '</div>';
}
$output .= $btn_html;
$output .= '</div></div>';

// We need additional dom-elements for 'cubeflip' animations (:before / :after won't suit)
if ( $atts['animation'] == 'cubeflip' ) {

	$front_bgcolor = ( ! empty( $atts['front_bgcolor'] ) ) ? $atts['front_bgcolor'] : us_get_option( 'color_content_bg_alt' );

	// Top & bottom flank with shaded color
	if ( in_array( $atts['direction'], array( 'ne', 'e', 'se', 'sw', 'w', 'nw' ) ) ) {
		$shaded_color = us_shade_color( $front_bgcolor );
		$output .= '<div class="w-flipbox-yflank"' . us_prepare_inline_css( array( 'background-color' => $shaded_color ) ) . '></div>';
	}

	// Left & right flank with shaded color
	if ( in_array( $atts['direction'], array( 'n', 'ne', 'se', 's', 'sw', 'nw' ) ) ) {
		$shaded_color = us_shade_color( $front_bgcolor, 0.1 );
		$output .= '<div class="w-flipbox-xflank"' . us_prepare_inline_css( array( 'background-color' => $shaded_color ) ) . '></div>';
	}
}

if ( $atts['animation'] == 'cubeflip' AND in_array( $atts['direction'], array( 'ne', 'se', 'sw', 'nw' ) ) ) {
	$output .= '</div>';
}

$output .= '</div></div>';
$output .= '</' . $tag . '>';

echo $output;
