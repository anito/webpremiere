<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Page Title', 'us' ),
	'params' => array_merge( array(

		'font' => array(
			'title' => __( 'Font', 'us' ),
			'type' => 'select',
			'options' => us_get_fonts( 'without_groups' ),
			'std' => 'heading',
			'admin_label' => TRUE,
		),
		'text_styles' => array(
			'type' => 'checkboxes',
			'options' => array(
				'bold' => __( 'Bold', 'us' ),
				'uppercase' => __( 'Uppercase', 'us' ),
			),
			'std' => array(),
		),
		'font_size' => array(
			'title' => __( 'Font Size', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 2,
			'admin_label' => TRUE,
		),
		'line_height' => array(
			'title' => __( 'Line height', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 2,
			'admin_label' => TRUE,
		),
		'tag' => array(
			'title' => __( 'HTML tag', 'us' ),
			'type' => 'select',
			'options' => $misc['html_tag_values'],
			'std' => 'h1',
			'cols' => 2,
		),
		'color' => array(
			'title' => us_translate( 'Color' ),
			'type' => 'color',
			'std' => '',
		),
		'align' => array(
			'title' => us_translate( 'Alignment' ),
			'type' => 'select',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'left',
			'admin_label' => TRUE,
		),
		'inline' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show the next text in the same line', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'align', '=', array( 'left', 'right' ) ),
		),
		'description' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show archive pages description', 'us' ),
			'std' => FALSE,
		),

	), $design_options ),
);
