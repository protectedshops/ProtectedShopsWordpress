<?php

/*
Plugin Name: ProtectedShops
*/

/*Plugin helpers*/
require_once 'helpers.php';

add_action( 'init', 'activate');
//add_action('wp', 'protectedshops_frontpage_init');
add_action('admin_menu', 'protected_shops_admin_page');
//add_action('wp', 'protectedshops_frontend_page_init');
add_filter('the_content', 'protectedshops_frontend_page_init');
add_action('wp_enqueue_scripts', 'add_scripts');
add_action( 'rest_api_init', function () {
    register_rest_route( 'protectedshops/v1', '/questionary', array(
        'methods' => 'GET',
        'callback' => 'buildQuestionary',
    ) );

    register_rest_route( 'protectedshops/v1', '/questionary/answer', array(
        'methods' => 'POST',
        'callback' => 'saveAnswers',
    ) );

    register_rest_route( 'protectedshops/v1', '/questionary/download', array(
        'methods' => 'GET',
        'callback' => 'downloadDocument',
    ) );
} );

add_action( 'wp_default_scripts', function( $scripts ) {
    if ( ! empty( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
    }
} );

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
        changed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        wp_user_ID INT NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
    dbDelta($sql2);
    dbDelta($sql3);
}


function protected_shops_admin_page() {
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

function protectedshops_frontend_page_init($text)
{
    global $wpdb;
    $pluginDir = plugin_dir_path( __FILE__ );
    $projects_table = $wpdb->prefix . 'ps_project';

    $docServer = ps_document_server();
    $psPage = ps_get_page();
    $wpUser = wp_get_current_user();
    /*$wpNonce is used for the plugin API calls so the user can authenticate*/
    $wpNonce = wp_create_nonce('wp_rest');
    $settings = ps_get_settings();
    $pluginURL = plugin_dir_url(__FILE__);

    if(is_page($psPage[0]->post_title) && $psPage) {
        if (!is_user_logged_in()) {
            include($pluginDir . "tabs/login_first.php");
        } elseif ($_POST['moduleId']) {
            if (array_key_exists('command', $_POST) && 'create_project' == $_POST['command']) {

                $newProject = $docServer->createProject($_POST['moduleId'], $_POST['title'], $_POST['url']);
                if (array_key_exists('shopId', $newProject)) {
                    $wpdb->insert(
                        $projects_table,
                        array(
                            'projectId' => $newProject['shopId'],
                            'title' => $newProject['title'],
                            'url' => $_POST['url'],
                            'moduleId' => $newProject['module'],
                            'bundleId' => $newProject['bundleId'],
                            'partner' => $newProject['partnerId'],
                            'wp_user_ID' => $wpUser->ID
                        ),
                        array('%s', '%s', '%s', '%s', '%s', '%s')
                    );
                }
            }
        } elseif (array_key_exists('tab', $_GET) && 'downloads' == $_GET['tab']) {
            $sqlProject = "SELECT * FROM $projects_table WHERE wp_user_ID=$wpUser->ID AND projectId='" . sanitize_text_field($_GET['project']) ."';";
            $project = $wpdb->get_results($sqlProject);
            $documents = json_decode($docServer->getDocuments($_GET['partner'], $_GET['project']), 1);
            include($pluginDir . "tabs/document_list.php");
        } else {
            if (array_key_exists('command', $_GET) && 'delete_project' == $_GET['command']) {
                $deleteSql = "DELETE FROM $projects_table WHERE wp_user_ID=$wpUser->ID AND projectId='" . sanitize_text_field($_GET['project']) ."';";
                $wpdb->query($deleteSql);
            }

            $sqlProjects = "SELECT * FROM $projects_table WHERE wp_user_ID=$wpUser->ID AND moduleId='" . $psPage[0]->moduleId . "';";
            $remoteProjects = ps_get_remote_projects($settings[0]->partner);
            $projects = $wpdb->get_results($sqlProjects);
            foreach ($projects as $project) {
                $project->isValid = is_project_valid($remoteProjects, $project->projectId);
            }

            $psTemplatesUrl = plugins_url('integration-package/templates', __FILE__ );
            include($pluginDir . "tabs/project_list.php");
        }
    } else {
        return $text;
    }
}

function add_scripts()
{
    wp_register_script('questionary', plugins_url('integration-package/js/questionary.js', __FILE__ ), array( 'jquery' ));
    wp_register_script('dust_core', plugins_url('integration-package/js/dust-core.js', __FILE__ ), array( ));
    wp_register_script('dust', plugins_url('integration-package/js/dust-full.js', __FILE__ ), array( ));
    wp_register_script('dust-helper', plugins_url('integration-package/js/dust-helpers.js', __FILE__ ), array());

    wp_enqueue_script('questionary');
    wp_enqueue_script('dust_core');
    wp_enqueue_script('dust');
    wp_enqueue_script('dust-helper');
}

function buildQuestionary(WP_REST_Request $request)
{
    $partner = $request->get_param('partner');
    $project = $request->get_param('project');
    $wpUser = wp_get_current_user();

    if (!ps_is_project_access_allowed($project, $wpUser->ID)) {
        return '{}';
    }

    $docServer = ps_document_server();

    return $docServer->getQuestionary($partner, $project);
}

function saveAnswers(WP_REST_Request $request)
{
    $partner = $request->get_param('partner');
    $project = $request->get_param('project');
    $answers = $request->get_param('answers');
    $wpUser = wp_get_current_user();

    if (!ps_is_project_access_allowed($project, $wpUser->ID)) {
        return '{}';
    }

    ps_project_change($partner, $project);
    $docServer = ps_document_server();

    return $docServer->answerQuestion($partner, $project, $answers);
}

function downloadDocument(WP_REST_Request $request)
{
    $partner = $request->get_param('partner');
    $project = $request->get_param('project');
    $docType = $request->get_param('docType');
    $formatType = $request->get_param('formatType');
    $wpUser = wp_get_current_user();

    if (!ps_is_project_access_allowed($project, $wpUser->ID)) {
        return '{}';
    }

    $docServer = ps_document_server();

    $remoteResponse = $docServer->downloadDocument($partner, $project, $docType, $formatType);
    $remoteResponse = json_decode($remoteResponse);

    if ('base64' === $remoteResponse->contentEncoding)
    {
        $binaryData = base64_decode($remoteResponse->content);
    }
    else
    {
        $binaryData = $remoteResponse->content;
    }

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-disposition: attachment; filename=' . "$docType.$formatType");
    header('Content-Length: ' . strlen($binaryData));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    header('Pragma: public');
    echo $binaryData;
    exit;
}
