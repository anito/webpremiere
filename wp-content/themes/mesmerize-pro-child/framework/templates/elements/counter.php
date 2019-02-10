<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Counter
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 */

$atts = us_shortcode_atts( $atts, 'us_counter' );
$classes = $value_inline_css = '';

$classes .= ' color_' . $atts['color'];
$classes .= ' align_' . $atts['align'];
if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}
$classes .= ( ! empty( $atts['el_class'] ) ) ? ( ' ' . $atts['el_class'] ) : '';
$el_id = ( ! empty( $atts['el_id'] ) ) ? ( ' id="' . esc_attr( $atts['el_id'] ) . '"' ) : '';

// Generate inline styles for Value
if ( $atts['font'] != 'body' ) {
	$value_inline_css .= us_get_font_css( $atts['font'] );
}
if ( strpos( $atts['text_styles'], 'bold' ) !== FALSE ) {
	$value_inline_css .= 'font-weight:bold;';
}
if ( strpos( $atts['text_styles'], 'uppercase' ) !== FALSE ) {
	$value_inline_css .= 'text-transform:uppercase;';
}
$value_inline_css .= us_prepare_inline_css(
	array(
		'font-size' => $atts['size'],
		'color' => $atts['custom_color'],
	),
	$style_attr = FALSE
);

// Generate inline styles for Title
$title_inline_css = us_prepare_inline_css(
	array(
		'font-size' => $atts['title_size'],
		'color' => $atts['title_color'],
	)
);

// Finding numbers positions in both initial and final strings
$initial = $atts['initial'];
$final = $atts['final'];
$pos = array();
foreach ( array( 'initial', 'final' ) as $key ) {
	$pos[ $key ] = array();
	// In this array we'll store the string's character number, where primitive changes from letter to number or back
	preg_match_all( '~(\(\-?\d+([\.,\'· ]\d+)*\))|(\-?\d+([\.,\'· ]\d+)*)~u', $$key, $matches, PREG_OFFSET_CAPTURE );
	foreach ( $matches[0] as $match ) {
		$pos[ $key ][] = $match[1];
		$pos[ $key ][] = $match[1] + strlen( $match[0] );
	}
};

// Making sure we have the equal number of numbers in both strings
if ( count( $pos['initial'] ) != count( $pos['final'] ) ) {
	// Not-paired numbers will be treated as letters
	if ( count( $pos['initial'] ) > count( $pos['final'] ) ) {
		$pos['initial'] = array_slice( $pos['initial'], 0, count( $pos['final'] ) );
	} else/*if ( count( $positions['initial'] ) < count( $positions['final'] ) )*/ {
		$pos['final'] = array_slice( $pos['final'], 0, count( $pos['initial'] ) );
	}
}

// Position boundaries
foreach ( array( 'initial', 'final' ) as $key ) {
	array_unshift( $pos[ $key ], 0 );
	$pos[ $key ][] = strlen( $$key );
}

// Output the element
$output = '<div class="w-counter' . $classes . '" data-duration="' . intval( $atts['duration'] ) * 1000 . '"' . $el_id . '>';
$output .= '<div class="w-counter-value"';
if ( ! empty ( $value_inline_css ) ) {
	$output .= ' style="' . esc_attr( $value_inline_css ) . '"';
}
$output .= '>';

// Determining if we treat each part as a number or as a letter combination
for ( $index = 0, $length = count( $pos['initial'] ) - 1; $index < $length; $index++ ) {
	$part_type = ( $index % 2 ) ? 'number' : 'text';
	$part_initial = substr( $initial, $pos['initial'][ $index ], $pos['initial'][ $index + 1 ] - $pos['initial'][ $index ] );
	$part_final = substr( $final, $pos['final'][ $index ], $pos['final'][ $index + 1 ] - $pos['final'][ $index ] );
	$output .= '<span class="w-counter-value-part type_' . $part_type . '" data-final="' . esc_attr( $part_final ) . '">' . $part_initial . '</span>';
}

$output .= '</div>';

if ( ! empty ( $atts['title'] ) ) {
	$output .= '<' . $atts['title_tag'] .' class="w-counter-title"' . $title_inline_css . '>';
	$output .= $atts['title'];
	$output .= '</' . $atts['title_tag'] . '>';
}
$output .= '</div>';

// If we are in front end editor mode, apply JS to logos
if ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) {
	$output .= '<script>
	jQuery(function($){
		if (typeof $.fn.wCounter === "function") {
			jQuery(".w-counter").wCounter();
		}
	});
	</script>';
}

echo $output;
