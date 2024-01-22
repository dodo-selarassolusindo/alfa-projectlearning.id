<?php

namespace PHPMaker2024\demo2024;

// Table
$trademarks = Container("trademarks");
$trademarks->TableClass = "table table-bordered table-hover table-sm ew-table ew-master-table";
?>
<?php if ($trademarks->Visible) { ?>
<div id="t_trademarks" class="card ew-grid ew-list-form ew-master-div <?= $trademarks->TableContainerClass ?>">
<table id="tbl_trademarksmaster" class="<?= $trademarks->TableClass ?>">
    <thead>
        <tr class="ew-table-header">
<?php if ($trademarks->ID->Visible) { // ID ?>
            <th class="<?= $trademarks->ID->headerCellClass() ?>"><?= $trademarks->ID->caption() ?></th>
<?php } ?>
<?php if ($trademarks->Trademark->Visible) { // Trademark ?>
            <th class="<?= $trademarks->Trademark->headerCellClass() ?>"><?= $trademarks->Trademark->caption() ?></th>
<?php } ?>
        </tr>
    </thead>
    <tbody>
        <tr>
<?php if ($trademarks->ID->Visible) { // ID ?>
            <td<?= $trademarks->ID->cellAttributes() ?>>
<span id="el_trademarks_ID">
<span<?= $trademarks->ID->viewAttributes() ?>>
<?= $trademarks->ID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trademarks->Trademark->Visible) { // Trademark ?>
            <td<?= $trademarks->Trademark->cellAttributes() ?>>
<span id="el_trademarks_Trademark">
<span<?= $trademarks->Trademark->viewAttributes() ?>>
<?= $trademarks->Trademark->getViewValue() ?></span>
</span>
</td>
<?php } ?>
        </tr>
    </tbody>
</table>
</div>
<?php } ?>
