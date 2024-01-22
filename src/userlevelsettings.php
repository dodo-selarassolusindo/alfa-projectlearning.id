<?php
/**
 * PHPMaker 2024 User Level Settings
 */
namespace PHPMaker2024\demo2024;

/**
 * User levels
 *
 * @var array<int, string>
 * [0] int User level ID
 * [1] string User level name
 */
$USER_LEVELS = [["-2","Anonymous"],
    ["0","Default"],
    ["1","Sales"],
    ["2","Manager"]];

/**
 * User level permissions
 *
 * @var array<string, int, int>
 * [0] string Project ID + Table name
 * [1] int User level ID
 * [2] int Permissions
 */
$USER_LEVEL_PRIVS = [["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars","-2","72"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars","0","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars","1","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}categories","-2","72"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}categories","0","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}categories","1","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}categories","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}customers","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}customers","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}customers","1","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}customers","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employees","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employees","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employees","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employees","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orderdetails","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orderdetails","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orderdetails","1","105"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orderdetails","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders","1","105"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products","-2","72"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products","0","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products","1","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}shippers","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}shippers","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}shippers","1","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}shippers","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}suppliers","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}suppliers","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}suppliers","1","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}suppliers","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}models","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}models","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}models","1","105"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}models","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}trademarks","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}trademarks","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}trademarks","1","105"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}trademarks","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevelpermissions","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevelpermissions","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevelpermissions","1","105"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevelpermissions","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevels","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevels","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevels","1","105"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevels","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended","1","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders2","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders2","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders2","1","105"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders2","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars2","-2","72"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars2","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars2","1","105"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars2","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}home.php","-2","72"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}home.php","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}home.php","1","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}home.php","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}news.php","-2","72"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}news.php","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}news.php","1","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}news.php","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}dji","-2","72"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}dji","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}dji","1","104"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}dji","2","111"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employee_sales_by_country_for_2014","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employee_sales_by_country_for_2014","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employee_sales_by_country_for_2014","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employee_sales_by_country_for_2014","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}list_of_products","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}list_of_products","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}list_of_products","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}list_of_products","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended 2","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended 2","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended 2","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended 2","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders_by_product","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders_by_product","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders_by_product","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders_by_product","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}product_sales_for_2014","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}product_sales_for_2014","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}product_sales_for_2014","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}product_sales_for_2014","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products_by_category","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products_by_category","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products_by_category","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products_by_category","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}sales_by_category_for_2014","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}sales_by_category_for_2014","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}sales_by_category_for_2014","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}sales_by_category_for_2014","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}sales_by_year","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}sales_by_year","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}sales_by_year","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}sales_by_year","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Quarterly Orders By Product","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Quarterly Orders By Product","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Quarterly Orders By Product","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Quarterly Orders By Product","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer (Compact)","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer (Compact)","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer (Compact)","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer (Compact)","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Alphabetical List of Products","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Alphabetical List of Products","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Alphabetical List of Products","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Alphabetical List of Products","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Products By Category","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Products By Category","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Products By Category","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Products By Category","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales by Category for 2014","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales by Category for 2014","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales by Category for 2014","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales by Category for 2014","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Year","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Year","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Year","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Year","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Order","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Order","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Order","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Order","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer 2","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer 2","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer 2","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer 2","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Dashboard1","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Dashboard1","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Dashboard1","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Dashboard1","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}gantt3","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}gantt3","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}gantt3","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}gantt3","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders_by_product2","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders_by_product2","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders_by_product2","1","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders_by_product2","2","8"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Gantt","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Gantt","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Gantt","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Gantt","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}tasks","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}tasks","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}tasks","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}tasks","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations2","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations2","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations2","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations2","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations3","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations3","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations3","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations3","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}calendar","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}calendar","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}calendar","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}calendar","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Calendar1","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Calendar1","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Calendar1","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Calendar1","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}messages","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}messages","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}messages","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}messages","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}favorites","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}favorites","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}favorites","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}favorites","2","15"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}audittrail","-2","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}audittrail","0","0"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}audittrail","1","9"],
    ["{DFB61542-7FFC-43AB-88E7-31D7F8D95066}audittrail","2","15"]];

/**
 * Tables
 *
 * @var array<string, string, string, bool, string>
 * [0] string Table name
 * [1] string Table variable name
 * [2] string Table caption
 * [3] bool Allowed for update (for userpriv.php)
 * [4] string Project ID
 * [5] string URL (for OthersController::index)
 */
$USER_LEVEL_TABLES = [["cars","cars","Cars",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","carslist"],
    ["categories","categories","Categories",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","categorieslist"],
    ["customers","customers","Customers",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","customerslist"],
    ["employees","employees","Employees",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","employeeslist"],
    ["orderdetails","orderdetails","Order Details",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","orderdetailslist"],
    ["orders","orders","Orders",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","orderslist"],
    ["products","products","Products",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","productslist"],
    ["shippers","shippers","Shippers",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","shipperslist"],
    ["suppliers","suppliers","Suppliers",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","supplierslist"],
    ["models","models","Models",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","modelslist"],
    ["trademarks","trademarks","Trademarks",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","trademarkslist"],
    ["userlevelpermissions","userlevelpermissions","User Level Permissions",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","userlevelpermissionslist"],
    ["userlevels","userlevels","User Levels",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","userlevelslist"],
    ["order details extended","order_details_extended","Order Details Extended",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","orderdetailsextendedlist"],
    ["orders2","orders2","Orders 2",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","orders2list"],
    ["cars2","cars2","Cars 2",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","cars2list"],
    ["home.php","home","Home Page",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","home"],
    ["news.php","news","News",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","news"],
    ["dji","dji","Dow Jones Index",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["employee_sales_by_country_for_2014","employee_sales_by_country_for_2014","employee sales by county for 2014",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["list_of_products","list_of_products","list of products",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["order details extended 2","order_details_extended_2","order details extended 2",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["orders_by_product","orders_by_product","orders by product",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["product_sales_for_2014","product_sales_for_2014","product sales for 2014",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["products_by_category","products_by_category2","products by category 2",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["sales_by_category_for_2014","sales_by_category_for_20142","sales by category for 2014 2",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["sales_by_year","sales_by_year2","sales by year 2",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["Quarterly Orders By Product","Quarterly_Orders_By_Product","Quarterly Orders by Product",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","quarterlyordersbyproduct"],
    ["Sales By Customer","Sales_By_Customer","Sales By Customer",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","salesbycustomer"],
    ["Sales By Customer (Compact)","Sales_By_Customer_Compact","Sales By Customer (Compact)",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","salesbycustomercompact"],
    ["Alphabetical List of Products","Alphabetical_List_of_Products","Alphabetical List of Products",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","alphabeticallistofproducts"],
    ["Products By Category","Products_By_Category","Products By Category",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","productsbycategory"],
    ["Sales by Category for 2014","Sales_by_Category_for_2014","Sales by Category for 2014",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","salesbycategoryfor2014"],
    ["Sales By Year","Sales_By_Year","Sales By Year",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","salesbyyear"],
    ["Sales By Order","Sales_By_Order","Sales By Order",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","salesbyorder"],
    ["Sales By Customer 2","Sales_By_Customer_2","Sales By Customer 2",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","salesbycustomer2"],
    ["Dashboard1","Dashboard1","Dashboard",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","dashboard1"],
    ["gantt3","gantt3","gantt 3",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["orders_by_product2","orders_by_product2","Orders by Product",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","ordersbyproduct2"],
    ["Gantt","Gantt","Gantt",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","gantt"],
    ["tasks","tasks","Tasks",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["locations","locations","Locations (GoogleMaps)",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["locations2","locations2","Locations (Leaflet OSM)",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["locations3","locations3","Locations (Leaflet Mapbox)",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["calendar","calendar","calendar",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","calendarlist"],
    ["Calendar1","Calendar1","Calendar 1",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","calendar1"],
    ["messages","messages2","messages",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["favorites","favorites","favorites",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}",""],
    ["audittrail","audittrail","Audit Trail",true,"{DFB61542-7FFC-43AB-88E7-31D7F8D95066}","audittraillist"]];
