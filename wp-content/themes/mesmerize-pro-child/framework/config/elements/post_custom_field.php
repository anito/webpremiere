<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$custom_fields_options = us_get_custom_fields();

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Post Custom Field', 'us' ),
	'params' => array_merge( array(

		'key' => array(
			'title' => us_translate( 'Show' ),
			'type' => 'select',
			'options' => $custom_fields_options,
			'std' => key( $custom_fields_options ),
		),
		'custom_key' => array(
			'title' => __( 'Custom Field Name', 'us' ),
			'description' => __( 'Enter custom field name to retrieve meta data value.', 'us' ),
			'type' => 'text',
			'std' => '',
			'show_if' => array( 'key', '=', 'custom' ),
		),
		'link' => array(
			'title' => us_translate( 'Link' ),
			'type' => 'radio',
			'options' => array(
				'post' => __( 'To a Post', 'us' ),
				'custom' => __( 'Custom', 'us' ),
				'none' => us_translate( 'None' ),
			),
			'std' => 'none',
		),
		'custom_link' => array(
			'placeholder' => us_translate( 'Enter the URL' ),
			'description' => $misc['desc_grid_custom_link'],
			'type' => 'link',
			'std' => array(),
			'classes' => 'desc_3',
			'show_if' => array( 'link', '=', 'custom' ),
		),
		'color_link' => array(
			'title' => __( 'Link Color', 'us' ),
			'type' => 'switch',
			'switch_text' => __( 'Inherit from text color', 'us' ),
			'std' => TRUE,
			'show_if' => array( 'link', '!=', 'none' ),
		),
		'thumbnail_size' => array(
			'title' => __( 'Image Size', 'us' ),
			'description' => $misc['desc_img_sizes'],
			'type' => 'select',
			'options' => us_image_sizes_select_values(),
			'std' => 'large',
			'show_if' => array( 'key', '=', 'us_tile_additional_image' ),
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
			'show_if' => array( 'key', 'in', array(
				'us_testimonial_author',
				'us_testimonial_role',
				'us_testimonial_company',
				'custom',
			) ),
		),

	), $typography_options, $design_options, $hover_options ),
);
