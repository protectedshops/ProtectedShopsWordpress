<?php

/*
Plugin Name: ProtectedShops
*/

add_action( 'init', 'activate');
//add_action( 'wp', 'protectedshops_frontpage_init' );
add_action('admin_menu', 'awesome_page_create');

function activate()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $shop_table = $wpdb->prefix . 'ps_shops';
    $protected_shop_settings_table = $wpdb->prefix . 'ps_settings';

    $sql = "CREATE TABLE IF NOT EXISTS $shop_table (
        ID INT NOT NULL AUTO_INCREMENT,
        shopId varchar(32) NOT NULL,
        shopName varchar(32) NOT NULL,
        wp_userID INT NOT NULL,
        PRIMARY KEY id (id)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE IF NOT EXISTS $protected_shop_settings_table (
        ID INT NOT NULL AUTO_INCREMENT,
        partnerId varchar(32) NOT NULL,
        partnerSecret varchar(32) NOT NULL,
        url varchar(255) NOT NULL,
        PRIMARY KEY id (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
    dbDelta($sql2);
}


function awesome_page_create() {
    $page_title = 'ProtectedShops Settings';
    $menu_title = 'ProtectedShops';
    $capability = 'edit_posts';
    $menu_slug = 'protectedshops';
    $function = 'protectedshops_admin_page_display';
    $icon_url = '';
    $position = 24;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

function protectedshops_admin_page_display()
{
    global $wpdb;
    $protected_shop_settings_table = $wpdb->prefix . 'ps_settings';
    $selectSettings = "SELECT * FROM $protected_shop_settings_table LIMIT 1";

    if ($_POST['partner'] !== NULL && $_POST['secret'] !== NULL && $_POST['doc_server_url'] !== NULL) {
        $currentSettings = $wpdb->get_results($selectSettings);

        if (!$currentSettings) {
            $wpdb->insert(
                $protected_shop_settings_table,
                array(
                    'partnerId' => $_POST['partner'],
                    'partnerSecret' => $_POST['secret'],
                    'url' => $_POST['doc_server_url'],
                ),
                array('%s', '%s', '%s')
            );
        } else {
            $wpdb->update(
                $protected_shop_settings_table,
                array(
                    'partnerId' => $_POST['partner'],
                    'partnerSecret' => $_POST['secret'],
                    'url' => $_POST['doc_server_url'],
                ),
                array(
                    'ID' => $currentSettings[0]->ID
                ),
                array('%s', '%s', '%s'),
                array('%d')
            );
        }
    }

    include 'protectedshops_settings.php';
}

//function protectedshops_frontpage_init()
//{
//    if(is_page('protectedshops')){
//        $dir = plugin_dir_path( __FILE__ );
//        include($dir."frontend.php");
//        die();
//    }
//}