<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>My WordPress Plugin Front-end Page</title>
    <?php
    //call the wp head so  you can get most of your wordpress
    wp_head();
    ?>
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
            <th>projectId</th>
            <th>title</th>
            <th>url</th>
            <th>module</th>
        </tr>
    <?php foreach ($projects as $project) {?>
        <tr>
            <td><?php echo $project->projectId; ?></td>
            <td><?php echo $project->title; ?></td>
            <td><?php echo $project->url; ?></td>
            <td><?php echo $project->moduleId; ?></td>
        </tr>
    <?php }?>
    </table>
</div>
<?php
//call the wp foooter
wp_footer();
?>
</body>
</html>