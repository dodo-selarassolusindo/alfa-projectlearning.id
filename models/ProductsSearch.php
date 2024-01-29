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
class ProductsSearch extends Products
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "ProductsSearch";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "productssearch";

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
        $this->ProductID->setVisibility();
        $this->ProductName->setVisibility();
        $this->SupplierID->setVisibility();
        $this->CategoryID->setVisibility();
        $this->QuantityPerUnit->setVisibility();
        $this->UnitPrice->setVisibility();
        $this->UnitsInStock->setVisibility();
        $this->UnitsOnOrder->setVisibility();
        $this->ReorderLevel->setVisibility();
        $this->Discontinued->setVisibility();
        $this->EAN13->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'products';
        $this->TableName = 'products';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-search-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("app.language");

        // Table object (products)
        if (!isset($GLOBALS["products"]) || $GLOBALS["products"]::class == PROJECT_NAMESPACE . "products") {
            $GLOBALS["products"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'products');
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
                if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                    $result["caption"] = $this->getModalCaption($pageName);
                    $result["view"] = SameString($pageName, "productsview"); // If View page, no primary button
                } else { // List page
                    $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    $this->clearFailureMessage();
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
            $key .= @$ar['ProductID'];
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
            $this->ProductID->Visible = false;
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
    public $FormClassName = "ew-form ew-search-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;

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

        // Set up lookup cache
        $this->setupLookupOptions($this->SupplierID);
        $this->setupLookupOptions($this->CategoryID);
        $this->setupLookupOptions($this->Discontinued);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Get action
        $this->CurrentAction = Post("action");
        if ($this->isSearch()) {
            // Build search string for advanced search, remove blank field
            $this->loadSearchValues(); // Get search values
            $srchStr = $this->validateSearch() ? $this->buildAdvancedSearch() : "";
            if ($srchStr != "") {
                $srchStr = "productslist" . "?" . $srchStr;
                // Do not return Json for UseAjaxActions
                if ($this->IsModal && $this->UseAjaxActions) {
                    $this->IsModal = false;
                }
                $this->terminate($srchStr); // Go to list page
                return;
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Render row for search
        $this->RowType = RowType::SEARCH;
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

    // Build advanced search
    protected function buildAdvancedSearch()
    {
        $srchUrl = "";
        $this->buildSearchUrl($srchUrl, $this->ProductID); // ProductID
        $this->buildSearchUrl($srchUrl, $this->ProductName); // ProductName
        $this->buildSearchUrl($srchUrl, $this->SupplierID); // SupplierID
        $this->buildSearchUrl($srchUrl, $this->CategoryID); // CategoryID
        $this->buildSearchUrl($srchUrl, $this->QuantityPerUnit); // QuantityPerUnit
        $this->buildSearchUrl($srchUrl, $this->UnitPrice); // UnitPrice
        $this->buildSearchUrl($srchUrl, $this->UnitsInStock); // UnitsInStock
        $this->buildSearchUrl($srchUrl, $this->UnitsOnOrder); // UnitsOnOrder
        $this->buildSearchUrl($srchUrl, $this->ReorderLevel); // ReorderLevel
        $this->buildSearchUrl($srchUrl, $this->Discontinued, true); // Discontinued
        $this->buildSearchUrl($srchUrl, $this->EAN13); // EAN13
        if ($srchUrl != "") {
            $srchUrl .= "&";
        }
        $srchUrl .= "cmd=search";
        return $srchUrl;
    }

    // Build search URL
    protected function buildSearchUrl(&$url, $fld, $oprOnly = false)
    {
        global $CurrentForm;
        $wrk = "";
        $fldParm = $fld->Param;
        [
            "value" => $fldVal,
            "operator" => $fldOpr,
            "condition" => $fldCond,
            "value2" => $fldVal2,
            "operator2" => $fldOpr2
        ] = $CurrentForm->getSearchValues($fldParm);
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        $fldDataType = $fld->DataType;
        $value = ConvertSearchValue($fldVal, $fldOpr, $fld); // For testing if numeric only
        $value2 = ConvertSearchValue($fldVal2, $fldOpr2, $fld); // For testing if numeric only
        $fldOpr = ConvertSearchOperator($fldOpr, $fld, $value);
        $fldOpr2 = ConvertSearchOperator($fldOpr2, $fld, $value2);
        if (in_array($fldOpr, ["BETWEEN", "NOT BETWEEN"])) {
            $isValidValue = $fldDataType != DataType::NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value, $fldOpr, $fld) && IsNumericSearchValue($value2, $fldOpr2, $fld);
            if ($fldVal != "" && $fldVal2 != "" && $isValidValue) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) . "&y_" . $fldParm . "=" . urlencode($fldVal2) . "&z_" . $fldParm . "=" . urlencode($fldOpr);
            }
        } else {
            $isValidValue = $fldDataType != DataType::NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value, $fldOpr, $fld);
            if ($fldVal != "" && $isValidValue && IsValidOperator($fldOpr)) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) . "&z_" . $fldParm . "=" . urlencode($fldOpr);
            } elseif (in_array($fldOpr, ["IS NULL", "IS NOT NULL", "IS EMPTY", "IS NOT EMPTY"]) || ($fldOpr != "" && $oprOnly && IsValidOperator($fldOpr))) {
                $wrk = "z_" . $fldParm . "=" . urlencode($fldOpr);
            }
            $isValidValue = $fldDataType != DataType::NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value2, $fldOpr2, $fld);
            if ($fldVal2 != "" && $isValidValue && IsValidOperator($fldOpr2)) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "y_" . $fldParm . "=" . urlencode($fldVal2) . "&w_" . $fldParm . "=" . urlencode($fldOpr2);
            } elseif (in_array($fldOpr2, ["IS NULL", "IS NOT NULL", "IS EMPTY", "IS NOT EMPTY"]) || ($fldOpr2 != "" && $oprOnly && IsValidOperator($fldOpr2))) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "w_" . $fldParm . "=" . urlencode($fldOpr2);
            }
        }
        if ($wrk != "") {
            if ($url != "") {
                $url .= "&";
            }
            $url .= $wrk;
        }
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // ProductID
        if ($this->ProductID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ProductName
        if ($this->ProductName->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // SupplierID
        if ($this->SupplierID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // CategoryID
        if ($this->CategoryID->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // QuantityPerUnit
        if ($this->QuantityPerUnit->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // UnitPrice
        if ($this->UnitPrice->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // UnitsInStock
        if ($this->UnitsInStock->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // UnitsOnOrder
        if ($this->UnitsOnOrder->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // ReorderLevel
        if ($this->ReorderLevel->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // Discontinued
        if ($this->Discontinued->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->Discontinued->AdvancedSearch->SearchValue)) {
            $this->Discontinued->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Discontinued->AdvancedSearch->SearchValue);
        }
        if (is_array($this->Discontinued->AdvancedSearch->SearchValue2)) {
            $this->Discontinued->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->Discontinued->AdvancedSearch->SearchValue2);
        }

        // EAN13
        if ($this->EAN13->AdvancedSearch->get()) {
            $hasValue = true;
        }
        return $hasValue;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // ProductID
        $this->ProductID->RowCssClass = "row";

        // ProductName
        $this->ProductName->RowCssClass = "row";

        // SupplierID
        $this->SupplierID->RowCssClass = "row";

        // CategoryID
        $this->CategoryID->RowCssClass = "row";

        // QuantityPerUnit
        $this->QuantityPerUnit->RowCssClass = "row";

        // UnitPrice
        $this->UnitPrice->RowCssClass = "row";

        // UnitsInStock
        $this->UnitsInStock->RowCssClass = "row";

        // UnitsOnOrder
        $this->UnitsOnOrder->RowCssClass = "row";

        // ReorderLevel
        $this->ReorderLevel->RowCssClass = "row";

        // Discontinued
        $this->Discontinued->RowCssClass = "row";

        // EAN13
        $this->EAN13->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // ProductID
            $this->ProductID->ViewValue = $this->ProductID->CurrentValue;

            // ProductName
            $this->ProductName->ViewValue = $this->ProductName->CurrentValue;

            // SupplierID
            $curVal = strval($this->SupplierID->CurrentValue);
            if ($curVal != "") {
                $this->SupplierID->ViewValue = $this->SupplierID->lookupCacheOption($curVal);
                if ($this->SupplierID->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->SupplierID->Lookup->getTable()->Fields["SupplierID"]->searchExpression(), "=", $curVal, $this->SupplierID->Lookup->getTable()->Fields["SupplierID"]->searchDataType(), "");
                    $sqlWrk = $this->SupplierID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->SupplierID->Lookup->renderViewRow($rswrk[0]);
                        $this->SupplierID->ViewValue = $this->SupplierID->displayValue($arwrk);
                    } else {
                        $this->SupplierID->ViewValue = $this->SupplierID->CurrentValue;
                    }
                }
            } else {
                $this->SupplierID->ViewValue = null;
            }

            // CategoryID
            $curVal = strval($this->CategoryID->CurrentValue);
            if ($curVal != "") {
                $this->CategoryID->ViewValue = $this->CategoryID->lookupCacheOption($curVal);
                if ($this->CategoryID->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->CategoryID->Lookup->getTable()->Fields["CategoryID"]->searchExpression(), "=", $curVal, $this->CategoryID->Lookup->getTable()->Fields["CategoryID"]->searchDataType(), "");
                    $sqlWrk = $this->CategoryID->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCache($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->CategoryID->Lookup->renderViewRow($rswrk[0]);
                        $this->CategoryID->ViewValue = $this->CategoryID->displayValue($arwrk);
                    } else {
                        $this->CategoryID->ViewValue = $this->CategoryID->CurrentValue;
                    }
                }
            } else {
                $this->CategoryID->ViewValue = null;
            }

            // QuantityPerUnit
            $this->QuantityPerUnit->ViewValue = $this->QuantityPerUnit->CurrentValue;

            // UnitPrice
            $this->UnitPrice->ViewValue = $this->UnitPrice->CurrentValue;
            $this->UnitPrice->ViewValue = FormatCurrency($this->UnitPrice->ViewValue, $this->UnitPrice->formatPattern());

            // UnitsInStock
            $this->UnitsInStock->ViewValue = $this->UnitsInStock->CurrentValue;

            // UnitsOnOrder
            $this->UnitsOnOrder->ViewValue = $this->UnitsOnOrder->CurrentValue;

            // ReorderLevel
            $this->ReorderLevel->ViewValue = $this->ReorderLevel->CurrentValue;

            // Discontinued
            if (ConvertToBool($this->Discontinued->CurrentValue)) {
                $this->Discontinued->ViewValue = $this->Discontinued->tagCaption(1) != "" ? $this->Discontinued->tagCaption(1) : "Yes";
            } else {
                $this->Discontinued->ViewValue = $this->Discontinued->tagCaption(2) != "" ? $this->Discontinued->tagCaption(2) : "No";
            }

            // EAN13
            $this->EAN13->ViewValue = $this->EAN13->CurrentValue;

            // ProductID
            $this->ProductID->HrefValue = "";
            $this->ProductID->TooltipValue = "";

            // ProductName
            $this->ProductName->HrefValue = "";
            $this->ProductName->TooltipValue = "";

            // SupplierID
            $this->SupplierID->HrefValue = "";
            $this->SupplierID->TooltipValue = "";

            // CategoryID
            $this->CategoryID->HrefValue = "";
            $this->CategoryID->TooltipValue = "";

            // QuantityPerUnit
            $this->QuantityPerUnit->HrefValue = "";
            $this->QuantityPerUnit->TooltipValue = "";

            // UnitPrice
            $this->UnitPrice->HrefValue = "";
            $this->UnitPrice->TooltipValue = "";

            // UnitsInStock
            $this->UnitsInStock->HrefValue = "";
            $this->UnitsInStock->TooltipValue = "";

            // UnitsOnOrder
            $this->UnitsOnOrder->HrefValue = "";
            $this->UnitsOnOrder->TooltipValue = "";

            // ReorderLevel
            $this->ReorderLevel->HrefValue = "";
            $this->ReorderLevel->TooltipValue = "";

            // Discontinued
            $this->Discontinued->HrefValue = "";
            $this->Discontinued->TooltipValue = "";

            // EAN13
            $this->EAN13->HrefValue = "";
            $this->EAN13->ExportHrefValue = PhpBarcode::barcode(true)->getHrefValue($this->EAN13->CurrentValue, 'EAN-13', 60);
            $this->EAN13->TooltipValue = "";
        } elseif ($this->RowType == RowType::SEARCH) {
            // ProductID
            $this->ProductID->setupEditAttributes();
            $this->ProductID->EditValue = $this->ProductID->AdvancedSearch->SearchValue;
            $this->ProductID->PlaceHolder = RemoveHtml($this->ProductID->caption());

            // ProductName
            $this->ProductName->setupEditAttributes();
            if (!$this->ProductName->Raw) {
                $this->ProductName->AdvancedSearch->SearchValue = HtmlDecode($this->ProductName->AdvancedSearch->SearchValue);
            }
            $this->ProductName->EditValue = HtmlEncode($this->ProductName->AdvancedSearch->SearchValue);
            $this->ProductName->PlaceHolder = RemoveHtml($this->ProductName->caption());

            // SupplierID
            $curVal = trim(strval($this->SupplierID->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->SupplierID->AdvancedSearch->ViewValue = $this->SupplierID->lookupCacheOption($curVal);
            } else {
                $this->SupplierID->AdvancedSearch->ViewValue = $this->SupplierID->Lookup !== null && is_array($this->SupplierID->lookupOptions()) && count($this->SupplierID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->SupplierID->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->SupplierID->EditValue = array_values($this->SupplierID->lookupOptions());
                if ($this->SupplierID->AdvancedSearch->ViewValue == "") {
                    $this->SupplierID->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->SupplierID->Lookup->getTable()->Fields["SupplierID"]->searchExpression(), "=", $this->SupplierID->AdvancedSearch->SearchValue, $this->SupplierID->Lookup->getTable()->Fields["SupplierID"]->searchDataType(), "");
                }
                $sqlWrk = $this->SupplierID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->SupplierID->Lookup->renderViewRow($rswrk[0]);
                    $this->SupplierID->AdvancedSearch->ViewValue = $this->SupplierID->displayValue($arwrk);
                } else {
                    $this->SupplierID->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->SupplierID->EditValue = $arwrk;
            }
            $this->SupplierID->PlaceHolder = RemoveHtml($this->SupplierID->caption());

            // CategoryID
            $this->CategoryID->setupEditAttributes();
            $curVal = trim(strval($this->CategoryID->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->CategoryID->AdvancedSearch->ViewValue = $this->CategoryID->lookupCacheOption($curVal);
            } else {
                $this->CategoryID->AdvancedSearch->ViewValue = $this->CategoryID->Lookup !== null && is_array($this->CategoryID->lookupOptions()) && count($this->CategoryID->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->CategoryID->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->CategoryID->EditValue = array_values($this->CategoryID->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->CategoryID->Lookup->getTable()->Fields["CategoryID"]->searchExpression(), "=", $this->CategoryID->AdvancedSearch->SearchValue, $this->CategoryID->Lookup->getTable()->Fields["CategoryID"]->searchDataType(), "");
                }
                $sqlWrk = $this->CategoryID->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCache($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->CategoryID->EditValue = $arwrk;
            }
            $this->CategoryID->PlaceHolder = RemoveHtml($this->CategoryID->caption());

            // QuantityPerUnit
            $this->QuantityPerUnit->setupEditAttributes();
            if (!$this->QuantityPerUnit->Raw) {
                $this->QuantityPerUnit->AdvancedSearch->SearchValue = HtmlDecode($this->QuantityPerUnit->AdvancedSearch->SearchValue);
            }
            $this->QuantityPerUnit->EditValue = HtmlEncode($this->QuantityPerUnit->AdvancedSearch->SearchValue);
            $this->QuantityPerUnit->PlaceHolder = RemoveHtml($this->QuantityPerUnit->caption());

            // UnitPrice
            $this->UnitPrice->setupEditAttributes();
            $this->UnitPrice->EditValue = $this->UnitPrice->AdvancedSearch->SearchValue;
            $this->UnitPrice->PlaceHolder = RemoveHtml($this->UnitPrice->caption());

            // UnitsInStock
            $this->UnitsInStock->setupEditAttributes();
            $this->UnitsInStock->EditValue = $this->UnitsInStock->AdvancedSearch->SearchValue;
            $this->UnitsInStock->PlaceHolder = RemoveHtml($this->UnitsInStock->caption());

            // UnitsOnOrder
            $this->UnitsOnOrder->setupEditAttributes();
            $this->UnitsOnOrder->EditValue = $this->UnitsOnOrder->AdvancedSearch->SearchValue;
            $this->UnitsOnOrder->PlaceHolder = RemoveHtml($this->UnitsOnOrder->caption());

            // ReorderLevel
            $this->ReorderLevel->setupEditAttributes();
            $this->ReorderLevel->EditValue = $this->ReorderLevel->AdvancedSearch->SearchValue;
            $this->ReorderLevel->PlaceHolder = RemoveHtml($this->ReorderLevel->caption());

            // Discontinued
            $this->Discontinued->EditValue = $this->Discontinued->options(false);
            $this->Discontinued->PlaceHolder = RemoveHtml($this->Discontinued->caption());

            // EAN13
            $this->EAN13->setupEditAttributes();
            if (!$this->EAN13->Raw) {
                $this->EAN13->AdvancedSearch->SearchValue = HtmlDecode($this->EAN13->AdvancedSearch->SearchValue);
            }
            $this->EAN13->EditValue = HtmlEncode($this->EAN13->AdvancedSearch->SearchValue);
            $this->EAN13->PlaceHolder = RemoveHtml($this->EAN13->caption());
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckInteger($this->ProductID->AdvancedSearch->SearchValue)) {
            $this->ProductID->addErrorMessage($this->ProductID->getErrorMessage(false));
        }
        if (!CheckNumber($this->UnitPrice->AdvancedSearch->SearchValue)) {
            $this->UnitPrice->addErrorMessage($this->UnitPrice->getErrorMessage(false));
        }
        if (!CheckInteger($this->UnitsInStock->AdvancedSearch->SearchValue)) {
            $this->UnitsInStock->addErrorMessage($this->UnitsInStock->getErrorMessage(false));
        }
        if (!CheckInteger($this->UnitsOnOrder->AdvancedSearch->SearchValue)) {
            $this->UnitsOnOrder->addErrorMessage($this->UnitsOnOrder->getErrorMessage(false));
        }
        if (!CheckInteger($this->ReorderLevel->AdvancedSearch->SearchValue)) {
            $this->ReorderLevel->addErrorMessage($this->ReorderLevel->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->ProductID->AdvancedSearch->load();
        $this->ProductName->AdvancedSearch->load();
        $this->SupplierID->AdvancedSearch->load();
        $this->CategoryID->AdvancedSearch->load();
        $this->QuantityPerUnit->AdvancedSearch->load();
        $this->UnitPrice->AdvancedSearch->load();
        $this->UnitsInStock->AdvancedSearch->load();
        $this->UnitsOnOrder->AdvancedSearch->load();
        $this->ReorderLevel->AdvancedSearch->load();
        $this->Discontinued->AdvancedSearch->load();
        $this->EAN13->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("home2");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("productslist"), "", $this->TableVar, true);
        $pageId = "search";
        $Breadcrumb->add("search", $pageId, $url);
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
                case "x_SupplierID":
                    break;
                case "x_CategoryID":
                    break;
                case "x_Discontinued":
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
