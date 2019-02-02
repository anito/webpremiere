<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_pricing
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config    ['atts'] array Shortcode's attributes and default values
 * @param $congig    ['items_atts'] array Items' attributes and default values
 */

vc_map(
	array(
		'base' => 'us_pricing',
		'name' => __( 'Pricing Table', 'us' ),
		'description' => '',
		'category' => us_translate( 'Content', 'js_composer' ),
		'weight' => 150,
		'params' => array(
			array(
				'param_name' => 'items',
				'type' => 'param_group',
				'heading' => __( 'Pricing Items', 'us' ),
				'value' => $config['atts']['items'],
				'params' => array(
					array(
						'param_name' => 'title',
						'heading' => us_translate( 'Title' ),
						'type' => 'textfield',
						'std' => $config['items_atts']['title'],
						'admin_label' => TRUE,
					),
					array(
						'param_name' => 'type',
						'type' => 'checkbox',
						'value' => array( __( 'Mark this item as featured', 'us' ) => 'featured' ),
						( ( $config['items_atts']['type'] !== FALSE ) ? 'std' : '_std' ) => $config['items_atts']['type'],
					),
					array(
						'param_name' => 'price',
						'heading' => __( 'Price', 'us' ),
						'type' => 'textfield',
						'std' => $config['items_atts']['price'],
						'edit_field_class' => 'vc_col-sm-6',
						'admin_label' => TRUE,
					),
					array(
						'param_name' => 'substring',
						'heading' => __( 'Price Substring', 'us' ),
						'type' => 'textfield',
						'std' => $config['items_atts']['substring'],
						'edit_field_class' => 'vc_col-sm-6',
					),
					array(
						'param_name' => 'features',
						'heading' => __( 'Features List', 'us' ),
						'type' => 'textarea',
						'std' => $config['items_atts']['features'],
					),
					array(
						'param_name' => 'btn_text',
						'heading' => __( 'Button Label', 'us' ),
						'type' => 'textfield',
						'std' => $config['items_atts']['btn_text'],
						'class' => 'wpb_button',
					),
					array(
						'param_name' => 'btn_link',
						'heading' => __( 'Button Link', 'us' ),
						'type' => 'vc_link',
						'std' => $config['items_atts']['btn_link'],
					),
					array(
						'param_name' => 'btn_style',
						'heading' => __( 'Button Style', 'us' ),
						'description' => sprintf( __( 'Add or edit Button Styles on %sTheme Options%s', 'us' ), '<a href="' . admin_url() . 'admin.php?page=us-theme-options#buttons" target="_blank">', '</a>' ),
						'type' => 'dropdown',
						'value' => us_get_btn_styles( $for_shortcode = TRUE ),
						'std' => $config['items_atts']['btn_style'],
					),
					array(
						'param_name' => 'btn_size',
						'heading' => __( 'Button Size', 'us' ),
						'description' => sprintf( __( 'Examples: %s', 'us' ), '16px, 1.2em, 1rem' ),
						'type' => 'textfield',
						'std' => $config['items_atts']['btn_size'],
					),
					array(
						'param_name' => 'btn_icon',
						'heading' => __( 'Button Icon', 'us' ),
						'type' => 'us_icon',
						'std' => $config['items_atts']['btn_icon'],
					),
					array(
						'param_name' => 'btn_iconpos',
						'heading' => __( 'Button Icon Position', 'us' ),
						'type' => 'dropdown',
						'value' => array(
							us_translate( 'Left' ) => 'left',
							us_translate( 'Right' ) => 'right',
						),
						'std' => $config['items_atts']['btn_iconpos'],
					),
				),
				'weight' => 20,
			),
			array(
				'param_name' => 'el_class',
				'heading' => us_translate( 'Extra class name', 'js_composer' ),
				'type' => 'textfield',
				'std' => $config['atts']['el_class'],
				'weight' => 10,
			),
		),

	)
);

