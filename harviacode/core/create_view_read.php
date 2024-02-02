<?php

$string = "
<div class=\"col-12\">
    <div class=\"card\">
        <div class=\"card-header\">
            <h3 class=\"card-title\"><?= \$judul_form ?></h3>
        </div>
        <div class=\"card-body\">
            <table class=\"table\">";
                foreach ($non_pk as $row) {
                    $string .= "
                <tr><td>".label($row["column_name"])."</td><td><?php echo $".$row["column_name"]."; ?></td></tr>";
                }
                $string .= "
            </table>
        </div>
        <div class=\"card-footer text-start\">
            <a href=\"<?= site_url('".$c_url."') ?>\" class=\"btn btn-secondary\">Back</a>
        </div>
    </div>
</div>
";



$hasil_view_read = createFile($string, $target."views/" . $c_url . "/" . $v_read_file);

?>
