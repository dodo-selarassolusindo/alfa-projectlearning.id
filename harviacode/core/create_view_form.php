<?php

$string = "
<div class=\"col-12\">
    <form action=\"<?php echo \$action; ?>\" method=\"post\" class=\"card\">
        <div class=\"card-header\">
            <h3 class=\"card-title\"><?= \$button ?></h3>
        </div>
        <div class=\"card-body col-6\">";
        foreach ($non_pk as $row) {
            if ($row["data_type"] == 'text') {
                $string .= "
            <div class=\"mb-3 row\">
                <label class=\"col-3 col-form-label required\">".label($row["column_name"])." </label>
                <div class=\"col\">
                    <textarea class=\"form-control\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"><?= $".$row["column_name"]." ?></textarea>
                    <small class=\"form-hint\"><?= form_error('".$row["column_name"]."') ?></small>
                </div>
            </div>";
            } else {
                $string .= "
            <div class=\"mb-3 row\">
                <label class=\"col-3 col-form-label required\">".label($row["column_name"])." </label>
                <div class=\"col\">
                    <input type=\"text\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?= $".$row["column_name"]." ?>\" />
                    <small class=\"form-hint\"><?= form_error('".$row["column_name"]."') ?></small>
                </div>
            </div>";
            }
        }
        $string .= "
            <input type=\"hidden\" name=\"".$pk."\" value=\"<?= $".$pk." ?>\" />
        </div>
        <div class=\"card-footer text-start\">
            <button type=\"submit\" class=\"btn btn-primary\"><?= \$button ?></button>
            <a href=\"<?= site_url('".$c_url."') ?>\" class=\"btn btn-secondary\">Cancel</a>
        </div>
    </form>
</div>";


$hasil_view_form = createFile($string, $target."views/" . $c_url . "/" . $v_form_file);

?>
