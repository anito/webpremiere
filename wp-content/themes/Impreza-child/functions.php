<?php
require_once( __DIR__ . '/includes/product_category_handler.php');
require_once( __DIR__ . '/includes/duplicate_content.php');
require_once( __DIR__ . '/includes/sender_email.php');

add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );


/* ----------------------------------------------------------------------------------- */
/* Display a Backup Warning
/* ----------------------------------------------------------------------------------- */
add_action( 'admin_notices', 'add_notices' );

/*
 * 	return a given subdomain for home_url
 * 
 */
function get_subdomain_url( $subdomain_name ) {
	$home_url = home_url( '/' );
	$find = array( 'http://', 'https://' );
	$replace = $subdomain_name . '.';
	$output = str_replace( $find, $replace, $home_url );
	return is_ssl() ? 'https://' . $output : 'http://' . $output;
}

/*
 * get the age in days of Backup
 * 
 */
function get_day_diff( $time, $time_unit = "d" ) {
	
	$timeZone = 'Europe/Berlin';
    date_default_timezone_set($timeZone);
	
	$now = date_create();

	if ( !isset( $time ) )
		$time = $now;

	$lst = date_create( date( "Y-m-d H:i:s", $time ) );
	$diff = date_diff( $lst, $now );
	switch( $time_unit ) {
		case "y":
			$total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
			$unit_name = sprintf( __('Jahr%s', ''), 1 !== $total ? 'en' : ''  );
			break;
		case "m":
			$total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
			$unit_name = sprintf( __('Monat%s', ''), 1 !== $total ? 'en' : ''  );
			break;
		case "d":
			$total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i/60;
			$unit_name = sprintf( __('Tag%s', ''), 1 !== $total ? 'en' : ''  );
			break;
		case "h":
			$total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
			$unit_name = sprintf( __('Stunde%s', ''), 1 !== $total ? 'n' : ''  );
			break;
		case "i":
			$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
			$unit_name = sprintf( __('Minute%s', ''), 1 !== $total ? 'n' : ''  );
			break;
	}
	return array( 'total' => round($total, 0, PHP_ROUND_HALF_DOWN), 'name' => $unit_name );
}

/*
 * read last backup time
 * 
 */
function read_last_backup( $human = "" ) {

	$backup_domain = get_subdomain_url( 'backup' );
	
	$arrContextOptions = array(
		"ssl" => array(
			"verify_peer" => false, // ignore SSL Cert
			"verify_peer_name" => true,
		),
	);

	if ( IS_DEV_MODE )
		$response = file_get_contents( $backup_domain . 'read/' . $human, false, stream_context_create( $arrContextOptions ) );
	else
		$response = file_get_contents( $backup_domain . 'read/' . $human );

	return $response;
}

/**
 * Add notices - will be displayed on dashboard
 *
 * TODO: improve API on http://backup
 * currently we need to read twice for the different time formats
 * 
 */
function add_notices() {
	
	$max_allowed_age = -1; // -1 for display always
	$screen = get_current_screen();
	$notices = array();
	$backup_domain = get_subdomain_url( 'backup' );
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$return_url = urlencode($current_url);

	$date = read_last_backup( TRUE ); // human readable time
	if( !$date ) {
		$date	= '<strong><span style="color: #f00;">Noch kein Backup vorhanden!</span></strong>';
		$text =  sprintf( __( 'Letztes <strong><a href="%s" target="_blank">Datenbank Backup</a></strong>: %s ', '' ), $backup_domain, $date );
	} else {
		$last_backup = intval( read_last_backup() ); // get UNIX-Timestamp
		if( ( $diff = get_day_diff( $last_backup, 'i' ) ) && ( $diff['total'] > 59 ) ) { // express in minutes
			if( ( $diff = get_day_diff( $last_backup, 'h' ) ) && ( $diff['total'] > 23 ) ) { // express in hours
				if( ( $diff = get_day_diff( $last_backup, 'd' ) ) && ( $diff['total'] > 29 ) ) { // express in days
					if( ( $diff = get_day_diff( $last_backup, 'm' ) ) && ( $diff['total'] > 11 ) ) { // express in months
						$diff = get_day_diff( $last_backup, 'y' ); // express in years
					}
				}
			}
		}
		if ( $max_allowed_age >= $diff['total'] )
			return;
		$age = $diff['total'] . ' ' . $diff['name'];
		$text =  sprintf( __( 'Letztes <strong><a href="%s" target="_blank">Datenbank Backup</a></strong> vor <i>%s</i><span class="dimmed"> am %s</span>', '' ), $backup_domain, $age, $date );
	}

	$current_user = wp_get_current_user();
	$notices['make_backup'] = array(
		'class' => 'notice notice-info',
		'msg' => sprintf( __( '<div class="ha-admin backup-info"> %s</div>', '' ), $text )
	);

	// template
	wp_enqueue_style( 'ha-adm', get_stylesheet_directory_uri() . '/css/admin/style.css', array(), '0.5' );
	require_once( __DIR__ . '/includes/notice.php');
}

//add_action( 'admin_enqueue_scripts', 'my_enqueue' );
function my_enqueue($hook) {
    if( 'index.php' != $hook ) {
		// Only applies to dashboard panel
		return;
    }
	
	wp_enqueue_script( 'ajax-script', get_stylesheet_directory_uri() . '/js/user.js' , array('jquery') );

	// hand over the userID to the analytics script
    $current_user = wp_get_current_user();
	$id = (0 == $current_user->ID) ? '' : $current_user->ID;
	$login = $current_user->data->user_login;
	$pw = $current_user->data->user_pass;
	$md5 = md5($pw);
	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'ajax-script', 'my_ajax_object', array(
//		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'ajax_url' => 'https://backup.ha-lehmann.dev/users/login',
		'we_value' => 12345,
		'id' => $id,
		'login' => $login,
//		'password' => $pw
		'password' => 'kakadax'
		));
}