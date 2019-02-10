<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Image Gallery', 'us' ),
	'icon' => 'icon-wpb-images-stack',
	'params' => array_merge( array(

		'ids' => array(
			'title' => us_translate( 'Images' ),
			'type' => 'upload',
			'is_multiple' => TRUE,
			'extension' => 'png,jpg,jpeg,gif,svg',
		),
		'layout' => array(
			'title' => __( 'Display items as', 'us' ),
			'type' => 'select',
			'options' => array(
				'default' => __( 'Regular Grid', 'us' ),
				'masonry' => __( 'Masonry', 'us' ),
			),
			'std' => 'default',
			'cols' => 2,
			'admin_label' => TRUE,
		),
		'columns' => array(
			'title' => us_translate( 'Columns' ),
			'type' => 'select',
			'options' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10',
			),
			'std' => '6',
			'admin_label' => TRUE,
			'cols' => 2,
		),
		'orderby' => array(
			'type' => 'switch',
			'switch_text' => __( 'Display items in random order', 'us' ),
			'std' => FALSE,
		),
		'indents' => array(
			'type' => 'switch',
			'switch_text' => __( 'Add indents between items', 'us' ),
			'std' => FALSE,
		),
		'meta' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show items titles and description', 'us' ),
			'std' => FALSE,
		),
		'meta_style' => array(
			'title' => __( 'Title and Description Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'simple' => __( 'Below the image', 'us' ),
				'modern' => __( 'Over the image', 'us' ),
			),
			'std' => 'simple',
			'show_if' => array( 'meta', '!=', '' ),
		),
		'link' => array(
			'type' => 'switch',
			'switch_text' => __( 'Disable popup opening on click', 'us' ),
			'std' => FALSE,
		),
		'masonry' => array( // needed for WP gallery shortcode
			'type' => 'switch',
			'switch_text' => __( 'Display items as Masonry', 'us' ),
			'std' => FALSE,
			'show_if' => array( 'layout', '=', 'none' ),
		),
		'size' => array( // needed for WP gallery shortcode
			'type' => 'text',
			'std' => 'thumbnail',
			'show_if' => array( 'layout', '=', 'none' ),
		),
		'img_size' => array(
			'title' => __( 'Images Size', 'us' ),
			'description' => $misc['desc_img_sizes'],
			'type' => 'select',
			'options' => array_merge(
				array( 'default' => us_translate( 'Default' ) ),
				us_image_sizes_select_values()
			),
			'std' => 'default',
		),

	), $design_options ),
);
