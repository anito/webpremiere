<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Post Date', 'us' ),
	'params' => array_merge( array(

		'type' => array(
			'type' => 'radio',
			'options' => array(
				'published' => __( 'Date of creation', 'us' ),
				'modified' => __( 'Date of update', 'us' ),
			),
			'std' => 'published',
		),
		'format' => array(
			'title' => __( 'Format', 'us' ),
			'type' => 'select',
			'options' => array(
				'default' => us_translate( 'Default' ) . ': ' . date_i18n( get_option( 'date_format' ) ),
				'jS F Y' => date_i18n( 'jS F Y' ),
				'j M, G:i' => date_i18n( 'j M, G:i' ),
				'm/d/Y' => date_i18n( 'm/d/Y' ),
				'j.m.y' => date_i18n( 'j.m.y' ),
				'custom' => __( 'Custom', 'us' ),
			),
			'std' => 'default',
		),
		'format_custom' => array(
			'description' => '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">' . __( 'Documentation on date and time formatting.', 'us' ) . '</a>',
			'type' => 'text',
			'std' => 'F j, Y',
			'show_if' => array( 'format', '=', 'custom' ),
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
		),

	), $typography_options, $design_options, $hover_options ),
);
