<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
?>

<div class="site-about">
    <p>Drive Files List:</p>
    <table class="table">
        <tr>
            <th>Title</th>
            <th>Thumb link</th>
            <th>Embed link</th>
            <th>Modified date</th>
            <th>Wwners</th>
            <th>Size</th>
        </tr>
        <tbody>
        <?php
        //        var_dump($data,!empty($data));

        if (!empty($data)) {
            foreach ($data as $row) {
//                var_dump($data);

                ?>
                <tr>
                    <td><?= "" . $row['title']; ?></td>
                    <td><?= "" . $row['thumbLink']; ?></td>
                    <td><?= "" . $row['embedLink']; ?></td>
                    <td><?= "" . $row['modifiedDate']; ?></td>
                    <td><?= "" . $row['owners']; ?></td>
                    <td><?= "" . $row['size']; ?></td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
    <form name="next" action="/files/file?next=true" method="GET">
        <input type="submit" name="next" value="next=page" />
    </form>
</div>



