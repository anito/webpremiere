<?php
require_once( __DIR__ . '/includes/product_category_handler.php');
require_once( __DIR__ . '/includes/duplicate_content.php');
require_once( __DIR__ . '/includes/sender_email.php');

add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );

/*
 * Load Translations (Loco-Translate)
 */
function child_theme_slug_setup() {
    
    load_child_theme_textdomain( 'us', get_stylesheet_directory() . '/common/languages' );
    
}
add_action( 'after_setup_theme', 'child_theme_slug_setup' );

// Cookie Policy Settings
// add_action( 'wp_head', 'add_cookie_policy' );
function add_cookie_policy() {
	?>
	<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="efe535a2-5b4a-41c9-a3c9-cd065404fbb2" data-blockingmode="auto" type="text/javascript"></script>
	<?php
}

// add_action( 'wp_footer', 'display_cookie_policy' );
function display_cookie_policy() {
	?>
	<script id="CookieDeclaration" src="https://consent.cookiebot.com/efe535a2-5b4a-41c9-a3c9-cd065404fbb2/cd.js" type="text/javascript" async></script>
	<?php
}