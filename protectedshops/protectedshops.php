<?php

/*
Plugin Name: ProtectedShops
Description: Das Plugin ermöglicht die Zuweisung von ProtectedShops-Modul zu Wordpress-Seite. Also, wenn ein registrierter Benutzer diese Seite besuchen, wird er in der Lage sein, Projekt für dieses Modul zu erstellen, die Frage zu beantworten und die erzeugten Dokumente herunterzuladen.
Version: 1.0.0
Author: Protected Shops GmbH
Author URI: https://protectedshops.de
*/

/*Plugin helpers*/
require_once 'helpers.php';
include_once(ABSPATH . 'wp-includes/pluggable.php');

add_action( 'init', 'activate');
//add_action('wp', 'protectedshops_frontpage_init');
add_action('admin_menu', 'protected_shops_admin_page');
//add_action('wp', 'protectedshops_frontend_page_init');
add_filter('the_content', 'protectedshops_frontend_page_init');
add_action('wp_enqueue_scripts', 'add_scripts');
add_action('rest_api_init', function () {
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

add_action('wp_default_scripts', function( $scripts) {
    if (!empty($scripts->registered['jquery']) && !is_admin()) {
        return;
    }
    $psPage = ps_get_page();
    if(isset($psPage[0]) && is_page($psPage[0]->post_title) && $psPage && !empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array('jquery-migrate'));
    }
} );

function activate()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $module_page_table = $wpdb->prefix . 'ps_module_page';
    $protected_shop_settings_table = $wpdb->prefix . 'ps_settings';
    $projects_table = $wpdb->prefix . 'ps_project';

    $sql = "CREATE TABLE $module_page_table (
        ID INT NOT NULL AUTO_INCREMENT,
        wp_post_ID INT NOT NULL,
        moduleId varchar(255) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE $protected_shop_settings_table (
        ID int(11) NOT NULL AUTO_INCREMENT,
        partner varchar(255) NOT NULL,
        partnerId varchar(255) NOT NULL,
        partnerSecret varchar(255) NOT NULL,
        url varchar(255) NOT NULL,
        modules text NOT NULL,
        templatePageId int(11) NULL,
        PRIMARY KEY  (ID)
    ) $charset_collate;";

    $sql3 = "CREATE TABLE $projects_table (
        ID int(11) NOT NULL AUTO_INCREMENT,
        projectId varchar(255) NOT NULL,
        title varchar(255) NOT NULL,
        url varchar(255) NOT NULL,
        moduleId varchar(255) NOT NULL,
        bundleId int(11) NOT NULL,
        partner varchar(255) NOT NULL,
        changed timestamp DEFAULT CURRENT_TIMESTAMP,
        wp_user_ID int(11) NOT NULL,
        templateId varchar(255) NULL,
        PRIMARY KEY  (ID)
    ) $charset_collate;";

    $sql3_1 = "ALTER TABLE $projects_table DROP COLUMN url;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
    dbDelta($sql2);
    dbDelta($sql3);
    dbDelta($sql3_1);

    $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$protected_shop_settings_table' AND column_name = 'gdprPageId'"  );

    if(empty($row)){
        $wpdb->query("ALTER TABLE $protected_shop_settings_table ADD gdprPageId INT(11) NULL");
    }
}


function protected_shops_admin_page() {
    $page_title = 'ProtectedShops Settings';
    $menu_title = 'ProtectedShops';
    $capability = 'edit_posts';
    $menu_slug = 'protectedshops';
    $function = 'protectedshops_admin_page_display';
    $icon_url = '';
    $position = 24;

    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
}

function protectedshops_admin_page_display()
{
    global $wpdb;
    $selectPagesSql = "SELECT ID, post_title FROM `wp_posts` WHERE post_status = 'publish';";
    $wpPages = $wpdb->get_results($selectPagesSql);

    $protected_shop_settings_table = $wpdb->prefix . 'ps_settings';
    $selectSettings = "SELECT * FROM $protected_shop_settings_table LIMIT 1";
    $currentSettings = $wpdb->get_results($selectSettings);

    $module_page_table = $wpdb->prefix . 'ps_module_page';
    $overtakenPagesSql = "SELECT * FROM $module_page_table";
    $overtakenPages = $wpdb->get_results($overtakenPagesSql);

    if (
        $_POST['partner'] !== NULL
        && $_POST['secret'] !== NULL
        && $_POST['doc_server_url'] !== NULL
        && $_POST['modules'] !== NULL
        && $_POST['partnerId'] !== NULL
    ) {
        $errors = validate_new_settings();
        if (!$currentSettings && $errors === false) {
            $wpdb->insert(
                $protected_shop_settings_table,
                array(
                    'partnerId' => $_POST['partnerId'],
                    'partner' => $_POST['partner'],
                    'partnerSecret' => $_POST['secret'],
                    'url' => $_POST['doc_server_url'],
                    'modules' => $_POST['modules'],
                    'templatePageId' => $_POST['wordpress_page_id_templates'],
                    'gdprPageId' => $_POST['wordpress_page_id_gdpr']

                ),
                array('%s', '%s', '%s', '%s', '%s', '%s')
            );
        } elseif ($errors === false) {
            $wpdb->update(
                $protected_shop_settings_table,
                array(
                    'partnerId' => $_POST['partnerId'],
                    'partner' => $_POST['partner'],
                    'partnerSecret' => $_POST['secret'],
                    'url' => $_POST['doc_server_url'],
                    'modules' => $_POST['modules'],
                    'templatePageId' => $_POST['wordpress_page_id_templates'],
                    'gdprPageId' => $_POST['wordpress_page_id_gdpr']
                ),
                array(
                    'ID' => $currentSettings[0]->ID
                ),
                array('%s', '%s', '%s', '%s', '%s', '%s')
            );
        }
    }

    if ($_POST['wordpress_page_id'] !== NULL && $_POST['moduleId'] !== NULL) {

        $wpdb->query(
            'DELETE FROM ' . $module_page_table . '
               WHERE wp_post_ID = "' . $_POST['wordpress_page_id'] . '"'
        );
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
    $isTemplatesPage = ps_is_templates_page();
    $isGdprPage = ps_is_gdpr_page();
    $wpUser = wp_get_current_user();
    /*$wpNonce is used for the plugin API calls so the user can authenticate*/
    $wpNonce = wp_create_nonce('wp_rest');
    $settings = ps_get_settings();
    $pluginURL = plugin_dir_url(__FILE__);
    $error = false;

    try {
        if (isset($_POST['command']) && 'create_projects_from_templates' == $_POST['command']) {
            if (!is_user_logged_in()) {
                wp_redirect( home_url() . '/login' );
            }
            $bundleId = get_gdpr_bundle_id();
            if (!$bundleId)
            {
                $result = ps_init_gdpr_projects();
                if (!$result['success']) {
                    $error = $result['error'];
                    throw new Exception('Could not create dsgvo projects.');
                }
                $bundleId = get_gdpr_bundle_id();
            }

            $templates = json_decode($docServer->getTemplates($settings[0]->partner,'dsgvo_ps_DE_verarbeitungsverzeichnisanlage'), true);
            $templateIds = $_POST['templateIds'];
            $errors = [];
            foreach ($templateIds as $templateId => $val) {
                $title = '';
                foreach ($templates as $template) {
                    if ($template['id'] == $templateId) {
                        $title = $template['title'];
                    }
                }
                $result = ps_create_project($_POST['moduleId'], $title, $templateId, $bundleId);
                if (!$result['success']) {
                    $errors[] = $result['error'];
                }
            }
            if (!empty($errors)) {
                $error = join('<br />', $errors);
            }

            goto LOAD_GDPR_PAGE;
        }

        if (isset($psPage[0]) && is_page($psPage[0]->post_title) && $psPage) {
            if (!is_user_logged_in()) {
                wp_redirect( home_url() . '/login' );
            } elseif (isset($_POST['moduleId'])) {
                if (array_key_exists('command', $_POST) && 'create_project' == $_POST['command']) {
                    $result = ps_create_project($_POST['moduleId'], $_POST['title']);
                    if (!$result['success']) {
                        $error = $result['error'];
                    }

                    goto LOAD_PAGE;
                }
            } elseif (array_key_exists('tab', $_GET) && 'downloads' == $_GET['tab']) {
                $sqlProject = "SELECT * FROM $projects_table WHERE wp_user_ID=$wpUser->ID AND projectId='" . sanitize_text_field($_GET['project']) . "';";
                $project = $wpdb->get_results($sqlProject);
                $remoteProject = json_decode($docServer->getProject($_GET['partner'], $_GET['project']), 1);

                if (array_key_exists('error_description', $remoteProject)) {
                    $error = $remoteProject['error_description'];
                }

                if ($remoteProject['answersValid'] == 1 && $remoteProject['hasDraftAnswers'] == 0) {
                    $documents = json_decode($docServer->getDocuments($_GET['partner'], $_GET['project']), 1);
                    include($pluginDir . "tabs/document_list.php");
                } else {
                    include($pluginDir . "tabs/no_documents.php");
                }

            } else {
                LOAD_PAGE:
                if (!is_user_logged_in()) {
                    wp_redirect( home_url() . '/login' );
                }
                if (array_key_exists('command', $_GET) && 'delete_project' == $_GET['command']) {
                    $deleteSql = "DELETE FROM $projects_table WHERE wp_user_ID=$wpUser->ID AND projectId='" . sanitize_text_field($_GET['project']) . "';";
                    $wpdb->query($deleteSql);
                }

                $sqlProjects = "SELECT * FROM $projects_table WHERE wp_user_ID=$wpUser->ID AND moduleId='" . $psPage[0]->moduleId . "';";
                $projects = $wpdb->get_results($sqlProjects);
                $shopIds = array();
                foreach ($projects as $project) {
                    $shopIds[] = $project->projectId;
                }

                $remoteProjects = ps_get_remote_projects($settings[0]->partner, $shopIds);

                foreach ($projects as $project) {
                    $project->documents = [];
                    $project->isValid = is_project_valid($remoteProjects, $project->projectId);
                    $project->documents = json_decode($docServer->getDocuments($project->partner, $project->projectId), 1);
                }

                $psTemplatesUrl = plugins_url('integration-package/templates', __FILE__);
                include($pluginDir . "tabs/project_list.php");
            }
        } elseif ($isTemplatesPage) {
            if (!is_user_logged_in()) {
                wp_redirect( home_url() . '/login' );
            } else {
                $templates = json_decode($docServer->getTemplates($settings[0]->partner, 'dsgvo_ps_DE_verarbeitungsverzeichnisanlage'), true);
                $groups = [];
                foreach ($templates as $template) {
                    $groupName = $template['group'];
                    if (!isset($groups[$groupName])) {
                        $groups[$groupName] = array();
                    }
                    $groups[$groupName][] = $template;
                }

                $usedTemplateIds = $wpdb->get_col("SELECT templateId FROM $projects_table WHERE wp_user_ID=$wpUser->ID AND templateId IS NOT NULL");
                include($pluginDir . "tabs/template_list.php");
            }
        } elseif ($isGdprPage) {
            if (!is_user_logged_in()) {
                wp_redirect( home_url() . '/login' );
            } else {
                if (array_key_exists('command', $_GET) && 'delete_project' == $_GET['command']) {
                    $deleteSql = "DELETE FROM $projects_table WHERE wp_user_ID=$wpUser->ID AND projectId='" . sanitize_text_field($_GET['project']) . "';";
                    $wpdb->query($deleteSql);
                }
                
                if (array_key_exists('command', $_POST) && 'create_project' == $_POST['command']) {
                    $result = ps_create_project($_POST['moduleId'], $_POST['title'], null, $_POST['bundleId']);
                    if (!$result['success']) {
                        $error = $result['error'];
                    };
                }

                LOAD_GDPR_PAGE:
                if (!is_user_logged_in()) {
                    wp_redirect( home_url() . '/login' );
                }
                $sqlProjects = "SELECT * FROM $projects_table
                                WHERE wp_user_ID=$wpUser->ID 
                                AND moduleId IN('dsgvo_ps_DE_verarbeitungsverzeichnis', 'dsgvo_ps_DE_verarbeitungsverzeichnisanlage');";

                $projects = $wpdb->get_results($sqlProjects);

                if (empty($projects))
                {
                    $result = ps_init_gdpr_projects();
                    if (!$result['success']) {
                        $error = $result['error'];
                        throw new Exception('Could not create dsgvo projects.');
                    }
                }

                $projects = $wpdb->get_results($sqlProjects);

                $shopIds = array();
                foreach ($projects as $project) {
                    $shopIds[] = $project->projectId;
                }

                $remoteProjects = ps_get_remote_projects($settings[0]->partner, $shopIds);

                foreach ($projects as $project) {
                    $project->documents = [];
                    $project->isValid = is_project_valid($remoteProjects, $project->projectId);
                    $project->documents = json_decode($docServer->getDocuments($project->partner, $project->projectId), 1);
                }

                $psTemplatesUrl = plugins_url('integration-package/templates', __FILE__);
                include($pluginDir . "tabs/gdpr_list.php");
            }
        } else {
            return $text;
        }

    } catch (\Exception $e) {
        $projects = [];
        $psTemplatesUrl = plugins_url('integration-package/templates', __FILE__);
        $error = "Ihre Anfrage kann nicht sofort abgewickelt werden";
        include($pluginDir . "tabs/project_list.php");
        return $text;
    }
}

function add_scripts()
{
    wp_register_script('questionary', plugins_url('integration-package/js/questionary.js', __FILE__ ), array( 'jquery' ));
    wp_register_script('dust_core', plugins_url('integration-package/js/dust-core.js', __FILE__ ), array( ));
    wp_register_script('dust', plugins_url('integration-package/js/dust-full.js', __FILE__ ), array( ));
    wp_register_script('dust-helper', plugins_url('integration-package/js/dust-helpers.js', __FILE__ ), array());

    $psPage = ps_get_page();

    if ((isset($psPage[0]) && is_page($psPage[0]->post_title)) || ps_is_gdpr_page() || ps_is_templates_page()) {
        wp_enqueue_script('questionary');
        wp_enqueue_script('dust_core');
        wp_enqueue_script('dust');
        wp_enqueue_script('dust-helper');
    }

    wp_enqueue_script('jquery');
    wp_register_script('google-jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'));
    wp_register_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css', true);
    wp_register_style('template-style', 'http://www.frontporchdeals.com/wordpress/wp-includes/js/jqueryui/css/ui-lightness/jquery-ui-1.12.1.custom.css', true);
    wp_enqueue_style('jquery-style');
    wp_enqueue_style('jquery-template');
    wp_enqueue_script('google-jquery-ui');
    wp_enqueue_script('jquery-template');
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
