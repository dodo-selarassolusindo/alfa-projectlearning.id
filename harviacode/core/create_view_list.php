<?php

$string = "
<div class=\"col-12\">
    <div class=\"card\">
        <div class=\"card-body\">
            <div class=\"row\" style=\"margin-bottom: 10px\">
                <div class=\"col-md-4\">
                    <?php echo anchor(site_url('".$c_url."/create'),'Create', 'class=\"btn btn-primary\"'); ?>
                </div>
                <div class=\"col-md-4 text-center\">
                    <div style=\"margin-top: 8px\" id=\"message\">
                        <?php echo \$this->session->userdata('message') <> '' ? \$this->session->userdata('message') : ''; ?>
                    </div>
                </div>
                <div class=\"col-md-1 text-right\">
                </div>
                <div class=\"col-md-3 text-right\">
                    <form action=\"<?php echo site_url('$c_url/index'); ?>\" class=\"form-inline\" method=\"get\">
                        <div class=\"input-group\">
                            <input type=\"text\" class=\"form-control\" name=\"q\" value=\"<?php echo \$q; ?>\">
                            <span class=\"input-group-btn\">
                                <?php
                                    if (\$q <> '')
                                    {
                                        ?>
                                        <a href=\"<?php echo site_url('$c_url'); ?>\" class=\"btn btn-default\">Reset</a>
                                        <?php
                                    }
                                ?>
                              <button class=\"btn btn-primary\" type=\"submit\">Search</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <table class=\"table table-bordered\" style=\"margin-bottom: 10px\">
                <tr>
                    <th>No</th>";
                    foreach ($non_pk as $row) {
                        $string .= "
                    <th>" . label($row['column_name']) . "</th>";
                    }
                    $string .= "
                    <th>Action</th>
                </tr>";
                $string .= "
                <?php foreach ($" . $c_url . "_data as \$$c_url) { ?>
                <tr>";
                $string .= "
                    <td width=\"80px\"><?php echo ++\$start ?></td>";
                foreach ($non_pk as $row) {
                    $string .= "
                    <td><?php echo $" . $c_url ."->". $row['column_name'] . " ?></td>";
                }
                $string .= "
                    <td style=\"text-align:center\" width=\"200px\">
                    <?php
                        echo anchor(site_url('".$c_url."/read/'.$".$c_url."->".$pk."),'Read');
                        echo ' | ';
                        echo anchor(site_url('".$c_url."/update/'.$".$c_url."->".$pk."),'Update');
                        echo ' | ';
                        echo anchor(site_url('".$c_url."/delete/'.$".$c_url."->".$pk."),'Delete','onclick=\"javasciprt: return confirm(\\'Are You Sure ?\\')\"');
                    ?>
                    </td>";
                $string .=  "
                </tr>
                <?php } ?>
            </table>
            <div class=\"row\">
                <div class=\"col-md-6\">
                    <a href=\"#\" class=\"btn btn-primary\">Total Record : <?php echo \$total_rows ?></a>";
                    if ($export_excel == '1') {
                        $string .= "
                    <?php echo anchor(site_url('".$c_url."/excel'), 'Excel', 'class=\"btn btn-primary\"'); ?>";
                    }
                    if ($export_word == '1') {
                        $string .= "
                    <?php echo anchor(site_url('".$c_url."/word'), 'Word', 'class=\"btn btn-primary\"'); ?>";
                    }
                    if ($export_pdf == '1') {
                        $string .= "
                    <?php echo anchor(site_url('".$c_url."/pdf'), 'PDF', 'class=\"btn btn-primary\"'); ?>";
                    }
                    $string .= "
                </div>
                <div class=\"col-md-6 text-right\">
                    <?php echo \$pagination ?>
                </div>
            </div>
        </div>
    </div>
</div>
";


$hasil_view_list = createFile($string, $target."views/" . $c_url . "/" . $v_list_file);

?>
