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

    $post_table = $wpdb->prefix . 'posts';
    $module_page_table = $wpdb->prefix . 'ps_module_page';
    $selectPagesSql = "SELECT $post_table.post_title, $module_page_table.moduleId
                       FROM $post_table
                       JOIN $module_page_table ON $post_table.ID = $module_page_table.wp_post_ID
                       WHERE $post_table.post_title = '$pageName';";

    return $wpdb->get_results($selectPagesSql);
}

function ps_is_templates_page()
{
    $settings = ps_get_settings();

    return (isset($settings[0]) && $settings[0]->templatePageId == get_the_ID());
}

function ps_is_gdpr_page()
{
    $settings = ps_get_settings();

    return (isset($settings[0]) && $settings[0]->gdprPageId == get_the_ID());
}

function ps_is_project_access_allowed($projectId, $userId)
{
    global $wpdb;
    $projects_table = $wpdb->prefix . 'ps_project';

    $checkQuery = "SELECT count(*) AS logged FROM $projects_table WHERE projectId='$projectId' AND wp_user_ID=$userId";
    $result = $wpdb->get_results($checkQuery);

    return $result[0]->logged == '1' ? true : false;
}

function ps_get_remote_projects($partner, $shopIds = array())
{
    $docServer = ps_document_server();
    $projects = json_decode($docServer->getProjects($partner, $shopIds), 1);

    return $projects;
}

function is_project_valid($remoteProjects, $projectId)
{
    foreach ($remoteProjects as $project) {
        if ($project['shopId'] == $projectId) {
            return $project['hasDraftAnswers'] == 0 && $project['answersValid'] == 1 ? true : false;
        }
    }

    return false;
}

function ps_project_change($partner, $project)
{
    global $wpdb;

    $projects_table = $wpdb->prefix . 'ps_project';
    $updateSql = "UPDATE $projects_table SET changed=NOW() WHERE partner='" . sanitize_text_field($partner) . "' AND projectId='" . sanitize_text_field($project) . "';";

    $wpdb->query($updateSql);
}

function get_gdpr_bundle_id()
{
    global $wpdb;
    $wpUser = wp_get_current_user();
    $projects_table = $wpdb->prefix . 'ps_project';

    $sql = "SELECT bundleId FROM $projects_table WHERE wp_user_ID = $wpUser->ID AND moduleId = 'dsgvo_ps_DE_verarbeitungsverzeichnis'";

    $result = $wpdb->get_results($sql);

    if (empty($result))
    {
        return null;
    }

    return $result[0]->bundleId;
}

function ps_create_project($moduleId, $title, $templateId = null, $bundleId = null)
{
    global $wpdb;
    $wpUser = wp_get_current_user();
    $projects_table = $wpdb->prefix . 'ps_project';
    $success = false;
    $error = '';

    $docServer = ps_document_server();
    $newProject = $docServer->createProject($moduleId, $title, $templateId, $bundleId);
    if (array_key_exists('shopId', $newProject)) {
        $wpdb->insert(
            $projects_table,
            array(
                'projectId' => $newProject['shopId'],
                'title' => $newProject['title'],
                'moduleId' => $newProject['module'],
                'bundleId' => $newProject['bundleId'],
                'partner' => $newProject['partnerId'],
                'wp_user_ID' => $wpUser->ID,
                'templateId' => $templateId
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        $success = true;
    } elseif (array_key_exists('error_description', $newProject)) {
        $error = $newProject['error_description'];
    }

    return array(
        'success' => $success,
        'error' => $error
    );
}

function ps_init_gdpr_projects()
{
    global $wpdb;
    $wpUser = wp_get_current_user();
    $projects_table = $wpdb->prefix . 'ps_project';
    $success = false;
    $error = '';

    $docServer = ps_document_server();
    $vvhProject = $docServer->createProject('dsgvo_ps_DE_verarbeitungsverzeichnis', 'Verzeichnis von Verarbeitungstätigkeiten Hauptblatt');
    if (array_key_exists('shopId', $vvhProject)) {
        $wpdb->insert(
            $projects_table,
            array(
                'projectId' => $vvhProject['shopId'],
                'title' => $vvhProject['title'],
                'moduleId' => $vvhProject['module'],
                'bundleId' => $vvhProject['bundleId'],
                'partner' => $vvhProject['partnerId'],
                'wp_user_ID' => $wpUser->ID,
                'templateId' => null
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        $success = true;
    } elseif (array_key_exists('error_description', $vvhProject)) {
        $error = $vvhProject['error_description'];

        return array(
            'success' => $success,
            'error' => $error
        );
    }

    $vvaProject = $docServer->createProject(
        'dsgvo_ps_DE_verarbeitungsverzeichnisanlage',
        'Verzeichnis von Verarbeitungstätigkeiten Anlage',
        null,
        $vvhProject['bundleId']
        );
    if (array_key_exists('shopId', $vvaProject)) {
        $wpdb->insert(
            $projects_table,
            array(
                'projectId' => $vvaProject['shopId'],
                'title' => $vvaProject['title'],
                'moduleId' => $vvaProject['module'],
                'bundleId' => $vvhProject['bundleId'],
                'partner' => $vvaProject['partnerId'],
                'wp_user_ID' => $wpUser->ID,
                'templateId' => null
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        $success = true;
    } elseif (array_key_exists('error_description', $vvaProject)) {
        $error = $vvaProject['error_description'];
    }

    return array(
        'success' => $success,
        'error' => $error
    );
}

/**
 * @return array|bool
 */
function validate_new_settings()
{
    require_once 'document_server.php';
    $errors = [];

    if (!DocumentServer::test($_POST['doc_server_url'], $_POST['partnerId'], $_POST['secret'])) {
        $errors[] = "Ungültige API-URL, Partner-ID, Partner-Name oder Client-Geheimnis.";
    }

    return empty($errors) ? false : $errors;
}