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
class SuppliersUpdate extends Suppliers
{
    use MessagesTrait;

    // Page ID
    public $PageID = "update";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "SuppliersUpdate";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "suppliersupdate";

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
        $this->SupplierID->setVisibility();
        $this->CompanyName->setVisibility();
        $this->ContactName->setVisibility();
        $this->ContactTitle->setVisibility();
        $this->Address->setVisibility();
        $this->City->setVisibility();
        $this->Region->setVisibility();
        $this->PostalCode->setVisibility();
        $this->Country->setVisibility();
        $this->Phone->setVisibility();
        $this->Fax->setVisibility();
        $this->HomePage->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'suppliers';
        $this->TableName = 'suppliers';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-update-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (suppliers)
        if (!isset($GLOBALS["suppliers"]) || $GLOBALS["suppliers"]::class == PROJECT_NAMESPACE . "suppliers") {
            $GLOBALS["suppliers"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'suppliers');
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
                        $result["view"] = SameString($pageName, "suppliersview"); // If View page, no primary button
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
            $key .= @$ar['SupplierID'];
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
            $this->SupplierID->Visible = false;
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
    public $FormClassName = "ew-form ew-update-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $RecKeys;
    public $Disabled;
    public $UpdateCount = 0;

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

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Try to load keys from list form
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        if (Post("action") !== null && Post("action") !== "") {
            // Get action
            $this->CurrentAction = Post("action");
            $this->loadFormValues(); // Get form values

            // Validate form
            if (!$this->validateForm()) {
                $this->CurrentAction = "show"; // Form error, reset action
                if (!$this->hasInvalidFields()) { // No fields selected
                    $this->setFailureMessage($Language->phrase("NoFieldSelected"));
                }
            }
        } else {
            $this->loadMultiUpdateValues(); // Load initial values to form
        }
        if (count($this->RecKeys) <= 0) {
            $this->terminate("supplierslist"); // No records selected, return to list
            return;
        }
        if ($this->isUpdate()) {
                if ($this->updateRows()) { // Update Records based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Set up update success message
                    }

                    // Do not return Json for UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                    }
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson([
                        "success" => false,
                        "validation" => $this->getValidationErrors(),
                        "error" => $this->getFailureMessage()
                    ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } else {
                    $this->restoreFormValues(); // Restore form values
                }
        }

        // Render row
        $this->RowType = RowType::EDIT; // Render edit
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

    // Load initial values to form if field values are identical in all selected records
    protected function loadMultiUpdateValues()
    {
        $this->CurrentFilter = $this->getFilterFromRecordKeys();

        // Load result set
        if ($rs = $this->loadRecordset()) {
            $i = 1;
            while ($row = $rs->fetch()) {
                if ($i == 1) {
                    $this->SupplierID->setDbValue($row['SupplierID']);
                    $this->CompanyName->setDbValue($row['CompanyName']);
                    $this->ContactName->setDbValue($row['ContactName']);
                    $this->ContactTitle->setDbValue($row['ContactTitle']);
                    $this->Address->setDbValue($row['Address']);
                    $this->City->setDbValue($row['City']);
                    $this->Region->setDbValue($row['Region']);
                    $this->PostalCode->setDbValue($row['PostalCode']);
                    $this->Country->setDbValue($row['Country']);
                    $this->Phone->setDbValue($row['Phone']);
                    $this->Fax->setDbValue($row['Fax']);
                    $this->HomePage->setDbValue($row['HomePage']);
                } else {
                    if (!CompareValue($this->SupplierID->DbValue, $row['SupplierID'])) {
                        $this->SupplierID->CurrentValue = null;
                    }
                    if (!CompareValue($this->CompanyName->DbValue, $row['CompanyName'])) {
                        $this->CompanyName->CurrentValue = null;
                    }
                    if (!CompareValue($this->ContactName->DbValue, $row['ContactName'])) {
                        $this->ContactName->CurrentValue = null;
                    }
                    if (!CompareValue($this->ContactTitle->DbValue, $row['ContactTitle'])) {
                        $this->ContactTitle->CurrentValue = null;
                    }
                    if (!CompareValue($this->Address->DbValue, $row['Address'])) {
                        $this->Address->CurrentValue = null;
                    }
                    if (!CompareValue($this->City->DbValue, $row['City'])) {
                        $this->City->CurrentValue = null;
                    }
                    if (!CompareValue($this->Region->DbValue, $row['Region'])) {
                        $this->Region->CurrentValue = null;
                    }
                    if (!CompareValue($this->PostalCode->DbValue, $row['PostalCode'])) {
                        $this->PostalCode->CurrentValue = null;
                    }
                    if (!CompareValue($this->Country->DbValue, $row['Country'])) {
                        $this->Country->CurrentValue = null;
                    }
                    if (!CompareValue($this->Phone->DbValue, $row['Phone'])) {
                        $this->Phone->CurrentValue = null;
                    }
                    if (!CompareValue($this->Fax->DbValue, $row['Fax'])) {
                        $this->Fax->CurrentValue = null;
                    }
                    if (!CompareValue($this->HomePage->DbValue, $row['HomePage'])) {
                        $this->HomePage->CurrentValue = null;
                    }
                }
                $i++;
            }
            $rs->free();
        }
    }

    // Set up key value
    protected function setupKeyValues($key)
    {
        $keyFld = $key;
        if (!is_numeric($keyFld)) {
            return false;
        }
        $this->SupplierID->OldValue = $keyFld;
        return true;
    }

    // Update all selected rows
    protected function updateRows()
    {
        global $Language;
        $conn = $this->getConnection();
        if ($this->UseTransaction) {
            $conn->beginTransaction();
        }

        // Get old records
        $this->CurrentFilter = $this->getFilterFromRecordKeys(false);
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAllAssociative($sql);

        // Update all rows
        $successKeys = [];
        $failKeys = [];
        foreach ($this->RecKeys as $reckey) {
            if ($this->setupKeyValues($reckey)) {
                $thisKey = $reckey;
                $this->SendEmail = false; // Do not send email on update success
                $this->UpdateCount += 1; // Update record count for records being updated
                $rowUpdated = $this->editRow(); // Update this row
            } else {
                $rowUpdated = false;
            }
            if (!$rowUpdated) {
                if ($this->UseTransaction) { // Update failed
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $thisKey;
            } else {
                $successKeys[] = $thisKey;
            }
        }

        // Check if any rows updated
        if (count($successKeys) > 0) {
            if ($this->UseTransaction) { // Commit transaction
                if ($conn->isTransactionActive()) {
                    $conn->commit();
                }
            }

            // Set warning message if update some records failed
            if (count($failKeys) > 0) {
                $this->setWarningMessage(str_replace("%k", explode(", ", $failKeys), $Language->phrase("UpdateSomeRecordsFailed")));
            }

            // Get new records
            $rsnew = $conn->fetchAllAssociative($sql);
            return true;
        } else {
            if ($this->UseTransaction) { // Rollback transaction
                if ($conn->isTransactionActive()) {
                    $conn->rollback();
                }
            }
            return false;
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'SupplierID' first before field var 'x_SupplierID'
        $val = $CurrentForm->hasValue("SupplierID") ? $CurrentForm->getValue("SupplierID") : $CurrentForm->getValue("x_SupplierID");
        if (!$this->SupplierID->IsDetailKey) {
            $this->SupplierID->setFormValue($val);
        }

        // Check field name 'CompanyName' first before field var 'x_CompanyName'
        $val = $CurrentForm->hasValue("CompanyName") ? $CurrentForm->getValue("CompanyName") : $CurrentForm->getValue("x_CompanyName");
        if (!$this->CompanyName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CompanyName->Visible = false; // Disable update for API request
            } else {
                $this->CompanyName->setFormValue($val);
            }
        }
        $this->CompanyName->MultiUpdate = $CurrentForm->getValue("u_CompanyName");

        // Check field name 'ContactName' first before field var 'x_ContactName'
        $val = $CurrentForm->hasValue("ContactName") ? $CurrentForm->getValue("ContactName") : $CurrentForm->getValue("x_ContactName");
        if (!$this->ContactName->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ContactName->Visible = false; // Disable update for API request
            } else {
                $this->ContactName->setFormValue($val);
            }
        }
        $this->ContactName->MultiUpdate = $CurrentForm->getValue("u_ContactName");

        // Check field name 'ContactTitle' first before field var 'x_ContactTitle'
        $val = $CurrentForm->hasValue("ContactTitle") ? $CurrentForm->getValue("ContactTitle") : $CurrentForm->getValue("x_ContactTitle");
        if (!$this->ContactTitle->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ContactTitle->Visible = false; // Disable update for API request
            } else {
                $this->ContactTitle->setFormValue($val);
            }
        }
        $this->ContactTitle->MultiUpdate = $CurrentForm->getValue("u_ContactTitle");

        // Check field name 'Address' first before field var 'x_Address'
        $val = $CurrentForm->hasValue("Address") ? $CurrentForm->getValue("Address") : $CurrentForm->getValue("x_Address");
        if (!$this->Address->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Address->Visible = false; // Disable update for API request
            } else {
                $this->Address->setFormValue($val);
            }
        }
        $this->Address->MultiUpdate = $CurrentForm->getValue("u_Address");

        // Check field name 'City' first before field var 'x_City'
        $val = $CurrentForm->hasValue("City") ? $CurrentForm->getValue("City") : $CurrentForm->getValue("x_City");
        if (!$this->City->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->City->Visible = false; // Disable update for API request
            } else {
                $this->City->setFormValue($val);
            }
        }
        $this->City->MultiUpdate = $CurrentForm->getValue("u_City");

        // Check field name 'Region' first before field var 'x_Region'
        $val = $CurrentForm->hasValue("Region") ? $CurrentForm->getValue("Region") : $CurrentForm->getValue("x_Region");
        if (!$this->Region->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Region->Visible = false; // Disable update for API request
            } else {
                $this->Region->setFormValue($val);
            }
        }
        $this->Region->MultiUpdate = $CurrentForm->getValue("u_Region");

        // Check field name 'PostalCode' first before field var 'x_PostalCode'
        $val = $CurrentForm->hasValue("PostalCode") ? $CurrentForm->getValue("PostalCode") : $CurrentForm->getValue("x_PostalCode");
        if (!$this->PostalCode->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PostalCode->Visible = false; // Disable update for API request
            } else {
                $this->PostalCode->setFormValue($val);
            }
        }
        $this->PostalCode->MultiUpdate = $CurrentForm->getValue("u_PostalCode");

        // Check field name 'Country' first before field var 'x_Country'
        $val = $CurrentForm->hasValue("Country") ? $CurrentForm->getValue("Country") : $CurrentForm->getValue("x_Country");
        if (!$this->Country->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Country->Visible = false; // Disable update for API request
            } else {
                $this->Country->setFormValue($val);
            }
        }
        $this->Country->MultiUpdate = $CurrentForm->getValue("u_Country");

        // Check field name 'Phone' first before field var 'x_Phone'
        $val = $CurrentForm->hasValue("Phone") ? $CurrentForm->getValue("Phone") : $CurrentForm->getValue("x_Phone");
        if (!$this->Phone->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Phone->Visible = false; // Disable update for API request
            } else {
                $this->Phone->setFormValue($val);
            }
        }
        $this->Phone->MultiUpdate = $CurrentForm->getValue("u_Phone");

        // Check field name 'Fax' first before field var 'x_Fax'
        $val = $CurrentForm->hasValue("Fax") ? $CurrentForm->getValue("Fax") : $CurrentForm->getValue("x_Fax");
        if (!$this->Fax->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Fax->Visible = false; // Disable update for API request
            } else {
                $this->Fax->setFormValue($val);
            }
        }
        $this->Fax->MultiUpdate = $CurrentForm->getValue("u_Fax");

        // Check field name 'HomePage' first before field var 'x_HomePage'
        $val = $CurrentForm->hasValue("HomePage") ? $CurrentForm->getValue("HomePage") : $CurrentForm->getValue("x_HomePage");
        if (!$this->HomePage->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->HomePage->Visible = false; // Disable update for API request
            } else {
                $this->HomePage->setFormValue($val);
            }
        }
        $this->HomePage->MultiUpdate = $CurrentForm->getValue("u_HomePage");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->SupplierID->CurrentValue = $this->SupplierID->FormValue;
        $this->CompanyName->CurrentValue = $this->CompanyName->FormValue;
        $this->ContactName->CurrentValue = $this->ContactName->FormValue;
        $this->ContactTitle->CurrentValue = $this->ContactTitle->FormValue;
        $this->Address->CurrentValue = $this->Address->FormValue;
        $this->City->CurrentValue = $this->City->FormValue;
        $this->Region->CurrentValue = $this->Region->FormValue;
        $this->PostalCode->CurrentValue = $this->PostalCode->FormValue;
        $this->Country->CurrentValue = $this->Country->FormValue;
        $this->Phone->CurrentValue = $this->Phone->FormValue;
        $this->Fax->CurrentValue = $this->Fax->FormValue;
        $this->HomePage->CurrentValue = $this->HomePage->FormValue;
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
        $this->SupplierID->setDbValue($row['SupplierID']);
        $this->CompanyName->setDbValue($row['CompanyName']);
        $this->ContactName->setDbValue($row['ContactName']);
        $this->ContactTitle->setDbValue($row['ContactTitle']);
        $this->Address->setDbValue($row['Address']);
        $this->City->setDbValue($row['City']);
        $this->Region->setDbValue($row['Region']);
        $this->PostalCode->setDbValue($row['PostalCode']);
        $this->Country->setDbValue($row['Country']);
        $this->Phone->setDbValue($row['Phone']);
        $this->Fax->setDbValue($row['Fax']);
        $this->HomePage->setDbValue($row['HomePage']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['SupplierID'] = $this->SupplierID->DefaultValue;
        $row['CompanyName'] = $this->CompanyName->DefaultValue;
        $row['ContactName'] = $this->ContactName->DefaultValue;
        $row['ContactTitle'] = $this->ContactTitle->DefaultValue;
        $row['Address'] = $this->Address->DefaultValue;
        $row['City'] = $this->City->DefaultValue;
        $row['Region'] = $this->Region->DefaultValue;
        $row['PostalCode'] = $this->PostalCode->DefaultValue;
        $row['Country'] = $this->Country->DefaultValue;
        $row['Phone'] = $this->Phone->DefaultValue;
        $row['Fax'] = $this->Fax->DefaultValue;
        $row['HomePage'] = $this->HomePage->DefaultValue;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // SupplierID
        $this->SupplierID->RowCssClass = "row";

        // CompanyName
        $this->CompanyName->RowCssClass = "row";

        // ContactName
        $this->ContactName->RowCssClass = "row";

        // ContactTitle
        $this->ContactTitle->RowCssClass = "row";

        // Address
        $this->Address->RowCssClass = "row";

        // City
        $this->City->RowCssClass = "row";

        // Region
        $this->Region->RowCssClass = "row";

        // PostalCode
        $this->PostalCode->RowCssClass = "row";

        // Country
        $this->Country->RowCssClass = "row";

        // Phone
        $this->Phone->RowCssClass = "row";

        // Fax
        $this->Fax->RowCssClass = "row";

        // HomePage
        $this->HomePage->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // SupplierID
            $this->SupplierID->ViewValue = $this->SupplierID->CurrentValue;

            // CompanyName
            $this->CompanyName->ViewValue = $this->CompanyName->CurrentValue;

            // ContactName
            $this->ContactName->ViewValue = $this->ContactName->CurrentValue;

            // ContactTitle
            $this->ContactTitle->ViewValue = $this->ContactTitle->CurrentValue;

            // Address
            $this->Address->ViewValue = $this->Address->CurrentValue;

            // City
            $this->City->ViewValue = $this->City->CurrentValue;

            // Region
            $this->Region->ViewValue = $this->Region->CurrentValue;

            // PostalCode
            $this->PostalCode->ViewValue = $this->PostalCode->CurrentValue;

            // Country
            $this->Country->ViewValue = $this->Country->CurrentValue;

            // Phone
            $this->Phone->ViewValue = $this->Phone->CurrentValue;

            // Fax
            $this->Fax->ViewValue = $this->Fax->CurrentValue;

            // HomePage
            $this->HomePage->ViewValue = $this->HomePage->CurrentValue;
            if ($this->HomePage->ViewValue != null) {
                $this->HomePage->ViewValue = str_replace(["\r\n", "\n", "\r"], "<br>", $this->HomePage->ViewValue);
            }

            // SupplierID
            $this->SupplierID->HrefValue = "";
            $this->SupplierID->TooltipValue = "";

            // CompanyName
            $this->CompanyName->HrefValue = "";
            $this->CompanyName->TooltipValue = "";

            // ContactName
            $this->ContactName->HrefValue = "";
            $this->ContactName->TooltipValue = "";

            // ContactTitle
            $this->ContactTitle->HrefValue = "";
            $this->ContactTitle->TooltipValue = "";

            // Address
            $this->Address->HrefValue = "";
            $this->Address->TooltipValue = "";

            // City
            $this->City->HrefValue = "";
            $this->City->TooltipValue = "";

            // Region
            $this->Region->HrefValue = "";
            $this->Region->TooltipValue = "";

            // PostalCode
            $this->PostalCode->HrefValue = "";
            $this->PostalCode->TooltipValue = "";

            // Country
            $this->Country->HrefValue = "";
            $this->Country->TooltipValue = "";

            // Phone
            $this->Phone->HrefValue = "";
            $this->Phone->TooltipValue = "";

            // Fax
            $this->Fax->HrefValue = "";
            $this->Fax->TooltipValue = "";

            // HomePage
            $this->HomePage->HrefValue = "";
            $this->HomePage->TooltipValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // SupplierID
            $this->SupplierID->setupEditAttributes();
            $this->SupplierID->EditValue = $this->SupplierID->CurrentValue;

            // CompanyName
            $this->CompanyName->setupEditAttributes();
            if (!$this->CompanyName->Raw) {
                $this->CompanyName->CurrentValue = HtmlDecode($this->CompanyName->CurrentValue);
            }
            $this->CompanyName->EditValue = HtmlEncode($this->CompanyName->CurrentValue);
            $this->CompanyName->PlaceHolder = RemoveHtml($this->CompanyName->caption());

            // ContactName
            $this->ContactName->setupEditAttributes();
            if (!$this->ContactName->Raw) {
                $this->ContactName->CurrentValue = HtmlDecode($this->ContactName->CurrentValue);
            }
            $this->ContactName->EditValue = HtmlEncode($this->ContactName->CurrentValue);
            $this->ContactName->PlaceHolder = RemoveHtml($this->ContactName->caption());

            // ContactTitle
            $this->ContactTitle->setupEditAttributes();
            if (!$this->ContactTitle->Raw) {
                $this->ContactTitle->CurrentValue = HtmlDecode($this->ContactTitle->CurrentValue);
            }
            $this->ContactTitle->EditValue = HtmlEncode($this->ContactTitle->CurrentValue);
            $this->ContactTitle->PlaceHolder = RemoveHtml($this->ContactTitle->caption());

            // Address
            $this->Address->setupEditAttributes();
            if (!$this->Address->Raw) {
                $this->Address->CurrentValue = HtmlDecode($this->Address->CurrentValue);
            }
            $this->Address->EditValue = HtmlEncode($this->Address->CurrentValue);
            $this->Address->PlaceHolder = RemoveHtml($this->Address->caption());

            // City
            $this->City->setupEditAttributes();
            if (!$this->City->Raw) {
                $this->City->CurrentValue = HtmlDecode($this->City->CurrentValue);
            }
            $this->City->EditValue = HtmlEncode($this->City->CurrentValue);
            $this->City->PlaceHolder = RemoveHtml($this->City->caption());

            // Region
            $this->Region->setupEditAttributes();
            if (!$this->Region->Raw) {
                $this->Region->CurrentValue = HtmlDecode($this->Region->CurrentValue);
            }
            $this->Region->EditValue = HtmlEncode($this->Region->CurrentValue);
            $this->Region->PlaceHolder = RemoveHtml($this->Region->caption());

            // PostalCode
            $this->PostalCode->setupEditAttributes();
            if (!$this->PostalCode->Raw) {
                $this->PostalCode->CurrentValue = HtmlDecode($this->PostalCode->CurrentValue);
            }
            $this->PostalCode->EditValue = HtmlEncode($this->PostalCode->CurrentValue);
            $this->PostalCode->PlaceHolder = RemoveHtml($this->PostalCode->caption());

            // Country
            $this->Country->setupEditAttributes();
            if (!$this->Country->Raw) {
                $this->Country->CurrentValue = HtmlDecode($this->Country->CurrentValue);
            }
            $this->Country->EditValue = HtmlEncode($this->Country->CurrentValue);
            $this->Country->PlaceHolder = RemoveHtml($this->Country->caption());

            // Phone
            $this->Phone->setupEditAttributes();
            if (!$this->Phone->Raw) {
                $this->Phone->CurrentValue = HtmlDecode($this->Phone->CurrentValue);
            }
            $this->Phone->EditValue = HtmlEncode($this->Phone->CurrentValue);
            $this->Phone->PlaceHolder = RemoveHtml($this->Phone->caption());

            // Fax
            $this->Fax->setupEditAttributes();
            if (!$this->Fax->Raw) {
                $this->Fax->CurrentValue = HtmlDecode($this->Fax->CurrentValue);
            }
            $this->Fax->EditValue = HtmlEncode($this->Fax->CurrentValue);
            $this->Fax->PlaceHolder = RemoveHtml($this->Fax->caption());

            // HomePage
            $this->HomePage->setupEditAttributes();
            $this->HomePage->EditValue = HtmlEncode($this->HomePage->CurrentValue);
            $this->HomePage->PlaceHolder = RemoveHtml($this->HomePage->caption());

            // Edit refer script

            // SupplierID
            $this->SupplierID->HrefValue = "";

            // CompanyName
            $this->CompanyName->HrefValue = "";

            // ContactName
            $this->ContactName->HrefValue = "";

            // ContactTitle
            $this->ContactTitle->HrefValue = "";

            // Address
            $this->Address->HrefValue = "";

            // City
            $this->City->HrefValue = "";

            // Region
            $this->Region->HrefValue = "";

            // PostalCode
            $this->PostalCode->HrefValue = "";

            // Country
            $this->Country->HrefValue = "";

            // Phone
            $this->Phone->HrefValue = "";

            // Fax
            $this->Fax->HrefValue = "";

            // HomePage
            $this->HomePage->HrefValue = "";
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
        $updateCnt = 0;
        if ($this->SupplierID->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->CompanyName->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->ContactName->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->ContactTitle->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Address->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->City->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Region->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->PostalCode->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Country->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Phone->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->Fax->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->HomePage->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($updateCnt == 0) {
            return false;
        }

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
            if ($this->SupplierID->Visible && $this->SupplierID->Required) {
                if ($this->SupplierID->MultiUpdate != "" && !$this->SupplierID->IsDetailKey && EmptyValue($this->SupplierID->FormValue)) {
                    $this->SupplierID->addErrorMessage(str_replace("%s", $this->SupplierID->caption(), $this->SupplierID->RequiredErrorMessage));
                }
            }
            if ($this->CompanyName->Visible && $this->CompanyName->Required) {
                if ($this->CompanyName->MultiUpdate != "" && !$this->CompanyName->IsDetailKey && EmptyValue($this->CompanyName->FormValue)) {
                    $this->CompanyName->addErrorMessage(str_replace("%s", $this->CompanyName->caption(), $this->CompanyName->RequiredErrorMessage));
                }
            }
            if ($this->ContactName->Visible && $this->ContactName->Required) {
                if ($this->ContactName->MultiUpdate != "" && !$this->ContactName->IsDetailKey && EmptyValue($this->ContactName->FormValue)) {
                    $this->ContactName->addErrorMessage(str_replace("%s", $this->ContactName->caption(), $this->ContactName->RequiredErrorMessage));
                }
            }
            if ($this->ContactTitle->Visible && $this->ContactTitle->Required) {
                if ($this->ContactTitle->MultiUpdate != "" && !$this->ContactTitle->IsDetailKey && EmptyValue($this->ContactTitle->FormValue)) {
                    $this->ContactTitle->addErrorMessage(str_replace("%s", $this->ContactTitle->caption(), $this->ContactTitle->RequiredErrorMessage));
                }
            }
            if ($this->Address->Visible && $this->Address->Required) {
                if ($this->Address->MultiUpdate != "" && !$this->Address->IsDetailKey && EmptyValue($this->Address->FormValue)) {
                    $this->Address->addErrorMessage(str_replace("%s", $this->Address->caption(), $this->Address->RequiredErrorMessage));
                }
            }
            if ($this->City->Visible && $this->City->Required) {
                if ($this->City->MultiUpdate != "" && !$this->City->IsDetailKey && EmptyValue($this->City->FormValue)) {
                    $this->City->addErrorMessage(str_replace("%s", $this->City->caption(), $this->City->RequiredErrorMessage));
                }
            }
            if ($this->Region->Visible && $this->Region->Required) {
                if ($this->Region->MultiUpdate != "" && !$this->Region->IsDetailKey && EmptyValue($this->Region->FormValue)) {
                    $this->Region->addErrorMessage(str_replace("%s", $this->Region->caption(), $this->Region->RequiredErrorMessage));
                }
            }
            if ($this->PostalCode->Visible && $this->PostalCode->Required) {
                if ($this->PostalCode->MultiUpdate != "" && !$this->PostalCode->IsDetailKey && EmptyValue($this->PostalCode->FormValue)) {
                    $this->PostalCode->addErrorMessage(str_replace("%s", $this->PostalCode->caption(), $this->PostalCode->RequiredErrorMessage));
                }
            }
            if ($this->Country->Visible && $this->Country->Required) {
                if ($this->Country->MultiUpdate != "" && !$this->Country->IsDetailKey && EmptyValue($this->Country->FormValue)) {
                    $this->Country->addErrorMessage(str_replace("%s", $this->Country->caption(), $this->Country->RequiredErrorMessage));
                }
            }
            if ($this->Phone->Visible && $this->Phone->Required) {
                if ($this->Phone->MultiUpdate != "" && !$this->Phone->IsDetailKey && EmptyValue($this->Phone->FormValue)) {
                    $this->Phone->addErrorMessage(str_replace("%s", $this->Phone->caption(), $this->Phone->RequiredErrorMessage));
                }
            }
            if ($this->Fax->Visible && $this->Fax->Required) {
                if ($this->Fax->MultiUpdate != "" && !$this->Fax->IsDetailKey && EmptyValue($this->Fax->FormValue)) {
                    $this->Fax->addErrorMessage(str_replace("%s", $this->Fax->caption(), $this->Fax->RequiredErrorMessage));
                }
            }
            if ($this->HomePage->Visible && $this->HomePage->Required) {
                if ($this->HomePage->MultiUpdate != "" && !$this->HomePage->IsDetailKey && EmptyValue($this->HomePage->FormValue)) {
                    $this->HomePage->addErrorMessage(str_replace("%s", $this->HomePage->caption(), $this->HomePage->RequiredErrorMessage));
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

        // CompanyName
        $this->CompanyName->setDbValueDef($rsnew, $this->CompanyName->CurrentValue, $this->CompanyName->ReadOnly || $this->CompanyName->MultiUpdate != "1");

        // ContactName
        $this->ContactName->setDbValueDef($rsnew, $this->ContactName->CurrentValue, $this->ContactName->ReadOnly || $this->ContactName->MultiUpdate != "1");

        // ContactTitle
        $this->ContactTitle->setDbValueDef($rsnew, $this->ContactTitle->CurrentValue, $this->ContactTitle->ReadOnly || $this->ContactTitle->MultiUpdate != "1");

        // Address
        $this->Address->setDbValueDef($rsnew, $this->Address->CurrentValue, $this->Address->ReadOnly || $this->Address->MultiUpdate != "1");

        // City
        $this->City->setDbValueDef($rsnew, $this->City->CurrentValue, $this->City->ReadOnly || $this->City->MultiUpdate != "1");

        // Region
        $this->Region->setDbValueDef($rsnew, $this->Region->CurrentValue, $this->Region->ReadOnly || $this->Region->MultiUpdate != "1");

        // PostalCode
        $this->PostalCode->setDbValueDef($rsnew, $this->PostalCode->CurrentValue, $this->PostalCode->ReadOnly || $this->PostalCode->MultiUpdate != "1");

        // Country
        $this->Country->setDbValueDef($rsnew, $this->Country->CurrentValue, $this->Country->ReadOnly || $this->Country->MultiUpdate != "1");

        // Phone
        $this->Phone->setDbValueDef($rsnew, $this->Phone->CurrentValue, $this->Phone->ReadOnly || $this->Phone->MultiUpdate != "1");

        // Fax
        $this->Fax->setDbValueDef($rsnew, $this->Fax->CurrentValue, $this->Fax->ReadOnly || $this->Fax->MultiUpdate != "1");

        // HomePage
        $this->HomePage->setDbValueDef($rsnew, $this->HomePage->CurrentValue, $this->HomePage->ReadOnly || $this->HomePage->MultiUpdate != "1");
        return $rsnew;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow($row)
    {
        if (isset($row['CompanyName'])) { // CompanyName
            $this->CompanyName->CurrentValue = $row['CompanyName'];
        }
        if (isset($row['ContactName'])) { // ContactName
            $this->ContactName->CurrentValue = $row['ContactName'];
        }
        if (isset($row['ContactTitle'])) { // ContactTitle
            $this->ContactTitle->CurrentValue = $row['ContactTitle'];
        }
        if (isset($row['Address'])) { // Address
            $this->Address->CurrentValue = $row['Address'];
        }
        if (isset($row['City'])) { // City
            $this->City->CurrentValue = $row['City'];
        }
        if (isset($row['Region'])) { // Region
            $this->Region->CurrentValue = $row['Region'];
        }
        if (isset($row['PostalCode'])) { // PostalCode
            $this->PostalCode->CurrentValue = $row['PostalCode'];
        }
        if (isset($row['Country'])) { // Country
            $this->Country->CurrentValue = $row['Country'];
        }
        if (isset($row['Phone'])) { // Phone
            $this->Phone->CurrentValue = $row['Phone'];
        }
        if (isset($row['Fax'])) { // Fax
            $this->Fax->CurrentValue = $row['Fax'];
        }
        if (isset($row['HomePage'])) { // HomePage
            $this->HomePage->CurrentValue = $row['HomePage'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home2");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("supplierslist"), "", $this->TableVar, true);
        $pageId = "update";
        $Breadcrumb->add("update", $pageId, $url);
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
