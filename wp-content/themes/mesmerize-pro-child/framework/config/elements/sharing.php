<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Sharing Buttons', 'us' ),
	'params' => array_merge( array(

		'type' => array(
			'title' => __( 'Buttons Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'simple' => __( 'Simple', 'us' ),
				'solid' => __( 'Solid', 'us' ),
				'outlined' => __( 'Outlined', 'us' ),
				'fixed' => __( 'Fixed', 'us' ),
			),
			'std' => 'simple',
			'cols' => 2,
			'admin_label' => TRUE,
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
			'cols' => 2,
		),
		'color' => array(
			'title' => __( 'Color Style', 'us' ),
			'type' => 'select',
			'options' => array(
				'default' => __( 'Default brands colors', 'us' ),
				'primary' => __( 'Primary (theme color)', 'us' ),
				'secondary' => __( 'Secondary (theme color)', 'us' ),
			),
			'std' => 'default',
			'cols' => 2,
			'admin_label' => TRUE,
		),
		'counters' => array(
			'title' => __( 'Share Counters', 'us' ),
			'type' => 'select',
			'options' => array(
				'show' => __( 'Show counters', 'us' ),
				'hide' => __( 'Don\'t show counters', 'us' ),
			),
			'std' => 'show',
			'cols' => 2,
		),
		'providers' => array(
			'type' => 'checkboxes',
			'options' => array(
				'email' => us_translate( 'Email' ),
				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
				'gplus' => 'Google+',
				'linkedin' => 'LinkedIn',
				'pinterest' => 'Pinterest',
				'vk' => 'Vkontakte',
			),
			'std' => array( 'facebook', 'twitter', 'gplus' ),
		),
		'url' => array(
			'title' => __( 'Sharing URL (optional)', 'us' ),
			'description' => __( 'If not specified, the opened page URL will be used by default', 'us' ),
			'type' => 'textfield',
			'std' => '',
		),

	), $design_options ),
);