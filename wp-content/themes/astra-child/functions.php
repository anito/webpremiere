<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define('CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0');
define('CONSENT_PRO_ID', '71a30230-f01c-48ee-ad6b-4cd05b3f2308-test');

/**
 * Enqueue styles
 */
function child_enqueue_styles()
{

    wp_enqueue_style('astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all');

}

add_action('wp_enqueue_scripts', 'child_enqueue_styles', 15);

/**
 * Enqueue Consens Pro script
 */
function enqueue_cp()
{
    wp_enqueue_style('consent-pro', get_stylesheet_directory_uri() . '/consent-pro/style.css');
    wp_enqueue_script('consent-pro', 'https://cookie-cdn.cookiepro.com/scripttemplates/otSDKStub.js');

}

add_action('wp_enqueue_scripts', 'enqueue_cp', 1);

function add_cp_data_attribute($tag, $handle, $src)
{
    if ('consent-pro' === $handle) {
        $tag = str_replace('src=', 'data-domain-script=' . CONSENT_PRO_ID . ' src=', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'add_cp_data_attribute', 10, 3);
