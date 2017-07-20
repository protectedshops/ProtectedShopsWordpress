<div>
    <table class="table">
        <tr>
            <th>Name des Dokument</th>
            <th></th>
        </tr>
        <?php foreach ($documents['content']['documents'] as $document) { ?>
            <tr>
                <td><?php echo $document['name']; ?></td>
                <td>
                    <a href="<?php echo site_url() . "/wp-json/protectedshops/v1/questionary/download"; ?>?_wpnonce=<?php echo $wpNonce; ?>&partner=<?php echo $project[0]->partner; ?>&project=<?php echo $project[0]->projectId; ?>&docType=<?php echo $document['type']; ?>&formatType=docx" target="_blank"><img src="<?php echo $pluginURL . "assets/icons/icon-docx.png"; ?>"></a>
                    <a href="<?php echo site_url() . "/wp-json/protectedshops/v1/questionary/download"; ?>?_wpnonce=<?php echo $wpNonce; ?>&partner=<?php echo $project[0]->partner; ?>&project=<?php echo $project[0]->projectId; ?>&docType=<?php echo $document['type']; ?>&formatType=pdf" target="_blank"><img src="<?php echo $pluginURL . "assets/icons/icon-pdf.png"; ?>"></a>
                    <a href="<?php echo site_url() . "/wp-json/protectedshops/v1/questionary/download"; ?>?_wpnonce=<?php echo $wpNonce; ?>&partner=<?php echo $project[0]->partner; ?>&project=<?php echo $project[0]->projectId; ?>&docType=<?php echo $document['type']; ?>&formatType=text" target="_blank"><img src="<?php echo $pluginURL . "assets/icons/icon-txt.png"; ?>"></a>
                </td>
            </tr>
        <?php }?>
    </table>
</div>