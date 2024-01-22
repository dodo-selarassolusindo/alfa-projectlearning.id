<?php

namespace PHPMaker2024\demo2024;

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
class DjiAdd extends Dji
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "DjiAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "djiadd";

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
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
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

    // Set field visibility
    public function setVisibility()
    {
        $this->ID->Visible = false;
        $this->Date->setVisibility();
        $this->Open->setVisibility();
        $this->High->setVisibility();
        $this->Low->setVisibility();
        $this->Close->setVisibility();
        $this->Volume->setVisibility();
        $this->AdjClose->setVisibility();
        $this->Name->setVisibility();
        $this->Name2->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'dji';
        $this->TableName = 'dji';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (dji)
        if (!isset($GLOBALS["dji"]) || $GLOBALS["dji"]::class == PROJECT_NAMESPACE . "dji") {
            $GLOBALS["dji"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'dji');
        }

        // Start timer
        $DebugTimer = Container("debug.timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
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

        // Close connection
        CloseConnections();

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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $pageName = GetPageName($url);
                $result = ["url" => GetUrl($url), "modal" => "1"];  // Assume return to modal for simplicity
                if (
                    SameString($pageName, GetPageName($this->getListUrl())) ||
                    SameString($pageName, GetPageName($this->getViewUrl())) ||
                    SameString($pageName, GetPageName(CurrentMasterTable()?->getViewUrl() ?? ""))
                ) { // List / View / Master View page
                    if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                        $result["caption"] = $this->getModalCaption($pageName);
                        $result["view"] = SameString($pageName, "djiview"); // If View page, no primary button
                    } else { // List page
                        $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                        $this->clearFailureMessage();
                    }
                } else { // Other pages (add messages and then clear messages)
                    $result = array_merge($this->getMessages(), ["modal" => "1"]);
                    $this->clearMessages();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from result set
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Result set
            while ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($row);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DataType::BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['ID'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->ID->Visible = false;
        }
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
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $CopyRecord;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Load user profile
        if (IsLoggedIn()) {
            Profile()->setUserName(CurrentUserName())->loadFromStorage();
        }

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::NAME);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("ID") ?? Route("ID")) !== null) {
                $this->ID->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $rsold = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$rsold) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("djilist"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "djilist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "djiview") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "djilist") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "djilist"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson(["success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage()]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = RowType::ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Date' first before field var 'x_Date'
        $val = $CurrentForm->hasValue("Date") ? $CurrentForm->getValue("Date") : $CurrentForm->getValue("x_Date");
        if (!$this->Date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Date->Visible = false; // Disable update for API request
            } else {
                $this->Date->setFormValue($val, true, $validate);
            }
            $this->Date->CurrentValue = UnFormatDateTime($this->Date->CurrentValue, $this->Date->formatPattern());
        }

        // Check field name 'Open' first before field var 'x_Open'
        $val = $CurrentForm->hasValue("Open") ? $CurrentForm->getValue("Open") : $CurrentForm->getValue("x_Open");
        if (!$this->Open->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Open->Visible = false; // Disable update for API request
            } else {
                $this->Open->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'High' first before field var 'x_High'
        $val = $CurrentForm->hasValue("High") ? $CurrentForm->getValue("High") : $CurrentForm->getValue("x_High");
        if (!$this->High->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->High->Visible = false; // Disable update for API request
            } else {
                $this->High->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Low' first before field var 'x_Low'
        $val = $CurrentForm->hasValue("Low") ? $CurrentForm->getValue("Low") : $CurrentForm->getValue("x_Low");
        if (!$this->Low->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Low->Visible = false; // Disable update for API request
            } else {
                $this->Low->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Close' first before field var 'x_Close'
        $val = $CurrentForm->hasValue("Close") ? $CurrentForm->getValue("Close") : $CurrentForm->getValue("x_Close");
        if (!$this->Close->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Close->Visible = false; // Disable update for API request
            } else {
                $this->Close->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Volume' first before field var 'x_Volume'
        $val = $CurrentForm->hasValue("Volume") ? $CurrentForm->getValue("Volume") : $CurrentForm->getValue("x_Volume");
        if (!$this->Volume->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Volume->Visible = false; // Disable update for API request
            } else {
                $this->Volume->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Adj Close' first before field var 'x_AdjClose'
        $val = $CurrentForm->hasValue("Adj Close") ? $CurrentForm->getValue("Adj Close") : $CurrentForm->getValue("x_AdjClose");
        if (!$this->AdjClose->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->AdjClose->Visible = false; // Disable update for API request
            } else {
                $this->AdjClose->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Name' first before field var 'x_Name'
        $val = $CurrentForm->hasValue("Name") ? $CurrentForm->getValue("Name") : $CurrentForm->getValue("x_Name");
        if (!$this->Name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Name->Visible = false; // Disable update for API request
            } else {
                $this->Name->setFormValue($val, true, $validate);
            }
            $this->Name->CurrentValue = UnFormatDateTime($this->Name->CurrentValue, $this->Name->formatPattern());
        }

        // Check field name 'Name2' first before field var 'x_Name2'
        $val = $CurrentForm->hasValue("Name2") ? $CurrentForm->getValue("Name2") : $CurrentForm->getValue("x_Name2");
        if (!$this->Name2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Name2->Visible = false; // Disable update for API request
            } else {
                $this->Name2->setFormValue($val);
            }
        }

        // Check field name 'ID' first before field var 'x_ID'
        $val = $CurrentForm->hasValue("ID") ? $CurrentForm->getValue("ID") : $CurrentForm->getValue("x_ID");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->Date->CurrentValue = $this->Date->FormValue;
        $this->Date->CurrentValue = UnFormatDateTime($this->Date->CurrentValue, $this->Date->formatPattern());
        $this->Open->CurrentValue = $this->Open->FormValue;
        $this->High->CurrentValue = $this->High->FormValue;
        $this->Low->CurrentValue = $this->Low->FormValue;
        $this->Close->CurrentValue = $this->Close->FormValue;
        $this->Volume->CurrentValue = $this->Volume->FormValue;
        $this->AdjClose->CurrentValue = $this->AdjClose->FormValue;
        $this->Name->CurrentValue = $this->Name->FormValue;
        $this->Name->CurrentValue = UnFormatDateTime($this->Name->CurrentValue, $this->Name->formatPattern());
        $this->Name2->CurrentValue = $this->Name2->FormValue;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from result set or record
     *
     * @param array $row Record
     * @return void
     */
    public function loadRowValues($row = null)
    {
        $row = is_array($row) ? $row : $this->newRow();

        // Call Row Selected event
        $this->rowSelected($row);
        $this->ID->setDbValue($row['ID']);
        $this->Date->setDbValue($row['Date']);
        $this->Open->setDbValue($row['Open']);
        $this->High->setDbValue($row['High']);
        $this->Low->setDbValue($row['Low']);
        $this->Close->setDbValue($row['Close']);
        $this->Volume->setDbValue($row['Volume']);
        $this->AdjClose->setDbValue($row['Adj Close']);
        $this->Name->setDbValue($row['Name']);
        $this->Name2->setDbValue($row['Name2']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ID'] = $this->ID->DefaultValue;
        $row['Date'] = $this->Date->DefaultValue;
        $row['Open'] = $this->Open->DefaultValue;
        $row['High'] = $this->High->DefaultValue;
        $row['Low'] = $this->Low->DefaultValue;
        $row['Close'] = $this->Close->DefaultValue;
        $row['Volume'] = $this->Volume->DefaultValue;
        $row['Adj Close'] = $this->AdjClose->DefaultValue;
        $row['Name'] = $this->Name->DefaultValue;
        $row['Name2'] = $this->Name2->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = ExecuteQuery($sql, $conn);
            if ($row = $rs->fetch()) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // ID
        $this->ID->RowCssClass = "row";

        // Date
        $this->Date->RowCssClass = "row";

        // Open
        $this->Open->RowCssClass = "row";

        // High
        $this->High->RowCssClass = "row";

        // Low
        $this->Low->RowCssClass = "row";

        // Close
        $this->Close->RowCssClass = "row";

        // Volume
        $this->Volume->RowCssClass = "row";

        // Adj Close
        $this->AdjClose->RowCssClass = "row";

        // Name
        $this->Name->RowCssClass = "row";

        // Name2
        $this->Name2->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // ID
            $this->ID->ViewValue = $this->ID->CurrentValue;

            // Date
            $this->Date->ViewValue = $this->Date->CurrentValue;
            $this->Date->ViewValue = FormatDateTime($this->Date->ViewValue, $this->Date->formatPattern());

            // Open
            $this->Open->ViewValue = $this->Open->CurrentValue;
            $this->Open->ViewValue = FormatNumber($this->Open->ViewValue, $this->Open->formatPattern());

            // High
            $this->High->ViewValue = $this->High->CurrentValue;
            $this->High->ViewValue = FormatNumber($this->High->ViewValue, $this->High->formatPattern());

            // Low
            $this->Low->ViewValue = $this->Low->CurrentValue;
            $this->Low->ViewValue = FormatNumber($this->Low->ViewValue, $this->Low->formatPattern());

            // Close
            $this->Close->ViewValue = $this->Close->CurrentValue;
            $this->Close->ViewValue = FormatNumber($this->Close->ViewValue, $this->Close->formatPattern());

            // Volume
            $this->Volume->ViewValue = $this->Volume->CurrentValue;
            $this->Volume->ViewValue = FormatNumber($this->Volume->ViewValue, $this->Volume->formatPattern());

            // Adj Close
            $this->AdjClose->ViewValue = $this->AdjClose->CurrentValue;
            $this->AdjClose->ViewValue = FormatNumber($this->AdjClose->ViewValue, $this->AdjClose->formatPattern());

            // Name
            $this->Name->ViewValue = $this->Name->CurrentValue;
            $this->Name->ViewValue = FormatDateTime($this->Name->ViewValue, $this->Name->formatPattern());

            // Name2
            $this->Name2->ViewValue = $this->Name2->CurrentValue;

            // Date
            $this->Date->HrefValue = "";

            // Open
            $this->Open->HrefValue = "";

            // High
            $this->High->HrefValue = "";

            // Low
            $this->Low->HrefValue = "";

            // Close
            $this->Close->HrefValue = "";

            // Volume
            $this->Volume->HrefValue = "";

            // Adj Close
            $this->AdjClose->HrefValue = "";

            // Name
            $this->Name->HrefValue = "";

            // Name2
            $this->Name2->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // Date
            $this->Date->setupEditAttributes();
            $this->Date->EditValue = HtmlEncode(FormatDateTime($this->Date->CurrentValue, $this->Date->formatPattern()));
            $this->Date->PlaceHolder = RemoveHtml($this->Date->caption());

            // Open
            $this->Open->setupEditAttributes();
            $this->Open->EditValue = $this->Open->CurrentValue;
            $this->Open->PlaceHolder = RemoveHtml($this->Open->caption());
            if (strval($this->Open->EditValue) != "" && is_numeric($this->Open->EditValue)) {
                $this->Open->EditValue = FormatNumber($this->Open->EditValue, null);
            }

            // High
            $this->High->setupEditAttributes();
            $this->High->EditValue = $this->High->CurrentValue;
            $this->High->PlaceHolder = RemoveHtml($this->High->caption());
            if (strval($this->High->EditValue) != "" && is_numeric($this->High->EditValue)) {
                $this->High->EditValue = FormatNumber($this->High->EditValue, null);
            }

            // Low
            $this->Low->setupEditAttributes();
            $this->Low->EditValue = $this->Low->CurrentValue;
            $this->Low->PlaceHolder = RemoveHtml($this->Low->caption());
            if (strval($this->Low->EditValue) != "" && is_numeric($this->Low->EditValue)) {
                $this->Low->EditValue = FormatNumber($this->Low->EditValue, null);
            }

            // Close
            $this->Close->setupEditAttributes();
            $this->Close->EditValue = $this->Close->CurrentValue;
            $this->Close->PlaceHolder = RemoveHtml($this->Close->caption());
            if (strval($this->Close->EditValue) != "" && is_numeric($this->Close->EditValue)) {
                $this->Close->EditValue = FormatNumber($this->Close->EditValue, null);
            }

            // Volume
            $this->Volume->setupEditAttributes();
            $this->Volume->EditValue = $this->Volume->CurrentValue;
            $this->Volume->PlaceHolder = RemoveHtml($this->Volume->caption());
            if (strval($this->Volume->EditValue) != "" && is_numeric($this->Volume->EditValue)) {
                $this->Volume->EditValue = FormatNumber($this->Volume->EditValue, null);
            }

            // Adj Close
            $this->AdjClose->setupEditAttributes();
            $this->AdjClose->EditValue = $this->AdjClose->CurrentValue;
            $this->AdjClose->PlaceHolder = RemoveHtml($this->AdjClose->caption());
            if (strval($this->AdjClose->EditValue) != "" && is_numeric($this->AdjClose->EditValue)) {
                $this->AdjClose->EditValue = FormatNumber($this->AdjClose->EditValue, null);
            }

            // Name
            $this->Name->setupEditAttributes();
            $this->Name->EditValue = HtmlEncode(FormatDateTime($this->Name->CurrentValue, $this->Name->formatPattern()));
            $this->Name->PlaceHolder = RemoveHtml($this->Name->caption());

            // Name2
            $this->Name2->setupEditAttributes();
            if (!$this->Name2->Raw) {
                $this->Name2->CurrentValue = HtmlDecode($this->Name2->CurrentValue);
            }
            $this->Name2->EditValue = HtmlEncode($this->Name2->CurrentValue);
            $this->Name2->PlaceHolder = RemoveHtml($this->Name2->caption());

            // Add refer script

            // Date
            $this->Date->HrefValue = "";

            // Open
            $this->Open->HrefValue = "";

            // High
            $this->High->HrefValue = "";

            // Low
            $this->Low->HrefValue = "";

            // Close
            $this->Close->HrefValue = "";

            // Volume
            $this->Volume->HrefValue = "";

            // Adj Close
            $this->AdjClose->HrefValue = "";

            // Name
            $this->Name->HrefValue = "";

            // Name2
            $this->Name2->HrefValue = "";
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
            if ($this->Date->Visible && $this->Date->Required) {
                if (!$this->Date->IsDetailKey && EmptyValue($this->Date->FormValue)) {
                    $this->Date->addErrorMessage(str_replace("%s", $this->Date->caption(), $this->Date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->Date->FormValue, $this->Date->formatPattern())) {
                $this->Date->addErrorMessage($this->Date->getErrorMessage(false));
            }
            if ($this->Open->Visible && $this->Open->Required) {
                if (!$this->Open->IsDetailKey && EmptyValue($this->Open->FormValue)) {
                    $this->Open->addErrorMessage(str_replace("%s", $this->Open->caption(), $this->Open->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->Open->FormValue)) {
                $this->Open->addErrorMessage($this->Open->getErrorMessage(false));
            }
            if ($this->High->Visible && $this->High->Required) {
                if (!$this->High->IsDetailKey && EmptyValue($this->High->FormValue)) {
                    $this->High->addErrorMessage(str_replace("%s", $this->High->caption(), $this->High->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->High->FormValue)) {
                $this->High->addErrorMessage($this->High->getErrorMessage(false));
            }
            if ($this->Low->Visible && $this->Low->Required) {
                if (!$this->Low->IsDetailKey && EmptyValue($this->Low->FormValue)) {
                    $this->Low->addErrorMessage(str_replace("%s", $this->Low->caption(), $this->Low->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->Low->FormValue)) {
                $this->Low->addErrorMessage($this->Low->getErrorMessage(false));
            }
            if ($this->Close->Visible && $this->Close->Required) {
                if (!$this->Close->IsDetailKey && EmptyValue($this->Close->FormValue)) {
                    $this->Close->addErrorMessage(str_replace("%s", $this->Close->caption(), $this->Close->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->Close->FormValue)) {
                $this->Close->addErrorMessage($this->Close->getErrorMessage(false));
            }
            if ($this->Volume->Visible && $this->Volume->Required) {
                if (!$this->Volume->IsDetailKey && EmptyValue($this->Volume->FormValue)) {
                    $this->Volume->addErrorMessage(str_replace("%s", $this->Volume->caption(), $this->Volume->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->Volume->FormValue)) {
                $this->Volume->addErrorMessage($this->Volume->getErrorMessage(false));
            }
            if ($this->AdjClose->Visible && $this->AdjClose->Required) {
                if (!$this->AdjClose->IsDetailKey && EmptyValue($this->AdjClose->FormValue)) {
                    $this->AdjClose->addErrorMessage(str_replace("%s", $this->AdjClose->caption(), $this->AdjClose->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->AdjClose->FormValue)) {
                $this->AdjClose->addErrorMessage($this->AdjClose->getErrorMessage(false));
            }
            if ($this->Name->Visible && $this->Name->Required) {
                if (!$this->Name->IsDetailKey && EmptyValue($this->Name->FormValue)) {
                    $this->Name->addErrorMessage(str_replace("%s", $this->Name->caption(), $this->Name->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->Name->FormValue, $this->Name->formatPattern())) {
                $this->Name->addErrorMessage($this->Name->getErrorMessage(false));
            }
            if ($this->Name2->Visible && $this->Name2->Required) {
                if (!$this->Name2->IsDetailKey && EmptyValue($this->Name2->FormValue)) {
                    $this->Name2->addErrorMessage(str_replace("%s", $this->Name2->caption(), $this->Name2->RequiredErrorMessage));
                }
            }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Get new row
        $rsnew = $this->getAddRow();

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            } elseif (!EmptyValue($this->DbErrorMessage)) { // Show database error
                $this->setFailureMessage($this->DbErrorMessage);
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow;
    }

    /**
     * Get add row
     *
     * @return array
     */
    protected function getAddRow()
    {
        global $Security;
        $rsnew = [];

        // Date
        $this->Date->setDbValueDef($rsnew, UnFormatDateTime($this->Date->CurrentValue, $this->Date->formatPattern()), false);

        // Open
        $this->Open->setDbValueDef($rsnew, $this->Open->CurrentValue, false);

        // High
        $this->High->setDbValueDef($rsnew, $this->High->CurrentValue, false);

        // Low
        $this->Low->setDbValueDef($rsnew, $this->Low->CurrentValue, false);

        // Close
        $this->Close->setDbValueDef($rsnew, $this->Close->CurrentValue, false);

        // Volume
        $this->Volume->setDbValueDef($rsnew, $this->Volume->CurrentValue, false);

        // Adj Close
        $this->AdjClose->setDbValueDef($rsnew, $this->AdjClose->CurrentValue, false);

        // Name
        $this->Name->setDbValueDef($rsnew, UnFormatDateTime($this->Name->CurrentValue, $this->Name->formatPattern()), false);

        // Name2
        $this->Name2->setDbValueDef($rsnew, $this->Name2->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['Date'])) { // Date
            $this->Date->setFormValue($row['Date']);
        }
        if (isset($row['Open'])) { // Open
            $this->Open->setFormValue($row['Open']);
        }
        if (isset($row['High'])) { // High
            $this->High->setFormValue($row['High']);
        }
        if (isset($row['Low'])) { // Low
            $this->Low->setFormValue($row['Low']);
        }
        if (isset($row['Close'])) { // Close
            $this->Close->setFormValue($row['Close']);
        }
        if (isset($row['Volume'])) { // Volume
            $this->Volume->setFormValue($row['Volume']);
        }
        if (isset($row['Adj Close'])) { // Adj Close
            $this->AdjClose->setFormValue($row['Adj Close']);
        }
        if (isset($row['Name'])) { // Name
            $this->Name->setFormValue($row['Name']);
        }
        if (isset($row['Name2'])) { // Name2
            $this->Name2->setFormValue($row['Name2']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("djilist"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
