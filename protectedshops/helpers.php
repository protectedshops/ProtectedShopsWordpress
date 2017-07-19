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

function ps_is_project_access_allowed($projectId, $userId)
{
    global $wpdb;
    $projects_table = $wpdb->prefix . 'ps_project';

    $checkQuery = "SELECT count(*) AS logged FROM $projects_table WHERE projectId='$projectId' AND wp_user_ID=$userId";
    $result = $wpdb->get_results($checkQuery);

    return $result[0]->logged == '1' ? true : false;
}

function ps_get_remote_projects($partner)
{
    $docServer = ps_document_server();
    $projects = json_decode($docServer->getProjects($partner), 1);

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