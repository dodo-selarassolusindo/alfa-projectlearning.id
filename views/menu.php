<?php

namespace PHPMaker2024\demo2024;

// Navbar menu
$topMenu = new Menu("navbar", true, true);
$topMenu->addMenuItem(10087, "mi_home", $Language->menuPhrase("10087", "MenuText"), "home", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}home.php'), false, false, "", "", true, false);
$topMenu->addMenuItem(10088, "mi_news", $Language->menuPhrase("10088", "MenuText"), "news", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}news.php'), false, false, "", "", true, false);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(10148, "mi_locations", $Language->menuPhrase("10148", "MenuText"), "locationslist", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10149, "mi_locations2", $Language->menuPhrase("10149", "MenuText"), "locations2list", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations2'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10150, "mi_locations3", $Language->menuPhrase("10150", "MenuText"), "locations3list", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}locations3'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10087, "mi_home", $Language->menuPhrase("10087", "MenuText"), "home", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}home.php'), false, false, "", "", true, true);
$sideMenu->addMenuItem(10088, "mi_news", $Language->menuPhrase("10088", "MenuText"), "news", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}news.php'), false, false, "", "", true, true);
$sideMenu->addMenuItem(16, "mci_CARS_RELATED", $Language->menuPhrase("16", "MenuText"), "", -1, "", true, false, true, "fa-car", "", false, true);
$sideMenu->addMenuItem(1, "mi_cars", $Language->menuPhrase("1", "MenuText"), "carslist", 16, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10086, "mi_cars2", $Language->menuPhrase("10086", "MenuText"), "cars2list", 16, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}cars2'), false, false, "", "", false, true);
$sideMenu->addMenuItem(11, "mi_trademarks", $Language->menuPhrase("11", "MenuText"), "trademarkslist", 16, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}trademarks'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10, "mi_models", $Language->menuPhrase("10", "MenuText"), "modelslist?cmd=resetall", 16, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}models'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10109, "mci_OTHER_TABLES", $Language->menuPhrase("10109", "MenuText"), "", -1, "", true, false, true, "fa-table", "", false, true);
$sideMenu->addMenuItem(2, "mi_categories", $Language->menuPhrase("2", "MenuText"), "categorieslist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}categories'), false, false, "", "", false, true);
$sideMenu->addMenuItem(3, "mi_customers", $Language->menuPhrase("3", "MenuText"), "customerslist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}customers'), false, false, "", "", false, true);
$sideMenu->addMenuItem(5, "mi_orderdetails", $Language->menuPhrase("5", "MenuText"), "orderdetailslist?cmd=resetall", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orderdetails'), false, false, "", "", false, true);
$sideMenu->addMenuItem(6, "mi_orders", $Language->menuPhrase("6", "MenuText"), "orderslist?cmd=resetall", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10085, "mi_orders2", $Language->menuPhrase("10085", "MenuText"), "orders2list", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}orders2'), false, false, "", "", false, true);
$sideMenu->addMenuItem(7, "mi_products", $Language->menuPhrase("7", "MenuText"), "productslist?cmd=resetall", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}products'), false, false, "", "", false, true);
$sideMenu->addMenuItem(8, "mi_shippers", $Language->menuPhrase("8", "MenuText"), "shipperslist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}shippers'), false, false, "", "", false, true);
$sideMenu->addMenuItem(9, "mi_suppliers", $Language->menuPhrase("9", "MenuText"), "supplierslist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}suppliers'), false, false, "", "", false, true);
$sideMenu->addMenuItem(12, "mi_order_details_extended", $Language->menuPhrase("12", "MenuText"), "orderdetailsextendedlist?cmd=resetall", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}order details extended'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10089, "mi_dji", $Language->menuPhrase("10089", "MenuText"), "djilist", 10109, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}dji'), false, false, "", "", false, true);
$sideMenu->addMenuItem(17, "mci_ADMIN_ONLY", $Language->menuPhrase("17", "MenuText"), "", -1, "", true, false, true, "fa-key", "", false, true);
$sideMenu->addMenuItem(4, "mi_employees", $Language->menuPhrase("4", "MenuText"), "employeeslist", 17, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}employees'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10084, "mi_userlevels", $Language->menuPhrase("10084", "MenuText"), "userlevelslist", 17, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevels'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10083, "mi_userlevelpermissions", $Language->menuPhrase("10083", "MenuText"), "userlevelpermissionslist", 17, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}userlevelpermissions'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10127, "mi_Dashboard1", $Language->menuPhrase("10127", "MenuText"), "dashboard1", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Dashboard1'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10152, "mi_Calendar1", $Language->menuPhrase("10152", "MenuText"), "calendar1", -1, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Calendar1'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10144, "mci_REPORTS", $Language->menuPhrase("10144", "MenuText"), "", -1, "", true, false, true, "fa-file-alt", "", false, true);
$sideMenu->addMenuItem(10118, "mi_Quarterly_Orders_By_Product", $Language->menuPhrase("10118", "MenuText"), "quarterlyordersbyproduct", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Quarterly Orders By Product'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10119, "mi_Sales_By_Customer", $Language->menuPhrase("10119", "MenuText"), "salesbycustomer", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10120, "mi_Sales_By_Customer_Compact", $Language->menuPhrase("10120", "MenuText"), "salesbycustomercompact", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer (Compact)'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10121, "mi_Alphabetical_List_of_Products", $Language->menuPhrase("10121", "MenuText"), "alphabeticallistofproducts", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Alphabetical List of Products'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10122, "mi_Products_By_Category", $Language->menuPhrase("10122", "MenuText"), "productsbycategory", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Products By Category'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10123, "mi_Sales_by_Category_for_2014", $Language->menuPhrase("10123", "MenuText"), "salesbycategoryfor2014", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales by Category for 2014'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10124, "mi_Sales_By_Year", $Language->menuPhrase("10124", "MenuText"), "salesbyyear", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Year'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10126, "mi_Sales_By_Customer_2", $Language->menuPhrase("10126", "MenuText"), "salesbycustomer2", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Sales By Customer 2'), false, false, "", "", false, true);
$sideMenu->addMenuItem(10146, "mi_Gantt", $Language->menuPhrase("10146", "MenuText"), "gantt", 10144, "", AllowListMenu('{DFB61542-7FFC-43AB-88E7-31D7F8D95066}Gantt'), false, false, "", "", false, true);
echo $sideMenu->toScript();
