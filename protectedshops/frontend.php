<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>My WordPress Plugin Front-end Page</title>
    <?php
    //call the wp head so  you can get most of your wordpress
    wp_head();
    ?>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/dustjs-linkedin/2.6.1/dust-full.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/dustjs-helpers/1.6.1/dust-helpers.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $(".generate-questionary").click(function () {
                ps.questionary({
                    container: "#main-questionary",
                    templatePath: "<?php echo $psTemplatesUrl; ?>/",
                    buildUrl: "?command=buildQuestionary&partner=" + $(this).data('partner') + "&projectId=" + jQuery(this).data('projectid'),
                    saveUrl: "?command=answerSave&partner=" + $(this).data('partner') + "&projectId=" + jQuery(this).data('projectid'),
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
</head>
<body>
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
            <td><?php echo "vollstanding"; ?></td>
            <td><?php echo date('d.m.Y'); ?></td>
            <td><a class="generate-questionary" data-url="<?php echo $project->url; ?>" data-partner="<?php echo $project->partner; ?>" data-projectId="<?php echo $project->projectId; ?>" href="#">Fragebogen bearbeiten</a></td>
            <td><a href="#">Dukumente herunterladen</a></td>
            <td><a href="#">(löschen)</a></td>
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

<?php
//call the wp foooter
wp_footer();
?>
</body>
</html>