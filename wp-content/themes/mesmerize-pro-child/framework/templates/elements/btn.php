<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Button element
 */

// Wrapper classes & inline styles
$wrapper_classes = $wrapper_inline_css = '';

if ( isset( $us_elm_context ) AND $us_elm_context == 'shortcode' ) {
	$wrapper_classes .= ' width_' . $width_type;
	if ( $width_type != 'full' ) {
		$wrapper_classes .= ' align_' . $align;
	}
	if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $css );
	}

	$wrapper_inline_css = us_prepare_inline_css(
		array(
			'width' => ( $width_type == 'custom' AND $align != 'center' ) ? $custom_width : NULL,
			'max-width' => ( $width_type == 'max' AND $align != 'center' ) ? $custom_width : NULL,
		)
	);
} else {
	$wrapper_classes = isset( $classes ) ? $classes : '';
}

// Button classes & inline styles
$btn_inline_css = $responsive_css = '';
$btn_classes = 'w-btn us-btn-style_' . $style;
$btn_classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

if ( isset( $us_elm_context ) AND $us_elm_context == 'shortcode' ) {
	if ( ! isset( $font_size ) OR trim( $font_size ) == us_get_option( 'body_fontsize', '16px' ) ) {
		$font_size = '';
	}
	$btn_inline_css = us_prepare_inline_css(
		array(
			'font-size' => $font_size,
			'width' => ( $width_type == 'custom' AND $align == 'center' ) ? $custom_width : NULL,
			'max-width' => ( $width_type == 'max' AND $align == 'center' ) ? $custom_width : NULL,
		)
	);
	if ( ! empty( $font_size_mobiles ) ) {
		global $us_btn_index;
		$us_btn_index = isset( $us_btn_index ) ? ( $us_btn_index + 1 ) : 1;
		$btn_classes .= ' us_btn_' . $us_btn_index;
		$responsive_css = '<style>@media(max-width:600px){.us_btn_' . $us_btn_index . '{font-size:' . $font_size_mobiles . '!important}}</style>';
	}
}

// Icon
$icon_html = '';
if ( ! empty( $icon ) ) {
	$icon_html = us_prepare_icon_tag( $icon );
	$btn_classes .= ' icon_at' . $iconpos;
} else {
	$btn_classes .= ' icon_none';
}

// Text
$text = trim( strip_tags( $label, '<br>' ) );
if ( $text == '' ) {
	$btn_classes .= ' text_none';
}

// Link
$link_atts = '';
$link_array = array();

if ( isset( $us_elm_context ) AND $us_elm_context == 'grid' ) {
	if ( isset( $link_type ) AND $link_type === 'post' ) {
		$link_array['href'] = apply_filters( 'the_permalink', get_permalink() );
	} elseif ( empty( $link_type ) OR $link_type === 'custom' ) {
		$link_array = us_grid_get_custom_link( $link );
	} else { //elseif ( $link_type == 'none' )
		$link_array['href'] = '';
	}
} elseif ( isset( $us_elm_context ) AND $us_elm_context == 'shortcode' ) {
	$link_array = us_vc_build_link( $link );
	$link_array['href'] = $link_array['url'];
	unset( $link_array['url'] );
} else { // elseif ( $us_elm_context == 'header' )
	$link_array = us_grid_get_custom_link( $link );
}

if ( isset( $link_array['href'] ) AND $link_array['href'] != '' ) {
	$link_atts .= ' href="' . esc_url( $link_array['href'] ) . '"';
	$link_atts .= ( ! empty( $link_array['title'] ) ) ? ( ' title="' . esc_attr( $link_array['title'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['target'] ) ) ? ( ' target="' . esc_attr( $link_array['target'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['rel'] ) ) ? ( ' rel="' . esc_attr( $link_array['rel'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['meta'] ) ) ? $link_array['meta'] : '';
	$btn_tag = 'a';
} else {
	$btn_tag = 'div';
}

// Output the element
$output = '<div class="w-btn-wrapper' . $wrapper_classes . '"' . $wrapper_inline_css . '>';
$output .= $responsive_css;
$output .= '<' . $btn_tag . ' class="' . $btn_classes . '"';
$output .= $link_atts . $btn_inline_css . $el_id;
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
$output .= '</' . $btn_tag . '>';
$output .= '</div>';

echo $output;
