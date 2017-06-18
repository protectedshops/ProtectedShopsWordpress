<?php

/*
Plugin Name: ProtectedShops
*/

add_action( 'init', 'activate');

function activate()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'ps_shops';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        ID INT NOT NULL AUTO_INCREMENT,
        shopId varchar(32) NOT NULL,
        wp_userID INT NOT NULL,
        PRIMARY KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}