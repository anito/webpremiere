<?php
/*
Plugin Name: DB Backup
Plugin URI: 
Description: Hook for Database Backup 
Version: 1.0.0
Author: Axel Nitzschner
Author URI: 
License: GPLv2
*/
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * DEFINE PATHS
 */
define('DBBU_PATH', plugin_dir_path(__FILE__));
define('DBBU_CLASSES_PATH', DBBU_PATH . 'includes/classes/');
define('DBBU_FUNCTIONS_PATH', DBBU_PATH . 'includes/functions/');
define('DBBU_LANGUAGES_PATH', basename(DBBU_PATH) . '/languages/');
define('DBBU_VIEWS_PATH', DBBU_PATH . 'views/');
define('DBBU_CSS_PATH', DBBU_PATH . 'assets/css/');

/**
 * DEFINE URLS
 */
define('DBBU_URL', plugin_dir_url(__FILE__));
define('DBBU_JS_URL', DBBU_URL . 'assets/js/');
define('DBBU_CSS_URL', DBBU_URL . 'assets/css/');
define('DBBU_IMAGES_URL', DBBU_URL . 'assets/images/');

/**
 * FRONTEND
 */
require_once(DBBU_CLASSES_PATH . 'DB_Backup.php');
register_activation_hook(__FILE__, array('DB_Backup', 'activate'));
register_deactivation_hook(__FILE__, array('DB_Backup', 'deactivate'));
