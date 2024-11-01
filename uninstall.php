<?php
    if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
    global $wpdb;
	$the_table = $wpdb->prefix."track_logins";
    $wpdb->query( "DROP TABLE IF EXISTS $the_table" );
    //delete_option("my_plugin_db_version");
?>