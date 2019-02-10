<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$receiver_email = get_option( 'admin_email' );
$body_fontsize = us_get_option( 'body_fontsize', '16px' );
$btn_styles = us_get_btn_styles();

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );

return array(
	'title' => __( 'Contact Form', 'us' ),
	'params' => array_merge( array(

		// General
		'receiver_email' => array(
			'title' => __( 'Receiver Email', 'us' ),
			'description' => __( 'Requests will be sent to this email. You can insert multiple comma-separated emails as well.', 'us' ),
			'type' => 'text',
			'std' => $receiver_email,
			'admin_label' => TRUE,
		),
		'name_field' => array(
			'title' => __( 'Name field', 'us' ),
			'type' => 'select',
			'options' => array(
				'required' => __( 'Shown, required', 'us' ),
				'shown' => __( 'Shown, not required', 'us' ),
				'hidden' => __( 'Hidden', 'us' ),
			),
			'std' => 'required',
			'cols' => 2,
		),
		'email_field' => array(
			'title' => __( 'Email field', 'us' ),
			'type' => 'select',
			'options' => array(
				'required' => __( 'Shown, required', 'us' ),
				'shown' => __( 'Shown, not required', 'us' ),
				'hidden' => __( 'Hidden', 'us' ),
			),
			'std' => 'required',
			'cols' => 2,
		),
		'phone_field' => array(
			'title' => __( 'Phone field', 'us' ),
			'type' => 'select',
			'options' => array(
				'required' => __( 'Shown, required', 'us' ),
				'shown' => __( 'Shown, not required', 'us' ),
				'hidden' => __( 'Hidden', 'us' ),
			),
			'std' => 'required',
			'cols' => 2,
		),
		'message_field' => array(
			'title' => __( 'Message field', 'us' ),
			'type' => 'select',
			'options' => array(
				'required' => __( 'Shown, required', 'us' ),
				'shown' => __( 'Shown, not required', 'us' ),
				'hidden' => __( 'Hidden', 'us' ),
			),
			'std' => 'required',
			'cols' => 2,
		),
		'captcha_field' => array(
			'title' => __( 'Captcha field', 'us' ),
			'type' => 'select',
			'options' => array(
				'hidden' => __( 'Hidden', 'us' ),
				'required' => __( 'Shown, required', 'us' ),
			),
			'std' => 'hidden',
			'cols' => 2,
		),
		'checkbox_field' => array(
			'title' => __( 'Agreement Checkbox', 'us' ),
			'type' => 'select',
			'options' => array(
				'hidden' => __( 'Hidden', 'us' ),
				'required' => __( 'Shown, required', 'us' ),
			),
			'std' => 'hidden',
			'cols' => 2,
		),
		'content' => array(
			'title' => __( 'Agreement text', 'us' ),
			'type' => 'textarea',
			'std' => 'I consent to the processing and storage of my personal data',
			'show_if' => array( 'checkbox_field', '=', 'required' ),
		),

		// Button
		'button_text' => array(
			'title' => __( 'Button Label', 'us' ),
			'type' => 'text',
			'std' => __( 'Send Message', 'us' ),
			'group' => __( 'Button', 'us' ),
		),
		'button_style' => array(
			'title' => us_translate( 'Style' ),
			'description' => $misc['desc_btn_styles'],
			'type' => 'select',
			'options' => $btn_styles,
			'std' => '1',
			'group' => __( 'Button', 'us' ),
		),
		'button_size' => array(
			'title' => us_translate( 'Size' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => $body_fontsize,
			'cols' => 2,
			'group' => __( 'Button', 'us' ),
		),
		'button_size_mobiles' => array(
			'title' => __( 'Size on Mobiles', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 2,
			'group' => __( 'Button', 'us' ),
		),
		'button_fullwidth' => array(
			'type' => 'switch',
			'switch_text' => __( 'Stretch to the full width', 'us' ),
			'std' => FALSE,
			'group' => __( 'Button', 'us' ),
		),
		'button_align' => array(
			'title' => __( 'Button Alignment', 'us' ),
			'type' => 'select',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'center' => us_translate( 'Center' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'left',
			'group' => __( 'Button', 'us' ),
		),
		'icon' => array(
			'title' => __( 'Icon', 'us' ),
			'type' => 'icon',
			'std' => '',
			'group' => __( 'Button', 'us' ),
		),
		'iconpos' => array(
			'title' => __( 'Icon Position', 'us' ),
			'type' => 'select',
			'options' => array(
				'left' => us_translate( 'Left' ),
				'right' => us_translate( 'Right' ),
			),
			'std' => 'left',
			'group' => __( 'Button', 'us' ),
		),

	), $design_options ),
);
