<?php

namespace PHPMaker2024\prj_alfa;

// Set up and run Grid object
$Grid = Container("ModelsGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fmodelsgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { models: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fmodelsgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["ID", [fields.ID.visible && fields.ID.required ? ew.Validators.required(fields.ID.caption) : null], fields.ID.isInvalid],
            ["Trademark", [fields.Trademark.visible && fields.Trademark.required ? ew.Validators.required(fields.Trademark.caption) : null], fields.Trademark.isInvalid],
            ["Model", [fields.Model.visible && fields.Model.required ? ew.Validators.required(fields.Model.caption) : null], fields.Model.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["Trademark",false],["Model",false]];
                if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
                    return false;
                return true;
            }
        )

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "Trademark": <?= $Grid->Trademark->toClientList($Grid) ?>,
        })
        .build();
    window[form.id] = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<main class="list">
<div id="ew-header-options">
<?php $Grid->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Grid->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Grid->TableGridClass ?>">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<div id="fmodelsgrid" class="ew-form ew-list-form">
<div id="gmp_models" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_modelsgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = RowType::HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->ID->Visible) { // ID ?>
        <th data-name="ID" class="<?= $Grid->ID->headerCellClass() ?>"><div id="elh_models_ID" class="models_ID"><?= $Grid->renderFieldHeader($Grid->ID) ?></div></th>
<?php } ?>
<?php if ($Grid->Trademark->Visible) { // Trademark ?>
        <th data-name="Trademark" class="<?= $Grid->Trademark->headerCellClass() ?>"><div id="elh_models_Trademark" class="models_Trademark"><?= $Grid->renderFieldHeader($Grid->Trademark) ?></div></th>
<?php } ?>
<?php if ($Grid->Model->Visible) { // Model ?>
        <th data-name="Model" class="<?= $Grid->Model->headerCellClass() ?>"><div id="elh_models_Model" class="models_Model"><?= $Grid->renderFieldHeader($Grid->Model) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Grid->getPageNumber() ?>">
<?php
$Grid->setupGrid();
while ($Grid->RecordCount < $Grid->StopRecord || $Grid->RowIndex === '$rowindex$') {
    if (
        $Grid->CurrentRow !== false &&
        $Grid->RowIndex !== '$rowindex$' &&
        (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy") &&
        (!(($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0))
    ) {
        $Grid->fetch();
    }
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Grid->RowAction != "delete" &&
            $Grid->RowAction != "insertdelete" &&
            !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow()) &&
            $Grid->RowAction != "hide"
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->ID->Visible) { // ID ?>
        <td data-name="ID"<?= $Grid->ID->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_ID" class="el_models_ID"></span>
<input type="hidden" data-table="models" data-field="x_ID" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_ID" id="o<?= $Grid->RowIndex ?>_ID" value="<?= HtmlEncode($Grid->ID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_ID" class="el_models_ID">
<span<?= $Grid->ID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->ID->getDisplayValue($Grid->ID->EditValue))) ?>"></span>
<input type="hidden" data-table="models" data-field="x_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_ID" id="x<?= $Grid->RowIndex ?>_ID" value="<?= HtmlEncode($Grid->ID->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_ID" class="el_models_ID">
<span<?= $Grid->ID->viewAttributes() ?>>
<?= $Grid->ID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="models" data-field="x_ID" data-hidden="1" name="fmodelsgrid$x<?= $Grid->RowIndex ?>_ID" id="fmodelsgrid$x<?= $Grid->RowIndex ?>_ID" value="<?= HtmlEncode($Grid->ID->FormValue) ?>">
<input type="hidden" data-table="models" data-field="x_ID" data-hidden="1" data-old name="fmodelsgrid$o<?= $Grid->RowIndex ?>_ID" id="fmodelsgrid$o<?= $Grid->RowIndex ?>_ID" value="<?= HtmlEncode($Grid->ID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="models" data-field="x_ID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_ID" id="x<?= $Grid->RowIndex ?>_ID" value="<?= HtmlEncode($Grid->ID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->Trademark->Visible) { // Trademark ?>
        <td data-name="Trademark"<?= $Grid->Trademark->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<?php if ($Grid->Trademark->getSessionValue() != "") { ?>
<span<?= $Grid->Trademark->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Trademark->getDisplayValue($Grid->Trademark->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Trademark" name="x<?= $Grid->RowIndex ?>_Trademark" value="<?= HtmlEncode($Grid->Trademark->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_Trademark" class="el_models_Trademark">
    <select
        id="x<?= $Grid->RowIndex ?>_Trademark"
        name="x<?= $Grid->RowIndex ?>_Trademark"
        class="form-select ew-select<?= $Grid->Trademark->isInvalidClass() ?>"
        <?php if (!$Grid->Trademark->IsNativeSelect) { ?>
        data-select2-id="fmodelsgrid_x<?= $Grid->RowIndex ?>_Trademark"
        <?php } ?>
        data-table="models"
        data-field="x_Trademark"
        data-value-separator="<?= $Grid->Trademark->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Trademark->getPlaceHolder()) ?>"
        <?= $Grid->Trademark->editAttributes() ?>>
        <?= $Grid->Trademark->selectOptionListHtml("x{$Grid->RowIndex}_Trademark") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Trademark->getErrorMessage() ?></div>
<?= $Grid->Trademark->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Trademark") ?>
<?php if (!$Grid->Trademark->IsNativeSelect) { ?>
<script>
loadjs.ready("fmodelsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Trademark", selectId: "fmodelsgrid_x<?= $Grid->RowIndex ?>_Trademark" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmodelsgrid.lists.Trademark?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Trademark", form: "fmodelsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Trademark", form: "fmodelsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.models.fields.Trademark.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<input type="hidden" data-table="models" data-field="x_Trademark" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_Trademark" id="o<?= $Grid->RowIndex ?>_Trademark" value="<?= HtmlEncode($Grid->Trademark->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<?php if ($Grid->Trademark->getSessionValue() != "") { ?>
<span<?= $Grid->Trademark->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->Trademark->getDisplayValue($Grid->Trademark->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_Trademark" name="x<?= $Grid->RowIndex ?>_Trademark" value="<?= HtmlEncode($Grid->Trademark->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_Trademark" class="el_models_Trademark">
    <select
        id="x<?= $Grid->RowIndex ?>_Trademark"
        name="x<?= $Grid->RowIndex ?>_Trademark"
        class="form-select ew-select<?= $Grid->Trademark->isInvalidClass() ?>"
        <?php if (!$Grid->Trademark->IsNativeSelect) { ?>
        data-select2-id="fmodelsgrid_x<?= $Grid->RowIndex ?>_Trademark"
        <?php } ?>
        data-table="models"
        data-field="x_Trademark"
        data-value-separator="<?= $Grid->Trademark->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->Trademark->getPlaceHolder()) ?>"
        <?= $Grid->Trademark->editAttributes() ?>>
        <?= $Grid->Trademark->selectOptionListHtml("x{$Grid->RowIndex}_Trademark") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->Trademark->getErrorMessage() ?></div>
<?= $Grid->Trademark->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_Trademark") ?>
<?php if (!$Grid->Trademark->IsNativeSelect) { ?>
<script>
loadjs.ready("fmodelsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_Trademark", selectId: "fmodelsgrid_x<?= $Grid->RowIndex ?>_Trademark" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmodelsgrid.lists.Trademark?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_Trademark", form: "fmodelsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_Trademark", form: "fmodelsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.models.fields.Trademark.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_Trademark" class="el_models_Trademark">
<span<?= $Grid->Trademark->viewAttributes() ?>>
<?= $Grid->Trademark->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="models" data-field="x_Trademark" data-hidden="1" name="fmodelsgrid$x<?= $Grid->RowIndex ?>_Trademark" id="fmodelsgrid$x<?= $Grid->RowIndex ?>_Trademark" value="<?= HtmlEncode($Grid->Trademark->FormValue) ?>">
<input type="hidden" data-table="models" data-field="x_Trademark" data-hidden="1" data-old name="fmodelsgrid$o<?= $Grid->RowIndex ?>_Trademark" id="fmodelsgrid$o<?= $Grid->RowIndex ?>_Trademark" value="<?= HtmlEncode($Grid->Trademark->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Model->Visible) { // Model ?>
        <td data-name="Model"<?= $Grid->Model->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_Model" class="el_models_Model">
<input type="<?= $Grid->Model->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Model" id="x<?= $Grid->RowIndex ?>_Model" data-table="models" data-field="x_Model" value="<?= $Grid->Model->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->Model->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Model->formatPattern()) ?>"<?= $Grid->Model->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Model->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="models" data-field="x_Model" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_Model" id="o<?= $Grid->RowIndex ?>_Model" value="<?= HtmlEncode($Grid->Model->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_Model" class="el_models_Model">
<input type="<?= $Grid->Model->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_Model" id="x<?= $Grid->RowIndex ?>_Model" data-table="models" data-field="x_Model" value="<?= $Grid->Model->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->Model->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->Model->formatPattern()) ?>"<?= $Grid->Model->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Model->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_models_Model" class="el_models_Model">
<span<?= $Grid->Model->viewAttributes() ?>>
<?= $Grid->Model->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="models" data-field="x_Model" data-hidden="1" name="fmodelsgrid$x<?= $Grid->RowIndex ?>_Model" id="fmodelsgrid$x<?= $Grid->RowIndex ?>_Model" value="<?= HtmlEncode($Grid->Model->FormValue) ?>">
<input type="hidden" data-table="models" data-field="x_Model" data-hidden="1" data-old name="fmodelsgrid$o<?= $Grid->RowIndex ?>_Model" id="fmodelsgrid$o<?= $Grid->RowIndex ?>_Model" value="<?= HtmlEncode($Grid->Model->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == RowType::ADD || $Grid->RowType == RowType::EDIT) { ?>
<script data-rowindex="<?= $Grid->RowIndex ?>">
loadjs.ready(["fmodelsgrid","load"], () => fmodelsgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking

    // Reset for template row
    if ($Grid->RowIndex === '$rowindex$') {
        $Grid->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0) {
        $Grid->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fmodelsgrid">
</div><!-- /.ew-list-form -->
<?php
// Close result set
$Grid->Recordset?->free();
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Grid->FooterOptions?->render("body") ?>
</div>
</main>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("models");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
