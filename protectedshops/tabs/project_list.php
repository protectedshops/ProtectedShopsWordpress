
<script>
    jQuery(document).ready(function($) {
        $(".generate-questionary").click(function () {
            ps.questionary({
                container: "#main-questionary",
                templatePath: "<?php echo $psTemplatesUrl; ?>/",
                buildUrl: "/wp-json/protectedshops/v1/questionary?_wpnonce=<?php echo $wpNonce; ?>&partner="+$(this).data('partner')+"&project=" + $(this).data('projectid'),
                saveUrl: "/wp-json/protectedshops/v1/questionary/answer?_wpnonce=<?php echo $wpNonce; ?>&partner="+$(this).data('partner')+"&project=" + $(this).data('projectid'),
                beforeReload: function () {
                    $('#loadingIndicator').show();
                    $('#questionary-holder').hide();
                },
                afterReload: function () {
                    $('#loadingIndicator').hide();
                    $('#questionary-holder').show();
                },
                onFinish: function () {
                    $('#documents').show();
                    $('html,body').animate({scrollTop: $('#documents').offset().top}, 'slow');
                }
            });
        });

        $("#show_create_project_table_button").click(function(){
            $("#show_create_project_table_button").hide();
            $("#project_create_form").show();
        });
    });
</script>

<p>
    <button id="show_create_project_table_button" class="button-large">Neues Dokument hinzufügen</button>
    <div id="project_create_form" style="display: none;">
        <form class="form" method="POST">
            <input type="hidden" name="moduleId" value="<?php echo $psPage[0]->moduleId; ?>" />
            <input type="hidden" name="command" value="create_project" />
            <label for="title">Project title</label>
            <input type="text" name="title" id="title">
            <br />
            <br />
            <label for="url">Project URL</label>
            <input type="text" name="url" id="url">
            <br />
            <br />
            <button class="button-large" type="submit">Neues Dokument hinzufügen </button>
        </form>
    </div>
</p>
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
            <td><?php if ($project->isValid) { echo "vollständig"; } else { echo "unvollständig"; }; ?></td>
            <td><?php echo $project->changed; ?></td>
            <td><a class="generate-questionary" data-url="<?php echo $project->url; ?>" data-partner="<?php echo $project->partner; ?>" data-projectId="<?php echo $project->projectId; ?>" href="#">Fragebogen bearbeiten</a></td>
            <td><a href="?tab=downloads&partner=<?php echo $project->partner; ?>&project=<?php echo $project->projectId; ?>">Dukumente herunterladen</a></td>
            <td><a href="?command=delete_project&project=<?php echo $project->projectId; ?>">(löschen)</a></td>
        </tr>
    <?php }?>
    </table>
</p>

<section id="questionary">

    <div id="questionary-holder" style="border: 2px solid #adb6bd;">
        <div id="main-questionary"></div>
    </div>
    <div style="display: none;" id="loadingIndicator">
        <img src="<?php echo $pluginURL . "assets/loader.gif"; ?>" />
    </div>
</section>
