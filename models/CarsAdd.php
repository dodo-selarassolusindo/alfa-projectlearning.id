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
class CarsAdd extends Cars
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "CarsAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "carsadd";

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
        $this->Trademark->setVisibility();
        $this->Model->setVisibility();
        $this->HP->setVisibility();
        $this->Cylinders->setVisibility();
        $this->TransmissionSpeeds->setVisibility();
        $this->TransmissAutomatic->setVisibility();
        $this->MPGCity->setVisibility();
        $this->MPGHighway->setVisibility();
        $this->Description->setVisibility();
        $this->Price->setVisibility();
        $this->Picture->setVisibility();
        $this->Doors->setVisibility();
        $this->Torque->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'cars';
        $this->TableName = 'cars';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (cars)
        if (!isset($GLOBALS["cars"]) || $GLOBALS["cars"]::class == PROJECT_NAMESPACE . "cars") {
            $GLOBALS["cars"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cars');
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
                        $result["view"] = SameString($pageName, "carsview"); // If View page, no primary button
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
    public $MultiPages; // Multi pages object

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

        // Set up multi page object
        $this->setupMultiPages();

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

        // Set up lookup cache
        $this->setupLookupOptions($this->Trademark);
        $this->setupLookupOptions($this->Model);
        $this->setupLookupOptions($this->TransmissAutomatic);

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
                    $this->terminate("carslist"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "carslist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "carsview") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "carslist") {
                            Container("app.flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "carslist"; // Return list page content
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
        if ($this->isConfirm()) { // Confirm page
            $this->RowType = RowType::VIEW; // Render view type
        } else {
            $this->RowType = RowType::ADD; // Render add type
        }

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
        $this->Picture->Upload->Index = $CurrentForm->Index;
        $this->Picture->Upload->uploadFile();
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->Trademark->DefaultValue = $this->Trademark->getDefault(); // PHP
        $this->Trademark->OldValue = $this->Trademark->DefaultValue;
        $this->TransmissAutomatic->DefaultValue = $this->TransmissAutomatic->getDefault(); // PHP
        $this->TransmissAutomatic->OldValue = $this->TransmissAutomatic->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Trademark' first before field var 'x_Trademark'
        $val = $CurrentForm->hasValue("Trademark") ? $CurrentForm->getValue("Trademark") : $CurrentForm->getValue("x_Trademark");
        if (!$this->Trademark->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Trademark->Visible = false; // Disable update for API request
            } else {
                $this->Trademark->setFormValue($val);
            }
        }

        // Check field name 'Model' first before field var 'x_Model'
        $val = $CurrentForm->hasValue("Model") ? $CurrentForm->getValue("Model") : $CurrentForm->getValue("x_Model");
        if (!$this->Model->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Model->Visible = false; // Disable update for API request
            } else {
                $this->Model->setFormValue($val);
            }
        }

        // Check field name 'HP' first before field var 'x_HP'
        $val = $CurrentForm->hasValue("HP") ? $CurrentForm->getValue("HP") : $CurrentForm->getValue("x_HP");
        if (!$this->HP->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->HP->Visible = false; // Disable update for API request
            } else {
                $this->HP->setFormValue($val);
            }
        }

        // Check field name 'Cylinders' first before field var 'x_Cylinders'
        $val = $CurrentForm->hasValue("Cylinders") ? $CurrentForm->getValue("Cylinders") : $CurrentForm->getValue("x_Cylinders");
        if (!$this->Cylinders->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Cylinders->Visible = false; // Disable update for API request
            } else {
                $this->Cylinders->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Transmission Speeds' first before field var 'x_TransmissionSpeeds'
        $val = $CurrentForm->hasValue("Transmission Speeds") ? $CurrentForm->getValue("Transmission Speeds") : $CurrentForm->getValue("x_TransmissionSpeeds");
        if (!$this->TransmissionSpeeds->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->TransmissionSpeeds->Visible = false; // Disable update for API request
            } else {
                $this->TransmissionSpeeds->setFormValue($val);
            }
        }

        // Check field name 'TransmissAutomatic' first before field var 'x_TransmissAutomatic'
        $val = $CurrentForm->hasValue("TransmissAutomatic") ? $CurrentForm->getValue("TransmissAutomatic") : $CurrentForm->getValue("x_TransmissAutomatic");
        if (!$this->TransmissAutomatic->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->TransmissAutomatic->Visible = false; // Disable update for API request
            } else {
                $this->TransmissAutomatic->setFormValue($val);
            }
        }

        // Check field name 'MPG City' first before field var 'x_MPGCity'
        $val = $CurrentForm->hasValue("MPG City") ? $CurrentForm->getValue("MPG City") : $CurrentForm->getValue("x_MPGCity");
        if (!$this->MPGCity->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->MPGCity->Visible = false; // Disable update for API request
            } else {
                $this->MPGCity->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'MPG Highway' first before field var 'x_MPGHighway'
        $val = $CurrentForm->hasValue("MPG Highway") ? $CurrentForm->getValue("MPG Highway") : $CurrentForm->getValue("x_MPGHighway");
        if (!$this->MPGHighway->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->MPGHighway->Visible = false; // Disable update for API request
            } else {
                $this->MPGHighway->setFormValue($val, true, $validate);
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

        // Check field name 'Price' first before field var 'x_Price'
        $val = $CurrentForm->hasValue("Price") ? $CurrentForm->getValue("Price") : $CurrentForm->getValue("x_Price");
        if (!$this->Price->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Price->Visible = false; // Disable update for API request
            } else {
                $this->Price->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Doors' first before field var 'x_Doors'
        $val = $CurrentForm->hasValue("Doors") ? $CurrentForm->getValue("Doors") : $CurrentForm->getValue("x_Doors");
        if (!$this->Doors->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Doors->Visible = false; // Disable update for API request
            } else {
                $this->Doors->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'Torque' first before field var 'x_Torque'
        $val = $CurrentForm->hasValue("Torque") ? $CurrentForm->getValue("Torque") : $CurrentForm->getValue("x_Torque");
        if (!$this->Torque->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Torque->Visible = false; // Disable update for API request
            } else {
                $this->Torque->setFormValue($val);
            }
        }

        // Check field name 'ID' first before field var 'x_ID'
        $val = $CurrentForm->hasValue("ID") ? $CurrentForm->getValue("ID") : $CurrentForm->getValue("x_ID");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->Trademark->CurrentValue = $this->Trademark->FormValue;
        $this->Model->CurrentValue = $this->Model->FormValue;
        $this->HP->CurrentValue = $this->HP->FormValue;
        $this->Cylinders->CurrentValue = $this->Cylinders->FormValue;
        $this->TransmissionSpeeds->CurrentValue = $this->TransmissionSpeeds->FormValue;
        $this->TransmissAutomatic->CurrentValue = $this->TransmissAutomatic->FormValue;
        $this->MPGCity->CurrentValue = $this->MPGCity->FormValue;
        $this->MPGHighway->CurrentValue = $this->MPGHighway->FormValue;
        $this->Description->CurrentValue = $this->Description->FormValue;
        $this->Price->CurrentValue = $this->Price->FormValue;
        $this->Doors->CurrentValue = $this->Doors->FormValue;
        $this->Torque->CurrentValue = $this->Torque->FormValue;
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
        $this->Trademark->setDbValue($row['Trademark']);
        if (array_key_exists('EV__Trademark', $row)) {
            $this->Trademark->VirtualValue = $row['EV__Trademark']; // Set up virtual field value
        } else {
            $this->Trademark->VirtualValue = ""; // Clear value
        }
        $this->Model->setDbValue($row['Model']);
        if (array_key_exists('EV__Model', $row)) {
            $this->Model->VirtualValue = $row['EV__Model']; // Set up virtual field value
        } else {
            $this->Model->VirtualValue = ""; // Clear value
        }
        $this->HP->setDbValue($row['HP']);
        $this->Cylinders->setDbValue($row['Cylinders']);
        $this->TransmissionSpeeds->setDbValue($row['Transmission Speeds']);
        $this->TransmissAutomatic->setDbValue($row['TransmissAutomatic']);
        $this->MPGCity->setDbValue($row['MPG City']);
        $this->MPGHighway->setDbValue($row['MPG Highway']);
        $this->Description->setDbValue($row['Description']);
        $this->Price->setDbValue($row['Price']);
        $this->Picture->Upload->DbValue = $row['Picture'];
        if (is_resource($this->Picture->Upload->DbValue) && get_resource_type($this->Picture->Upload->DbValue) == "stream") { // Byte array
            $this->Picture->Upload->DbValue = stream_get_contents($this->Picture->Upload->DbValue);
        }
        $this->Doors->setDbValue($row['Doors']);
        $this->Torque->setDbValue($row['Torque']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ID'] = $this->ID->DefaultValue;
        $row['Trademark'] = $this->Trademark->DefaultValue;
        $row['Model'] = $this->Model->DefaultValue;
        $row['HP'] = $this->HP->DefaultValue;
        $row['Cylinders'] = $this->Cylinders->DefaultValue;
        $row['Transmission Speeds'] = $this->TransmissionSpeeds->DefaultValue;
        $row['TransmissAutomatic'] = $this->TransmissAutomatic->DefaultValue;
        $row['MPG City'] = $this->MPGCity->DefaultValue;
        $row['MPG Highway'] = $this->MPGHighway->DefaultValue;
        $row['Description'] = $this->Description->DefaultValue;
        $row['Price'] = $this->Price->DefaultValue;
        $row['Picture'] = $this->Picture->DefaultValue;
        $row['Doors'] = $this->Doors->DefaultValue;
        $row['Torque'] = $this->Torque->DefaultValue;
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

        // Trademark
        $this->Trademark->RowCssClass = "row";

        // Model
        $this->Model->RowCssClass = "row";

        // HP
        $this->HP->RowCssClass = "row";

        // Cylinders
        $this->Cylinders->RowCssClass = "row";

        // Transmission Speeds
        $this->TransmissionSpeeds->RowCssClass = "row";

        // TransmissAutomatic
        $this->TransmissAutomatic->RowCssClass = "row";

        // MPG City
        $this->MPGCity->RowCssClass = "row";

        // MPG Highway
        $this->MPGHighway->RowCssClass = "row";

        // Description
        $this->Description->RowCssClass = "row";

        // Price
        $this->Price->RowCssClass = "row";

        // Picture
        $this->Picture->RowCssClass = "row";

        // Doors
        $this->Doors->RowCssClass = "row";

        // Torque
        $this->Torque->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // ID
            $this->ID->ViewValue = $this->ID->CurrentValue;

            // Trademark
            if ($this->Trademark->VirtualValue != "") {
                $this->Trademark->ViewValue = $this->Trademark->VirtualValue;
            } else {
                $curVal = strval($this->Trademark->CurrentValue);
                if ($curVal != "") {
                    $this->Trademark->ViewValue = $this->Trademark->lookupCacheOption($curVal);
                    if ($this->Trademark->ViewValue === null) { // Lookup from database
                        $filterWrk = SearchFilter($this->Trademark->Lookup->getTable()->Fields["ID"]->searchExpression(), "=", $curVal, $this->Trademark->Lookup->getTable()->Fields["ID"]->searchDataType(), "");
                        $sqlWrk = $this->Trademark->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCache($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->Trademark->Lookup->renderViewRow($rswrk[0]);
                            $this->Trademark->ViewValue = $this->Trademark->displayValue($arwrk);
                        } else {
                            $this->Trademark->ViewValue = $this->Trademark->CurrentValue;
                        }
                    }
                } else {
                    $this->Trademark->ViewValue = null;
                }
            }

            // Model
            if ($this->Model->VirtualValue != "") {
                $this->Model->ViewValue = $this->Model->VirtualValue;
            } else {
                $curVal = strval($this->Model->CurrentValue);
                if ($curVal != "") {
                    $this->Model->ViewValue = $this->Model->lookupCacheOption($curVal);
                    if ($this->Model->ViewValue === null) { // Lookup from database
                        $filterWrk = SearchFilter($this->Model->Lookup->getTable()->Fields["ID"]->searchExpression(), "=", $curVal, $this->Model->Lookup->getTable()->Fields["ID"]->searchDataType(), "");
                        $sqlWrk = $this->Model->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCache($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->Model->Lookup->renderViewRow($rswrk[0]);
                            $this->Model->ViewValue = $this->Model->displayValue($arwrk);
                        } else {
                            $this->Model->ViewValue = $this->Model->CurrentValue;
                        }
                    }
                } else {
                    $this->Model->ViewValue = null;
                }
            }

            // HP
            $this->HP->ViewValue = $this->HP->CurrentValue;

            // Cylinders
            $this->Cylinders->ViewValue = $this->Cylinders->CurrentValue;

            // Transmission Speeds
            $this->TransmissionSpeeds->ViewValue = $this->TransmissionSpeeds->CurrentValue;

            // TransmissAutomatic
            if (ConvertToBool($this->TransmissAutomatic->CurrentValue)) {
                $this->TransmissAutomatic->ViewValue = $this->TransmissAutomatic->tagCaption(1) != "" ? $this->TransmissAutomatic->tagCaption(1) : "Yes";
            } else {
                $this->TransmissAutomatic->ViewValue = $this->TransmissAutomatic->tagCaption(2) != "" ? $this->TransmissAutomatic->tagCaption(2) : "No";
            }

            // MPG City
            $this->MPGCity->ViewValue = $this->MPGCity->CurrentValue;

            // MPG Highway
            $this->MPGHighway->ViewValue = $this->MPGHighway->CurrentValue;

            // Description
            $this->Description->ViewValue = $this->Description->CurrentValue;

            // Price
            $this->Price->ViewValue = $this->Price->CurrentValue;
            $this->Price->ViewValue = FormatCurrency($this->Price->ViewValue, $this->Price->formatPattern());

            // Picture
            if (!EmptyValue($this->Picture->Upload->DbValue)) {
                $this->Picture->ImageWidth = 200;
                $this->Picture->ImageHeight = 0;
                $this->Picture->ImageAlt = $this->Picture->alt();
                $this->Picture->ImageCssClass = "ew-image";
                $this->Picture->ViewValue = $this->ID->CurrentValue;
                $this->Picture->IsBlobImage = IsImageFile(ContentExtension($this->Picture->Upload->DbValue));
            } else {
                $this->Picture->ViewValue = "";
            }

            // Doors
            $this->Doors->ViewValue = $this->Doors->CurrentValue;
            $this->Doors->ViewValue = FormatNumber($this->Doors->ViewValue, $this->Doors->formatPattern());

            // Torque
            $this->Torque->ViewValue = $this->Torque->CurrentValue;

            // Trademark
            $this->Trademark->HrefValue = "";

            // Model
            $this->Model->HrefValue = "";

            // HP
            $this->HP->HrefValue = "";

            // Cylinders
            $this->Cylinders->HrefValue = "";

            // Transmission Speeds
            $this->TransmissionSpeeds->HrefValue = "";

            // TransmissAutomatic
            $this->TransmissAutomatic->HrefValue = "";

            // MPG City
            $this->MPGCity->HrefValue = "";

            // MPG Highway
            $this->MPGHighway->HrefValue = "";

            // Description
            $this->Description->HrefValue = "";

            // Price
            $this->Price->HrefValue = "";

            // Picture
            if (!empty($this->Picture->Upload->DbValue)) {
                $this->Picture->HrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);
                $this->Picture->LinkAttrs["target"] = "_blank";
                if ($this->Picture->IsBlobImage && empty($this->Picture->LinkAttrs["target"])) {
                    $this->Picture->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Picture->HrefValue = FullUrl($this->Picture->HrefValue, "href");
                }
            } else {
                $this->Picture->HrefValue = "";
            }
            $this->Picture->ExportHrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);

            // Doors
            $this->Doors->HrefValue = "";

            // Torque
            $this->Torque->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // Trademark
            $this->Trademark->setupEditAttributes();
            $curVal = trim(strval($this->Trademark->CurrentValue));
            if ($curVal != "") {
                $this->Trademark->ViewValue = $this->Trademark->lookupCacheOption($curVal);
            } else {
                $this->Trademark->ViewValue = $this->Trademark->Lookup !== null && is_array($this->Trademark->lookupOptions()) && count($this->Trademark->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Trademark->ViewValue !== null) { // Load from cache
                $this->Trademark->EditValue = array_values($this->Trademark->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->Trademark->Lookup->getTable()->Fields["ID"]->searchExpression(), "=", $this->Trademark->CurrentValue, $this->Trademark->Lookup->getTable()->Fields["ID"]->searchDataType(), "");
                }
                $sqlWrk = $this->Trademark->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Trademark->EditValue = $arwrk;
            }
            $this->Trademark->PlaceHolder = RemoveHtml($this->Trademark->caption());

            // Model
            $this->Model->setupEditAttributes();
            $curVal = trim(strval($this->Model->CurrentValue));
            if ($curVal != "") {
                $this->Model->ViewValue = $this->Model->lookupCacheOption($curVal);
            } else {
                $this->Model->ViewValue = $this->Model->Lookup !== null && is_array($this->Model->lookupOptions()) && count($this->Model->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Model->ViewValue !== null) { // Load from cache
                $this->Model->EditValue = array_values($this->Model->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->Model->Lookup->getTable()->Fields["ID"]->searchExpression(), "=", $this->Model->CurrentValue, $this->Model->Lookup->getTable()->Fields["ID"]->searchDataType(), "");
                }
                $sqlWrk = $this->Model->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Model->EditValue = $arwrk;
            }
            $this->Model->PlaceHolder = RemoveHtml($this->Model->caption());

            // HP
            $this->HP->setupEditAttributes();
            if (!$this->HP->Raw) {
                $this->HP->CurrentValue = HtmlDecode($this->HP->CurrentValue);
            }
            $this->HP->EditValue = HtmlEncode($this->HP->CurrentValue);
            $this->HP->PlaceHolder = RemoveHtml($this->HP->caption());

            // Cylinders
            $this->Cylinders->setupEditAttributes();
            $this->Cylinders->EditValue = $this->Cylinders->CurrentValue;
            $this->Cylinders->PlaceHolder = RemoveHtml($this->Cylinders->caption());
            if (strval($this->Cylinders->EditValue) != "" && is_numeric($this->Cylinders->EditValue)) {
                $this->Cylinders->EditValue = $this->Cylinders->EditValue;
            }

            // Transmission Speeds
            $this->TransmissionSpeeds->setupEditAttributes();
            if (!$this->TransmissionSpeeds->Raw) {
                $this->TransmissionSpeeds->CurrentValue = HtmlDecode($this->TransmissionSpeeds->CurrentValue);
            }
            $this->TransmissionSpeeds->EditValue = HtmlEncode($this->TransmissionSpeeds->CurrentValue);
            $this->TransmissionSpeeds->PlaceHolder = RemoveHtml($this->TransmissionSpeeds->caption());

            // TransmissAutomatic
            $this->TransmissAutomatic->EditValue = $this->TransmissAutomatic->options(false);
            $this->TransmissAutomatic->PlaceHolder = RemoveHtml($this->TransmissAutomatic->caption());

            // MPG City
            $this->MPGCity->setupEditAttributes();
            $this->MPGCity->EditValue = $this->MPGCity->CurrentValue;
            $this->MPGCity->PlaceHolder = RemoveHtml($this->MPGCity->caption());
            if (strval($this->MPGCity->EditValue) != "" && is_numeric($this->MPGCity->EditValue)) {
                $this->MPGCity->EditValue = $this->MPGCity->EditValue;
            }

            // MPG Highway
            $this->MPGHighway->setupEditAttributes();
            $this->MPGHighway->EditValue = $this->MPGHighway->CurrentValue;
            $this->MPGHighway->PlaceHolder = RemoveHtml($this->MPGHighway->caption());
            if (strval($this->MPGHighway->EditValue) != "" && is_numeric($this->MPGHighway->EditValue)) {
                $this->MPGHighway->EditValue = $this->MPGHighway->EditValue;
            }

            // Description
            $this->Description->setupEditAttributes();
            $this->Description->EditValue = HtmlEncode($this->Description->CurrentValue);
            $this->Description->PlaceHolder = RemoveHtml($this->Description->caption());

            // Price
            $this->Price->setupEditAttributes();
            $this->Price->EditValue = $this->Price->CurrentValue;
            $this->Price->PlaceHolder = RemoveHtml($this->Price->caption());
            if (strval($this->Price->EditValue) != "" && is_numeric($this->Price->EditValue)) {
                $this->Price->EditValue = FormatNumber($this->Price->EditValue, null);
            }

            // Picture
            $this->Picture->setupEditAttributes();
            if (!EmptyValue($this->Picture->Upload->DbValue)) {
                $this->Picture->ImageWidth = 200;
                $this->Picture->ImageHeight = 0;
                $this->Picture->ImageAlt = $this->Picture->alt();
                $this->Picture->ImageCssClass = "ew-image";
                $this->Picture->EditValue = $this->ID->CurrentValue;
                $this->Picture->IsBlobImage = IsImageFile(ContentExtension($this->Picture->Upload->DbValue));
            } else {
                $this->Picture->EditValue = "";
            }
            if (!Config("CREATE_UPLOAD_FILE_ON_COPY")) {
                $this->Picture->Upload->DbValue = null;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->Picture);
            }

            // Doors
            $this->Doors->setupEditAttributes();
            $this->Doors->EditValue = $this->Doors->CurrentValue;
            $this->Doors->PlaceHolder = RemoveHtml($this->Doors->caption());
            if (strval($this->Doors->EditValue) != "" && is_numeric($this->Doors->EditValue)) {
                $this->Doors->EditValue = FormatNumber($this->Doors->EditValue, null);
            }

            // Torque
            $this->Torque->setupEditAttributes();
            if (!$this->Torque->Raw) {
                $this->Torque->CurrentValue = HtmlDecode($this->Torque->CurrentValue);
            }
            $this->Torque->EditValue = HtmlEncode($this->Torque->CurrentValue);
            $this->Torque->PlaceHolder = RemoveHtml($this->Torque->caption());

            // Add refer script

            // Trademark
            $this->Trademark->HrefValue = "";

            // Model
            $this->Model->HrefValue = "";

            // HP
            $this->HP->HrefValue = "";

            // Cylinders
            $this->Cylinders->HrefValue = "";

            // Transmission Speeds
            $this->TransmissionSpeeds->HrefValue = "";

            // TransmissAutomatic
            $this->TransmissAutomatic->HrefValue = "";

            // MPG City
            $this->MPGCity->HrefValue = "";

            // MPG Highway
            $this->MPGHighway->HrefValue = "";

            // Description
            $this->Description->HrefValue = "";

            // Price
            $this->Price->HrefValue = "";

            // Picture
            if (!empty($this->Picture->Upload->DbValue)) {
                $this->Picture->HrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);
                $this->Picture->LinkAttrs["target"] = "_blank";
                if ($this->Picture->IsBlobImage && empty($this->Picture->LinkAttrs["target"])) {
                    $this->Picture->LinkAttrs["target"] = "_blank";
                }
                if ($this->isExport()) {
                    $this->Picture->HrefValue = FullUrl($this->Picture->HrefValue, "href");
                }
            } else {
                $this->Picture->HrefValue = "";
            }
            $this->Picture->ExportHrefValue = GetFileUploadUrl($this->Picture, $this->ID->CurrentValue);

            // Doors
            $this->Doors->HrefValue = "";

            // Torque
            $this->Torque->HrefValue = "";
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
            if ($this->Trademark->Visible && $this->Trademark->Required) {
                if (!$this->Trademark->IsDetailKey && EmptyValue($this->Trademark->FormValue)) {
                    $this->Trademark->addErrorMessage(str_replace("%s", $this->Trademark->caption(), $this->Trademark->RequiredErrorMessage));
                }
            }
            if ($this->Model->Visible && $this->Model->Required) {
                if (!$this->Model->IsDetailKey && EmptyValue($this->Model->FormValue)) {
                    $this->Model->addErrorMessage(str_replace("%s", $this->Model->caption(), $this->Model->RequiredErrorMessage));
                }
            }
            if ($this->HP->Visible && $this->HP->Required) {
                if (!$this->HP->IsDetailKey && EmptyValue($this->HP->FormValue)) {
                    $this->HP->addErrorMessage(str_replace("%s", $this->HP->caption(), $this->HP->RequiredErrorMessage));
                }
            }
            if ($this->Cylinders->Visible && $this->Cylinders->Required) {
                if (!$this->Cylinders->IsDetailKey && EmptyValue($this->Cylinders->FormValue)) {
                    $this->Cylinders->addErrorMessage(str_replace("%s", $this->Cylinders->caption(), $this->Cylinders->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->Cylinders->FormValue)) {
                $this->Cylinders->addErrorMessage($this->Cylinders->getErrorMessage(false));
            }
            if ($this->TransmissionSpeeds->Visible && $this->TransmissionSpeeds->Required) {
                if (!$this->TransmissionSpeeds->IsDetailKey && EmptyValue($this->TransmissionSpeeds->FormValue)) {
                    $this->TransmissionSpeeds->addErrorMessage(str_replace("%s", $this->TransmissionSpeeds->caption(), $this->TransmissionSpeeds->RequiredErrorMessage));
                }
            }
            if ($this->TransmissAutomatic->Visible && $this->TransmissAutomatic->Required) {
                if ($this->TransmissAutomatic->FormValue == "") {
                    $this->TransmissAutomatic->addErrorMessage(str_replace("%s", $this->TransmissAutomatic->caption(), $this->TransmissAutomatic->RequiredErrorMessage));
                }
            }
            if ($this->MPGCity->Visible && $this->MPGCity->Required) {
                if (!$this->MPGCity->IsDetailKey && EmptyValue($this->MPGCity->FormValue)) {
                    $this->MPGCity->addErrorMessage(str_replace("%s", $this->MPGCity->caption(), $this->MPGCity->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->MPGCity->FormValue)) {
                $this->MPGCity->addErrorMessage($this->MPGCity->getErrorMessage(false));
            }
            if ($this->MPGHighway->Visible && $this->MPGHighway->Required) {
                if (!$this->MPGHighway->IsDetailKey && EmptyValue($this->MPGHighway->FormValue)) {
                    $this->MPGHighway->addErrorMessage(str_replace("%s", $this->MPGHighway->caption(), $this->MPGHighway->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->MPGHighway->FormValue)) {
                $this->MPGHighway->addErrorMessage($this->MPGHighway->getErrorMessage(false));
            }
            if ($this->Description->Visible && $this->Description->Required) {
                if (!$this->Description->IsDetailKey && EmptyValue($this->Description->FormValue)) {
                    $this->Description->addErrorMessage(str_replace("%s", $this->Description->caption(), $this->Description->RequiredErrorMessage));
                }
            }
            if ($this->Price->Visible && $this->Price->Required) {
                if (!$this->Price->IsDetailKey && EmptyValue($this->Price->FormValue)) {
                    $this->Price->addErrorMessage(str_replace("%s", $this->Price->caption(), $this->Price->RequiredErrorMessage));
                }
            }
            if (!CheckNumber($this->Price->FormValue)) {
                $this->Price->addErrorMessage($this->Price->getErrorMessage(false));
            }
            if ($this->Picture->Visible && $this->Picture->Required) {
                if ($this->Picture->Upload->FileName == "" && !$this->Picture->Upload->KeepFile) {
                    $this->Picture->addErrorMessage(str_replace("%s", $this->Picture->caption(), $this->Picture->RequiredErrorMessage));
                }
            }
            if ($this->Doors->Visible && $this->Doors->Required) {
                if (!$this->Doors->IsDetailKey && EmptyValue($this->Doors->FormValue)) {
                    $this->Doors->addErrorMessage(str_replace("%s", $this->Doors->caption(), $this->Doors->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->Doors->FormValue)) {
                $this->Doors->addErrorMessage($this->Doors->getErrorMessage(false));
            }
            if ($this->Torque->Visible && $this->Torque->Required) {
                if (!$this->Torque->IsDetailKey && EmptyValue($this->Torque->FormValue)) {
                    $this->Torque->addErrorMessage(str_replace("%s", $this->Torque->caption(), $this->Torque->RequiredErrorMessage));
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

        // Trademark
        $this->Trademark->setDbValueDef($rsnew, $this->Trademark->CurrentValue, strval($this->Trademark->CurrentValue) == "");

        // Model
        $this->Model->setDbValueDef($rsnew, $this->Model->CurrentValue, false);

        // HP
        $this->HP->setDbValueDef($rsnew, $this->HP->CurrentValue, false);

        // Cylinders
        $this->Cylinders->setDbValueDef($rsnew, $this->Cylinders->CurrentValue, false);

        // Transmission Speeds
        $this->TransmissionSpeeds->setDbValueDef($rsnew, $this->TransmissionSpeeds->CurrentValue, false);

        // TransmissAutomatic
        $tmpBool = $this->TransmissAutomatic->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->TransmissAutomatic->setDbValueDef($rsnew, $tmpBool, strval($this->TransmissAutomatic->CurrentValue) == "");

        // MPG City
        $this->MPGCity->setDbValueDef($rsnew, $this->MPGCity->CurrentValue, false);

        // MPG Highway
        $this->MPGHighway->setDbValueDef($rsnew, $this->MPGHighway->CurrentValue, false);

        // Description
        $this->Description->setDbValueDef($rsnew, $this->Description->CurrentValue, false);

        // Price
        $this->Price->setDbValueDef($rsnew, $this->Price->CurrentValue, false);

        // Picture
        if ($this->Picture->Visible && !$this->Picture->Upload->KeepFile) {
            if ($this->Picture->Upload->Value === null) {
                $rsnew['Picture'] = null;
            } else {
                $this->Picture->Upload->Resize(100, 0);
                $this->Picture->ImageWidth = 100; // Resize width
                $this->Picture->ImageHeight = 0; // Resize height
                $rsnew['Picture'] = $this->Picture->Upload->Value;
            }
        }

        // Doors
        $this->Doors->setDbValueDef($rsnew, $this->Doors->CurrentValue, false);

        // Torque
        $this->Torque->setDbValueDef($rsnew, $this->Torque->CurrentValue, false);
        return $rsnew;
    }

    /**
     * Restore add form from row
     * @param array $row Row
     */
    protected function restoreAddFormFromRow($row)
    {
        if (isset($row['Trademark'])) { // Trademark
            $this->Trademark->setFormValue($row['Trademark']);
        }
        if (isset($row['Model'])) { // Model
            $this->Model->setFormValue($row['Model']);
        }
        if (isset($row['HP'])) { // HP
            $this->HP->setFormValue($row['HP']);
        }
        if (isset($row['Cylinders'])) { // Cylinders
            $this->Cylinders->setFormValue($row['Cylinders']);
        }
        if (isset($row['Transmission Speeds'])) { // Transmission Speeds
            $this->TransmissionSpeeds->setFormValue($row['Transmission Speeds']);
        }
        if (isset($row['TransmissAutomatic'])) { // TransmissAutomatic
            $this->TransmissAutomatic->setFormValue($row['TransmissAutomatic']);
        }
        if (isset($row['MPG City'])) { // MPG City
            $this->MPGCity->setFormValue($row['MPG City']);
        }
        if (isset($row['MPG Highway'])) { // MPG Highway
            $this->MPGHighway->setFormValue($row['MPG Highway']);
        }
        if (isset($row['Description'])) { // Description
            $this->Description->setFormValue($row['Description']);
        }
        if (isset($row['Price'])) { // Price
            $this->Price->setFormValue($row['Price']);
        }
        if (isset($row['Picture'])) { // Picture
            $this->Picture->setFormValue($row['Picture']);
        }
        if (isset($row['Doors'])) { // Doors
            $this->Doors->setFormValue($row['Doors']);
        }
        if (isset($row['Torque'])) { // Torque
            $this->Torque->setFormValue($row['Torque']);
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home2");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("carslist"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Set up multi pages
    protected function setupMultiPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        if ($pages->isAccordion()) {
            $pages->Parent = "#accordion_" . $this->PageObjName;
        }
        $pages->add(0);
        $pages->add(1);
        $pages->add(2);
        $pages->add(3);
        $this->MultiPages = $pages;
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
                case "x_Trademark":
                    break;
                case "x_Model":
                    break;
                case "x_TransmissAutomatic":
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
