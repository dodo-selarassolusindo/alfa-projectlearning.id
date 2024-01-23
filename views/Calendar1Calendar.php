<?php

namespace PHPMaker2024\demo2024;

// Page object
$Calendar1Calendar = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Calendar1: currentTable } });
var currentPageID = ew.PAGE_ID = "calendar";
var currentForm;
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<!-- Content Container -->
<div id="ew-report" class="ew-report container-fluid">
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<div class="btn-toolbar ew-toolbar">
<?php
    $Page->SearchOptions->render("body");
    $Page->FilterOptions->render("body");
?>
</div>
<form name="fCalendar1srch" id="fCalendar1srch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="fCalendar1srch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { Calendar1: currentTable } });
var currentPageID = ew.PAGE_ID = "calendar";
var currentForm;
var fCalendar1srch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fCalendar1srch")
        .setPageId("calendar")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["_Title", [], fields._Title.isInvalid],
            ["Description", [], fields.Description.isInvalid]
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
        .setLists({
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = RowType::SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->_Title->Visible) { // Title ?>
<?php
if (!$Page->_Title->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs__Title" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->_Title->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x__Title" class="ew-search-caption ew-label"><?= $Page->_Title->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__Title" id="z__Title" value="LIKE">
</div>
        </div>
        <div id="el_Calendar1__Title" class="ew-search-field">
<input type="<?= $Page->_Title->getInputTextType() ?>" name="x__Title" id="x__Title" data-table="Calendar1" data-field="x__Title" value="<?= $Page->_Title->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_Title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_Title->formatPattern()) ?>"<?= $Page->_Title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_Title->getErrorMessage() ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->Description->Visible) { // Description ?>
<?php
if (!$Page->Description->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_Description" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->Description->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_Description" class="ew-search-caption ew-label"><?= $Page->Description->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_Description" id="z_Description" value="LIKE">
</div>
        </div>
        <div id="el_Calendar1_Description" class="ew-search-field">
<input type="<?= $Page->Description->getInputTextType() ?>" name="x_Description" id="x_Description" data-table="Calendar1" data-field="x_Description" value="<?= $Page->Description->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->Description->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->Description->formatPattern()) ?>"<?= $Page->Description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->Description->getErrorMessage() ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->SearchColumnCount > 0) { ?>
   <div class="col-sm-auto mb-3">
       <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
   </div>
<?php } ?>
</div><!-- /.row -->
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<!-- Calendar report (begin) -->
<main class="report-calendar">
    <div class="ew-calendar">
<script>
var currentCalendar; // FullCalendar.Calendar
var calendarLoaded = loadjs.isDefined("fullcalendar");
if (!calendarLoaded) {
    ew.loadjs([
        ew.PATH_BASE + "fullcalendar/index.global.min.js?v=24.7.5",
        ew.PATH_BASE + "fullcalendar/bootstrap5/index.global.min.js?v=24.7.5"
    ], "fullcalendar");
    ew.ready(["fullcalendar", "luxon"], ew.PATH_BASE + "fullcalendar/luxon3/index.global.min.js?v=24.7.5");
}
loadjs.ready(
    ["luxon", "fullcalendar", "makerjs"],
    function () {
        // Override Bootstrap 5 styles and fonts
        FullCalendar.Bootstrap5.Internal.BootstrapTheme.prototype.classes = {
            ...FullCalendar.Bootstrap5.Internal.BootstrapTheme.prototype.classes,
            ...{ root: "fc-theme-bootstrap5", table: null, tableCellShaded: "fc-theme-bootstrap5-shaded" }
        }; // Use Bootstrap 5 styles
        FullCalendar.Bootstrap5.Internal.BootstrapTheme.prototype.baseIconClass = "fa-solid"; // Font Awesome 6 icons
        FullCalendar.Bootstrap5.Internal.BootstrapTheme.prototype.iconClasses = {
            close: "fa-xmark",
            prev: "fa-angle-left",
            next: "fa-angle-right",
            prevYear: "fa-angles-left",
            nextYear: "fa-angles-right",
            add: "fa-plus"
        };
        FullCalendar.Bootstrap5.Internal.BootstrapTheme.prototype.rtlIconClasses = {
            prev: "fa-angle-right",
            next: "fa-angle-left",
            prevYear: "fa-angles-right",
            nextYear: "fa-angles-left",
        };
        FullCalendar.globalLocales.push({ code: ew.LANGUAGE_ID.toLowerCase(), ...ew.language.phrase("fullcalendar") });
        let container = document.getElementById("full-calendar"),
            dropdown = document.getElementById("calendar-dropdown"),
            options = ew.deepAssign({}, ew.calendarOptions, <?= CurrentPage()->getCalendarOptions() ?>);
        try {
            currentCalendar = ew.fullCalendar(container, options, dropdown);
            if (calendarLoaded)
                currentCalendar.refetchEvents();
            currentCalendar.render();
        } finally {
            document.querySelector(".ew-calendar-card").classList.remove("border-transparent");
            if (!calendarLoaded)
                document.querySelector(".overlay").remove();
        }
        // Hide popover on modal shown
        $(".modal").on("show.bs.modal", () => $(".ew-event-popover.show").popover("hide"));
        // Enable right click
        container.addEventListener("contextmenu", (e) => {
            let eventEl = e.target.closest(".fc-event");
            if (eventEl) {
                e.preventDefault();
                e.stopPropagation();
                eventEl.dispatchEvent(new MouseEvent("click", e));
            }
        });
    }
);
</script>
<div class="overlay-wrapper ew-calendar-wrapper">
    <div class="card p-0 ew-calendar-card border-transparent">
        <div class="card-body p-0">
            <!-- Calendar -->
            <div id="full-calendar" class="ew-calendar"></div>
        </div>
    </div>
    <div class="dropdown ew-calendar-dropdown">
        <button class="dropdown-toggle d-none" type="button" id="calendar-dropdown" aria-expanded="false"></button>
        <ul class="dropdown-menu" aria-labelledby="full-calendar">
            <li><a class="dropdown-item" role="button" data-action="view"><?= $Language->phrase("ViewLink", null) ?></a></li>
            <li><a class="dropdown-item" role="button" data-action="edit"><?= $Language->phrase("EditLink", null) ?></a></li>
            <li><a class="dropdown-item" role="button" data-action="copy"><?= $Language->phrase("CopyLink", null) ?></a></li>
            <li><a class="dropdown-item" role="button" data-action="delete"><?= $Language->phrase("DeleteLink", null) ?></a></li>
        </ul>
    </div>
</div>
<script>
if (!calendarLoaded)
    document.querySelector(".ew-calendar-wrapper").insertAdjacentHTML("afterbegin", ew.overlayTemplate());
</script>
    </div><!-- /.ew-calendar -->
</main>
<!-- /.report-calendar -->
<!-- Calendar report (end) -->
</div>
<!-- /.ew-report -->
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script type="text/html" data-name="event-popover" data-seq="10">
{{if extendedProps.timeSpan}}
<p>{{:extendedProps.timeSpan}}</p>
{{/if}}
{{:extendedProps.description}}
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
