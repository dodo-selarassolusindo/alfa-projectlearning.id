<?php

namespace PHPMaker2024\prj_alfa;

// Page object
$ProductsByCategorySummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Products_By_Category: currentTable } });
var currentPageID = ew.PAGE_ID = "summary";
var currentForm;
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<a id="top"></a>
<!-- Content Container -->
<div id="ew-report" class="ew-report container-fluid">
<div class="btn-toolbar ew-toolbar">
<?php
if (!$Page->DrillDownInPanel) {
    $Page->ExportOptions->render("body");
    $Page->SearchOptions->render("body");
    $Page->FilterOptions->render("body");
}
?>
</div>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->ShowReport) { ?>
<!-- Summary report (begin) -->
<?php if (!$Page->isExport("pdf")) { ?>
<main class="report-summary<?= ($Page->TotalGroups == 0) ? " ew-no-record" : "" ?>">
<?php } ?>
<?php
while ($Page->GroupCount <= count($Page->GroupRecords) && $Page->GroupCount <= $Page->DisplayGroups) {
?>
<?php
    // Show header
    if ($Page->ShowHeader) {
?>
<?php if ($Page->GroupCount > 1) { ?>
</tbody>
</table>
<?php if (!$Page->isExport("pdf")) { ?>
</div>
<!-- /.ew-grid-middle-panel -->
<!-- Report grid (end) -->
<?php } ?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0) && $Page->Pager->Visible) { ?>
<!-- Bottom pager -->
<div class="card-footer ew-grid-lower-panel">
<?= $Page->Pager->render() ?>
</div>
<?php } ?>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
</div>
<!-- /.ew-grid -->
<?php } ?>
<?= $Page->PageBreakHtml ?>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
<div class="<?= $Page->ReportContainerClass ?>">
<?php } ?>
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0) && $Page->Pager->Visible) { ?>
<!-- Top pager -->
<div class="card-header ew-grid-upper-panel">
<?= $Page->Pager->render() ?>
</div>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
<!-- Report grid (begin) -->
<div id="gmp_Products_By_Category" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->CategoryName->Visible) { ?>
    <?php if ($Page->CategoryName->ShowGroupHeaderAsRow) { ?>
    <th data-name="CategoryName"<?= $Page->CategoryName->cellAttributes("ew-rpt-grp-caret") ?>><?= $Page->CategoryName->groupToggleIcon() ?></th>
    <?php } else { ?>
    <th data-name="CategoryName" class="<?= $Page->CategoryName->headerCellClass() ?>"><div class="Products_By_Category_CategoryName"><?= $Page->renderFieldHeader($Page->CategoryName) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
    <th data-name="ProductName" class="<?= $Page->ProductName->headerCellClass() ?>"><div class="Products_By_Category_ProductName"><?= $Page->renderFieldHeader($Page->ProductName) ?></div></th>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
    <th data-name="UnitsInStock" class="<?= $Page->UnitsInStock->headerCellClass() ?>"><div class="Products_By_Category_UnitsInStock"><?= $Page->renderFieldHeader($Page->UnitsInStock) ?></div></th>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { ?>
    <th data-name="Discontinued" class="<?= $Page->Discontinued->headerCellClass() ?>"><div class="Products_By_Category_Discontinued"><?= $Page->renderFieldHeader($Page->Discontinued) ?></div></th>
<?php } ?>
    </tr>
</thead>
<tbody>
<?php
        if ($Page->TotalGroups == 0) {
            break; // Show header only
        }
        $Page->ShowHeader = false;
    } // End show header
?>
<?php

    // Build detail SQL
    $where = DetailFilterSql($Page->CategoryName, $Page->getSqlFirstGroupField(), $Page->CategoryName->groupValue(), $Page->Dbid);
    AddFilter($Page->PageFirstGroupFilter, $where, "OR");
    AddFilter($where, $Page->Filter);
    $sql = $Page->buildReportSql($Page->getSqlSelect(), $Page->getSqlFrom(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $where, $Page->Sort);
    $rs = $sql->executeQuery();
    $Page->DetailRecords = $rs?->fetchAll() ?? [];
    $Page->DetailRecordCount = count($Page->DetailRecords);
    $Page->setGroupCount($Page->DetailRecordCount, $Page->GroupCount);

    // Load detail records
    $Page->CategoryName->Records = &$Page->DetailRecords;
    $Page->CategoryName->LevelBreak = true; // Set field level break
        $Page->GroupCounter[1] = $Page->GroupCount;
        $Page->CategoryName->getCnt($Page->CategoryName->Records); // Get record count
        $Page->setGroupCount($Page->CategoryName->Count, $Page->GroupCounter[1]);
?>
<?php if ($Page->CategoryName->Visible && $Page->CategoryName->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->resetAttributes();
        $Page->RowType = RowType::TOTAL;
        $Page->RowTotalType = RowSummary::GROUP;
        $Page->RowTotalSubType = RowTotal::HEADER;
        $Page->RowGroupLevel = 1;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CategoryName->Visible) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes("ew-rpt-grp-caret") ?>><?= $Page->CategoryName->groupToggleIcon() ?></td>
<?php } ?>
        <td data-field="CategoryName" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 1) ?>"<?= $Page->CategoryName->cellAttributes() ?>>
            <span class="ew-summary-caption Products_By_Category_CategoryName"><?= $Page->renderFieldHeader($Page->CategoryName) ?></span><?= $Language->phrase("SummaryColon") ?><span<?= $Page->CategoryName->viewAttributes() ?>><?= $Page->CategoryName->GroupViewValue ?></span>
            <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->CategoryName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span>
        </td>
    </tr>
<?php } ?>
<?php
        $Page->RecordCount = 0; // Reset record count
        foreach ($Page->CategoryName->Records as $record) {
            $Page->RecordCount++;
            $Page->RecordIndex++;
            $Page->loadRowValues($record);
?>
<?php
        // Render detail row
        $Page->resetAttributes();
        $Page->RowType = RowType::DETAIL;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CategoryName->Visible) { ?>
    <?php if ($Page->CategoryName->ShowGroupHeaderAsRow) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>></td>
    <?php } else { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>><span<?= $Page->CategoryName->viewAttributes() ?>><?= $Page->CategoryName->GroupViewValue ?></span></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->ProductName->cellAttributes() ?>>
<span<?= $Page->ProductName->viewAttributes() ?>>
<?= $Page->ProductName->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->UnitsInStock->cellAttributes() ?>>
<span<?= $Page->UnitsInStock->viewAttributes() ?>>
<?= $Page->UnitsInStock->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { ?>
        <td data-field="Discontinued"<?= $Page->Discontinued->cellAttributes() ?>>
<span<?= $Page->Discontinued->viewAttributes() ?>>
<?= $Page->Discontinued->getViewValue() ?></span>
</td>
<?php } ?>
    </tr>
<?php
    }
?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php
    $Page->resetAttributes();
    $Page->RowType = RowType::TOTAL;
    $Page->RowTotalType = RowSummary::GROUP;
    $Page->RowTotalSubType = RowTotal::FOOTER;
    $Page->RowGroupLevel = 1;
    $Page->renderRow();
?>
<?php if ($Page->CategoryName->ShowCompactSummaryFooter) { ?>
    <?php if (!$Page->CategoryName->ShowGroupHeaderAsRow) { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->CategoryName->Visible) { ?>
    <?php if ($Page->CategoryName->ShowGroupHeaderAsRow) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>></td>
    <?php } elseif ($Page->RowGroupLevel != 1) { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>>
        </td>
    <?php } else { ?>
        <td data-field="CategoryName"<?= $Page->CategoryName->cellAttributes() ?>>
            <span class="ew-summary-count"><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->CategoryName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?></span></span>
        </td>
    <?php } ?>
<?php } ?>
<?php if ($Page->ProductName->Visible) { ?>
        <td data-field="ProductName"<?= $Page->CategoryName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->UnitsInStock->Visible) { ?>
        <td data-field="UnitsInStock"<?= $Page->CategoryName->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->Discontinued->Visible) { ?>
        <td data-field="Discontinued"<?= $Page->CategoryName->cellAttributes() ?>></td>
<?php } ?>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount + $Page->DetailColumnCount > 0) { ?>
        <td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"<?= $Page->CategoryName->cellAttributes() ?>><?= str_replace(["%v", "%c"], [$Page->CategoryName->GroupViewValue, $Page->CategoryName->caption()], $Language->phrase("RptSumHead")) ?> <span class="ew-dir-ltr">(<?= FormatNumber($Page->CategoryName->Count, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
    </tr>
<?php } ?>
<?php } ?>
<?php
?>
<?php

    // Next group
    $Page->loadGroupRowValues();

    // Show header if page break
    if ($Page->isExport()) {
        $Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? false : ($Page->GroupCount % $Page->ExportPageBreakCount == 0);
    }

    // Page_Breaking server event
    if ($Page->ShowHeader) {
        $Page->pageBreaking($Page->ShowHeader, $Page->PageBreakHtml);
    }
    $Page->GroupCount++;
} // End while
?>
<?php if ($Page->TotalGroups > 0) { ?>
</tbody>
<tfoot>
<?php
    $Page->resetAttributes();
    $Page->RowType = RowType::TOTAL;
    $Page->RowTotalType = RowSummary::GRAND;
    $Page->RowTotalSubType = RowTotal::FOOTER;
    $Page->RowAttrs["class"] = "ew-rpt-grand-summary";
    $Page->renderRow();
?>
<?php if ($Page->CategoryName->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><span class="ew-aggregate-equal"><?= $Language->phrase("AggregateEqual") ?></span><span class="ew-aggregate-value"><?= FormatNumber($Page->TotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?></span>)</span></td></tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<?= FormatNumber($Page->TotalCount, Config("DEFAULT_NUMBER_FORMAT")) ?><?= $Language->phrase("RptDtlRec") ?>)</span></td></tr>
<?php } ?>
</tfoot>
</table>
<?php if (!$Page->isExport("pdf")) { ?>
</div>
<!-- /.ew-grid-middle-panel -->
<!-- Report grid (end) -->
<?php } ?>
<?php if ($Page->TotalGroups > 0) { ?>
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0) && $Page->Pager->Visible) { ?>
<!-- Bottom pager -->
<div class="card-footer ew-grid-lower-panel">
<?= $Page->Pager->render() ?>
</div>
<?php } ?>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
</div>
<!-- /.ew-grid -->
<?php } ?>
<?php } ?>
<?php if (!$Page->isExport("pdf")) { ?>
</main>
<!-- /.report-summary -->
<?php } ?>
<!-- Summary report (end) -->
<?php } ?>
</div>
<!-- /.ew-report -->
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
