<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_cta
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config    ['atts'] array Shortcode's attributes and default values
 */

vc_map(
	array(
		'base' => 'us_cta',
		'name' => __( 'ActionBox', 'us' ),
		'description' => '',
		'icon' => 'icon-wpb-call-to-action',
		'category' => us_translate( 'Content', 'js_composer' ),
		'weight' => 220,
		'params' => array(
			array(
				'param_name' => 'title',
				'heading' => us_translate( 'Title' ),
				'type' => 'textfield',
				'std' => $config['atts']['title'],
				'holder' => 'div',
			),
			array(
				'param_name' => 'title_tag',
				'heading' => __( 'Title HTML tag', 'us' ),
				'description' => __( 'Used for SEO purposes', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					'h1' => 'h1',
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'p' => 'p',
					'div' => 'div',
				),
				'std' => $config['atts']['title_tag'],
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array( 'element' => 'title', 'not_empty' => TRUE ),
			),
			array(
				'param_name' => 'title_size',
				'heading' => __( 'Title Size', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '26px, 1.3em, 200%' ),
				'type' => 'textfield',
				'std' => $config['atts']['title_size'],
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array( 'element' => 'title', 'not_empty' => TRUE ),
			),
			array(
				'param_name' => 'content',
				'heading' => us_translate( 'Description' ),
				'type' => 'textarea',
				'std' => '',
				'holder' => 'div',
			),
			array(
				'param_name' => 'color',
				'heading' => __( 'Color Style', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Primary bg & White text', 'us' ) => 'primary',
					__( 'Secondary bg & White text', 'us' ) => 'secondary',
					__( 'Alternate bg & Content text', 'us' ) => 'light',
					__( 'Custom colors', 'us' ) => 'custom',
				),
				'std' => $config['atts']['color'],
			),
			array(
				'param_name' => 'bg_color',
				'heading' => __( 'Background Color', 'us' ),
				'type' => 'colorpicker',
				'std' => $config['atts']['bg_color'],
				'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
			),
			array(
				'param_name' => 'text_color',
				'heading' => __( 'Text Color', 'us' ),
				'type' => 'colorpicker',
				'std' => $config['atts']['text_color'],
				'dependency' => array( 'element' => 'color', 'value' => 'custom' ),
			),
			array(
				'param_name' => 'controls',
				'heading' => __( 'Button(s) Location', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Right' ) => 'right',
					us_translate( 'Bottom' ) => 'bottom',
				),
				'std' => $config['atts']['controls'],
			),
			array(
				'param_name' => 'el_class',
				'heading' => us_translate( 'Extra class name', 'js_composer' ),
				'type' => 'textfield',
				'std' => $config['atts']['el_class'],
			),
			array(
				'param_name' => 'btn_label',
				'heading' => __( 'Button Label', 'us' ),
				'type' => 'textfield',
				'std' => $config['atts']['btn_label'],
				'group' => __( 'Button', 'us' ) . ' 1',
			),
			array(
				'param_name' => 'btn_link',
				'heading' => us_translate( 'Link' ),
				'type' => 'vc_link',
				'std' => $config['atts']['btn_link'],
				'group' => __( 'Button', 'us' ) . ' 1',
			),
			array(
				'param_name' => 'btn_style',
				'heading' => us_translate( 'Style' ),
				'description' => sprintf( __( 'Add or edit Button Styles on %sTheme Options%s', 'us' ), '<a href="' . admin_url() . 'admin.php?page=us-theme-options#buttons" target="_blank">', '</a>' ),
				'type' => 'dropdown',
				'value' => us_get_btn_styles( $for_shortcode = TRUE ),
				'std' => $config['atts']['btn_style'],
				'group' => __( 'Button', 'us' ) . ' 1',
			),
			array(
				'param_name' => 'btn_size',
				'heading' => us_translate( 'Size' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '16px, 1.2em, 1rem' ),
				'type' => 'textfield',
				'std' => $config['atts']['btn_size'],
				'group' => __( 'Button', 'us' ) . ' 1',
			),
			array(
				'param_name' => 'btn_icon',
				'heading' => __( 'Icon', 'us' ),
				'type' => 'us_icon',
				'std' => $config['atts']['btn_icon'],
				'group' => __( 'Button', 'us' ) . ' 1',
			),
			array(
				'param_name' => 'btn_iconpos',
				'heading' => __( 'Icon Position', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Left' ) => 'left',
					us_translate( 'Right' ) => 'right',
				),
				'std' => $config['atts']['btn_iconpos'],
				'group' => __( 'Button', 'us' ) . ' 1',
			),
			array(
				'param_name' => 'second_button',
				'type' => 'checkbox',
				'value' => array( __( 'Display second button', 'us' ) => TRUE ),
				( ( $config['atts']['second_button'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['second_button'],
				'group' => __( 'Button', 'us' ) . ' 2',
			),
			array(
				'param_name' => 'btn2_label',
				'heading' => __( 'Button Label', 'us' ),
				'type' => 'textfield',
				'std' => $config['atts']['btn2_label'],
				'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
				'group' => __( 'Button', 'us' ) . ' 2',
			),
			array(
				'param_name' => 'btn2_link',
				'heading' => us_translate( 'Link' ),
				'type' => 'vc_link',
				'std' => $config['atts']['btn2_link'],
				'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
				'group' => __( 'Button', 'us' ) . ' 2',
			),
			array(
				'param_name' => 'btn2_style',
				'heading' => us_translate( 'Style' ),
				'description' => sprintf( __( 'Add or edit Button Styles on %sTheme Options%s', 'us' ), '<a href="' . admin_url() . 'admin.php?page=us-theme-options#buttons" target="_blank">', '</a>' ),
				'type' => 'dropdown',
				'value' => us_get_btn_styles( $for_shortcode = TRUE ),
				'std' => $config['atts']['btn2_style'],
				'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
				'group' => __( 'Button', 'us' ) . ' 2',
			),
			array(
				'param_name' => 'btn2_size',
				'heading' => us_translate( 'Size' ),
				'type' => 'textfield',
				'std' => $config['atts']['btn2_size'],
				'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
				'group' => __( 'Button', 'us' ) . ' 2',
			),
			array(
				'param_name' => 'btn2_icon',
				'heading' => __( 'Icon', 'us' ),
				'type' => 'us_icon',
				'std' => $config['atts']['btn2_icon'],
				'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
				'group' => __( 'Button', 'us' ) . ' 2',
			),
			array(
				'param_name' => 'btn2_iconpos',
				'heading' => __( 'Icon Position', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Left' ) => 'left',
					us_translate( 'Right' ) => 'right',
				),
				'std' => $config['atts']['btn2_iconpos'],
				'dependency' => array( 'element' => 'second_button', 'not_empty' => TRUE ),
				'group' => __( 'Button', 'us' ) . ' 2',
			),
		),
	)
);
vc_remove_element( 'vc_cta' );

