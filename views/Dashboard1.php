<?php

namespace PHPMaker2024\prj_alfa;

// Dashboard Page object
$Dashboard1 = $Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Dashboard1: currentTable } });
var currentPageID = ew.PAGE_ID = "dashboard";
var currentForm;
var fDashboard1srch;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fDashboard1srch")
        .setPageId("dashboard")
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
<!-- Content Container -->
<div id="ew-report" class="ew-report">
<div class="btn-toolbar ew-toolbar">
<?php
    $Page->ExportOptions->render("body");
    $Page->SearchOptions->render("body");
?>
</div>
<?php $Page->showPageHeader(); ?>
<?php if (!$Page->ModalSearch && !$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Dashboard1: currentTable } });
var currentPageID = ew.PAGE_ID = "dashboard";
var currentForm;
var fDashboard1srch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fDashboard1srch")
        .setPageId("dashboard")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["CategoryName", [], fields.CategoryName.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
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
        .setQueryBuilderLists({
            "CategoryName": <?= $Page->CategoryName->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    loadjs.done(form.id);
});
</script>
<form name="fDashboard1srch" id="fDashboard1srch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="Dashboard1">
<input type="hidden" name="action" id="action" value="query">
<input type="hidden" name="rules" value="<?= HtmlEncode($Page->getSessionRules()) ?>">
<template id="tpx_Dashboard1_CategoryName" class="cars2list"><span id="el_Dashboard1_CategoryName" class="ew-search-field ew-search-field-single">
    <select
        id="x_CategoryName"
        name="x_CategoryName"
        class="form-select ew-select<?= $Page->CategoryName->isInvalidClass() ?>"
        <?php if (!$Page->CategoryName->IsNativeSelect) { ?>
        data-select2-id="fDashboard1srch_x_CategoryName"
        <?php } ?>
        data-table="Dashboard1"
        data-field="x_CategoryName"
        data-value-separator="<?= $Page->CategoryName->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->CategoryName->getPlaceHolder()) ?>"
        <?= $Page->CategoryName->editAttributes() ?>>
        <?= $Page->CategoryName->selectOptionListHtml("x_CategoryName") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->CategoryName->getErrorMessage() ?></div>
<?= $Page->CategoryName->Lookup->getParamTag($Page, "p_x_CategoryName") ?>
<?php if (!$Page->CategoryName->IsNativeSelect) { ?>
<script>
loadjs.ready("fDashboard1srch", function() {
    var options = { name: "x_CategoryName", selectId: "fDashboard1srch_x_CategoryName" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fDashboard1srch.lists.CategoryName?.lookupOptions.length) {
        options.data = { id: "x_CategoryName", form: "fDashboard1srch" };
    } else {
        options.ajax = { id: "x_CategoryName", form: "fDashboard1srch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.Dashboard1.fields.CategoryName.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span></template>
<div id="Dashboard1_query_builder" class="query-builder mb-3"></div>
<div class="btn-group mb-3 query-btn-group"></div>
<button type="button" id="btn-view-rules" class="btn btn-primary d-none disabled" title="<?= HtmlEncode($Language->phrase("View", true)) ?>"><i class="fa-solid fa-eye ew-icon"></i></button>
<button type="button" id="btn-clear-rules" class="btn btn-primary d-none disabled" title="<?= HtmlEncode($Language->phrase("Clear", true)) ?>"><i class="fa-solid fa-xmark ew-icon"></i></button>
<script>
// Filter builder
loadjs.ready(["wrapper", "head"], () => {
    let filters = [
            {
                id: "CategoryName",
                type: "string",
                label: currentTable.fields.CategoryName.caption,
                operators: currentTable.fields.CategoryName.clientSearchOperators,
                input: ew.getQueryBuilderFilterInput(),
                valueSetter: ew.getQueryBuilderValueSetter(),
                validation: ew.getQueryBuilderFilterValidation(fDashboard1srch.fields.CategoryName.validators),
                data: {
                    format: currentTable.fields.CategoryName.clientFormatPattern
                }
            },
        ],
        $ = jQuery,
        $qb = $("#Dashboard1_query_builder"),
        args = {},
        rules = ew.parseJson($("#fDashboard1srch input[name=rules]").val()),
        queryBuilderOptions = Object.assign({}, ew.queryBuilderOptions),
        allowViewRules = queryBuilderOptions.allowViewRules,
        allowClearRules = queryBuilderOptions.allowClearRules,
        hasRules = group => Array.isArray(group?.rules) && group.rules.length > 0,
        getRules = () => $qb.queryBuilder("getRules", { skip_empty: true }),
        getSql = () => $qb.queryBuilder("getSQL", false, false, rules)?.sql;
    delete queryBuilderOptions.allowViewRules;
    delete queryBuilderOptions.allowClearRules;
    args.options = ew.deepAssign({
        plugins: Object.assign({}, ew.queryBuilderPlugins),
        lang: ew.language.phrase("querybuilderjs"),
        select_placeholder: ew.language.phrase("PleaseSelect"),
        inputs_separator: `<div class="d-inline-flex ms-2 me-2">${ew.language.phrase("AND")}</div>`, // For "between"
        filters,
        rules
    }, queryBuilderOptions);
    $qb.trigger("querybuilder", [args]);
    $qb.queryBuilder(args.options).on("rulesChanged.queryBuilder", () => {
        let rules = getRules();
        !ew.DEBUG || console.log(rules, getSql());
        $("#btn-reset, #btn-action, #btn-clear-rules, #btn-view-rules").toggleClass("disabled", !rules);
    }).on("afterCreateRuleInput.queryBuilder", function(e, rule) {
        let select = rule.$el.find(".rule-value-container").find("selection-list, select")[0];
        if (select) { // Selection list
            let id = select.dataset.field.replace("^x_", ""),
                form = ew.forms.get(select);
            form.updateList(select, undefined, undefined, true); // Update immediately
        }
    });
    $("#fDashboard1srch").on("beforesubmit", function () {
        this.rules.value = JSON.stringify(getRules());
    });
    $("#btn-reset").toggleClass("d-none", false).on("click", () => {
        hasRules(rules) ? $qb.queryBuilder("setRules", rules) : $qb.queryBuilder("reset");
        return false;
    });
    $("#btn-action").toggleClass("d-none", false);
    if (allowClearRules) {
        $("#btn-clear-rules").appendTo(".query-btn-group").removeClass("d-none").on("click", () => $qb.queryBuilder("reset"));
    }
    if (allowViewRules) {
        $("#btn-view-rules").appendTo(".query-btn-group").removeClass("d-none").on("click", () => {
            let rules = getRules();
            if (hasRules(rules)) {
                let sql = getSql();
                ew.alert(sql ? '<pre class="text-start fs-6">' + sql + '</pre>' : '', "dark");
                !ew.DEBUG || console.log(rules, sql);
            } else {
                ew.alert(ew.language.phrase("EmptyLabel"));
            }
        });
    }
    $(".query-btn-group").toggleClass(".mb-3", $(".query-btn-group").find(".btn:not(.d-none)").length);
    if (hasRules(rules)) { // Enable buttons if rules exist initially
        $("#btn-reset, #btn-action, #btn-clear-rules, #btn-view-rules").removeClass("disabled");
    }
});
</script>
<div class="row ew-buttons"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fDashboard1srch" formaction="<?= CurrentPageUrl(false) ?>" data-ajax="true"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fDashboard1srch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="fDashboard1srch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
        <?php } ?>
    </div><!-- /buttons offset -->
</div><!-- /buttons .row -->
</form>
<?php } ?>
<?php
$Page->showMessage();
?>
<!-- Dashboard Container -->
<div id="ew-dashboard" class="ew-dashboard">
<div class="row">
<div class="<?= $Dashboard1->ItemClassNames[0] ?>">
<div id="Item1" class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $Language->tablePhrase("Sales_by_Category_for_2014", "TblCaption") ?></h3>
    <?php if (!$Dashboard1->isExport()) { ?>
        <div class="card-tools">
    <?php if ($Dashboard1->CanRefresh) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="<?= GetUrl("salesbycategoryfor2014") ?>?layout=false&<?= Config("PAGE_DASHBOARD") ?>=<?= $DashboardReport ?>" data-load-on-init="<?= $Page->LoadOnInit ? "true" : "false" ?>"><i class="fa-solid fa-rotate"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanMaximize) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fa-solid fa-maximize"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanCollapse) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa-solid fa-minus"></i></button>
    <?php } ?>
        </div>
    <?php } ?>
    </div><!-- /.card-header -->
    <div class="card-body">
        <?= $Dashboard1->renderItem($this, 1) ?>
    </div><!-- /.card-body -->
</div><!-- /.card -->
</div>
<div class="<?= $Dashboard1->ItemClassNames[1] ?>">
<div id="Item2" class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $Language->chartPhrase("Sales_by_Category_for_2014", "SalesByCategory2014", "ChartCaption") ?></h3>
    <?php if (!$Dashboard1->isExport()) { ?>
        <div class="card-tools">
    <?php if ($Dashboard1->CanRefresh) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="<?= GetUrl("salesbycategoryfor2014/SalesByCategory2014?layout=false&" . Config("PAGE_DASHBOARD") . "=" . $DashboardReport . "") ?>" data-load-on-init="<?= $Page->LoadOnInit ? "true" : "false" ?>"><i class="fa-solid fa-rotate"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanMaximize) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fa-solid fa-maximize"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanCollapse) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa-solid fa-minus"></i></button>
    <?php } ?>
        </div>
    <?php } ?>
    </div><!-- /.card-header -->
    <div class="card-body">
        <?= $Dashboard1->renderItem($this, 2) ?>
    </div><!-- /.card-body -->
</div><!-- /.card -->
</div>
<div class="<?= $Dashboard1->ItemClassNames[2] ?>">
<div id="Item3" class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $Language->chartPhrase("Quarterly_Orders_By_Product", "OrdersByCategory", "ChartCaption") ?></h3>
    <?php if (!$Dashboard1->isExport()) { ?>
        <div class="card-tools">
    <?php if ($Dashboard1->CanRefresh) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="<?= GetUrl("quarterlyordersbyproduct/OrdersByCategory?layout=false&" . Config("PAGE_DASHBOARD") . "=" . $DashboardReport . "") ?>" data-load-on-init="<?= $Page->LoadOnInit ? "true" : "false" ?>"><i class="fa-solid fa-rotate"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanMaximize) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fa-solid fa-maximize"></i></button>
    <?php } ?>
    <?php if ($Dashboard1->CanCollapse) { ?>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa-solid fa-minus"></i></button>
    <?php } ?>
        </div>
    <?php } ?>
    </div><!-- /.card-header -->
    <div class="card-body">
        <?= $Dashboard1->renderItem($this, 3) ?>
    </div><!-- /.card-body -->
</div><!-- /.card -->
</div>
</div>
</div>
<!-- /.ew-dashboard -->
</div>
<!-- /.ew-report -->
<script>
loadjs.ready("load", () => jQuery('[data-card-widget="card-refresh"]')
    .on("loaded.fail.lte.cardrefresh", (e, jqXHR, textStatus, errorThrown) => console.error(errorThrown))
    .on("loaded.success.lte.cardrefresh", (e, result) => !ew.getError(result) || console.error(result)));
</script>
<?php if ($Dashboard1->isExport() && !$Dashboard1->isExport("print")) { ?>
<script class="ew-export-dashboard">
loadjs.ready("load", function() {
    ew.exportCustom("ew-dashboard", "<?= $Dashboard1->Export ?>", "Dashboard1");
    loadjs.done("exportdashboard");
});
</script>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
