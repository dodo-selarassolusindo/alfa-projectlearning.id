<?php

namespace PHPMaker2024\demo2024;

// Page object
$CustomersEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcustomersedit" id="fcustomersedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { customers: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcustomersedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcustomersedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["CustomerID", [fields.CustomerID.visible && fields.CustomerID.required ? ew.Validators.required(fields.CustomerID.caption) : null], fields.CustomerID.isInvalid],
            ["CompanyName", [fields.CompanyName.visible && fields.CompanyName.required ? ew.Validators.required(fields.CompanyName.caption) : null], fields.CompanyName.isInvalid],
            ["ContactName", [fields.ContactName.visible && fields.ContactName.required ? ew.Validators.required(fields.ContactName.caption) : null], fields.ContactName.isInvalid],
            ["ContactTitle", [fields.ContactTitle.visible && fields.ContactTitle.required ? ew.Validators.required(fields.ContactTitle.caption) : null], fields.ContactTitle.isInvalid],
            ["Address", [fields.Address.visible && fields.Address.required ? ew.Validators.required(fields.Address.caption) : null], fields.Address.isInvalid],
            ["City", [fields.City.visible && fields.City.required ? ew.Validators.required(fields.City.caption) : null], fields.City.isInvalid],
            ["Region", [fields.Region.visible && fields.Region.required ? ew.Validators.required(fields.Region.caption) : null], fields.Region.isInvalid],
            ["PostalCode", [fields.PostalCode.visible && fields.PostalCode.required ? ew.Validators.required(fields.PostalCode.caption) : null], fields.PostalCode.isInvalid],
            ["Country", [fields.Country.visible && fields.Country.required ? ew.Validators.required(fields.Country.caption) : null], fields.Country.isInvalid],
            ["Phone", [fields.Phone.visible && fields.Phone.required ? ew.Validators.required(fields.Phone.caption) : null], fields.Phone.isInvalid],
            ["Fax", [fields.Fax.visible && fields.Fax.required ? ew.Validators.required(fields.Fax.caption) : null], fields.Fax.isInvalid]
        ])

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
            "CustomerID": <?= $Page->CustomerID->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="customers">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->CustomerID->Visible) { // CustomerID ?>
    <div id="r_CustomerID"<?= $Page->CustomerID->rowAttributes() ?>>
        <label id="elh_customers_CustomerID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CustomerID->caption() ?><?= $Page->CustomerID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CustomerID->cellAttributes() ?>>
<span id="el_customers_CustomerID">
<?php
if (IsRTL()) {
    $Page->CustomerID->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_CustomerID" class="ew-auto-suggest">
    <input type="<?= $Page->CustomerID->getInputTextType() ?>" class="form-control" name="sv_x_CustomerID" id="sv_x_CustomerID" value="<?= RemoveHtml($Page->CustomerID->EditValue) ?>" autocomplete="off" size="30" maxlength="5" placeholder="<?= HtmlEncode($Page->CustomerID->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->CustomerID->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CustomerID->formatPattern()) ?>"<?= $Page->CustomerID->editAttributes() ?> aria-describedby="x_CustomerID_help">
</span>
<selection-list hidden class="form-control" data-table="customers" data-field="x_CustomerID" data-input="sv_x_CustomerID" data-value-separator="<?= $Page->CustomerID->displayValueSeparatorAttribute() ?>" name="x_CustomerID" id="x_CustomerID" value="<?= HtmlEncode($Page->CustomerID->CurrentValue) ?>"></selection-list>
<?= $Page->CustomerID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CustomerID->getErrorMessage() ?></div>
<script>
loadjs.ready("fcustomersedit", function() {
    fcustomersedit.createAutoSuggest(Object.assign({"id":"x_CustomerID","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->CustomerID->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.customers.fields.CustomerID.autoSuggestOptions));
});
</script>
<?= $Page->CustomerID->Lookup->getParamTag($Page, "p_x_CustomerID") ?>
<input type="hidden" data-table="customers" data-field="x_CustomerID" data-hidden="1" data-old name="o_CustomerID" id="o_CustomerID" value="<?= HtmlEncode($Page->CustomerID->OldValue ?? $Page->CustomerID->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CompanyName->Visible) { // CompanyName ?>
    <div id="r_CompanyName"<?= $Page->CompanyName->rowAttributes() ?>>
        <label id="elh_customers_CompanyName" for="x_CompanyName" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CompanyName->caption() ?><?= $Page->CompanyName->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CompanyName->cellAttributes() ?>>
<span id="el_customers_CompanyName">
<input type="<?= $Page->CompanyName->getInputTextType() ?>" name="x_CompanyName" id="x_CompanyName" data-table="customers" data-field="x_CompanyName" value="<?= $Page->CompanyName->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->CompanyName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->CompanyName->formatPattern()) ?>"<?= $Page->CompanyName->editAttributes() ?> aria-describedby="x_CompanyName_help">
<?= $Page->CompanyName->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CompanyName->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ContactName->Visible) { // ContactName ?>
    <div id="r_ContactName"<?= $Page->ContactName->rowAttributes() ?>>
        <label id="elh_customers_ContactName" for="x_ContactName" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ContactName->caption() ?><?= $Page->ContactName->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ContactName->cellAttributes() ?>>
<span id="el_customers_ContactName">
<input type="<?= $Page->ContactName->getInputTextType() ?>" name="x_ContactName" id="x_ContactName" data-table="customers" data-field="x_ContactName" value="<?= $Page->ContactName->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ContactName->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ContactName->formatPattern()) ?>"<?= $Page->ContactName->editAttributes() ?> aria-describedby="x_ContactName_help">
<?= $Page->ContactName->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ContactName->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ContactTitle->Visible) { // ContactTitle ?>
    <div id="r_ContactTitle"<?= $Page->ContactTitle->rowAttributes() ?>>
        <label id="elh_customers_ContactTitle" for="x_ContactTitle" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ContactTitle->caption() ?><?= $Page->ContactTitle->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ContactTitle->cellAttributes() ?>>
<span id="el_customers_ContactTitle">
<input type="<?= $Page->ContactTitle->getInputTextType() ?>" name="x_ContactTitle" id="x_ContactTitle" data-table="customers" data-field="x_ContactTitle" value="<?= $Page->ContactTitle->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ContactTitle->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ContactTitle->formatPattern()) ?>"<?= $Page->ContactTitle->editAttributes() ?> aria-describedby="x_ContactTitle_help">
<?= $Page->ContactTitle->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ContactTitle->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Address->Visible) { // Address ?>
    <div id="r_Address"<?= $Page->Address->rowAttributes() ?>>
        <label id="elh_customers_Address" for="x_Address" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Address->caption() ?><?= $Page->Address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Address->cellAttributes() ?>>
<span id="el_customers_Address">
<input type="<?= $Page->Address->getInputTextType() ?>" name="x_Address" id="x_Address" data-table="customers" data-field="x_Address" value="<?= $Page->Address->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->Address->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Address->formatPattern()) ?>"<?= $Page->Address->editAttributes() ?> aria-describedby="x_Address_help">
<?= $Page->Address->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Address->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->City->Visible) { // City ?>
    <div id="r_City"<?= $Page->City->rowAttributes() ?>>
        <label id="elh_customers_City" for="x_City" class="<?= $Page->LeftColumnClass ?>"><?= $Page->City->caption() ?><?= $Page->City->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->City->cellAttributes() ?>>
<span id="el_customers_City">
<input type="<?= $Page->City->getInputTextType() ?>" name="x_City" id="x_City" data-table="customers" data-field="x_City" value="<?= $Page->City->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->City->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->City->formatPattern()) ?>"<?= $Page->City->editAttributes() ?> aria-describedby="x_City_help">
<?= $Page->City->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->City->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Region->Visible) { // Region ?>
    <div id="r_Region"<?= $Page->Region->rowAttributes() ?>>
        <label id="elh_customers_Region" for="x_Region" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Region->caption() ?><?= $Page->Region->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Region->cellAttributes() ?>>
<span id="el_customers_Region">
<input type="<?= $Page->Region->getInputTextType() ?>" name="x_Region" id="x_Region" data-table="customers" data-field="x_Region" value="<?= $Page->Region->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Region->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Region->formatPattern()) ?>"<?= $Page->Region->editAttributes() ?> aria-describedby="x_Region_help">
<?= $Page->Region->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Region->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->PostalCode->Visible) { // PostalCode ?>
    <div id="r_PostalCode"<?= $Page->PostalCode->rowAttributes() ?>>
        <label id="elh_customers_PostalCode" for="x_PostalCode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->PostalCode->caption() ?><?= $Page->PostalCode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->PostalCode->cellAttributes() ?>>
<span id="el_customers_PostalCode">
<input type="<?= $Page->PostalCode->getInputTextType() ?>" name="x_PostalCode" id="x_PostalCode" data-table="customers" data-field="x_PostalCode" value="<?= $Page->PostalCode->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->PostalCode->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->PostalCode->formatPattern()) ?>"<?= $Page->PostalCode->editAttributes() ?> aria-describedby="x_PostalCode_help">
<?= $Page->PostalCode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->PostalCode->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Country->Visible) { // Country ?>
    <div id="r_Country"<?= $Page->Country->rowAttributes() ?>>
        <label id="elh_customers_Country" for="x_Country" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Country->caption() ?><?= $Page->Country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Country->cellAttributes() ?>>
<span id="el_customers_Country">
<input type="<?= $Page->Country->getInputTextType() ?>" name="x_Country" id="x_Country" data-table="customers" data-field="x_Country" value="<?= $Page->Country->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->Country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Country->formatPattern()) ?>"<?= $Page->Country->editAttributes() ?> aria-describedby="x_Country_help">
<?= $Page->Country->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Country->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Phone->Visible) { // Phone ?>
    <div id="r_Phone"<?= $Page->Phone->rowAttributes() ?>>
        <label id="elh_customers_Phone" for="x_Phone" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Phone->caption() ?><?= $Page->Phone->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Phone->cellAttributes() ?>>
<span id="el_customers_Phone">
<input type="<?= $Page->Phone->getInputTextType() ?>" name="x_Phone" id="x_Phone" data-table="customers" data-field="x_Phone" value="<?= $Page->Phone->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->Phone->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Phone->formatPattern()) ?>"<?= $Page->Phone->editAttributes() ?> aria-describedby="x_Phone_help">
<?= $Page->Phone->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Phone->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Fax->Visible) { // Fax ?>
    <div id="r_Fax"<?= $Page->Fax->rowAttributes() ?>>
        <label id="elh_customers_Fax" for="x_Fax" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Fax->caption() ?><?= $Page->Fax->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->Fax->cellAttributes() ?>>
<span id="el_customers_Fax">
<input type="<?= $Page->Fax->getInputTextType() ?>" name="x_Fax" id="x_Fax" data-table="customers" data-field="x_Fax" value="<?= $Page->Fax->EditValue ?>" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->Fax->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Fax->formatPattern()) ?>"<?= $Page->Fax->editAttributes() ?> aria-describedby="x_Fax_help">
<?= $Page->Fax->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->Fax->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("orders", explode(",", $Page->getCurrentDetailTable())) && $orders->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("orders", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "OrdersGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcustomersedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcustomersedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("customers");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
