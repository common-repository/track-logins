<?php
/*
Plugin Name: Track Logins
Plugin URI: https://wordpress.org/plugins/track-logins/
Description: Track User Logins, record time stamps, username and ip address.
Version: 1.0
Author: Stephen Carr
Author URI: https://www.stephencarr.net
License: GPLv2
Author: Stephen Carr
*/

function track_logins_install() {
    global $wpdb;

    $table_name = $wpdb->prefix . "track_logins";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `username` varchar(100) CHARACTER SET utf8 NOT NULL,
            `date_time` varchar(100) CHARACTER SET utf8 NOT NULL,
            `ip_address` varchar(100) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
	
	
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'track_logins_install');

define('track_login_ROOTDIR', plugin_dir_path(__FILE__));
define('track_login_ROOTURL', plugin_dir_url(__FILE__));

	
//menu items
add_action('admin_menu','trackLogin_modifymenu');


require_once(track_login_ROOTDIR . 'login-list.php');




function trackLogin_modifymenu() {
	
	//this is the main item for the menu
	add_menu_page('Logins', //page title
	'Logins', //menu title
	'manage_options', //capabilities
	'login_track_list', //menu slug
	'login_track_list', //function
	track_login_ROOTURL.'images/icon.png',
	1
);
	
}//close function




########## TRACK USER LOGINS ##########
function do_the_track_for_users() {
//write query here to insert into track login DB's...
global $wpdb;
$table_name = $wpdb->prefix . "track_logins";
$date_time = strtotime("now");
$the_user_name = sanitize_user($_POST['log']);

$ip_address = $_SERVER['REMOTE_ADDR'];
$wpdb->insert(
$table_name, //table
array("username" => $the_user_name,"ip_address" => $ip_address,"date_time" => $date_time ), //data
array('%s','%s','%s') //data format	
);
}//close function
add_action('wp_login', 'do_the_track_for_users');
########## TRACK USER LOGINS ##########



