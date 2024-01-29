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
class CategoriesEdit extends Categories
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "CategoriesEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "categoriesedit";

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
        $this->CategoryID->setVisibility();
        $this->CategoryName->setVisibility();
        $this->Description->setVisibility();
        $this->Picture->setVisibility();
        $this->Icon_17->setVisibility();
        $this->Icon_25->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'categories';
        $this->TableName = 'categories';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (categories)
        if (!isset($GLOBALS["categories"]) || $GLOBALS["categories"]::class == PROJECT_NAMESPACE . "categories") {
            $GLOBALS["categories"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'categories');
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
                        $result["view"] = SameString($pageName, "categoriesview"); // If View page, no primary button
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
            $key .= @$ar['CategoryID'];
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
            $this->CategoryID->Visible = false;
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

    // Properties
    public $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Load record by position
        $loadByPosition = false;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("CategoryID") ?? Key(0) ?? Route(2)) !== null) {
                $this->CategoryID->setQueryStringValue($keyValue);
                $this->CategoryID->setOldValue($this->CategoryID->QueryStringValue);
            } elseif (Post("CategoryID") !== null) {
                $this->CategoryID->setFormValue(Post("CategoryID"));
                $this->CategoryID->setOldValue($this->CategoryID->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("CategoryID") ?? Route("CategoryID")) !== null) {
                    $this->CategoryID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->CategoryID->CurrentValue = null;
                }
                if (!$loadByQuery || Get(Config("TABLE_START_REC")) !== null || Get(Config("TABLE_PAGE_NUMBER")) !== null) {
                    $loadByPosition = true;
                }
            }

            // Load result set
            if ($this->isShow()) {
                if (!$this->IsModal) { // Normal edit page
                    $this->StartRecord = 1; // Initialize start position
                    $this->Recordset = $this->loadRecordset(); // Load records
                    if ($this->TotalRecords <= 0) { // No record found
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("categorieslist"); // Return to list page
                        return;
                    } elseif ($loadByPosition) { // Load record by position
                        $this->setupStartRecord(); // Set up start record position
                        // Point to current record
                        if ($this->StartRecord <= $this->TotalRecords) {
                            $this->fetch($this->StartRecord);
                            // Redirect to correct record
                            $this->loadRowValues($this->CurrentRow);
                            $url = $this->getCurrentUrl(Config("TABLE_SHOW_DETAIL") . "=" . $this->getCurrentDetailTable());
                            $this->terminate($url);
                            return;
                        }
                    } else { // Match key values
                        if ($this->CategoryID->CurrentValue != null) {
                            while ($this->fetch()) {
                                if (SameString($this->CategoryID->CurrentValue, $this->CurrentRow['CategoryID'])) {
                                    $this->setStartRecordNumber($this->StartRecord); // Save record position
                                    $loaded = true;
                                    break;
                                } else {
                                    $this->StartRecord++;
                                }
                            }
                        }
                    }

                    // Load current row values
                    if ($loaded) {
                        $this->loadRowValues($this->CurrentRow);
                    }
                } else {
                    // Load current record
                    $loaded = $this->loadRow();
                } // End modal checking
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values

            // Set up detail parameters
            $this->setupDetailParms();
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$this->IsModal) { // Normal edit page
                    if (!$loaded) {
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("categorieslist"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("categorieslist"); // No matching record, return to list
                        return;
                    }
                } // End modal checking

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "update": // Update
                if ($this->getCurrentDetailTable() != "") { // Master/detail edit
                    $returnUrl = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
                } else {
                    $returnUrl = $this->getReturnUrl();
                }
                if (GetPageName($returnUrl) == "categorieslist") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "categorieslist") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "categorieslist"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
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
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed

                    // Set up detail parameters
                    $this->setupDetailParms();
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = RowType::EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();
        if (!$this->IsModal) { // Normal view page
            $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, "", $this->RecordRange, $this->AutoHidePager, false, false);
            $this->Pager->PageNumberName = Config("TABLE_PAGE_NUMBER");
            $this->Pager->PagePhraseId = "Record"; // Show as record
        }

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
        $this->Picture->Upload->Index = $CurrentForm->Index;
        $this->Picture->Upload->uploadFile();
        $this->Icon_17->Upload->Index = $CurrentForm->Index;
        $this->Icon_17->Upload->uploadFile();
        $this->Icon_25->Upload->Index = $CurrentForm->Index;
        $this->Icon_25->Upload->uploadFile();
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'CategoryID' first before field var 'x_CategoryID'
        $val = $CurrentForm->hasValue("CategoryID") ? $CurrentForm->getValue("CategoryID") : $CurrentForm->getValue("x_CategoryID");
        if (!$this->CategoryID->IsDetailKey) {
            $this->CategoryID->setFormValue($val);
        }

        // Check field name 'CategoryName' first before field var 'x_CategoryName'
        $val = $CurrentForm->hasValue("CategoryName") ? $CurrentForm->getValue("CategoryName") : $CurrentForm->getValue("x_CategoryName");
        if (!$this->CategoryName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CategoryName->Visible = false; // Disable update for API request
            } else {
                $this->CategoryName->setFormValue($val);
            }
        }

        // Check field name 'Description' first before field var 'x_Description'
        $val = $CurrentForm->hasValue("Description") ? $CurrentForm->getValue("Description") : $CurrentForm->getValue("x_Description");
        if (!$this->Description->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Description->Visible = false; // Disable update for API request
            } else {
                $this->Description->setFormValue($val);
            }
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->CategoryID->CurrentValue = $this->CategoryID->FormValue;
        $this->CategoryName->CurrentValue = $this->CategoryName->FormValue;
        $this->Description->CurrentValue = $this->Description->FormValue;
    }

    /**
     * Load result set
     *
     * @param int $offset Offset
     * @param int $rowcnt Maximum number of rows
     * @return Doctrine\DBAL\Result Result
     */
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load result set
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->executeQuery();
        if (property_exists($this, "TotalRecords") && $rowcnt < 0) {
            $this->TotalRecords = $result->rowCount();
            if ($this->TotalRecords <= 0) { // Handle database drivers that does not return rowCount()
                $this->TotalRecords = $this->getRecordCount($this->getListSql());
            }
        }

        // Call Recordset Selected event
        $this->recordsetSelected($result);
        return $result;
    }

    /**
     * Load records as associative array
     *
     * @param int $offset Offset
     * @param int $rowcnt Maximum number of rows
     * @return void
     */
    public function loadRows($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load result set
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->executeQuery();
        return $result->fetchAllAssociative();
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
        $this->CategoryID->setDbValue($row['CategoryID']);
        $this->CategoryName->setDbValue($row['CategoryName']);
        $this->Description->setDbValue($row['Description']);
        $this->Picture->Upload->DbValue = $row['Picture'];
        if (is_resource($this->Picture->Upload->DbValue) && get_resource_type($this->Picture->Upload->DbValue) == "stream") { // Byte array
            $this->Picture->Upload->DbValue = stream_get_contents($this->Picture->Upload->DbValue);
        }
        $this->Icon_17->Upload->DbValue = $row['Icon_17'];
        if (is_resource($this->Icon_17->Upload->DbValue) && get_resource_type($this->Icon_17->Upload->DbValue) == "stream") { // Byte array
            $this->Icon_17->Upload->DbValue = stream_get_contents($this->Icon_17->Upload->DbValue);
        }
        $this->Icon_25->Upload->DbValue = $row['Icon_25'];
        if (is_resource($this->Icon_25->Upload->DbValue) && get_resource_type($this->Icon_25->Upload->DbValue) == "stream") { // Byte array
            $this->Icon_25->Upload->DbValue = stream_get_contents($this->Icon_25->Upload->DbValue);
        }
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['CategoryID'] = $this->CategoryID->DefaultValue;
        $row['CategoryName'] = $this->CategoryName->DefaultValue;
        $row['Description'] = $this->Description->DefaultValue;
        $row['Picture'] = $this->Picture->DefaultValue;
        $row['Icon_17'] = $this->Icon_17->DefaultValue;
        $row['Icon_25'] = $this->Icon_25->DefaultValue;
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

        // CategoryID
        $this->CategoryID->RowCssClass = "row";

        // CategoryName
        $this->CategoryName->RowCssClass = "row";

        // Description
        $this->Description->RowCssClass = "row";

        // Picture
        $this->Picture->RowCssClass = "row";

        // Icon_17
        $this->Icon_17->RowCssClass = "row";

        // Icon_25
        $this->Icon_25->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // CategoryID
            $this->CategoryID->ViewValue = $this->CategoryID->CurrentValue;

            // CategoryName
            $this->CategoryName->ViewValue = $this->CategoryName->CurrentValue;

            // Description
            $this->Description->ViewValue = $this->Description->CurrentValue;
            if ($this->Description->ViewValue != null) {
                $this->Description->ViewValue = str_replace(["\r\n", "\n", "\r"], "<br>", $this->Description->ViewValue);
            }

            // Picture
            if (!EmptyValue($this->Picture->Upload->DbValue)) {
                $this->Picture->ImageWidth = Config("THUMBNAIL_DEFAULT_WIDTH");
                $this->Picture->ImageHeight = Config("THUMBNAIL_DEFAULT_HEIGHT");
                $this->Picture->ImageAlt = $this->Picture->alt();
                $this->Picture->ImageCssClass = "ew-image";
                $this->Picture->ViewValue = $this->CategoryID->CurrentValue;
                $this->Picture->IsBlobImage = IsImageFile(ContentExtension($this->Picture->Upload->DbValue));
            } else {
                $this->Picture->ViewValue = "";
            }

            // Icon_17
            if (!EmptyValue($this->Icon_17->Upload->DbValue)) {
                $this->Icon_17->ImageAlt = $this->Icon_17->alt();
                $this->Icon_17->ImageCssClass = "ew-image";
                $this->Icon_17->ViewValue = $this->CategoryID->CurrentValue;
                $this->Icon_17->IsBlobImage = IsImageFile(ContentExtension($this->Icon_17->Upload->DbValue));
            } else {
                $this->Icon_17->ViewValue = "";
            }

            // Icon_25
            if (!EmptyValue($this->Icon_25->Upload->DbValue)) {
                $this->Icon_25->ImageAlt = $this->Icon_25->alt();
                $this->Icon_25->ImageCssClass = "ew-image";
                $this->Icon_25->ViewValue = $this->CategoryID->CurrentValue;
                $this->Icon_25->IsBlobImage = IsImageFile(ContentExtension($this->Icon_25->Upload->DbValue));
            } else {
                $this->Icon_25->ViewValue = "";
            }

            // CategoryID
            $this->CategoryID->HrefValue = "";

            // CategoryName
            $this->CategoryName->HrefValue = "";

            // Description
            $this->Description->HrefValue = "";

            // Picture
            if (!empty($this->Picture->Upload->DbValue)) {
                $this->Picture->HrefValue = GetFileUploadUrl($this->Picture, $this->CategoryID->CurrentValue);
                $this->Picture->LinkAttrs["target"] = "";
                if ($this->Picture->IsBlobImage && empty($this->Picture->LinkAttrs["target"])) {
                    $this->Picture->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Picture->HrefValue = FullUrl($this->Picture->HrefValue, "href");
                }
            } else {
                $this->Picture->HrefValue = "";
            }
            $this->Picture->ExportHrefValue = GetFileUploadUrl($this->Picture, $this->CategoryID->CurrentValue);

            // Icon_17
            if (!empty($this->Icon_17->Upload->DbValue)) {
                $this->Icon_17->HrefValue = GetFileUploadUrl($this->Icon_17, $this->CategoryID->CurrentValue);
                $this->Icon_17->LinkAttrs["target"] = "";
                if ($this->Icon_17->IsBlobImage && empty($this->Icon_17->LinkAttrs["target"])) {
                    $this->Icon_17->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Icon_17->HrefValue = FullUrl($this->Icon_17->HrefValue, "href");
                }
            } else {
                $this->Icon_17->HrefValue = "";
            }
            $this->Icon_17->ExportHrefValue = GetFileUploadUrl($this->Icon_17, $this->CategoryID->CurrentValue);

            // Icon_25
            if (!empty($this->Icon_25->Upload->DbValue)) {
                $this->Icon_25->HrefValue = GetFileUploadUrl($this->Icon_25, $this->CategoryID->CurrentValue);
                $this->Icon_25->LinkAttrs["target"] = "";
                if ($this->Icon_25->IsBlobImage && empty($this->Icon_25->LinkAttrs["target"])) {
                    $this->Icon_25->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Icon_25->HrefValue = FullUrl($this->Icon_25->HrefValue, "href");
                }
            } else {
                $this->Icon_25->HrefValue = "";
            }
            $this->Icon_25->ExportHrefValue = GetFileUploadUrl($this->Icon_25, $this->CategoryID->CurrentValue);
        } elseif ($this->RowType == RowType::EDIT) {
            // CategoryID
            $this->CategoryID->setupEditAttributes();
            $this->CategoryID->EditValue = $this->CategoryID->CurrentValue;

            // CategoryName
            $this->CategoryName->setupEditAttributes();
            if (!$this->CategoryName->Raw) {
                $this->CategoryName->CurrentValue = HtmlDecode($this->CategoryName->CurrentValue);
            }
            $this->CategoryName->EditValue = HtmlEncode($this->CategoryName->CurrentValue);
            $this->CategoryName->PlaceHolder = RemoveHtml($this->CategoryName->caption());

            // Description
            $this->Description->setupEditAttributes();
            $this->Description->EditValue = HtmlEncode($this->Description->CurrentValue);
            $this->Description->PlaceHolder = RemoveHtml($this->Description->caption());

            // Picture
            $this->Picture->setupEditAttributes();
            if (!EmptyValue($this->Picture->Upload->DbValue)) {
                $this->Picture->ImageWidth = Config("THUMBNAIL_DEFAULT_WIDTH");
                $this->Picture->ImageHeight = Config("THUMBNAIL_DEFAULT_HEIGHT");
                $this->Picture->ImageAlt = $this->Picture->alt();
                $this->Picture->ImageCssClass = "ew-image";
                $this->Picture->EditValue = $this->CategoryID->CurrentValue;
                $this->Picture->IsBlobImage = IsImageFile(ContentExtension($this->Picture->Upload->DbValue));
            } else {
                $this->Picture->EditValue = "";
            }
            if ($this->isShow()) {
                RenderUploadField($this->Picture);
            }

            // Icon_17
            $this->Icon_17->setupEditAttributes();
            if (!EmptyValue($this->Icon_17->Upload->DbValue)) {
                $this->Icon_17->ImageAlt = $this->Icon_17->alt();
                $this->Icon_17->ImageCssClass = "ew-image";
                $this->Icon_17->EditValue = $this->CategoryID->CurrentValue;
                $this->Icon_17->IsBlobImage = IsImageFile(ContentExtension($this->Icon_17->Upload->DbValue));
            } else {
                $this->Icon_17->EditValue = "";
            }
            if ($this->isShow()) {
                RenderUploadField($this->Icon_17);
            }

            // Icon_25
            $this->Icon_25->setupEditAttributes();
            if (!EmptyValue($this->Icon_25->Upload->DbValue)) {
                $this->Icon_25->ImageAlt = $this->Icon_25->alt();
                $this->Icon_25->ImageCssClass = "ew-image";
                $this->Icon_25->EditValue = $this->CategoryID->CurrentValue;
                $this->Icon_25->IsBlobImage = IsImageFile(ContentExtension($this->Icon_25->Upload->DbValue));
            } else {
                $this->Icon_25->EditValue = "";
            }
            if ($this->isShow()) {
                RenderUploadField($this->Icon_25);
            }

            // Edit refer script

            // CategoryID
            $this->CategoryID->HrefValue = "";

            // CategoryName
            $this->CategoryName->HrefValue = "";

            // Description
            $this->Description->HrefValue = "";

            // Picture
            if (!empty($this->Picture->Upload->DbValue)) {
                $this->Picture->HrefValue = GetFileUploadUrl($this->Picture, $this->CategoryID->CurrentValue);
                $this->Picture->LinkAttrs["target"] = "";
                if ($this->Picture->IsBlobImage && empty($this->Picture->LinkAttrs["target"])) {
                    $this->Picture->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Picture->HrefValue = FullUrl($this->Picture->HrefValue, "href");
                }
            } else {
                $this->Picture->HrefValue = "";
            }
            $this->Picture->ExportHrefValue = GetFileUploadUrl($this->Picture, $this->CategoryID->CurrentValue);

            // Icon_17
            if (!empty($this->Icon_17->Upload->DbValue)) {
                $this->Icon_17->HrefValue = GetFileUploadUrl($this->Icon_17, $this->CategoryID->CurrentValue);
                $this->Icon_17->LinkAttrs["target"] = "";
                if ($this->Icon_17->IsBlobImage && empty($this->Icon_17->LinkAttrs["target"])) {
                    $this->Icon_17->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Icon_17->HrefValue = FullUrl($this->Icon_17->HrefValue, "href");
                }
            } else {
                $this->Icon_17->HrefValue = "";
            }
            $this->Icon_17->ExportHrefValue = GetFileUploadUrl($this->Icon_17, $this->CategoryID->CurrentValue);

            // Icon_25
            if (!empty($this->Icon_25->Upload->DbValue)) {
                $this->Icon_25->HrefValue = GetFileUploadUrl($this->Icon_25, $this->CategoryID->CurrentValue);
                $this->Icon_25->LinkAttrs["target"] = "";
                if ($this->Icon_25->IsBlobImage && empty($this->Icon_25->LinkAttrs["target"])) {
                    $this->Icon_25->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Icon_25->HrefValue = FullUrl($this->Icon_25->HrefValue, "href");
                }
            } else {
                $this->Icon_25->HrefValue = "";
            }
            $this->Icon_25->ExportHrefValue = GetFileUploadUrl($this->Icon_25, $this->CategoryID->CurrentValue);
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
            if ($this->CategoryID->Visible && $this->CategoryID->Required) {
                if (!$this->CategoryID->IsDetailKey && EmptyValue($this->CategoryID->FormValue)) {
                    $this->CategoryID->addErrorMessage(str_replace("%s", $this->CategoryID->caption(), $this->CategoryID->RequiredErrorMessage));
                }
            }
            if ($this->CategoryName->Visible && $this->CategoryName->Required) {
                if (!$this->CategoryName->IsDetailKey && EmptyValue($this->CategoryName->FormValue)) {
                    $this->CategoryName->addErrorMessage(str_replace("%s", $this->CategoryName->caption(), $this->CategoryName->RequiredErrorMessage));
                }
            }
            if ($this->Description->Visible && $this->Description->Required) {
                if (!$this->Description->IsDetailKey && EmptyValue($this->Description->FormValue)) {
                    $this->Description->addErrorMessage(str_replace("%s", $this->Description->caption(), $this->Description->RequiredErrorMessage));
                }
            }
            if ($this->Picture->Visible && $this->Picture->Required) {
                if ($this->Picture->Upload->FileName == "" && !$this->Picture->Upload->KeepFile) {
                    $this->Picture->addErrorMessage(str_replace("%s", $this->Picture->caption(), $this->Picture->RequiredErrorMessage));
                }
            }
            if ($this->Icon_17->Visible && $this->Icon_17->Required) {
                if ($this->Icon_17->Upload->FileName == "" && !$this->Icon_17->Upload->KeepFile) {
                    $this->Icon_17->addErrorMessage(str_replace("%s", $this->Icon_17->caption(), $this->Icon_17->RequiredErrorMessage));
                }
            }
            if ($this->Icon_25->Visible && $this->Icon_25->Required) {
                if ($this->Icon_25->Upload->FileName == "" && !$this->Icon_25->Upload->KeepFile) {
                    $this->Icon_25->addErrorMessage(str_replace("%s", $this->Icon_25->caption(), $this->Icon_25->RequiredErrorMessage));
                }
            }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("ProductsGrid");
        if (in_array("products", $detailTblVar) && $detailPage->DetailEdit) {
            $detailPage->run();
            $validateForm = $validateForm && $detailPage->validateGridForm();
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Load old values
            $this->loadDbValues($rsold);
        }

        // Get new row
        $rsnew = $this->getEditRow($rsold);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Begin transaction
        if ($this->getCurrentDetailTable() != "" && $this->UseTransaction) {
            $conn->beginTransaction();
        }

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }

            // Update detail records
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            $detailPage = Container("ProductsGrid");
            if (in_array("products", $detailTblVar) && $detailPage->DetailEdit && $editRow) {
                $Security->loadCurrentUserLevel($this->ProjectID . "products"); // Load user level of detail table
                $editRow = $detailPage->gridUpdate();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
            }

            // Commit/Rollback transaction
            if ($this->getCurrentDetailTable() != "") {
                if ($editRow) {
                    if ($this->UseTransaction) { // Commit transaction
                        if ($conn->isTransactionActive()) {
                            $conn->commit();
                        }
                    }
                } else {
                    if ($this->UseTransaction) { // Rollback transaction
                        if ($conn->isTransactionActive()) {
                            $conn->rollback();
                        }
                    }
                }
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    /**
     * Get edit row
     *
     * @return array
     */
    protected function getEditRow($rsold)
    {
        global $Security;
        $rsnew = [];

        // CategoryName
        $this->CategoryName->setDbValueDef($rsnew, $this->CategoryName->CurrentValue, $this->CategoryName->ReadOnly);

        // Description
        $this->Description->setDbValueDef($rsnew, $this->Description->CurrentValue, $this->Description->ReadOnly);

        // Picture
        if ($this->Picture->Visible && !$this->Picture->ReadOnly && !$this->Picture->Upload->KeepFile) {
            if ($this->Picture->Upload->Value === null) {
                $rsnew['Picture'] = null;
            } else {
                $rsnew['Picture'] = $this->Picture->Upload->Value;
            }
        }

        // Icon_17
        if ($this->Icon_17->Visible && !$this->Icon_17->ReadOnly && !$this->Icon_17->Upload->KeepFile) {
            if ($this->Icon_17->Upload->Value === null) {
                $rsnew['Icon_17'] = null;
            } else {
                $rsnew['Icon_17'] = $this->Icon_17->Upload->Value;
            }
        }

        // Icon_25
        if ($this->Icon_25->Visible && !$this->Icon_25->ReadOnly && !$this->Icon_25->Upload->KeepFile) {
            if ($this->Icon_25->Upload->Value === null) {
                $rsnew['Icon_25'] = null;
            } else {
                $rsnew['Icon_25'] = $this->Icon_25->Upload->Value;
            }
        }
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['CategoryName'])) { // CategoryName
            $this->CategoryName->CurrentValue = $row['CategoryName'];
        }
        if (isset($row['Description'])) { // Description
            $this->Description->CurrentValue = $row['Description'];
        }
        if (isset($row['Picture'])) { // Picture
            $this->Picture->CurrentValue = $row['Picture'];
        }
        if (isset($row['Icon_17'])) { // Icon_17
            $this->Icon_17->CurrentValue = $row['Icon_17'];
        }
        if (isset($row['Icon_25'])) { // Icon_25
            $this->Icon_25->CurrentValue = $row['Icon_25'];
        }
    }

    // Set up detail parms based on QueryString
    protected function setupDetailParms()
    {
        // Get the keys for master table
        $detailTblVar = Get(Config("TABLE_SHOW_DETAIL"));
        if ($detailTblVar !== null) {
            $this->setCurrentDetailTable($detailTblVar);
        } else {
            $detailTblVar = $this->getCurrentDetailTable();
        }
        if ($detailTblVar != "") {
            $detailTblVar = explode(",", $detailTblVar);
            if (in_array("products", $detailTblVar)) {
                $detailPageObj = Container("ProductsGrid");
                if ($detailPageObj->DetailEdit) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    $detailPageObj->CurrentMode = "edit";
                    $detailPageObj->CurrentAction = "gridedit";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->CategoryID->IsDetailKey = true;
                    $detailPageObj->CategoryID->CurrentValue = $this->CategoryID->CurrentValue;
                    $detailPageObj->CategoryID->setSessionValue($detailPageObj->CategoryID->CurrentValue);
                }
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home2");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("categorieslist"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
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
