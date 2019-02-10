<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: Gallery
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 */

if ( empty( $ids ) ) {
	return;
}

// Gallery indexes start from 1
global $us_gallery_index;
$us_gallery_index = isset( $us_gallery_index ) ? ( $us_gallery_index + 1 ) : 1;

$classes = ' us_gallery_' . $us_gallery_index;

if ( $shortcode_base == 'gallery' ) {
	if ( empty( $atts['columns'] ) ) {
		// Default [gallery] shortcode has 3 columns by default
		$columns = '3';
	}
	if ( isset( $indents ) ) {
		$indents = ( $indents == 'true' );
	}
	if ( isset( $meta ) ) {
		$meta = ( $meta == 'true' );
	}
	if ( isset( $size ) ) {
		$img_size = $size;
	} else {
		$img_size = 'default';
	}
	if ( empty( $link ) ) {
		$link_type = 'attachment';
	} elseif ( $link == 'file' ) {
		$link_type = 'file';
	} elseif ( $link == 'none' ) {
		$link_type = 'none';
	}
} else {
	$link_type = 'file';
	if ( $link ) {
		$link_type = 'none';
	}
}
if ( ! isset( $ids ) OR empty( $ids ) ) {
	if ( isset( $include ) AND ! empty( $include ) ) {
		$ids = $include;
	} else {
		if ( ! isset( $id ) OR empty( $id ) ) {
			// Default fallback as from https://codex.wordpress.org/Gallery_Shortcode
			$id = get_the_ID();
		}
		$query_args = array(
			'post_parent' => $id,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'posts_per_page' => - 1,
			'numberposts' => - 1,
		);
		if ( isset( $exclude ) AND ! empty( $exclude ) ) {
			$query_args['exclude'] = $exclude;
		}
		if ( isset( $orderby ) AND in_array( $orderby, array( 'title', 'post_date', 'ID' ) ) ) {
			$query_args['orderby'] = $orderby;
			if ( ! isset( $order ) OR empty( $order ) ) {
				$order = ( $orderby == 'post_date' ) ? 'DESC' : 'ASC';
			}
			$query_args['order'] = ( strtoupper( $order ) == 'ASC' ) ? 'ASC' : 'DESC';
		}
		$ids = array();
		foreach ( get_posts( $query_args ) as $post ) {
			$ids[] = $post->ID;
		}
		$ids = implode( ',', $ids );
	}
}

$columns = intval( $columns );
if ( $columns < 1 OR $columns > 10 ) {
	$columns = 6;
}

// Masonry checkbox used in WP gallery
if ( $layout == 'default' AND isset( $masonry ) AND $masonry == 'true' ) {
	$layout = 'masonry';
}
if ( $layout == 'masonry' AND $columns > 1 ) {
	// We'll need the isotope script for this
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-isotope' );
	}
	$tnail_size = ( $columns < 6 ) ? 'large' : 'medium';
} else/*if($layout == 'default')*/ {
	if ( $columns == 1 ) {
		$tnail_size = 'full';
	} elseif ( $columns < 5 ) {
		$tnail_size = 'us_600_600_crop';
	} elseif ( $columns < 8 ) {
		$tnail_size = 'us_350_350_crop';
	} else {
		$tnail_size = 'thumbnail';
	}
}

if ( $img_size != 'default' AND in_array( $img_size, array_merge( array( 'full' ), get_intermediate_image_sizes() ) ) ) {
	$tnail_size = $img_size;
}
$classes .= ' type_' . $layout;

if ( $columns != 1 ) {
	$classes .= ' cols_' . $columns;
}
if ( $indents ) {
	$classes .= ' with_indents';
}
if ( ! empty( $meta_style ) ) {
	$classes .= ' style_' . $meta_style;
}
if ( ! empty( $css ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $css );
}
$classes .= ( ! empty( $el_class ) ) ? ( ' ' . $el_class ) : '';
$el_id = ( ! empty( $el_id ) ) ? ( ' id="' . esc_attr( $el_id ) . '"' ) : '';

// Getting images
$query_args = array(
	'include' => $ids,
	'post_status' => 'inherit',
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'orderby' => 'post__in',
);
if ( $orderby == 'rand' ) {
	$query_args['orderby'] = 'rand';
}
$attachments = get_posts( $query_args );
if ( ! is_array( $attachments ) OR empty( $attachments ) ) {
	return;
}

// Gallery shortcode usage in feeds
if ( is_feed() ) {
	$output = "\n";
	foreach ( $attachments as $attachment ) {
		$output .= wp_get_attachment_link( $attachment->ID, 'thumbnail', TRUE ) . "\n";
	}

	return $output;
}

$classes .= ' link_' . $link_type;

$classes = apply_filters( 'us_gallery_listing_classes', $classes );

$output = '<div class="w-gallery' . $classes . '"' . $el_id . '><div class="w-gallery-list">';

$item_tag_name = ( $link_type == 'none' ) ? 'div' : 'a';
foreach ( $attachments as $index => $attachment ) {

	// Use the Caption as title
	$title = trim( strip_tags( $attachment->post_excerpt ) );
	if ( empty( $title ) ) {
		// If no Caption, use the Alt
		$title = trim( strip_tags( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', TRUE ) ) );
	}
	if ( empty( $title ) ) {
		// If no Alt, use the Title
		$title = trim( strip_tags( $attachment->post_title ) );
	}

	$output .= '<' . $item_tag_name . ' class="w-gallery-item order_' . ( $index + 1 );
	$output .= apply_filters( 'us_gallery_listing_item_classes', '' );
	$output .= '"';
	if ( $link_type == 'file' ) {
		$output .= ' href="' . wp_get_attachment_url( $attachment->ID ) . '" title="' . esc_attr( $title ) . '"';
	} elseif ( $link_type == 'attachment' ) {
		$output .= ' href="' . get_attachment_link( $attachment->ID ) . '" title="' . esc_attr( $title ) . '"';
	}
	$output .= '>';
	$output .= '<div class="w-gallery-item-img">';
	$output .= us_get_attachment_image( $attachment->ID, $tnail_size );
	$output .= '</div>';
	if ( $meta ) {
		$output .= '<div class="w-gallery-item-meta">';
		if ( $title != '' ) {
			$output .= '<div class="w-gallery-item-title">' . $title . '</div>';
		}
		$output .= ( ! empty( $attachment->post_content ) ) ? '<div class="w-gallery-item-description">' . $attachment->post_content . '</div>' : '';
		$output .= '</div>';
	}
	$output .= '</' . $item_tag_name . '>';
}

$output .= "</div></div>";

echo $output;
