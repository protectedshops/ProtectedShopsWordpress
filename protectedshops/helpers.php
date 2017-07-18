<?php

function ps_get_settings()
{
    global $wpdb;

    $protected_shop_settings_table = $wpdb->prefix . 'ps_settings';
    $credentialsSql = "SELECT * FROM $protected_shop_settings_table;";
    $settings = $wpdb->get_results($credentialsSql);

    return $settings;
}

function ps_document_server()
{
    require_once 'document_server.php';
    $settings = ps_get_settings();

    return DocumentServer::me($settings[0]->url, $settings[0]->partnerId, $settings[0]->partner, $settings[0]->partnerSecret);
}

function ps_get_page()
{
    global $wpdb;

    $pageName = get_query_var('pagename');

    $post_table =$wpdb->prefix . 'posts';
    $module_page_table = $wpdb->prefix . 'ps_module_page';
    $selectPagesSql = "SELECT $post_table.post_title, $module_page_table.moduleId
                       FROM $post_table
                       JOIN $module_page_table ON $post_table.ID = $module_page_table.wp_post_ID
                       WHERE $post_table.post_title = '$pageName';";

    return $wpdb->get_results($selectPagesSql);
}