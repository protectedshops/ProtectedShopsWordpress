<style>
    .document-title-tr h4 {
        font-size: 16px;
    }
</style>
<script>
    jQuery(document).ready(function($) {
        $(".generate-questionary").click(function () {
            var projectId = $(this).data('projectid');
            ps.questionary({
                container: "#main-questionary",
                templatePath: "<?php echo $psTemplatesUrl; ?>/",
                buildUrl: "/wp-json/protectedshops/v1/questionary?_wpnonce=<?php echo $wpNonce; ?>&partner="+$(this).data('partner')+"&project=" + $(this).data('projectid'),
                saveUrl: "/wp-json/protectedshops/v1/questionary/answer?_wpnonce=<?php echo $wpNonce; ?>&partner="+$(this).data('partner')+"&project=" + $(this).data('projectid'),
                beforeReload: function () {
                    $('#loadingIndicator').show();
                },
                afterReload: function () {
                    $('#loadingIndicator').hide();
                    $('#questionary-holder').show();
                },
                onFinish: function () {
                    var projectDocumentsRow = $('.documents-' + projectId);
                    projectDocumentsRow.show();
                    $('html,body').animate({scrollTop: projectDocumentsRow.offset().top - 100}, 'slow');
                    $('#link-' + projectId).removeClass('unfinished');
                    $('#td-' + projectId).html('vollständig');
                    $('#questionary-holder').hide();
                }
            });
        });

        $("#show_create_project_table_button").click(function(){
            $("#show_create_project_table_button").hide();
            $("#project_create_form").show();
        });

        $('.project-documents').click(function(){
            if (!$(this).hasClass('unfinished')) {
                var id = $(this).data('id');
                $('.' + id).toggle();
            }
        });
    });
</script>

<p>
<div class="form-submit">
    <button id="show_create_project_table_button" class="fusion-button button-large">Neues Dokument hinzufügen</button>
    <div id="project_create_form" style="display: none;">
        <form class="form" method="POST">
            <button class="button-large fusion-button" type="submit">Neues Dokument hinzufügen </button>
            <br />
            <br />
            <input type="hidden" name="moduleId" value="dsgvo_ps_DE_verarbeitungsverzeichnisanlage" />
            <input type="hidden" name="bundleId" value="<?php echo $projects[0]->bundleId; ?>" />
            <input type="hidden" name="command" value="create_project" />
            <label for="title">Bitte geben Sie einen Namen für das Projekt an und bestätigen Sie mit der Eingabetaste</label>
            <input type="text" name="title" id="title">
        </form>
    </div>
</div>
</p>
<?php if ($error) {include 'error.php'; } ?>
<p>
<table class="table">
    <tr>
        <th>Name des Projekts</th>
        <th>Status</th>
        <th>letzte Änderung</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach ($projects as $project) {?>
        <tr>
            <td><?php echo $project->title; ?></td>
            <td id="td-<?php echo $project->projectId; ?>"><?php if ($project->isValid) { echo "vollständig"; } else { echo "unvollständig"; }; ?></td>
            <td><?php echo $project->changed; ?></td>
            <td><a class="generate-questionary" data-url="<?php echo $project->url; ?>" data-partner="<?php echo $project->partner; ?>" data-projectId="<?php echo $project->projectId; ?>" href="#">Fragebogen bearbeiten</a></td>
            <td><a id="link-<?php echo $project->projectId; ?>" class="project-documents <?php if (!$project->isValid) { echo "unfinished"; } ?>" data-id="documents-<?php echo $project->projectId; ?>" href="javascript:void(0)">Dokumente herunterladen</a></td>
            <td><a href="?command=delete_project&project=<?php echo $project->projectId; ?>">(löschen)</a></td>
        </tr>
        <?php foreach ($project->documents['content']['documents'] as $document) { ?>
            <tr style="display: none;" class="documents-<?php echo $project->projectId; ?>" class="project-documents">
                <td class="document-title-tr" style="white-space: nowrap;"><h4><b><?php echo $document['name']; ?></b></h4></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="white-space: nowrap;">
                    <a href="<?php echo site_url() . "/wp-json/protectedshops/v1/questionary/download"; ?>?_wpnonce=<?php echo $wpNonce; ?>&partner=<?php echo $project->partner; ?>&project=<?php echo $project->projectId; ?>&docType=<?php echo $document['type']; ?>&formatType=docx" target="_blank"><img width="36" src="<?php echo $pluginURL . "assets/icons/icon-docx.png"; ?>"></a>
                    <a href="<?php echo site_url() . "/wp-json/protectedshops/v1/questionary/download"; ?>?_wpnonce=<?php echo $wpNonce; ?>&partner=<?php echo $project->partner; ?>&project=<?php echo $project->projectId; ?>&docType=<?php echo $document['type']; ?>&formatType=pdf" target="_blank"><img width="36" src="<?php echo $pluginURL . "assets/icons/icon-pdf.png"; ?>"></a>
                    <a href="<?php echo site_url() . "/wp-json/protectedshops/v1/questionary/download"; ?>?_wpnonce=<?php echo $wpNonce; ?>&partner=<?php echo $project->partner; ?>&project=<?php echo $project->projectId; ?>&docType=<?php echo $document['type']; ?>&formatType=text" target="_blank"><img width="36" src="<?php echo $pluginURL . "assets/icons/icon-txt.png"; ?>"></a>
                </td>
                <td></td>
            </tr>
        <?php }?>

    <?php }?>
</table>
</p>

<section id="questionary">
    <div id="questionary-holder" style="display: none; padding: 5px; border: 2px solid #adb6bd;">
        <div id="main-questionary"></div>
    </div>
    <div id="loadingIndicator" style="display: none; position:fixed; padding:0; margin:0; top:0; left:0; width: 100%; height: 100%; background:rgba(255,255,255,0.5);">
        <div style="width: 150px; position:fixed; top:50%; left:40%; z-index: 10000;">
            <img src="<?php echo $pluginURL . "assets/loader.gif"; ?>" />
        </div>
    </div>
</section>

