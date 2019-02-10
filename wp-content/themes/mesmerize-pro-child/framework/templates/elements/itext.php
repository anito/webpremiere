<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Ineractive Text
 */

$atts = us_shortcode_atts( $atts, 'us_itext' );

$classes = ' type_' . $atts['animation_type'] . ' align_' . $atts['align'];
if ( $atts['dynamic_bold'] ) {
	$classes .= ' dynamic_bold';
}
if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}
$classes .= ( ! empty( $atts['el_class'] ) ) ? ( ' ' . $atts['el_class'] ) : '';
$el_id = ( ! empty( $atts['el_id'] ) ) ? ( ' id="' . esc_attr( $atts['el_id'] ) . '"' ) : '';

// Allows to use nbsps and other entities
$texts = html_entity_decode( $atts['texts'] );

$texts_arr = explode( "\n", strip_tags( $texts ) );

$js_data = array(
	'duration' => intval( $atts['duration'] ) * 1000,
	'delay' => intval( floatval( $atts['delay'] ) * 1000 ),
);
if ( ! empty( $atts['dynamic_color'] ) ) {
	$js_data['dynamicColor'] = $atts['dynamic_color'];
}

// Getting words and their delimiters to work on this level of abstraction
$_parts = array();
foreach ( $texts_arr as $index => $text ) {
	preg_match_all( '~[\w\-]+|[^\w\-]+~u', $text, $matches );
	$_parts[ $index ] = $matches[0];
}

// Getting the whole set of parts with all the intermediate values (part_index => part_states)
$groups = array();
foreach ( $_parts[0] as $part ) {
	$groups[] = array( $part );
}

for ( $i_index = count( $_parts ) - 1; $i_index > 0; $i_index-- ) {
	$f_index = isset( $_parts[ $i_index + 1 ] ) ? ( $i_index + 1 ) : 0;
	$initial = &$_parts[ $i_index ];
	$final = &$_parts[ $f_index ];
	// Counting arrays edit distance for the strings parts to find the common parts
	$dist = array();
	for ( $i = 0; $i <= count( $initial ); $i++ ) {
		$dist[ $i ] = array( $i );
	}
	for ( $j = 1; $j <= count( $final ); $j++ ) {
		$dist[0][ $j ] = $j;
		for ( $i = 1; $i <= count( $initial ); $i++ ) {
			if ( $initial[ $i - 1 ] == $final[ $j - 1 ] ) {
				$dist[ $i ][ $j ] = $dist[ $i - 1 ][ $j - 1 ];
			} else {
				$dist[ $i ][ $j ] = min( $dist[ $i - 1 ][ $j ], $dist[ $i ][ $j - 1 ], $dist[ $i - 1 ][ $j - 1 ] ) + 1;
			}
		}
	}
	for ( $i = count( $initial ), $j = count( $final ); $i > 0 OR $j > 0; $i--, $j-- ) {
		$min = $dist[ $i ][ $j ];
		if ( $i > 0 ) {
			$min = min( $min, $dist[ $i - 1 ][ $j ], ( $j > 0 ) ? $dist[ $i - 1 ][ $j - 1 ] : $min );
		}
		if ( $j > 0 ) {
			$min = min( $min, $dist[ $i ][ $j - 1 ] );
		}
		if ( $min >= $dist[ $i ][ $j ] ) {
			$groups[ $j - 1 ][ $i_index ] = $initial[ $i - 1 ];
			continue;
		}
		if ( $i > 0 AND $j > 0 AND $min == $dist[ $i - 1 ][ $j - 1 ] ) {
			// Modify
			$groups[ $j - 1 ][ $i_index ] = $initial[ $i - 1 ];
		} elseif ( $j > 0 AND $min == $dist[ $i ][ $j - 1 ] ) {
			// Remove
			$groups[ $j - 1 ][ $i_index ] = '';
			$i++;
		} elseif ( $min == $dist[ $i - 1 ][ $j ] ) {
			// Insert
			if ( $j == 0 ) {
				array_unshift( $groups, '' );
			} else {
				array_splice( $groups, $j, 0, '' );
			}
			$groups[ $j ] = array_fill( 0, count( $_parts ), '' );
			$groups[ $j ][ $i_index ] = $initial[ $i - 1 ];
			$j++;
		}
	}
	// Updating final parts
	$_parts[ $i_index ] = array();
	foreach ( $groups as $parts_group ) {
		$_parts[ $i_index ][] = $parts_group[ $i_index ];
	}
}

// Finding the dynamic parts and their animation indexes
$group_changes = array();
$nbsp_char = html_entity_decode( '&nbsp;' );
foreach ( $groups as $index => $group ) {
	$group_changes[ $index ] = array();
	for ( $i = 0; $i < count( $_parts ); $i++ ) {
		if ( $group[ $i ] != $group[ isset( $group[ $i + 1 ] ) ? ( $i + 1 ) : 0 ] OR $group[ $i ] === '' ) {
			$group_changes[ $index ][] = $i;
		}
		// HTML won't show spans with spaces at all, so replacing them with nbsps
		// A bit sophisticated check to speed up this frequent action
		if ( strlen( $group[ $i ] ) AND $group[ $i ][ 0 ] == ' ' AND preg_match( '~^ +$~u', $group[ $i ][ 0 ] ) ) {
			$groups[ $index ][ $i ] = str_replace( ' ', $nbsp_char, $group[ $i ] );
		}
	}
}

// Combining groups that are either static, or are changed at the same time
for ( $i = 1; $i < count( $group_changes ); $i++ ) {
	if ( $group_changes[ $i - 1 ] == $group_changes[ $i ] ) {
		// Combining with the previous element
		foreach ( $groups[ $i - 1 ] AS $index => $part ) {
			$groups[ $i - 1 ][ $index ] .= $groups[ $i ][ $index ];
		}
		array_splice( $groups, $i, 1 );
		array_splice( $group_changes, $i, 1 );
		$i--;
	}
}

// Generate inline styles
if ( $atts['font'] == 'body' AND in_array( $atts['tag'], array( 'div', 'span',  'p' ) ) ) {
	$inline_css = '';
} elseif ( $atts['font'] == 'heading' AND in_array( $atts['tag'], array( 'h1', 'h2',  'h3', 'h4',  'h5', 'h6' ) ) ) {
	$inline_css = '';
} else {
	$inline_css = us_get_font_css( $atts['font'] );
}

if ( strpos( $atts['text_styles'], 'bold' ) !== FALSE ) {
	$inline_css .= 'font-weight: bold;';
}
if ( strpos( $atts['text_styles'], 'uppercase' ) !== FALSE ) {
	$inline_css .= 'text-transform: uppercase;';
}
if ( strpos( $atts['text_styles'], 'italic' ) !== FALSE ) {
	$inline_css .= 'font-style: italic;';
}
$inline_css .= us_prepare_inline_css(
	array(
		'font-size' => $atts['font_size'],
		'line-height' => $atts['line_height'],
		'color' => $atts['color'],
	),
	$style_attr = FALSE
);

$responsive_css = '';
if ( ! empty( $atts['font_size_mobiles'] ) OR ! empty( $atts['line_height_mobiles'] ) ) {
	global $us_itext_id;
	$us_itext_id = isset( $us_itext_id ) ? ( $us_itext_id + 1 ) : 1;
	$classes .= ' us_itext_' . $us_itext_id;

	$responsive_css .= '<style>';
	$responsive_css .= '@media (max-width: 600px) {.us_itext_' . $us_itext_id . '{';
	$responsive_css .= ! empty( $atts['font_size_mobiles'] ) ? ( 'font-size:' . $atts['font_size_mobiles'] . '!important;' ) : '';
	$responsive_css .= ! empty( $atts['line_height_mobiles'] ) ? ( 'line-height:' . $atts['line_height_mobiles'] . '!important;' ) : '';
	$responsive_css .= '}}';
	$responsive_css .= '</style>';
}

// Output the element
$output = '<' . $atts['tag'] . ' class="w-itext' . $classes . '"';
$output .= $el_id;
$output .= empty( $inline_css ) ? '' : ( ' style="' . esc_attr( $inline_css ) . '"' );
$output .= us_pass_data_to_js( $js_data );
$output .= '>';

foreach ( $groups as $index => $group ) {
	ksort( $group );
	if ( empty( $group_changes[ $index ] ) ) {
		// Static part
		$output .= $group[0];
	} else {
		$output .= '<span class="w-itext-part';
		// Animation classes (just in case site editor wants some custom styling for them)
		foreach ( $group_changes[ $index ] as $changesat ) {
			$output .= ' changesat_' . $changesat;
		}
		if ( in_array( '0', $group_changes[ $index ] ) ) {
			// Highlighting dynamic parts at start
			$output .= ' dynamic"' . us_prepare_inline_css( array( 'color' => $atts['dynamic_color'] ) );
		} else {
			$output .= '"';
		}
		$output .= us_pass_data_to_js( $group ) . '>' . htmlentities( $group[0] ) . '</span>';
	}
}
$output .= '</' . $atts['tag'] . '>';
$output .= $responsive_css;

echo $output;
