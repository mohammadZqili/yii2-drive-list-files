<?php

/** @var yii\web\View $this */

use yii\helpers\Html;


?>

<div class="site-about">
    <p>This is the About page. You may modify the following file to customize its content:</p>
    <table class="table">
        <tr>
            <th>title</th>
            <th>thumb link</th>
            <th>embed link</th>
            <th>modified date</th>
            <th>owners</th>
            <th>size</th>
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
        <input type="submit" name="next" />
    </form>
</div>



