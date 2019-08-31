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