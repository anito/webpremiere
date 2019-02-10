<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Image element
 */

$img_html = $img_transparent_html = $img_shadow = $img_attr = '';

$classes = isset( $classes ) ? $classes : '';

$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

if ( class_exists( 'SitePress' ) ) {
	$image = apply_filters( 'wpml_object_id' ,$image );
}

// Classes & inline styles
if ( isset( $us_elm_context ) AND $us_elm_context == 'shortcode' ) {

	$img = $image;

	$classes .= ' align_' . $align;
	$classes .= ( ! empty( $style ) ) ? ' style_' . $style : '';
	$classes .= ( $meta ) ? ' meta_' . $meta_style : '';
	if ( ! empty( $animate ) ) {
		$classes .= ' animate_' . $animate;
		if ( ! empty( $animate_delay ) ) {
			$animate_delay = floatval( $animate_delay );
			$classes .= ' d' . intval( $animate_delay * 5 );
		}
	}
	if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
		$classes .= ' ' . vc_shortcode_custom_css_class( $css );
	}
	$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
} else { // elseif ( $us_elm_context == 'header' )
	$img_attr = array( 'class' => 'for_default' );
	if ( ! empty( $img_transparent ) ) {
		$classes .= ' with_transparent';
		$img_transparent_html = us_get_attachment_image( $img_transparent, $size, array( 'class' => 'for_transparent' ) );
	}
}

// Get the image
$img_html .= us_get_attachment_image( $img, $size, $img_attr );
$img_html .= $img_transparent_html;

if ( isset( $us_elm_context ) AND $us_elm_context == 'shortcode' AND $img_html ) {
	if ( $meta ) {
		$attachment = get_post( $img );

		// Use the Caption as a Title
		$title = trim( strip_tags( $attachment->post_excerpt ) );
		if ( empty( $title ) ) {
			// If not, Use the Alt
			$title = trim( strip_tags( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', TRUE ) ) );
		}
		if ( empty( $title ) ) {
			// If no Alt, use the Title
			$title = trim( strip_tags( $attachment->post_title ) );
		}

		$img_html .= '<div class="w-image-meta">';
		$img_html .= ( ! empty( $title ) ) ? '<div class="w-image-title">' . $title . '</div>' : '';
		$img_html .= ( ! empty( $attachment->post_content ) ) ? '<div class="w-image-description">' . $attachment->post_content . '</div>' : '';
		$img_html .= '</div>';
	}

	// Get url to the image to immitate shadow
	$img_src = wp_get_attachment_image_url( $img, $size );
	if ( $style == 'shadow-2' ) {
		$img_shadow = '<div class="w-image-shadow" style="background-image:url(' . $img_src . ');"></div>';
	}
}

// Link
$link_atts = '';
$link_array = array();

if ( isset( $us_elm_context ) AND $us_elm_context == 'shortcode' ) {
	if ( $onclick == 'lightbox' ) {
		$link_array['href'] = wp_get_attachment_image_url( $img, 'full' );
		$link_array['ref'] = 'magnificPopup';
	} elseif ( $onclick == 'custom_link' ) {
		$link_array = us_vc_build_link( $link );
		$link_array['href'] = $link_array['url'];
		unset( $link_array['url'] );
	}
} else { //elseif ( $us_elm_context == 'header' )
	$link_array = us_grid_get_custom_link( $link );
}

if ( isset( $link_array['href'] ) AND $link_array['href'] != '' ) {
	$link_atts .= ' href="' . esc_url( $link_array['href'] ) . '"';
	$link_atts .= ( ! empty( $link_array['title'] ) ) ? ( ' title="' . esc_attr( $link_array['title'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['target'] ) ) ? ( ' target="' . esc_attr( $link_array['target'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['rel'] ) ) ? ( ' rel="' . esc_attr( $link_array['rel'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['ref'] ) ) ? ( ' ref="' . esc_attr( $link_array['ref'] ) . '"' ) : '';
	$link_atts .= ( ! empty( $link_array['meta'] ) ) ? $link_array['meta'] : '';
	$tag = 'a';
} else {
	$tag = 'div';
}

if ( empty( $img_html ) ) {
	// Check if image ID is URL
	if ( strpos( $img, 'http' ) !== FALSE ) {
		$img_html = '<img src="' . esc_attr( $img ) . '" alt="" />';
	} else {
		$classes .= ' no_image';
		$img_html = '<div class="g-placeholder"></div>';
	}
}

// Output the element
$output = '<div class="w-image' . $classes . '"' . $el_id . '>';
$output .= '<' . $tag . ' class="w-image-h"' . $link_atts . '>';
$output .= $img_shadow;
$output .= $img_html;
$output .= '</' . $tag . '>';
$output .= '</div>';

echo $output;
