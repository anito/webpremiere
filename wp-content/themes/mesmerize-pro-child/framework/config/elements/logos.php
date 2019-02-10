<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Logos Showcase', 'us' ),
	'params' => array_merge( array(

		// General
		'items' => array(
			'type' => 'group',
			'is_sortable' => TRUE,
			'params' => array(
				'image' => array(
					'title' => us_translate( 'Image' ),
					'type' => 'upload',
					'is_multiple' => FALSE,
					'extension' => 'png,jpg,jpeg,gif,svg',
					'admin_label' => TRUE,
				),
				'link' => array(
					'title' => us_translate( 'Link' ),
					'type' => 'link',
					'std' => '',
				),
			),
		),

		// Appearance
		'type' => array(
			'title' => __( 'Display items as', 'us' ),
			'type' => 'select',
			'options' => array(
				'grid' => __( 'Regular Grid', 'us' ),
				'carousel' => __( 'Carousel', 'us' ),
			),
			'std' => 'carousel',
			'admin_label' => TRUE,
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),

		),
		'columns' => array(
			'title' => us_translate( 'Columns' ),
			'type' => 'select',
			'options' => array(
				1 => '1',
				2 => '2',
				3 => '3',
				4 => '4',
				5 => '5',
				6 => '6',
				7 => '7',
				8 => '8',
			),
			'std' => '5',
			'admin_label' => TRUE,
			'cols' => 2,
			'group' => us_translate( 'Appearance' ),
		),
		'style' => array(
			'title' => __( 'Hover Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'1' => __( 'Fade + Outline', 'us' ),
				'2' => __( 'Fade', 'us' ),
				'3' => us_translate( 'None' ),
			),
			'std' => '1',
			'admin_label' => TRUE,
			'group' => us_translate( 'Appearance' ),
		),
		'with_indents' => array(
			'type' => 'switch',
			'switch_text' => __( 'Add indents between items', 'us' ),
			'std' => FALSE,
			'group' => us_translate( 'Appearance' ),
		),
		'orderby' => array(
			'type' => 'switch',
			'switch_text' => __( 'Display items in random order', 'us' ),
			'std' => '',
			'group' => us_translate( 'Appearance' ),
		),
		'img_size' => array(
			'title' => __( 'Images Size', 'us' ),
			'description' => $misc['desc_img_sizes'],
			'type' => 'select',
			'options' => us_image_sizes_select_values(),
			'std' => 'medium',
			'group' => us_translate( 'Appearance' ),
		),

		// Carousel Settings
		'carousel_arrows' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show Navigation Arrows', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		'carousel_dots' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show Navigation Dots', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		'carousel_center' => array(
			'type' => 'switch',
			'switch_text' => __( 'Enable first item centering', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		'carousel_slideby' => array(
			'type' => 'switch',
			'switch_text' => __( 'Slide by several items instead of one', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		'carousel_autoplay' => array(
			'type' => 'switch',
			'switch_text' => __( 'Enable Auto Rotation', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		'carousel_interval' => array(
			'title' => __( 'Auto Rotation Interval (in seconds)', 'us' ),
			'type' => 'text',
			'std' => '3',
			'show_if' => array( 'carousel_autoplay', '!=', '' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),
		'carousel_autoplay_smooth' => array(
			'type' => 'switch',
			'switch_text' => __( 'Smooth Rotation', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'carousel_autoplay', '!=', '' ),
			'group' => __( 'Carousel Settings', 'us' ),
		),

		// Responsive Options
		'breakpoint_1_width' => array(
			'title' => __( 'Below screen width', 'us' ),
			'type' => 'text',
			'std' => '1024px',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_1_cols' => array(
			'title' => __( 'show', 'us' ),
			'type' => 'select',
			'options' => array(
				8 => sprintf( us_translate_n( '%s column', '%s columns', 8 ), 8 ),
				7 => sprintf( us_translate_n( '%s column', '%s columns', 7 ), 7 ),
				6 => sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ),
				5 => sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ),
				4 => sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ),
				3 => sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ),
				2 => sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ),
				1 => sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ),
			),
			'std' => '3',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_1_autoplay' => array(
			'type' => 'switch',
			'switch_text' => __( 'Enable Auto Rotation', 'us' ),
			'std' => TRUE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_2_width' => array(
			'title' => __( 'Below screen width', 'us' ),
			'type' => 'text',
			'std' => '768px',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_2_cols' => array(
			'title' => __( 'show', 'us' ),
			'type' => 'select',
			'options' => array(
				8 => sprintf( us_translate_n( '%s column', '%s columns', 8 ), 8 ),
				7 => sprintf( us_translate_n( '%s column', '%s columns', 7 ), 7 ),
				6 => sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ),
				5 => sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ),
				4 => sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ),
				3 => sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ),
				2 => sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ),
				1 => sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ),
			),
			'std' => '2',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_2_autoplay' => array(
			'type' => 'switch',
			'switch_text' => __( 'Enable Auto Rotation', 'us' ),
			'std' => TRUE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_3_width' => array(
			'title' => __( 'Below screen width', 'us' ),
			'type' => 'text',
			'std' => '480px',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_3_cols' => array(
			'title' => __( 'show', 'us' ),
			'type' => 'select',
			'options' => array(
				8 => sprintf( us_translate_n( '%s column', '%s columns', 8 ), 8 ),
				7 => sprintf( us_translate_n( '%s column', '%s columns', 7 ), 7 ),
				6 => sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ),
				5 => sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ),
				4 => sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ),
				3 => sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ),
				2 => sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ),
				1 => sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ),
			),
			'std' => '1',
			'cols' => 2,
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),
		'breakpoint_3_autoplay' => array(
			'type' => 'switch',
			'switch_text' => __( 'Enable Auto Rotation', 'us' ),
			'std' => TRUE,
			'show_if' => array( 'type', '=', 'carousel' ),
			'group' => us_translate( 'Responsive Options', 'js_composer' ),
		),

	), $design_options ),
);
