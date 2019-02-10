<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Common variables used in several elements to avoid translation duplications
 */

return array(

	// Dropdown effects for header
	'dropdown_effect_values' => array(
		'none' => us_translate( 'None' ),
		'opacity' => __( 'Fade', 'us' ),
		'slide' => __( 'SlideDown', 'us' ),
		'height' => __( 'Fade + SlideDown', 'us' ),
		'afb' => __( 'Appear From Bottom', 'us' ),
		'hor' => __( 'Horizontal Slide', 'us' ),
		'mdesign' => __( 'Material Design Effect', 'us' ),
	),

	// HTML tags
	'html_tag_values' => array(
		'h1' => 'h1',
		'h2' => 'h2',
		'h3' => 'h3',
		'h4' => 'h4',
		'h5' => 'h5',
		'h6' => 'h6',
		'div' => 'div',
		'p' => 'p',
		'span' => 'span',
	),

	// Font size examples
	'desc_font_size' => sprintf( __( 'Examples: %s', 'us' ), '<span class="usof-example">16px</span>, <span class="usof-example">1.2rem</span>' ),

	// Line height examples
	'desc_line_height' => sprintf( __( 'Examples: %s', 'us' ), '<span class="usof-example">28px</span>, <span class="usof-example">1.7</span>' ),

	// Height examples
	'desc_height' => sprintf( __( 'Examples: %s', 'us' ), '<span class="usof-example">200px</span>, <span class="usof-example">15rem</span>, <span class="usof-example">10vh</span>' ),

	// Width examples
	'desc_width' => sprintf( __( 'Examples: %s', 'us' ), '<span class="usof-example">200px</span>, <span class="usof-example">50%</span>, <span class="usof-example">14rem</span>, <span class="usof-example">10vw</span>' ),

	// Padding examples
	'desc_padding' => sprintf( __( 'Examples: %s', 'us' ), '<span class="usof-example">20px</span>, <span class="usof-example">15%</span>, <span class="usof-example">1rem</span>, <span class="usof-example">2vw</span>' ),

	// Border radius examples
	'desc_border_radius' => sprintf( __( 'Examples: %s', 'us' ), '<span class="usof-example">5px</span>, <span class="usof-example">50%</span>, <span class="usof-example">0.2rem</span>' ),

	// Menu selection
	'desc_menu_select' => sprintf( __( 'Add or edit a menu on the %s page', 'us' ), '<a href="' . admin_url( 'nav-menus.php' ) . '" target="_blank">' . us_translate( 'Menus' ) . '</a>' ),

	// Image Sizes
	'desc_img_sizes' => '<a target="_blank" href="' . admin_url( 'admin.php?page=us-theme-options' ) . '#advanced">' . __( 'Edit image sizes', 'us' ) . '</a>.',

	// Button styles
	'desc_btn_styles' => sprintf( __( 'Add or edit Button Styles on %sTheme Options%s', 'us' ), '<a href="' . admin_url() . 'admin.php?page=us-theme-options#buttons" target="_blank">', '</a>' ),

	// Custom link for Grid Layout
	'desc_grid_custom_link' => sprintf( __( 'To apply a URL from a custom field, use its name between the %s symbols.', 'us' ), '<code>{{}}</code>' ) . ' ' . sprintf( __( 'Examples: %s', 'us' ), '<span class="usof-example">{{us_tile_link}}</span>, <span class="usof-example">{{us_testimonial_link}}</span>' ),

);
