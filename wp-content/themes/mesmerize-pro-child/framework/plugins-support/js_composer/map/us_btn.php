<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_btn
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 */

vc_map(
	array(
		'base' => 'us_btn',
		'name' => __( 'Button', 'us' ),
		'description' => '',
		'icon' => 'icon-wpb-ui-button',
		'category' => us_translate( 'Content', 'js_composer' ),
		'weight' => 330,
		'params' => array(
			array(
				'param_name' => 'text',
				'heading' => __( 'Button Label', 'us' ),
				'type' => 'textfield',
				'value' => __( 'Click Me', 'us' ),
				'std' => $config['atts']['text'],
				'holder' => 'button',
				'class' => 'wpb_button',
			),
			array(
				'param_name' => 'link',
				'heading' => us_translate( 'Link' ),
				'type' => 'vc_link',
				'std' => $config['atts']['link'],
			),
			array(
				'param_name' => 'style',
				'heading' => us_translate( 'Style' ),
				'description' => sprintf( __( 'Add or edit Button Styles on %sTheme Options%s', 'us' ), '<a href="' . admin_url() . 'admin.php?page=us-theme-options#buttons" target="_blank">', '</a>' ),
				'type' => 'dropdown',
				'value' => us_get_btn_styles( $for_shortcode = TRUE ),
				'std' => $config['atts']['style'],
			),
			array(
				'param_name' => 'size',
				'heading' => us_translate( 'Size' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '16px, 1.2em, 1rem' ),
				'type' => 'textfield',
				'std' => $config['atts']['size'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'size_mobiles',
				'heading' => __( 'Size on Mobiles', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '14px, 1em, 0.9rem' ),
				'type' => 'textfield',
				'std' => $config['atts']['size_mobiles'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'width_type',
				'heading' => us_translate( 'Width' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Auto' ) => 'auto',
					__( 'Stretch to the full width', 'us' ) => 'full',
					__( 'Set a width', 'us' ) => 'custom',
					__( 'Set a maximum width', 'us' ) => 'max',
				),
				'std' => $config['atts']['width_type'],
			),
			array(
				'param_name' => 'width',
				'description' => sprintf( __( 'Examples: %s', 'us' ), '200px, 50%, 14rem' ),
				'type' => 'textfield',
				'std' => $config['atts']['width'],
				'dependency' => array( 'element' => 'width_type', 'value' => array( 'custom', 'max' ) ),
			),
			array(
				'param_name' => 'align',
				'heading' => __( 'Button Position', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Left' ) => 'left',
					us_translate( 'Center' ) => 'center',
					us_translate( 'Right' ) => 'right',
				),
				'std' => $config['atts']['align'],
				'dependency' => array( 'element' => 'width_type', 'value' => array( 'auto', 'custom', 'max' ) ),
			),
			array(
				'param_name' => 'el_id',
				'heading' => us_translate( 'Element ID', 'js_composer' ),
				'description' => sprintf( us_translate( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
				'type' => 'textfield',
				'std' => $config['atts']['el_id'],
			),
			array(
				'param_name' => 'el_class',
				'heading' => us_translate( 'Extra class name', 'js_composer' ),
				'type' => 'textfield',
				'std' => $config['atts']['el_class'],
			),
			array(
				'type' => 'us_icon',
				'heading' => __( 'Icon', 'us' ),
				'param_name' => 'icon',
				'std' => $config['atts']['icon'],
				'group' => __( 'Icon', 'us' ),
			),
			array(
				'param_name' => 'iconpos',
				'heading' => __( 'Icon Position', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Left' ) => 'left',
					us_translate( 'Right' ) => 'right',
				),
				'std' => $config['atts']['iconpos'],
				'group' => __( 'Icon', 'us' ),
			),
		),
		'js_view' => 'VcButtonView',
	)
);
