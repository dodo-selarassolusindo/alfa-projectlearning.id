<?php

namespace PHPMaker2024\prj_alfa;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Closure;

/**
 * Page class
 */
class Calendar1Calendar extends Calendar1
{
    use MessagesTrait;

    // Page ID
    public $PageID = "calendar";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Calendar1Calendar";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $ReportContainerClass = "ew-grid";
    public $CurrentPageName = "calendar1";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $CopyUrl;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<div id="ew-page-header">' . $header . '</div>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<div id="ew-page-footer">' . $footer . '</div>';
        }
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'Calendar1';
        $this->TableName = 'Calendar1';

        // Calendar options
        $this->CalendarOptions = new \Dflydev\DotAccessData\Data();

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (Calendar1)
        if (!isset($GLOBALS["Calendar1"]) || $GLOBALS["Calendar1"]::class == PROJECT_NAMESPACE . "Calendar1") {
            $GLOBALS["Calendar1"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'Calendar1');
        }

        // Start timer
        $DebugTimer = Container("debug.timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // Filter options
        $this->FilterOptions = new ListOptions(TagClassName: "ew-filter-option");
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return $Response?->getBody() ?? ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }
        DispatchEvent(new PageUnloadedEvent($this), PageUnloadedEvent::NAME);
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection if not in dashboard
        if (!$DashboardReport) {
            CloseConnections();
        }

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (WithJsonResponse()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }
            SaveDebugMessage();
            Redirect(GetUrl($url));
        }
        return; // Return to controller
    }

    // Lookup data
    public function lookup(array $req = [], bool $response = true)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $req["field"] ?? null;
        if (!$fieldName) {
            return [];
        }
        $fld = $this->Fields[$fieldName];
        $lookup = $fld->Lookup;
        $name = $req["name"] ?? "";
        if (ContainsString($name, "query_builder_rule")) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }
        if ($fld instanceof ReportField) {
            $lookup->RenderViewFunc = "renderLookup"; // Set up view renderer
        }
        $lookup->RenderEditFunc = ""; // Set up edit renderer

        // Get lookup parameters
        $lookupType = $req["ajax"] ?? "unknown";
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $req["q"] ?? $req["sv"] ?? "";
            $pageSize = $req["n"] ?? $req["recperpage"] ?? 10;
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $req["q"] ?? "";
            $pageSize = $req["n"] ?? -1;
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $req["start"] ?? -1;
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $req["page"] ?? -1;
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($req["s"] ?? "");
        $userFilter = Decrypt($req["f"] ?? "");
        $userOrderBy = Decrypt($req["o"] ?? "");
        $keys = $req["keys"] ?? null;
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $req["v0"] ?? $req["lookupValue"] ?? "";
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $req["v" . $i] ?? "";
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, $response); // Use settings from current page
    }

    // Options
    public $SearchOptions; // Search options
    public $FilterOptions; // Filter options
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = "";
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $SearchCommand = false;
    public $Events;
    public $DefaultOrderBy = "`Start` ASC";
    public $CalendarOptions; // Calendar options

    /**
     * Full calendar event object fields (see https://fullcalendar.io/docs/event-object)
     *
     * @var array
     */
    public $EventFields = [
        "id" => "Id",
        "groupId" => "GroupId",
        "allDay" => "AllDay",
        "start" => "Start",
        "end" => "End",
        "startStr" => null,
        "endStr" => null,
        "title" => "Title",
        "url" => "Url",
        "classNames" => "ClassNames",
        "editable" => null,
        "startEditable" => null,
        "durationEditable" => null,
        "resourceEditable" => null,
        "display" => "Display",
        "overlap" => null,
        "constraint" => null,
        "backgroundColor" => "BackgroundColor",
        "borderColor" => null,
        "textColor" => null,
        "extendedProps" => null,
        "source" => null,
        "description" => "Description",
    ];

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::NAME);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Set up View/Add/Edit/Delete URL
        $this->ViewUrl = $Security->canView() ? "calendar1view" : "";
        $this->AddUrl = $Security->canAdd() ? "calendar1add" : "";
        $this->EditUrl = $Security->canEdit() ? "calendar1edit" : "";
        $this->CopyUrl = $Security->canAdd() ? "calendar1add" : "";
        $this->DeleteUrl = $Security->canDelete() ? "calendar1delete" : "";

        // Check if search command
        $this->SearchCommand = Get("cmd") == "search";

        // Load custom filters
        $this->pageFilterLoad();

        // Extended filter
        $extendedFilter = "";

        // Restore filter list
        $this->restoreFilterList();

        // Build extended filter
        $extendedFilter = $this->getExtendedFilter();
        AddFilter($this->SearchWhere, $extendedFilter);

        // Setup other options
        $this->setupOtherOptions();

        // Call Page Selecting event
        $this->pageSelecting($this->SearchWhere);

        // Set up search panel class
        if ($this->SearchWhere != "") {
            AppendClass($this->SearchPanelClass, "show");
        }

        // Update filter
        AddFilter($this->Filter, $this->SearchWhere);
        $sql = $this->buildSelectSql($this->getSqlSelect(), $this->getSqlFrom(), $this->getSqlWhere(), "", "", $this->DefaultOrderBy, $this->Filter, "");
        $result = $sql->executeQuery();
        $this->Events = $result->fetchAllAssociative();
        if (count($this->Events) == 0) {
            $this->setWarningMessage($Language->phrase("NoRecord"));
        } elseif ($this->SearchWhere != "") { // Set initial date for first record
            $this->CalendarOptions->set("initialDate", $this->Events[0]['Start']);
        }

        // Search options
        $this->setupSearchOptions();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            DispatchEvent(new PageRenderingEvent($this), PageRenderingEvent::NAME);

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Get event field name
    public function getEventFieldName($id)
    {
        return $this->EventFields[$id] ?? "";
    }

    /**
     * Get events
     *
     * Note: Use ISO8601 string for date fields so FullCalendar can parse (see https://fullcalendar.io/docs/date-parsing)
     * No UTC offset specified, parsing will depend on the default time zone 'local' (see https://fullcalendar.io/docs/timeZone)
     * @return array
     */
    public function getEvents()
    {
        global $CurrentLocale;
        $locale = $CurrentLocale; // Backup current locale
        $CurrentLocale = "en-US"; // Format dates as en-US
        $this->Fields['Start']->FormatPattern = "yyyy-MM-dd'T'HH:mm:ss";
        $this->Fields['End']->FormatPattern = "yyyy-MM-dd'T'HH:mm:ss";
        try {
            return array_reduce($this->Events, function($ar, $event) {
                $this->loadRowValues($event);
                $this->resetAttributes();
                $this->RowType = RowType::VIEW;
                $this->renderRow();
                $evt = $this->getEvent();
                if ($this->eventAdding($evt)) {
                    $ar[] = $evt;
                }
                return $ar;
            }, []);
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $CurrentLocale = $locale; // Restore current locale
        }
    }

    /**
     * Load row values from record
     *
     * @param array $row Record
     * @return void
     */
    protected function loadRowValues($row)
    {
        $this->Id->setDbValue($row['Id']);
        $this->_Title->setDbValue($row['Title']);
        $this->Start->setDbValue($row['Start']);
        $this->End->setDbValue($row['End']);
        $this->AllDay->setDbValue($row['AllDay']);
        $this->Description->setDbValue($row['Description']);
        $this->GroupId->setDbValue($row['GroupId']);
        $this->Url->setDbValue($row['Url']);
        $this->ClassNames->setDbValue($row['ClassNames']);
        $this->Display->setDbValue($row['Display']);
        $this->BackgroundColor->setDbValue($row['BackgroundColor']);
    }

    /**
     * Render row
     *
     * @return void
     */
    public function renderRow()
    {
        global $Security, $Language;
        $conn = $this->getConnection();

        // Call Row_Rendering event
        $this->rowRendering();

        // Id

        // Title

        // Start

        // End

        // AllDay

        // Description

        // GroupId

        // Url

        // ClassNames

        // Display

        // BackgroundColor
        if ($this->RowType == RowType::SEARCH) {
            // Title
            $this->_Title->setupEditAttributes();
            if (!$this->_Title->Raw) {
                $this->_Title->AdvancedSearch->SearchValue = HtmlDecode($this->_Title->AdvancedSearch->SearchValue);
            }
            $this->_Title->EditValue = HtmlEncode($this->_Title->AdvancedSearch->SearchValue);
            $this->_Title->PlaceHolder = RemoveHtml($this->_Title->caption());

            // Description
            $this->Description->setupEditAttributes();
            $this->Description->EditValue = HtmlEncode($this->Description->AdvancedSearch->SearchValue);
            $this->Description->PlaceHolder = RemoveHtml($this->Description->caption());
        } elseif ($this->RowType == RowType::VIEW) {
            // Id
            $this->Id->ViewValue = $this->Id->CurrentValue;

            // Title
            $this->_Title->ViewValue = $this->_Title->CurrentValue;

            // Start
            $this->Start->ViewValue = $this->Start->CurrentValue;
            $this->Start->ViewValue = FormatDateTime($this->Start->ViewValue, $this->Start->formatPattern());

            // End
            $this->End->ViewValue = $this->End->CurrentValue;
            $this->End->ViewValue = FormatDateTime($this->End->ViewValue, $this->End->formatPattern());

            // AllDay
            if (ConvertToBool($this->AllDay->CurrentValue)) {
                $this->AllDay->ViewValue = $this->AllDay->tagCaption(1) != "" ? $this->AllDay->tagCaption(1) : "Yes";
            } else {
                $this->AllDay->ViewValue = $this->AllDay->tagCaption(2) != "" ? $this->AllDay->tagCaption(2) : "No";
            }

            // Description
            $this->Description->ViewValue = $this->Description->CurrentValue;
            if ($this->Description->ViewValue != null) {
                $this->Description->ViewValue = str_replace(["\r\n", "\n", "\r"], "<br>", $this->Description->ViewValue);
            }

            // GroupId
            $this->GroupId->ViewValue = $this->GroupId->CurrentValue;

            // Url
            $this->Url->ViewValue = $this->Url->CurrentValue;

            // ClassNames
            $this->ClassNames->ViewValue = $this->ClassNames->CurrentValue;

            // Display
            if (strval($this->Display->CurrentValue) != "") {
                $this->Display->ViewValue = $this->Display->optionCaption($this->Display->CurrentValue);
            } else {
                $this->Display->ViewValue = null;
            }

            // BackgroundColor
            $this->BackgroundColor->ViewValue = $this->BackgroundColor->CurrentValue;

            // Id
            $this->Id->HrefValue = "";
            $this->Id->TooltipValue = "";

            // Title
            $this->_Title->HrefValue = "";
            $this->_Title->TooltipValue = "";

            // Start
            $this->Start->HrefValue = "";
            $this->Start->TooltipValue = "";

            // End
            $this->End->HrefValue = "";
            $this->End->TooltipValue = "";

            // AllDay
            $this->AllDay->HrefValue = "";
            $this->AllDay->TooltipValue = "";

            // Description
            $this->Description->HrefValue = "";
            $this->Description->TooltipValue = "";

            // GroupId
            $this->GroupId->HrefValue = "";
            $this->GroupId->TooltipValue = "";

            // Url
            $this->Url->HrefValue = "";
            $this->Url->TooltipValue = "";

            // ClassNames
            $this->ClassNames->HrefValue = "";
            $this->ClassNames->TooltipValue = "";

            // Display
            $this->Display->HrefValue = "";
            $this->Display->TooltipValue = "";

            // BackgroundColor
            $this->BackgroundColor->HrefValue = "";
            $this->BackgroundColor->TooltipValue = "";
        }

        // Call Row_Rendered event
        $this->rowRendered();
    }

    /**
     * Get event
     *
     * @return array Output data
     */
    protected function getEvent()
    {
        $eventListFields = ["Id","Title","Start","End","AllDay","Description","GroupId","Url","ClassNames","Display","BackgroundColor"];
        $event = [];
        // Default permissions for event
        $event["_view"] = !EmptyValue($this->ViewUrl);
        $event["_edit"] = !EmptyValue($this->EditUrl);
        $event["_copy"] = !EmptyValue($this->CopyUrl);
        $event["_delete"] = !EmptyValue($this->DeleteUrl);
        foreach ($this->Fields as $fld) {
            if ($fld->DataType == DataType::BLOB || !in_array($fld->Name, $eventListFields)) { // Skip blob fields / non list fields
                continue;
            }
            $name = array_search($fld->Name, $this->EventFields) ?: $fld->Name;
            $value = $fld->isBoolean()
                ? ConvertToBool($fld->CurrentValue)
                : (is_null($fld->CurrentValue) ? "" : $fld->getViewValue());
            $event[$name] = $value;
        }
        return $event;
    }

    /**
     * Get calendar options As JSON
     *
     * @return string
     */
    public function getCalendarOptions()
    {
        global $CurrentLocale, $TIME_FORMAT;
        $locale = $CurrentLocale; // Backup current locale
        $CurrentLocale = "en-US"; // Format dates as en-US
        try {
            $this->CalendarOptions->import([
                "selectable" => true,
                "direction" => IsRTL() ? "rtl" : "ltr",
                "locale" => CurrentLanguageID(),
                "events" => $this->getEvents()
            ]);
            if ($this->CalendarOptions->has("initialDate")) {
                $this->CalendarOptions->set("initialDate", FormatDateTime($this->CalendarOptions->get("initialDate"), "yyyy-MM-dd")); // yyyy-MM-dd format (e.g. 2024-09-30)
            }
            return ArrayToJson([
                "fullCalendarOptions" => $this->CalendarOptions->export(),
                "ajax" => $this->UseAjaxActions,
                "updateTable" => $this->UpdateTable,
                "viewUrl" => $this->ViewUrl,
                "editUrl" => $this->EditUrl,
                "deleteUrl" => $this->DeleteUrl,
                "addUrl" => $this->AddUrl,
                "copyUrl" => $this->CopyUrl,
                "eventFields" => $this->EventFields
            ]);
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $CurrentLocale = $locale; // Restore current locale
        }
    }

    // Set up search options
    protected function setupSearchOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl(false);
        $this->SearchOptions = new ListOptions(TagClassName: "ew-search-option");

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : " active";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"fCalendar1srch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        if ($this->UseCustomTemplate || !$this->UseAjaxActions) {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" data-ew-action=\"refresh\" data-url=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        }
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction && $this->CurrentAction != "search") {
            $this->SearchOptions->hideAllOptions();
        }
        if (!$Security->canSearch()) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }
    }

    // Check if any search fields
    public function hasSearchFields()
    {
        return $this->_Title->Visible || $this->Description->Visible;
    }

    // Render search options
    protected function renderSearchOptions()
    {
        if (!$this->hasSearchFields() && $this->SearchOptions["searchtoggle"]) {
            $this->SearchOptions["searchtoggle"]->Visible = false;
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home2");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset(all)
        $Breadcrumb->add("calendar", $this->TableVar, $url, "", $this->TableVar, true);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_AllDay":
                    break;
                case "x_ClassNames":
                    break;
                case "x_Display":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fCalendar1srch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fCalendar1srch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Set up starting group
    protected function setupStartGroup()
    {
        // Exit if no groups
        if ($this->DisplayGroups == 0) {
            return;
        }
        $startGrp = Param(Config("TABLE_START_GROUP"));
        $pageNo = Param(Config("TABLE_PAGE_NUMBER"));

        // Check for a 'start' parameter
        if ($startGrp !== null) {
            $this->StartGroup = $startGrp;
            $this->setStartGroup($this->StartGroup);
        } elseif ($pageNo !== null) {
            $pageNo = ParseInteger($pageNo);
            if (is_numeric($pageNo)) {
                $this->StartGroup = ($pageNo - 1) * $this->DisplayGroups + 1;
                if ($this->StartGroup <= 0) {
                    $this->StartGroup = 1;
                } elseif ($this->StartGroup >= intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1) {
                    $this->StartGroup = intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1;
                }
                $this->setStartGroup($this->StartGroup);
            } else {
                $this->StartGroup = $this->getStartGroup();
            }
        } else {
            $this->StartGroup = $this->getStartGroup();
        }

        // Check if correct start group counter
        if (!is_numeric($this->StartGroup) || intval($this->StartGroup) <= 0) { // Avoid invalid start group counter
            $this->StartGroup = 1; // Reset start group counter
            $this->setStartGroup($this->StartGroup);
        } elseif (intval($this->StartGroup) > intval($this->TotalGroups)) { // Avoid starting group > total groups
            $this->StartGroup = intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1; // Point to last page first group
            $this->setStartGroup($this->StartGroup);
        } elseif (($this->StartGroup - 1) % $this->DisplayGroups != 0) {
            $this->StartGroup = intval(($this->StartGroup - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1; // Point to page boundary
            $this->setStartGroup($this->StartGroup);
        }
    }

    // Reset pager
    protected function resetPager()
    {
        // Reset start position (reset command)
        $this->StartGroup = 1;
        $this->setStartGroup($this->StartGroup);
    }

    // Get sort parameters based on sort links clicked
    protected function getSort()
    {
        if ($this->DrillDown) {
            return "";
        }
        $resetSort = Param("cmd") === "resetsort";
        $orderBy = Param("order", "");
        $orderType = Param("ordertype", "");

        // Check for a resetsort command
        if ($resetSort) {
            $this->setOrderBy("");
            $this->setStartGroup(1);
            $this->Id->setSort("");
            $this->_Title->setSort("");
            $this->Start->setSort("");
            $this->End->setSort("");
            $this->AllDay->setSort("");
            $this->Description->setSort("");
            $this->GroupId->setSort("");
            $this->Url->setSort("");
            $this->ClassNames->setSort("");
            $this->Display->setSort("");
            $this->BackgroundColor->setSort("");

        // Check for an Order parameter
        } elseif ($orderBy != "") {
            $this->CurrentOrder = $orderBy;
            $this->CurrentOrderType = $orderType;
            $this->updateSort($this->Id); // Id
            $this->updateSort($this->_Title); // Title
            $this->updateSort($this->Start); // Start
            $this->updateSort($this->End); // End
            $this->updateSort($this->AllDay); // AllDay
            $this->updateSort($this->Description); // Description
            $this->updateSort($this->GroupId); // GroupId
            $this->updateSort($this->Url); // Url
            $this->updateSort($this->ClassNames); // ClassNames
            $this->updateSort($this->Display); // Display
            $this->updateSort($this->BackgroundColor); // BackgroundColor
            $sortSql = $this->sortSql();
            $this->setOrderBy($sortSql);
            $this->setStartGroup(1);
        }
        return $this->getOrderBy();
    }

    // Return extended filter
    protected function getExtendedFilter()
    {
        $filter = "";
        if ($this->DrillDown) {
            return "";
        }
        $restoreSession = false;
        $restoreDefault = false;
        // Reset search command
        if (Get("cmd") == "reset") {
            // Set default values
            $this->_Title->AdvancedSearch->unsetSession();
            $this->Description->AdvancedSearch->unsetSession();
            $restoreDefault = true;
        } else {
            $restoreSession = !$this->SearchCommand;

            // Field Title
            if ($this->_Title->AdvancedSearch->get()) {
            }

            // Field Description
            if ($this->Description->AdvancedSearch->get()) {
            }
            if (!$this->validateForm()) {
                return $filter;
            }
        }

        // Restore session
        if ($restoreSession) {
            $restoreDefault = true;
            if ($this->_Title->AdvancedSearch->issetSession()) { // Field Title
                $this->_Title->AdvancedSearch->load();
                $restoreDefault = false;
            }
            if ($this->Description->AdvancedSearch->issetSession()) { // Field Description
                $this->Description->AdvancedSearch->load();
                $restoreDefault = false;
            }
        }

        // Restore default
        if ($restoreDefault) {
            $this->loadDefaultFilters();
        }

        // Call page filter validated event
        $this->pageFilterValidated();

        // Build SQL and save to session
        $this->buildExtendedFilter($this->_Title, $filter, false, true); // Field Title
        $this->_Title->AdvancedSearch->save();
        $this->buildExtendedFilter($this->Description, $filter, false, true); // Field Description
        $this->Description->AdvancedSearch->save();
        return $filter;
    }

    // Build dropdown filter
    protected function buildDropDownFilter(&$fld, &$filterClause, $default = false, $saveFilter = false)
    {
        $fldVal = $default ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $fldOpr = $default ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
        $fldVal2 = $default ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
        if (!EmptyValue($fld->DateFilter)) {
            $fldVal2 = "";
        } elseif ($fld->UseFilter) {
            $fldOpr = "";
            $fldVal2 = "";
        }
        $sql = "";
        if (is_array($fldVal)) {
            foreach ($fldVal as $val) {
                $wrk = DropDownFilter($fld, $val, $fldOpr, $this->Dbid);

                // Call Page Filtering event
                if (StartsString("@@", $val)) {
                    $this->pageFiltering($fld, $wrk, "custom", substr($val, 2));
                } else {
                    $this->pageFiltering($fld, $wrk, "dropdown", $fldOpr, $val);
                }
                AddFilter($sql, $wrk, "OR");
            }
        } else {
            $sql = DropDownFilter($fld, $fldVal, $fldOpr, $this->Dbid, $fldVal2);

            // Call Page Filtering event
            if (StartsString("@@", $fldVal)) {
                $this->pageFiltering($fld, $sql, "custom", substr($fldVal, 2));
            } else {
                $this->pageFiltering($fld, $sql, "dropdown", $fldOpr, $fldVal, "", "", $fldVal2);
            }
        }
        if ($sql != "") {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
            AddFilter($filterClause, $sql, $cond);
            if ($saveFilter) {
                $fld->CurrentFilter = $sql;
            }
        }
    }

    // Build extended filter
    protected function buildExtendedFilter(&$fld, &$filterClause, $default = false, $saveFilter = false)
    {
        $wrk = GetReportFilter($fld, $default, $this->Dbid);
        if (!$default) {
            $this->pageFiltering($fld, $wrk, "extended", $fld->AdvancedSearch->SearchOperator, $fld->AdvancedSearch->SearchValue, $fld->AdvancedSearch->SearchCondition, $fld->AdvancedSearch->SearchOperator2, $fld->AdvancedSearch->SearchValue2);
        }
        if ($wrk != "") {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
            AddFilter($filterClause, $wrk, $cond);
            if ($saveFilter) {
                $fld->CurrentFilter = $wrk;
            }
        }
    }

    // Get drop down value from querystring
    protected function getDropDownValue(&$fld)
    {
        if (IsPost()) {
            return false; // Skip post back
        }
        $res = false;
        $parm = $fld->Param;
        $opr = Get("z_$parm");
        if ($opr !== null) {
            $fld->AdvancedSearch->SearchOperator = $opr;
        }
        $val = Get("x_$parm");
        if ($val !== null) {
            if (is_array($val)) {
                $val = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $val);
            }
            $fld->AdvancedSearch->setSearchValue($val);
            $res = true;
        }
        $val2 = Get("y_$parm");
        if ($val2 !== null) {
            if (is_array($val2)) {
                $val2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $val2);
            }
            $fld->AdvancedSearch->setSearchValue2($val2);
            $res = true;
        }
        return $res;
    }

    // Dropdown filter exist
    protected function dropDownFilterExist(&$fld)
    {
        $wrk = "";
        $this->buildDropDownFilter($fld, $wrk);
        return ($wrk != "");
    }

    // Extended filter exist
    protected function extendedFilterExist(&$fld)
    {
        $extWrk = "";
        $this->buildExtendedFilter($fld, $extWrk);
        return ($extWrk != "");
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Load default value for filters
    protected function loadDefaultFilters()
    {
        // Field Title
        $this->_Title->AdvancedSearch->loadDefault();

        // Field Description
        $this->Description->AdvancedSearch->loadDefault();
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";

        // Field Title
        $extWrk = "";
        $this->buildExtendedFilter($this->_Title, $extWrk);
        $filter = "";
        if ($extWrk != "") {
            $filter .= "<span class=\"ew-filter-value\">$extWrk</span>";
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->_Title->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field Description
        $extWrk = "";
        $this->buildExtendedFilter($this->Description, $extWrk);
        $filter = "";
        if ($extWrk != "") {
            $filter .= "<span class=\"ew-filter-value\">$extWrk</span>";
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->Description->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Show Filters
        if ($filterList != "") {
            $message = "<div id=\"ew-filter-list\" class=\"callout callout-info d-table\"><div id=\"ew-current-filters\">" .
                $Language->phrase("CurrentFilters") . "</div>" . $filterList . "</div>";
            $this->messageShowing($message, "");
            Write($message);
        } else { // Output empty tag
            Write("<div id=\"ew-filter-list\"></div>");
        }
    }

    // Get list of filters
    public function getFilterList()
    {
        // Initialize
        $filterList = "";
        $savedFilterList = "";

        // Field Title
        $wrk = "";
        if ($this->_Title->AdvancedSearch->SearchValue != "" || $this->_Title->AdvancedSearch->SearchValue2 != "") {
            $wrk = "\"x__Title\":\"" . JsEncode($this->_Title->AdvancedSearch->SearchValue) . "\"," .
                "\"z__Title\":\"" . JsEncode($this->_Title->AdvancedSearch->SearchOperator) . "\"," .
                "\"v__Title\":\"" . JsEncode($this->_Title->AdvancedSearch->SearchCondition) . "\"," .
                "\"y__Title\":\"" . JsEncode($this->_Title->AdvancedSearch->SearchValue2) . "\"," .
                "\"w__Title\":\"" . JsEncode($this->_Title->AdvancedSearch->SearchOperator2) . "\"";
        }
        if ($wrk != "") {
            if ($filterList != "") {
                $filterList .= ",";
            }
            $filterList .= $wrk;
        }

        // Field Description
        $wrk = "";
        if ($this->Description->AdvancedSearch->SearchValue != "" || $this->Description->AdvancedSearch->SearchValue2 != "") {
            $wrk = "\"x_Description\":\"" . JsEncode($this->Description->AdvancedSearch->SearchValue) . "\"," .
                "\"z_Description\":\"" . JsEncode($this->Description->AdvancedSearch->SearchOperator) . "\"," .
                "\"v_Description\":\"" . JsEncode($this->Description->AdvancedSearch->SearchCondition) . "\"," .
                "\"y_Description\":\"" . JsEncode($this->Description->AdvancedSearch->SearchValue2) . "\"," .
                "\"w_Description\":\"" . JsEncode($this->Description->AdvancedSearch->SearchOperator2) . "\"";
        }
        if ($wrk != "") {
            if ($filterList != "") {
                $filterList .= ",";
            }
            $filterList .= $wrk;
        }

        // Return filter list in json
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd", "") != "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter", ""), true);
        return $this->setupFilterList($filter);
    }

    // Setup list of filters
    protected function setupFilterList($filter)
    {
        if (!is_array($filter)) {
            return false;
        }

        // Field Title
        if (!$this->_Title->AdvancedSearch->get($filter)) {
            $this->_Title->AdvancedSearch->loadDefault(); // Clear filter
        }
        $this->_Title->AdvancedSearch->save();

        // Field Description
        if (!$this->Description->AdvancedSearch->get($filter)) {
            $this->Description->AdvancedSearch->loadDefault(); // Clear filter
        }
        $this->Description->AdvancedSearch->save();
        return true;
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == "success") {
            //$msg = "your success message";
        } elseif ($type == "failure") {
            //$msg = "your failure message";
        } elseif ($type == "warning") {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Page Selecting event
    public function pageSelecting(&$filter)
    {
        // Enter your code here
    }

    // Load Filters event
    public function pageFilterLoad()
    {
        // Enter your code here
        // Example: Register/Unregister Custom Extended Filter
        //RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
        //RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
        //UnregisterFilter($this-><Field>, 'StartsWithA');
    }

    // Page Filter Validated event
    public function pageFilterValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Page Filtering event
    public function pageFiltering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "")
    {
        // Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
        //if ($typ == "dropdown" && $fld->Name == "MyField") // Dropdown filter
        //    $filter = "..."; // Modify the filter
        //if ($typ == "extended" && $fld->Name == "MyField") // Extended filter
        //    $filter = "..."; // Modify the filter
        //if ($typ == "custom" && $opr == "..." && $fld->Name == "MyField") // Custom filter, $opr is the custom filter ID
        //    $filter = "..."; // Modify the filter
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }

    // Event Adding event (Calendar Report)
    public function eventAdding(&$event)
    {
        // Example:
        // var_dump($event);
        // if (strtotime($event["start"]) < time()) { // Check past event
        //     // $event["_view"] = false; // Disable view
        //     $event["_edit"] = false; // Disable edit
        //     $event["_copy"] = false; // Disable copy
        //     $event["_delete"] = false; // Disable delete
        //     // return false; // Return false to hide event
        // }
        return true;
    }
}
