<h1>ProtectedShops Settings</h1>

<form method="POST">
    <label for="partner">Partner</label>
    <input type="text" name="partner" id="partner" value="<?php if ($partner) {echo $partner; }?>">
    <br />
    <label for="secret">Client Secret</label>
    <input type="text" name="secret" id="secret" value="<?php if ($secret) {echo $secret; }?>">
    <br />
    <label for="doc_server_url">Document Server URL</label>
    <input type="text" name="doc_server_url" id="doc_server_url" value="<?php if ($doc_server_url) {echo $doc_server_url; }?>">
    <br />
    <input type="submit" value="Save" class="button button-primary button-large">
</form>