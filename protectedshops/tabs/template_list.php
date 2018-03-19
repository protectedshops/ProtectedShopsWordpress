<?php if ($error) {
    include 'error.php';
} ?>
<h1>Verzeichnis von Verarbeitungstätigkeiten Anlage</h1>
<p>
    <form method="POST">
        <input type="hidden" name="command" value="create_projects_from_templates"/>
        <input type="hidden" name="moduleId" value="dsgvo_ps_DE_verarbeitungsverzeichnisanlage"/>

        <div style="display: none">
            <?php foreach ($groups as $name => $templates) { ?>
                <?php foreach ($templates as $template) { ?>
                    <input type="checkbox" class="target"
                           name="templateIds[<?php echo $template['id']; ?>]"
                        <? if (in_array($template['id'], $usedTemplateIds)) echo "checked='checked' disabled"; ?>
                    />
                <?php } ?>
            <?php } ?>
        </div>

        <?php foreach ($groups as $name => $templates) { ?>
            <div class="table-1 dialog" data-name="<?php echo $name; ?>" title="<?php echo $name; ?>">
                <table width="300px">
                    <?php foreach ($templates as $template) { ?>
                        <tr>
                            <td><?php echo $template['title']; ?></td>
                            <td><input type="checkbox" class="source"
                                       name="templateIds[<?php echo $template['id']; ?>]"
                                    <? if (in_array($template['id'], $usedTemplateIds)) echo "checked='checked' disabled"; ?>
                                />
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <button class="fusion-button button-large open-dialog"
                    data-name="<?php echo $name; ?>"><?php echo $name; ?></button>
        <?php } ?>
        <p style="margin-top: 20px;">
            <button class="fusion-button button-large" type="submit">Speichern</button>
        </p>
    </form>
</p>

<script>
    var dialogs = [];
    (function ($) {
        $('.dialog').each(function () {
            var name = $(this).data('name');
            dialogs[name] = $(this).dialog({
                autoOpen: false,
                modal: true,
                width: 350
            })
        })

        $('.open-dialog').click(function (e) {
            e.preventDefault();
            var name = $(this).data('name');
            if (typeof dialogs[name] != 'undefined') {
                dialogs[name].dialog('open');
            }
        })

        $('.source').change(function (e) {
            var name = $(this).attr('name');
            var val = $(this).attr('checked');
            var target = $('.target[name="' + name + '"]');
            target.attr('checked', val)
        })
    })(jQuery);
</script>