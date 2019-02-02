<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_cform
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config ['atts'] array Shortcode's attributes and default values
 */

vc_map(
	array(
		'base' => 'us_cform',
		'name' => __( 'Contact Form', 'us' ),
		'description' => '',
		'category' => us_translate( 'Content', 'js_composer' ),
		'weight' => 180,
		'params' => array(
			array(
				'param_name' => 'receiver_email',
				'heading' => __( 'Receiver Email', 'us' ),
				'description' => sprintf( __( 'Requests will be sent to this Email. You can insert multiple comma-separated emails as well.', 'us' ) ),
				'type' => 'textfield',
				'std' => $config['atts']['receiver_email'],
				'admin_label' => TRUE,
			),
			array(
				'param_name' => 'name_field',
				'heading' => __( 'Name field', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Shown, required', 'us' ) => 'required',
					__( 'Shown, not required', 'us' ) => 'shown',
					__( 'Hidden', 'us' ) => 'hidden',
				),
				'std' => $config['atts']['name_field'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'email_field',
				'heading' => __( 'Email field', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Shown, required', 'us' ) => 'required',
					__( 'Shown, not required', 'us' ) => 'shown',
					__( 'Hidden', 'us' ) => 'hidden',
				),
				'std' => $config['atts']['email_field'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'phone_field',
				'heading' => __( 'Phone field', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Shown, required', 'us' ) => 'required',
					__( 'Shown, not required', 'us' ) => 'shown',
					__( 'Hidden', 'us' ) => 'hidden',
				),
				'std' => $config['atts']['phone_field'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'message_field',
				'heading' => __( 'Message field', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Shown, required', 'us' ) => 'required',
					__( 'Shown, not required', 'us' ) => 'shown',
					__( 'Hidden', 'us' ) => 'hidden',
				),
				'std' => $config['atts']['message_field'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'captcha_field',
				'heading' => __( 'Captcha field', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Hidden', 'us' ) => 'hidden',
					__( 'Shown, required', 'us' ) => 'required',
				),
				'std' => $config['atts']['captcha_field'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'checkbox_field',
				'heading' => __( 'Agreement Checkbox', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Hidden', 'us' ) => 'hidden',
					__( 'Shown, required', 'us' ) => 'required',
				),
				'std' => $config['atts']['checkbox_field'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'content',
				'heading' => __( 'Agreement text', 'us' ),
				'type' => 'textarea',
				'std' => $config['content'],
				'dependency' => array( 'element' => 'checkbox_field', 'value' => 'required' ),
			),
			array(
				'param_name' => 'el_class',
				'heading' => us_translate( 'Extra class name', 'js_composer' ),
				'type' => 'textfield',
				'std' => $config['atts']['el_class'],
			),
			array(
				'param_name' => 'button_text',
				'heading' => __( 'Button Label', 'us' ),
				'type' => 'textfield',
				'std' => $config['atts']['button_text'],
				'group' => __( 'Button', 'us' ),
			),
			array(
				'param_name' => 'button_style',
				'heading' => us_translate( 'Style' ),
				'description' => sprintf( __( 'Add or edit Button Styles on %sTheme Options%s', 'us' ), '<a href="' . admin_url() . 'admin.php?page=us-theme-options#buttons" target="_blank">', '</a>' ),
				'type' => 'dropdown',
				'value' => us_get_btn_styles( $for_shortcode = TRUE ),
				'std' => $config['atts']['button_style'],
				'group' => __( 'Button', 'us' ),
			),
			array(
				'param_name' => 'button_size',
				'heading' => us_translate( 'Size' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '16px, 1.2em, 1rem' ),
				'type' => 'textfield',
				'std' => $config['atts']['button_size'],
				'edit_field_class' => 'vc_col-sm-6',
				'group' => __( 'Button', 'us' ),
			),
			array(
				'param_name' => 'button_size_mobiles',
				'heading' => __( 'Size on Mobiles', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '14px, 1em, 0.9rem' ),
				'type' => 'textfield',
				'std' => $config['atts']['button_size_mobiles'],
				'edit_field_class' => 'vc_col-sm-6',
				'group' => __( 'Button', 'us' ),
			),
			array(
				'param_name' => 'button_fullwidth',
				'type' => 'checkbox',
				'value' => array( __( 'Stretch to the full width', 'us' ) => TRUE ),
				( ( $config['atts']['button_fullwidth'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['button_fullwidth'],
				'group' => __( 'Button', 'us' ),
			),
			array(
				'param_name' => 'button_align',
				'heading' => __( 'Button Alignment', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Left' ) => 'left',
					us_translate( 'Center' ) => 'center',
					us_translate( 'Right' ) => 'right',
				),
				'std' => $config['atts']['button_align'],
				'group' => __( 'Button', 'us' ),
			),
			array(
				'param_name' => 'icon',
				'heading' => __( 'Icon', 'us' ),
				'type' => 'us_icon',
				'std' => $config['atts']['icon'],
				'group' => __( 'Button', 'us' ),
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
				'edit_field_class' => 'vc_col-sm-12',
				'group' => __( 'Button', 'us' ),
			),
		),
	)
);
