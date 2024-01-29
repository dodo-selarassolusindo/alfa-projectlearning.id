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
 * Table class for Quarterly Orders By Product
 */
class QuarterlyOrdersByProduct extends CrosstabTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = false;
    public $ModalView = false;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = false;
    public $InlineDelete = false;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;
    public $OrdersByCategory;
    public $YEAR__OrderDate;

    // Fields
    public $CategoryName;
    public $ProductName;
    public $CompanyName;
    public $OrderDate;
    public $Amount;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("app.language");
        $this->TableVar = "Quarterly_Orders_By_Product";
        $this->TableName = 'Quarterly Orders By Product';
        $this->TableType = "REPORT";
        $this->TableReportType = "crosstab"; // Report Type
        $this->ReportSourceTable = 'orders_by_product'; // Report source table
        $this->Dbid = 'DB';
        $this->ExportAll = false;
        $this->ExportPageBreakCount = 1; // Page break per every n record (report only)

        // PDF
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = null; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = null; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions

        // CategoryName
        $this->CategoryName = new ReportField(
            $this, // Table
            'x_CategoryName', // Variable name
            'CategoryName', // Name
            '`CategoryName`', // Expression
            '`CategoryName`', // Basic search expression
            200, // Type
            15, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`CategoryName`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->CategoryName->InputTextType = "text";
        $this->CategoryName->GroupingFieldId = 1;
        $this->CategoryName->Nullable = false; // NOT NULL field
        $this->CategoryName->Required = true; // Required field
        $this->CategoryName->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->CategoryName->SourceTableVar = 'orders_by_product';
        $this->Fields['CategoryName'] = &$this->CategoryName;

        // ProductName
        $this->ProductName = new ReportField(
            $this, // Table
            'x_ProductName', // Variable name
            'ProductName', // Name
            '`ProductName`', // Expression
            '`ProductName`', // Basic search expression
            200, // Type
            40, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`ProductName`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->ProductName->InputTextType = "text";
        $this->ProductName->GroupingFieldId = 2;
        $this->ProductName->Nullable = false; // NOT NULL field
        $this->ProductName->Required = true; // Required field
        $this->ProductName->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->ProductName->SourceTableVar = 'orders_by_product';
        $this->Fields['ProductName'] = &$this->ProductName;

        // CompanyName
        $this->CompanyName = new ReportField(
            $this, // Table
            'x_CompanyName', // Variable name
            'CompanyName', // Name
            '`CompanyName`', // Expression
            '`CompanyName`', // Basic search expression
            200, // Type
            40, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`CompanyName`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->CompanyName->InputTextType = "text";
        $this->CompanyName->Nullable = false; // NOT NULL field
        $this->CompanyName->Required = true; // Required field
        $this->CompanyName->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->CompanyName->SourceTableVar = 'orders_by_product';
        $this->Fields['CompanyName'] = &$this->CompanyName;

        // OrderDate
        $this->OrderDate = new ReportField(
            $this, // Table
            'x_OrderDate', // Variable name
            'OrderDate', // Name
            '`OrderDate`', // Expression
            CastDateFieldForLike("`OrderDate`", 0, "DB"), // Basic search expression
            135, // Type
            19, // Size
            0, // Date/Time format
            false, // Is upload field
            '`OrderDate`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->OrderDate->InputTextType = "text";
        $this->OrderDate->Raw = true;
        $this->OrderDate->setSelectMultiple(false); // Select one
        $this->OrderDate->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->OrderDate->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->OrderDate->Lookup = new Lookup($this->OrderDate, 'Quarterly_Orders_By_Product', true, 'OrderDate', ["OrderDate","","",""], '', '', [], [], [], [], [], [], false, '`OrderDate` ASC', '', "" . CastDateFieldForLike("`OrderDate`", 0, "DB") . "");
        $this->OrderDate->DateFilter = "Year";
        $this->OrderDate->LookupExpression = "YEAR(`OrderDate`)";
        $this->OrderDate->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->OrderDate->SearchOperators = ["="];
        $this->OrderDate->SourceTableVar = 'orders_by_product';
        $this->OrderDate->SearchType = "dropdown";
        $this->Fields['OrderDate'] = &$this->OrderDate;

        // Amount
        $this->Amount = new ReportField(
            $this, // Table
            'x_Amount', // Variable name
            'Amount', // Name
            '`Amount`', // Expression
            '`Amount`', // Basic search expression
            5, // Type
            23, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`Amount`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->Amount->addMethod("getDefault", fn() => 0);
        $this->Amount->InputTextType = "text";
        $this->Amount->Raw = true;
        $this->Amount->Nullable = false; // NOT NULL field
        $this->Amount->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->Amount->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Amount->SourceTableVar = 'orders_by_product';
        $this->Fields['Amount'] = &$this->Amount;

        // YEAR__OrderDate
        $this->YEAR__OrderDate = new ReportField($this, 'x_YEAR__OrderDate', 'YEAR__OrderDate', 'YEAR(`OrderDate`)', '', 3, -1, -1, false, '', false, false, false);
        $this->YEAR__OrderDate->Sortable = false;
        $this->YEAR__OrderDate->Caption = $Language->phrase("Year");
        $this->Fields['YEAR__OrderDate'] = &$this->YEAR__OrderDate;

        // OrdersByCategory
        $this->OrdersByCategory = new DbChart($this, 'OrdersByCategory', 'OrdersByCategory', 'CategoryName', 'Amount', 1004, '', 0, 'SUM', 550, 440);
        $this->OrdersByCategory->RunTimeSort = true;
        $this->OrdersByCategory->Position = 4;
        $this->OrdersByCategory->PageBreakType = "before";
        $this->OrdersByCategory->YAxisFormat = ["Currency"];
        $this->OrdersByCategory->YFieldFormat = ["Currency"];
        $this->OrdersByCategory->SortType = 0;
        $this->OrdersByCategory->SortSequence = "";
        $this->OrdersByCategory->SqlSelect = $this->getQueryBuilder()->select("`CategoryName`", "''", "SUM(`Amount`)");
        $this->OrdersByCategory->SqlGroupBy = "`CategoryName`";
        $this->OrdersByCategory->SqlOrderBy = "`CategoryName`";
        $this->OrdersByCategory->SeriesDateType = "";
        $this->OrdersByCategory->DrillDownTable = "orders_by_product2";
        $this->OrdersByCategory->DrillDownUrl = GetUrl("ordersbyproduct2?d=1&t=orders_by_product2&s=Quarterly_Orders_By_Product&CategoryName=%0:CategoryName:200%&OrderDate=f1");
        $this->OrdersByCategory->ID = "Quarterly_Orders_By_Product_OrdersByCategory"; // Chart ID
        $this->OrdersByCategory->setParameters([
            ["type", "1004"],
            ["seriestype", "0"]
        ]); // Chart type / Chart series type
        $this->OrdersByCategory->setParameter("bgcolor", "FCFCFC"); // Background color
        $this->OrdersByCategory->setParameters([
            ["caption", $this->OrdersByCategory->caption()],
            ["xaxisname", $this->OrdersByCategory->xAxisName()]
        ]); // Chart caption / X axis name
        $this->OrdersByCategory->setParameter("yaxisname", $this->OrdersByCategory->yAxisName()); // Y axis name
        $this->OrdersByCategory->setParameters([
            ["shownames", "1"],
            ["showvalues", "1"],
            ["showhovercap", "1"]
        ]); // Show names / Show values / Show hover
        $this->OrdersByCategory->setParameter("alpha", DbChart::getDefaultAlpha()); // Chart alpha (datasets background color)
        $this->OrdersByCategory->setParameters([["options.plugins.filler.propagate",false],["options.animation.animateRotate",false],["options.animation.animateScale",false],["dataset.showLine",false],["dataset.spanGaps",false],["dataset.steppedLine",false],["scale.gridLines.offsetGridLines",false],["annotation1.show",false],["annotation1.startValue",0],["annotation1.endValue",0],["annotation1.alpha",50],["annotation1.secondaryYAxis",false],["annotation2.show",false],["annotation2.secondaryYAxis",false],["annotation3.show",false],["annotation3.secondaryYAxis",false],["annotation4.show",false],["annotation4.secondaryYAxis",false],["options.plugins.legend.display",false],["options.plugins.legend.fullWidth",false],["options.plugins.legend.reverse",false],["options.plugins.legend.labels.usePointStyle",false],["options.plugins.title.display",false],["options.plugins.tooltip.enabled",true],["options.plugins.tooltip.mode","point"],["options.plugins.tooltip.intersect",false],["options.plugins.tooltip.displayColors",false],["options.plugins.legend.rtl",false],["options.plugins.tooltip.rtl",false],["options.scales.r.angleLines.display",false],["options.plugins.stacked100.enable",false],["dataset.circular",false],["scale.offset",true],["options.plugins.datalabels.clamp",true],["options.plugins.datalabels.clip",true],["options.plugins.datalabels.display",true]]);
        $this->Charts[$this->OrdersByCategory->ID] = &$this->OrdersByCategory;

        // Add Doctrine Cache
        $this->Cache = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Single column sort
    protected function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $lastOrderBy = in_array($lastSort, ["ASC", "DESC"]) ? $sortField . " " . $lastSort : "";
            $curOrderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            if ($fld->GroupingFieldId == 0) {
                $this->setDetailOrderBy($curOrderBy); // Save to Session
            }
        } else {
            if ($fld->GroupingFieldId == 0) {
                $fld->setSort("");
            }
        }
    }

    // Get Sort SQL
    protected function sortSql()
    {
        $dtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
        $argrps = [];
        foreach ($this->Fields as $fld) {
            if (in_array($fld->getSort(), ["ASC", "DESC"])) {
                $fldsql = $fld->Expression;
                if ($fld->GroupingFieldId > 0) {
                    if ($fld->GroupSql != "") {
                        $argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->GroupSql) . " " . $fld->getSort();
                    } else {
                        $argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
                    }
                }
            }
        }
        $sortSql = "";
        foreach ($argrps as $grp) {
            if ($sortSql != "") {
                $sortSql .= ", ";
            }
            $sortSql .= $grp;
        }
        if ($dtlSortSql != "") {
            if ($sortSql != "") {
                $sortSql .= ", ";
            }
            $sortSql .= $dtlSortSql;
        }
        return $sortSql;
    }

    // Table Level Group SQL
    private $sqlFirstGroupField = "";
    private $sqlSelectGroup = null;
    private $sqlOrderByGroup = "";

    // First Group Field
    public function getSqlFirstGroupField($alias = false)
    {
        if ($this->sqlFirstGroupField != "") {
            return $this->sqlFirstGroupField;
        }
        $firstGroupField = &$this->CategoryName;
        $expr = $firstGroupField->Expression;
        if ($firstGroupField->GroupSql != "") {
            $expr = str_replace("%s", $firstGroupField->Expression, $firstGroupField->GroupSql);
            if ($alias) {
                $expr .= " AS " . QuotedName($firstGroupField->getGroupName(), $this->Dbid);
            }
        }
        return $expr;
    }

    public function setSqlFirstGroupField($v)
    {
        $this->sqlFirstGroupField = $v;
    }

    // Select Group
    public function getSqlSelectGroup()
    {
        return $this->sqlSelectGroup ?? $this->getQueryBuilder()->select($this->getSqlFirstGroupField(true))->distinct();
    }

    public function setSqlSelectGroup($v)
    {
        $this->sqlSelectGroup = $v;
    }

    // Order By Group
    public function getSqlOrderByGroup()
    {
        if ($this->sqlOrderByGroup != "") {
            return $this->sqlOrderByGroup;
        }
        return $this->getSqlFirstGroupField() . " ASC";
    }

    public function setSqlOrderByGroup($v)
    {
        $this->sqlOrderByGroup = $v;
    }

    // Crosstab properties
    private $sqlSelectAggregate = null;
    private $sqlGroupByAggregate = "";

    // Select Aggregate
    public function getSqlSelectAggregate()
    {
        return $this->sqlSelectAggregate ?? $this->getQueryBuilder()->select();
    }

    public function setSqlSelectAggregate($v)
    {
        $this->sqlSelectAggregate = $v;
    }

    // Group By Aggregate
    public function getSqlGroupByAggregate()
    {
        return ($this->sqlGroupByAggregate != "") ? $this->sqlGroupByAggregate : "";
    }

    public function setSqlGroupByAggregate($v)
    {
        $this->sqlGroupByAggregate = $v;
    }

    // Table level SQL
    private $columnField = "";
    private $columnDateType = "";
    private $columnValues = "";
    private $sqlDistinctSelect = null;
    private $sqlDistinctWhere = "";
    private $sqlDistinctOrderBy = "";
    public $Columns;
    public $ColumnCount;
    public $Col;
    public $DistinctColumnFields = "";
    private $columnLoaded = false;

    // Column field
    public function getColumnField()
    {
        return ($this->columnField != "") ? $this->columnField : "`OrderDate`";
    }

    public function setColumnField($v)
    {
        $this->columnField = $v;
    }

    // Column date type
    public function getColumnDateType()
    {
        return ($this->columnDateType != "") ? $this->columnDateType : "y";
    }

    public function setColumnDateType($v)
    {
        $this->columnDateType = $v;
    }

    // Column values
    public function getColumnValues()
    {
        return ($this->columnValues != "") ? $this->columnValues : "";
    }

    public function setColumnValues($v)
    {
        $this->columnValues = $v;
    }

    // Select Distinct
    public function getSqlDistinctSelect()
    {
        return $this->sqlDistinctSelect ?? $this->getQueryBuilder()->select("YEAR(`OrderDate`)")->distinct();
    }

    public function setSqlDistinctSelect($v)
    {
        $this->sqlDistinctSelect = $v;
    }

    // Distinct Where
    public function getSqlDistinctWhere()
    {
        $where = ($this->sqlDistinctWhere != "") ? $this->sqlDistinctWhere : "";
        $filter = "";
        AddFilter($where, $filter);
        return $where;
    }

    public function setSqlDistinctWhere($v)
    {
        $this->sqlDistinctWhere = $v;
    }

    // Distinct Order By
    public function getSqlDistinctOrderBy()
    {
        return ($this->sqlDistinctOrderBy != "") ? $this->sqlDistinctOrderBy : "YEAR(`OrderDate`) ASC";
    }

    public function setSqlDistinctOrderBy($v)
    {
        $this->sqlDistinctOrderBy = $v;
    }

    // Load column values
    public function loadColumnValues($filter = "")
    {
        global $Language;

        // Data already loaded, return
        if ($this->columnLoaded) {
            return;
        }
        $conn = $this->getConnection();

        // Build SQL
        $sql = $this->buildReportSql($this->getSqlDistinctSelect(), $this->getSqlFrom(), $this->getSqlDistinctWhere(), "", "", $this->getSqlDistinctOrderBy(), $filter, "");

        // Load columns
        $rscol = $conn->executeQuery($sql)->fetchAllNumeric();

        // Get distinct column count
        $this->ColumnCount = count($rscol);
/* Uncomment to show phrase
        if ($this->ColumnCount == 0) {
            echo "<p>" . $Language->phrase("NoDistinctColVals") . $sql . "</p>";
            exit();
        }
*/
        $this->Columns = Init2DArray($this->ColumnCount + 1, 3, null);
        $colcnt = 0;
        foreach ($rscol as $row) {
            if ($row[0] === null) {
                $wrkValue = Config("NULL_VALUE");
                $wrkCaption = $Language->phrase("NullLabel");
            } elseif (strval($row[0]) == "") {
                $wrkValue = Config("EMPTY_VALUE");
                $wrkCaption = $Language->phrase("EmptyLabel");
            } else {
                $wrkValue = $row[0];
                $this->OrderDate->CurrentValue = $row[0];
                $this->OrderDate->ViewValue = $this->OrderDate->CurrentValue;
                $row[0] = is_object($this->OrderDate->ViewValue) ? $this->OrderDate->ViewValue->__toString() : $this->OrderDate->ViewValue;
                $this->OrderDate->CurrentValue = ""; // Reset current value
                $this->OrderDate->ViewValue = ""; // Reset view value
                $wrkCaption = $row[0];
            }
            $colcnt++;
            $this->Columns[$colcnt] = new CrosstabColumn($wrkCaption, $wrkValue, true);
        }

        // 1st dimension = no of groups (level 0 used for grand total)
        // 2nd dimension = no of distinct values
        $groupCount = 2;
        $this->SummaryFields[0] = new SummaryField('x_Amount', 'Amount', '`Amount`', 'SUM');
        $this->SummaryFields[0]->SummaryCaption = $Language->phrase("RptSum");
        $this->SummaryFields[0]->SummaryValues = InitArray($this->ColumnCount + 1, null);
        $this->SummaryFields[0]->SummaryValueCounts = InitArray($this->ColumnCount + 1, null);
        $this->SummaryFields[0]->SummaryInitValue = 0;

        // Update crosstab SQL
        $sqlFlds = "";
        $cnt = count($this->SummaryFields);
        for ($is = 0; $is < $cnt; $is++) {
            $smry = &$this->SummaryFields[$is];
            for ($i = 1; $i <= $this->ColumnCount; $i++) {
                $fld = CrosstabFieldExpression($smry->SummaryType, $smry->Expression, $this->getColumnField(), $this->getColumnDateType(), $this->Columns[$i]->Value, "'", "C" . $is . $i, $this->Dbid);
                if ($sqlFlds != "") {
                    $sqlFlds .= ", ";
                }
                $sqlFlds .= $fld;
            }
        }
        $this->DistinctColumnFields = $sqlFlds ?: "NULL"; // In case ColumnCount = 0
        $this->columnLoaded = true;
    }

    // Render for lookup
    public function renderLookup()
    {
        $this->ProductName->ViewValue = $this->ProductName->CurrentValue;
        $this->OrderDate->ViewValue = GetDropDownDisplayValue($this->OrderDate->CurrentValue, $this->OrderDate->DateFilter, $this->OrderDate->formatPattern());
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Get FROM clause
    public function getSqlFrom()
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "orders_by_product";
    }

    // Get FROM clause (for backward compatibility)
    public function sqlFrom()
    {
        return $this->getSqlFrom();
    }

    // Set FROM clause
    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    // Get SELECT clause
    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select($this->sqlSelectFields());
    }

    // Get list of fields
    private function sqlSelectFields()
    {
        return "`CategoryName`, `ProductName`";
    }

    // Get SELECT clause (for backward compatibility)
    public function sqlSelect()
    {
        return $this->getSqlSelect();
    }

    // Set SELECT clause
    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    // Get WHERE clause
    public function getSqlWhere()
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    // Get WHERE clause (for backward compatibility)
    public function sqlWhere()
    {
        return $this->getSqlWhere();
    }

    // Set WHERE clause
    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    // Get GROUP BY clause
    public function getSqlGroupBy()
    {
        return $this->SqlGroupBy != "" ? $this->SqlGroupBy : "`CategoryName`, `ProductName`";
    }

    // Get GROUP BY clause (for backward compatibility)
    public function sqlGroupBy()
    {
        return $this->getSqlGroupBy();
    }

    // set GROUP BY clause
    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    // Get HAVING clause
    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    // Get HAVING clause (for backward compatibility)
    public function sqlHaving()
    {
        return $this->getSqlHaving();
    }

    // Set HAVING clause
    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    // Get ORDER BY clause
    public function getSqlOrderBy()
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    // Get ORDER BY clause (for backward compatibility)
    public function sqlOrderBy()
    {
        return $this->getSqlOrderBy();
    }

    // set ORDER BY clause
    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return ($allow & Allow::ADD->value) == Allow::ADD->value;
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return ($allow & Allow::EDIT->value) == Allow::EDIT->value;
            case "delete":
                return ($allow & Allow::DELETE->value) == Allow::DELETE->value;
            case "view":
                return ($allow & Allow::VIEW->value) == Allow::VIEW->value;
            case "search":
                return ($allow & Allow::SEARCH->value) == Allow::SEARCH->value;
            case "lookup":
                return ($allow & Allow::LOOKUP->value) == Allow::LOOKUP->value;
            default:
                return ($allow & Allow::LIST->value) == Allow::LIST->value;
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $sqlwrk = $sql instanceof QueryBuilder // Query builder
            ? (clone $sql)->resetQueryPart("orderBy")->getSQL()
            : $sql;
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            in_array($this->TableType, ["TABLE", "VIEW", "LINKTABLE"]) &&
            preg_match($pattern, $sqlwrk) &&
            !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*SELECT\s+DISTINCT\s+/i', $sqlwrk) &&
            !preg_match('/\s+ORDER\s+BY\s+/i', $sqlwrk)
        ) {
            $sqlcnt = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlcnt = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlcnt);
        if ($cnt !== false) {
            return (int)$cnt;
        }
        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        $result = $conn->executeQuery($sqlwrk);
        $cnt = $result->rowCount();
        if ($cnt == 0) { // Unable to get record count, count directly
            while ($result->fetch()) {
                $cnt++;
            }
        }
        return $cnt;
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        return implode($keySeparator, $keys);
    }

    // Set Key
    public function setKey($key, $current = false, $keySeparator = null)
    {
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        $this->OldKey = strval($key);
        $keys = explode($keySeparator, $this->OldKey);
        if (count($keys) == 0) {
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        return match ($pageName) {
            "" => $Language->phrase("View"),
            "" => $Language->phrase("Edit"),
            "" => $Language->phrase("Add"),
            default => ""
        };
    }

    // Default route URL
    public function getDefaultRouteUrl()
    {
        return "quarterlyordersbyproduct";
    }

    // API page name
    public function getApiPageName($action)
    {
        return "QuarterlyOrdersByProductCrosstab";
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("", $parm);
        } else {
            $url = $this->keyUrl("", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "?" . $parm;
        } else {
            $url = "";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("", $parm);
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl($parm = "")
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("", $parm);
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language;
        $sortUrl = "";
        $attrs = "";
        if ($this->PageID != "grid" && $fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($this->PageID != "grid" && !$this->isExport() && $fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") .
                (is_array($fld->EditValue) ? str_replace("%c", count($fld->EditValue), $Language->phrase("FilterCount")) : '') .
                '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            $this->DrillDown ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;" . Config("PAGE_DASHBOARD") . "=" . $DashboardReport;
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            $isApi = IsApi();
            $keyValues = $isApi
                ? (Route(0) == "export"
                    ? array_map(fn ($i) => Route($i + 3), range(0, -1))  // Export API
                    : array_map(fn ($i) => Route($i + 2), range(0, -1))) // Other API
                : []; // Non-API
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        $keyFilter = "";
        foreach ($rows as $row) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter($row) . ")";
        }
        return $keyFilter;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load result set based on filter/sort
    public function loadRs($filter, $sort = "")
    {
        $sql = $this->getSql($filter, $sort); // Set up filter (WHERE Clause) / sort (ORDER BY Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
        return false;
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Email Sending event
    public function emailSending($email, $args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
