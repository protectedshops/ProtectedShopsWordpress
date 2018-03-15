<?php if ($error) {
    include 'error.php';
} ?>
<h1>Verzeichnis von Verarbeitungstätigkeiten Anlage</h1>
<p>
<form method="POST">
    <input type="hidden" name="command" value="create_projects_from_templates" />
    <input type="hidden" name="moduleId" value="dsgvo_ps_DE_verarbeitungsverzeichnisanlage" />
    <div class="table-1">
        <table width="300px">
            <?php foreach ($templates as $template) { ?>
                <tr>
                    <td><?php echo $template['title']; ?></td>
                    <td><input type="checkbox"
                               name="templateIds[<?php echo $template['id']; ?>]"
                            <? if (in_array($template['id'], $usedTemplateIds)) echo "checked='checked' disabled"; ?>
                        />
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <button class="fusion-button button-large" type="submit">Speichern</button>
</form>
</p>

