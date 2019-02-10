<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$body_fontsize = us_get_option( 'body_fontsize', '16px' );

$misc = us_config( 'elements_misc' );
$design_options = us_config( 'elements_design_options' );
$hover_options = us_config( 'elements_hover_options' );

return array(
	'title' => sprintf( __( '"%s" button', 'us' ), us_translate( 'Add to cart', 'woocommerce' ) ),
	'params' => array_merge( array(

		'font_size' => array(
			'title' => us_translate( 'Size' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => $body_fontsize,
			'cols' => 2,
		),
		'font_size_mobiles' => array(
			'title' => __( 'Size on Mobiles', 'us' ),
			'description' => $misc['desc_font_size'],
			'type' => 'text',
			'std' => '',
			'cols' => 2,
		),
		'view_cart_link' => array(
			'type' => 'switch',
			'switch_text' => __( 'Show link to cart when adding products', 'us' ),
			'std' => FALSE,
		),

	), $design_options, $hover_options ),
);
