<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$taxonomies_options = us_get_taxonomies();

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Post Taxonomy', 'us' ),
	'params' => array_merge( array(

		'taxonomy_name' => array(
			'title' => us_translate( 'Show' ),
			'type' => 'select',
			'options' => $taxonomies_options,
			'std' => key( $taxonomies_options ),
		),
		'style' => array(
			'title' => us_translate( 'Style' ),
			'type' => 'radio',
			'options' => array(
				'simple' => __( 'Simple', 'us' ),
				'badge' => __( 'Badges', 'us' ),
			),
			'std' => 'simple',
		),
		'separator' => array(
			'title' => __( 'Separator between items', 'us' ),
			'type' => 'text',
			'std' => ', ',
			'show_if' => array( 'style', '=', 'simple' ),
		),
		'link' => array(
			'title' => us_translate( 'Link' ),
			'type' => 'radio',
			'options' => array(
				'post' => __( 'To a Post', 'us' ),
				'archive' => __( 'To a Post Archive', 'us' ),
				'custom' => __( 'Custom', 'us' ),
				'none' => us_translate( 'None' ),
			),
			'std' => 'archive',
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
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
		),

	), $typography_options, $design_options, $hover_options ),
);
