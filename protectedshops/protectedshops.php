<?php

/*
Plugin Name: ProtectedShops
*/

add_action( 'init', 'activate');
//add_action( 'wp', 'protectedshops_frontpage_init' );
add_action('admin_menu', 'awesome_page_create');
add_action( 'wp', 'protectedshops_frontend_page_init');

function activate()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $module_page_table = $wpdb->prefix . 'ps_module_page';
    $protected_shop_settings_table = $wpdb->prefix . 'ps_settings';
    $projects_table = $wpdb->prefix . 'ps_project';

    $sql = "CREATE TABLE IF NOT EXISTS $module_page_table (
        ID INT NOT NULL AUTO_INCREMENT,
        wp_post_ID INT NOT NULL,
        moduleId varchar(32) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE IF NOT EXISTS $protected_shop_settings_table (
        ID INT NOT NULL AUTO_INCREMENT,
        partner varchar(255) NOT NULL,
        partnerId varchar(255) NOT NULL,
        partnerSecret varchar(255) NOT NULL,
        url varchar(255) NOT NULL,
        modules TEXT NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    $sql3 = "CREATE TABLE IF NOT EXISTS $projects_table (
        ID INT NOT NULL AUTO_INCREMENT,
        projectId varchar(255) NOT NULL,
        title varchar(255) NOT NULL,
        url varchar(255) NOT NULL,
        moduleId varchar(255) NOT NULL,
        bundleId INT NOT NULL,
        partner varchar(255) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
    dbDelta($sql2);
    dbDelta($sql3);
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
    $selectPagesSql = "SELECT ID, post_title FROM `wp_posts` WHERE post_status = 'publish';";
    $wpPages = $wpdb->get_results($selectPagesSql);
    $protected_shop_settings_table = $wpdb->prefix . 'ps_settings';
    $module_page_table = $wpdb->prefix . 'ps_module_page';
    $selectSettings = "SELECT * FROM $protected_shop_settings_table LIMIT 1";
    $currentSettings = $wpdb->get_results($selectSettings);

    if (
        $_POST['partner'] !== NULL
        && $_POST['secret'] !== NULL
        && $_POST['doc_server_url'] !== NULL
        && $_POST['modules'] !== NULL
        && $_POST['partnerId'] !== NULL
    ) {

        if (!$currentSettings) {
            $wpdb->insert(
                $protected_shop_settings_table,
                array(
                    'partnerId' => $_POST['partnerId'],
                    'partner' => $_POST['partner'],
                    'partnerSecret' => $_POST['secret'],
                    'url' => $_POST['doc_server_url'],
                    'modules' => $_POST['modules']
                ),
                array('%s', '%s', '%s', '%s', '%s')
            );
        } else {
            $wpdb->update(
                $protected_shop_settings_table,
                array(
                    'partnerId' => $_POST['partnerId'],
                    'partner' => $_POST['partner'],
                    'partnerSecret' => $_POST['secret'],
                    'url' => $_POST['doc_server_url'],
                    'modules' => $_POST['modules']
                ),
                array(
                    'ID' => $currentSettings[0]->ID
                ),
                array('%s', '%s', '%s', '%s', '%s')
            );
        }
    }

    if ($_POST['wordpress_page_id'] !== NULL && $_POST['moduleId'] !== NULL) {
        $wpdb->insert(
            $module_page_table,
            array(
                'wp_post_ID' => (int)$_POST['wordpress_page_id'],
                'moduleId' => $_POST['moduleId']
            ),
            array('%d', '%s')
        );
    }

    $currentSettings = $wpdb->get_results($selectSettings);
    include 'protectedshops_settings.php';
}

function protectedshops_frontend_page_init()
{
    global $wpdb;
    $pageName = get_query_var('pagename');
    $post_table =$wpdb->prefix . 'posts';
    $projects_table = $wpdb->prefix . 'ps_project';
    $module_page_table = $wpdb->prefix . 'ps_module_page';
    $selectPagesSql = "SELECT $post_table.post_title, $module_page_table.moduleId
                       FROM $post_table
                       JOIN $module_page_table ON $post_table.ID = $module_page_table.wp_post_ID
                       WHERE $post_table.post_title = '$pageName';";

    $psPage = $wpdb->get_results($selectPagesSql);
    if(is_page($psPage[0]->post_title) && $psPage) {
        $dir = plugin_dir_path( __FILE__ );
        require_once $dir . 'ds_communicator.php';

        if ($_POST['moduleId']) {
            $protected_shop_settings_table = $wpdb->prefix . 'ps_settings';
            $credentialsSql = "SELECT * FROM $protected_shop_settings_table;";
            $settings = $wpdb->get_results($credentialsSql);

            if (array_key_exists('command', $_POST) && 'create_project' == $_POST['command']) {
                $dsCommunicator = new Ds_Communicator($settings[0]->url, $settings[0]->partnerId, $settings[0]->partner, $settings[0]->partnerSecret);
                $newProject = $dsCommunicator->createProject($_POST['moduleId'], $_POST['title'], $_POST['url']);
                if (array_key_exists('shopId', $newProject)) {
                    $wpdb->insert(
                        $projects_table,
                        array(
                            'projectId' => $newProject['shopId'],
                            'title' => $newProject['title'],
                            'url' => $_POST['url'],
                            'moduleId' => $newProject['module'],
                            'bundleId' => $newProject['bundleId'],
                            'partner' => $newProject['partnerId']
                        ),
                        array('%s', '%s', '%s', '%s', '%s', '%s')
                    );
                }
            }
        }

        //Prepare data for-frontend
        $sqlProjects = "SELECT * FROM $projects_table";
        $projects = $wpdb->get_results($sqlProjects);

        include($dir . "frontend.php");
        die();
    }
}

//function protectedshops_frontpage_init()
//{
//    if(is_page('protectedshops')){
//        $dir = plugin_dir_path( __FILE__ );
//        include($dir."frontend.php");
//        die();
//    }
//}