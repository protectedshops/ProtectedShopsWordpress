
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
    });
</script>

<p>Project Name: <?php echo $psPage[0]->moduleId; ?></p>
<form method="POST">
    <input type="hidden" name="moduleId" value="<?php echo $psPage[0]->moduleId; ?>" />
    <input type="hidden" name="command" value="create_project" />
    <label for="title">Project title</label>
    <input type="text" name="title" id="title">
    <br />
    <label for="url">Project URL</label>
    <input type="text" name="url" id="url">
    <br />

    <input type="submit" value="Create New Project" />
</form>

<div>
    <table>
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
</div>

<section id="questionary">

    <div id="questionary-holder">
        <div id="main-questionary"></div>
        <div id="loadingIndicator">Loading...</div>
    </div>
</section>
