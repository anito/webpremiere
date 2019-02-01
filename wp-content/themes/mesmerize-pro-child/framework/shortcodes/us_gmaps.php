<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_gmaps
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts ['marker_address'] string Marker 1 address
 * @param $atts ['marker_text'] string Marker 1 text
 * @param $atts ['show_infowindow'] bool Show Marker's InfoWindow
 * @param $atts ['custom_marker_img'] int Custom marker image (from WordPress media)
 * @param $atts ['custom_marker_size'] int Custom marker size
 * @param $atts ['markers'] array Additional Markers
 * @param $atts ['provider'] string Map Provider: 'google' / 'osm'
 * @param $atts ['type'] string Map type: 'roadmap' / 'satellite' / 'hybrid' / 'terrain'
 * @param $atts ['height'] int Map height
 * @param $atts ['zoom'] int Map zoom
 * @param $atts ['hide_controls'] bool Hide all map controls
 * @param $atts ['disable_dragging'] bool Disable dragging on touch screens
 * @param $atts ['disable_zoom'] bool Disable map zoom on mouse wheel scroll
 * @param $atts ['map_bg_color'] string Map Background Color
 * @param $atts ['el_class'] string Extra class name
 * @param $atts ['map_style_json'] string Map Style
 * @param $atts ['layer_style'] string Leaflet Map TileLayer
 *
 * @filter 'us_maps_js_options' Allows to filter options, passed to JavaScript
 */

$atts = us_shortcode_atts( $atts, 'us_gmaps' );

global $us_maps_index;
$us_maps_index = isset( $us_maps_index ) ? ( $us_maps_index + 1 ) : 1;

$classes = ' provider_'. $atts['provider'];
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

// Decoding base64-encoded HTML attributes
if ( ! empty( $atts['marker_text'] ) ) {
	$atts['marker_text'] = rawurldecode( base64_decode( $atts['marker_text'] ) );
}

if ( ! in_array( $atts['zoom'], array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ) ) ) {
	$atts['zoom'] = 14;
}

// Form all options needed for JS
$script_options = array();
if ( ! empty( $atts['marker_address'] ) ) {
	$script_options['address'] = $atts['marker_address'];
} else {
	return NULL;
}
$script_options['markers'] = array(
	array_merge(
		$script_options, array(
			'html' => ( ! empty( $atts['marker_text'] ) ) ? $atts['marker_text'] : $atts['marker_address'],
			'infowindow' => $atts['show_infowindow'],
		)
	),
);

if ( empty( $atts['markers'] ) ) {
	$atts['markers'] = array();
} else {
	$atts['markers'] = json_decode( urldecode( $atts['markers'] ), TRUE );
	if ( ! is_array( $atts['markers'] ) ) {
		$atts['markers'] = array();
	}
}

foreach ( $atts['markers'] as $index => $marker ) {
	/**
	 * Filtering the included markers
	 *
	 * @param $marker ['marker_address'] string Address
	 * @param $marker ['marker_text'] string Marker Text
	 * @param $marker ['marker_img'] string Marker Image
	 * @param $marker ['marker_size'] string Marker Size
	 */
	if ( ! empty( $marker['marker_address'] ) ) {
		$script_options['markers'][] = array(
			'html' => ( ! empty( $marker['marker_text'] ) ) ? $marker['marker_text'] : $marker['marker_address'],
			'address' => $marker['marker_address'],
			'marker_img' => ( ! empty( $marker['marker_img'] ) ) ? wp_get_attachment_image_src( intval( $marker['marker_img'] ), 'thumbnail' ) : NULL,
			'marker_size' => ( ! empty( $marker['marker_size'] ) ) ? array(
				$marker['marker_size'],
				$marker['marker_size'],
			) : NULL,
		);
	}
}

if ( ! empty( $atts['zoom'] ) ) {
	$script_options['zoom'] = intval( $atts['zoom'] );
}

if ( ! empty( $atts['type'] ) AND $atts['provider'] == 'google' ) {
	$atts['type'] = strtoupper( $atts['type'] );
	if ( in_array( $atts['type'], array( 'ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN' ) ) ) {
		$script_options['maptype'] = $atts['type'];
	}
}

if ( ! empty( $atts['map_bg_color'] ) ) {
	$script_options['mapBgColor'] = $atts['map_bg_color'];
}

if ( $atts['custom_marker_img'] != '' ) {
	if ( is_numeric( $atts['custom_marker_img'] ) ) {
		$atts['custom_marker_img'] = wp_get_attachment_image_url( intval( $atts['custom_marker_img'] ), 'thumbnail' );
	}
	$atts['custom_marker_size'] = intval( $atts['custom_marker_size'] );
	$script_options['icon'] = array(
		'url' => $atts['custom_marker_img'],
		'size' => array( $atts['custom_marker_size'], $atts['custom_marker_size'] ),
	);
}

if ( empty( $atts['height'] ) ) {
	$atts['height'] = 400;
}
$script_options['height'] = $atts['height'];

if ( $atts['provider'] == 'osm' ) {
	if ( ! empty( $atts['layer_style'] ) ) {
		$script_options['style'] = $atts['layer_style'];
	} else {
		$script_options['style'] = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'; // default value for empty case
	}
}

if ( $atts['hide_controls'] ) {
	$script_options['hideControls'] = TRUE;
}

if ( $atts['disable_zoom'] ) {
	$script_options['disableZoom'] = TRUE;
}

if ( $atts['disable_dragging'] ) {
	$script_options['disableDragging'] = TRUE;
}

$script_options = apply_filters( 'us_maps_js_options', $script_options, get_the_ID(), $us_maps_index );

// Enqueue relevant scripts
if ( $atts['provider'] == 'osm' ) {
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-lmap' );
	}
} elseif ( $atts['provider'] == 'google' ) {
	wp_enqueue_script( 'us-google-maps' );
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-gmap' );
	}
}

$inline_css = us_prepare_inline_css(
	array(
		'background-color' => $atts['map_bg_color'],
		'height' => $atts['height'] . 'px',
	)
);

// Output the element
$output = '<div class="w-map' . $classes . '" id="us_map_' . $us_maps_index . '"' . $inline_css . '>';
$output .= '<div class="w-map-json"' . us_pass_data_to_js( $script_options ) . '></div>';
if ( $atts['provider'] == 'google' AND $atts['map_style_json'] != '' ) {
	$output .= '<div class="w-map-style-json" onclick=\'return ' . str_replace( "'", '&#39;', rawurldecode( base64_decode( $atts['map_style_json'] ) ) ) . '\'></div>';
}
$output .= '</div>';

// If we are in front end editor mode, apply JS to maps
if ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) {
	if ( $atts['provider'] == 'osm' ) {
		$output .= '<script>
		jQuery(function($){
			if (typeof $us !== "undefined" && typeof $us.WMaps === "function") {
				var $wLmap = $(".w-map.provider_osm");
				if ($wLmap.length){
					$us.getScript($us.templateDirectoryUri+"/framework/js/vendor/leaflet.js", function(){
						$wLmap.WLmaps();
					});
				}
			}
		});
		</script>';
	} else {
		$output .= '<script>
		jQuery(function($){
			if (typeof $us !== "undefined" && typeof $us.WMaps === "function") {
				var $wMap = $(".w-map.provider_google");
				if ($wMap.length){
					$us.getScript($us.templateDirectoryUri+"/framework/js/vendor/gmaps.js", function(){
						$wMap.wMaps();
					});
				}
			}
		});
		</script>';
	}

}

echo $output;
