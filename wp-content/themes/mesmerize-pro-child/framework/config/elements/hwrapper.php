<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Horizontal Wrapper', 'us' ),
	'params' => array_merge( array(

		'alignment' => array(
			'title' => __( 'Content Horizontal Alignment', 'us' ),
			'type' => 'radio',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'left',
			'cols' => 2,
		),
		'valign' => array(
			'title' => __( 'Content Vertical Alignment', 'us' ),
			'type' => 'radio',
			'options' => array(
				'top' => us_translate( 'Top' ),
				'middle' => us_translate( 'Middle' ),
				'bottom' => us_translate( 'Bottom' ),
			),
			'std' => 'top',
			'cols' => 2,
		),
		'wrap' => array(
			'switch_text' => __( 'Allow move content to the next line', 'us' ),
			'type' => 'switch',
			'std' => FALSE,
		),

	), $design_options, $hover_options ),
);
