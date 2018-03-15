<?php if ($error) {
    include 'error.php';
} ?>
<h1>Verzeichnis von Verarbeitungstätigkeiten Anlage</h1>
<p>
<form method="POST">
    <div class="table-1">
        <table width="300px">
            <?php foreach ($templates as $template) { ?>
                <tr>
                    <td><?php echo $template['title']; ?></td>
                    <td><input type="checkbox" name="templates[<?php echo $template['id']; ?>]"/></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <button class="fusion-button button-large" type="submit">Speichern</button>
</form>
</p>

