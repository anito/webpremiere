<?php
require_once( __DIR__ . '/includes/product_category_handler.php');
require_once( __DIR__ . '/includes/duplicate_content.php');
require_once( __DIR__ . '/includes/sender_email.php');

add_filter( 'upload_mimes', 'allow_svg_upload' );
function allow_svg_upload( $m ) {
    $m['svg'] = 'image/svg+xml';
    $m['svgz'] = 'image/svg+xml';
    return $m;
}

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Include all the needed files
 *
 * Do not modify this or other theme's files. Use child theme instead!
 */

if ( ! defined( 'US_ACTIVATION_THEMENAME' ) ) {
	define( 'US_ACTIVATION_THEMENAME', 'Impreza' );
}

$us_theme_supports = array(
	'plugins' => array(
		'js_composer' => '/framework/plugins-support/js_composer/js_composer.php',
		'Ultimate_VC_Addons' => '/framework/plugins-support/Ultimate_VC_Addons.php',
		'revslider' => '/framework/plugins-support/revslider.php',
		'contact-form-7' => NULL,
		'gravityforms' => '/framework/plugins-support/gravityforms.php',
		'woocommerce' => '/framework/plugins-support/woocommerce/woocommerce.php',
		'codelights' => '/framework/plugins-support/codelights.php',
		'wpml' => '/framework/plugins-support/wpml.php',
		'bbpress' => '/framework/plugins-support/bbpress.php',
		'tablepress' => '/framework/plugins-support/tablepress.php',
		'the-events-calendar' => '/framework/plugins-support/the_events_calendar.php',
		'us-header-builder' => '/framework/plugins-support/us_header_builder.php',
		'tiny_mce' => '/framework/plugins-support/tiny_mce.php',
	),
);

//require dirname( __FILE__ ) . '/impreza/framework/framework.php';

unset( $us_theme_supports );

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'mesmerize-woo' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION
