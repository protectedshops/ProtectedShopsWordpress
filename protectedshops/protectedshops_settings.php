<h2>Einstellungen</h2>

<form method="POST">
    <label for="partner">Partner</label>
    <input type="text" name="partner" id="partner" required="required" value="<?php if ($currentSettings[0]->partner) {echo $currentSettings[0]->partner; }?>">
    <br />
    <label for="partnerId">Ihre Client-ID</label>
    <input type="text" name="partnerId" id="partnerId" required="required" value="<?php if ($currentSettings[0]->partnerId) {echo $currentSettings[0]->partnerId; }?>">
    <br />
    <label for="secret">Kundengeheimnis</label>
    <input type="text" name="secret" id="secret" required="required" value="<?php if ($currentSettings[0]->partnerSecret) {echo $currentSettings[0]->partnerSecret; }?>">
    <br />
    <label for="doc_server_url">API-URL</label>
    <input type="text" name="doc_server_url" id="doc_server_url" required="required" value="<?php if ($currentSettings[0]->url) {echo $currentSettings[0]->url; }?>">
    <br />
    <label for="modules">Verfügbare Partnermodule: (Modul1, Modul2, Modul3)</label>
    <input type="text" name="modules" id="modules" required="required" value="<?php if ($currentSettings[0]->modules) {echo $currentSettings[0]->modules; }?>">
    <br />

    <input type="submit" value="Save" class="button button-primary button-large">
</form>

<?php if ($errors) {?>
    <ul style="color: red; list-style: hiragana; font-weight: bold;">
        <?php foreach ($errors as $error) {?>
            <li><?php echo $error; ?></li>
        <?php }?>
    </ul>
<?php } ?>

<?php if ($currentSettings[0]->modules) { /*Starting if*/?>

<h2>WordPress-Seite dem Modul zuordnen</h2>

<form method="POST">
    <label for="wordpress_page_id">Wählen Sie Modul</label>
    <select name="moduleId" id="moduleId" required="required">
        <?php foreach (explode(",", $currentSettings[0]->modules) as $module) {?>
            <option value="<?php echo "$module"; ?>"><?php echo "$module"; ?></option>
        <?php } ?>
    </select>
    <br />
    <label for="wordpress_page_id">Wählen Sie die Seite für das ausgewählte Modul aus</label>
    <select name="wordpress_page_id" id="wordpress_page_id" required="required">
        <?php foreach ($wpPages as $page) {?>
            <option  value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
        <?php } ?>
    </select>
    <br />
    <input type="submit" value="Save" class="button button-primary button-large">
</form>
<?php } /*Closing if*/?>

<h3>Zugeordnete WordPress-Seiten zu den ProtectedShops Modulen</h3>
<ul>
<?php foreach($overtakenPages as $page) { ?>
    <li><?php foreach ($wpPages as $wpPage) { if ($wpPage->ID == $page->wp_post_ID) { echo $wpPage->post_title; }} ?> ---> <?php echo $page->moduleId; ?></li>
<?php } ?>
</ul>