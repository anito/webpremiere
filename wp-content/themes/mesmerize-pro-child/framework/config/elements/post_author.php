<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$misc = us_config( 'elements_misc' );
$typography_options = us_config( 'elements_typography_options' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => __( 'Post Author', 'us' ),
	'params' => array_merge( array(

		'link' => array(
			'title' => us_translate( 'Link' ),
			'type' => 'select',
			'options' => array(
				'author_page' => __( 'To the page with the Author\'s posts', 'us' ),
				'author_website' => __( 'To the Author\'s website (if specified on his profile)', 'us' ),
				'post' => __( 'To a Post', 'us' ),
				'custom' => __( 'Custom', 'us' ),
				'none' => us_translate( 'None' ),
			),
			'std' => 'author_page',
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
		'avatar' => array(
			'title' => us_translate( 'Profile Picture' ),
			'type' => 'switch',
			'switch_text' => __( 'Show Author\'s avatar', 'us' ),
			'std' => FALSE,
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
			'show_if' => array( 'avatar', '=', '0' ),
		),

	), $typography_options, $design_options, $hover_options ),
);
