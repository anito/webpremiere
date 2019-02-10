<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Common Typography options used for several elements
 */

$misc = us_config( 'elements_misc' );

return array(
	'font' => array(
		'title' => __( 'Font', 'us' ),
		'type' => 'select',
		'options' => us_get_fonts(),
		'shortcode_options' => us_get_fonts( 'without_groups' ),
		'std' => 'body',
		'group' => __( 'Typography', 'us' ),
	),
	'text_styles' => array(
		'type' => 'checkboxes',
		'options' => array(
			'bold' => __( 'Bold', 'us' ),
			'uppercase' => __( 'Uppercase', 'us' ),
			'italic' => __( 'Italic', 'us' ),
		),
		'std' => array(),
		'classes' => 'for_above',
		'group' => __( 'Typography', 'us' ),
	),
	'font_size' => array(
		'title' => __( 'Font Size', 'us' ),
		'description' => $misc['desc_font_size'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'header_cols' => 3,
		'group' => __( 'Typography', 'us' ),
	),
	'font_size_tablets' => array(
		'title' => __( 'Size on Tablets', 'us' ),
		'description' => $misc['desc_font_size'],
		'type' => 'text',
		'std' => '',
		'cols' => 3,
		'group' => __( 'Typography', 'us' ),
		'context' => array( 'header' ),
	),
	'font_size_mobiles' => array(
		'title' => __( 'Font Size on Mobiles', 'us' ),
		'description' => $misc['desc_font_size'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'header_cols' => 3,
		'group' => __( 'Typography', 'us' ),
	),
	'line_height' => array(
		'title' => __( 'Line height', 'us' ),
		'description' => $misc['desc_line_height'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'header_cols' => 3,
		'group' => __( 'Typography', 'us' ),
	),
	'line_height_tablets' => array(
		'title' => __( 'Line height on Tablets', 'us' ),
		'description' => $misc['desc_line_height'],
		'type' => 'text',
		'std' => '',
		'cols' => 3,
		'group' => __( 'Typography', 'us' ),
		'context' => array( 'header' ),
	),
	'line_height_mobiles' => array(
		'title' => __( 'Line height on Mobiles', 'us' ),
		'description' => $misc['desc_line_height'],
		'type' => 'text',
		'std' => '',
		'cols' => 2,
		'header_cols' => 3,
		'group' => __( 'Typography', 'us' ),
	),
);
