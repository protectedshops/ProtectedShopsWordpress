<h2>ProtectedShops Settings</h2>

<form method="POST">
    <label for="partner">Partner</label>
    <input type="text" name="partner" id="partner" value="<?php if ($currentSettings[0]->partner) {echo $currentSettings[0]->partner; }?>">
    <br />
    <label for="partnerId">Your client id</label>
    <input type="text" name="partnerId" id="partnerId" value="<?php if ($currentSettings[0]->partnerId) {echo $currentSettings[0]->partnerId; }?>">
    <br />
    <label for="secret">Client Secret</label>
    <input type="text" name="secret" id="secret" value="<?php if ($currentSettings[0]->partnerSecret) {echo $currentSettings[0]->partnerSecret; }?>">
    <br />
    <label for="doc_server_url">Document Server URL</label>
    <input type="text" name="doc_server_url" id="doc_server_url" value="<?php if ($currentSettings[0]->url) {echo $currentSettings[0]->url; }?>">
    <br />
    <label for="modules">Available partner modules: (module1,module2,module3)</label>
    <input type="text" name="modules" id="modules" value="<?php if ($currentSettings[0]->modules) {echo $currentSettings[0]->modules; }?>">
    <br />

    <input type="submit" value="Save" class="button button-primary button-large">
</form>

<?php if ($currentSettings[0]->modules) { /*Starting if*/?>

<h2>ProtectedShops Settings</h2>

<form method="POST">
    <label for="wordpress_page_id">Choose Module</label>
    <select name="moduleId" id="moduleId">
        <?php foreach (explode(",", $currentSettings[0]->modules) as $module) {?>
            <option value="<?php echo "$module"; ?>"><?php echo "$module"; ?></option>
        <?php } ?>
    </select>
    <br />
    <label for="wordpress_page_id">Choose page for protectedshops</label>
    <select name="wordpress_page_id" id="wordpress_page_id">
        <?php foreach ($wpPages as $page) {?>
            <option  value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
        <?php } ?>
    </select>
    <br />
    <input type="submit" value="Save" class="button button-primary button-large">
</form>
<?php } /*Closing if*/?>