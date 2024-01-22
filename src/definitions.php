<?php

namespace PHPMaker2024\demo2024;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Slim\HttpCache\CacheProvider;
use Slim\Flash\Messages;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Platforms;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Mime\MimeTypes;
use FastRoute\RouteParser\Std;
use Illuminate\Encryption\Encrypter;
use HTMLPurifier_Config;
use HTMLPurifier;

// Connections and entity managers
$definitions = [];
$dbids = array_keys(Config("Databases"));
foreach ($dbids as $dbid) {
    $definitions["connection." . $dbid] = \DI\factory(function (string $dbid) {
        return ConnectDb(Db($dbid));
    })->parameter("dbid", $dbid);
    $definitions["entitymanager." . $dbid] = \DI\factory(function (ContainerInterface $c, string $dbid) {
        $cache = IsDevelopment()
            ? DoctrineProvider::wrap(new ArrayAdapter())
            : DoctrineProvider::wrap(new FilesystemAdapter(directory: Config("DOCTRINE.CACHE_DIR")));
        $config = Setup::createAttributeMetadataConfiguration(
            Config("DOCTRINE.METADATA_DIRS"),
            IsDevelopment(),
            null,
            $cache
        );
        $conn = $c->get("connection." . $dbid);
        return new EntityManager($conn, $config);
    })->parameter("dbid", $dbid);
}

return [
    "app.cache" => \DI\create(CacheProvider::class),
    "app.flash" => fn(ContainerInterface $c) => new Messages(),
    "app.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "views/"),
    "email.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "lang/"),
    "sms.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "lang/"),
    "app.audit" => fn(ContainerInterface $c) => (new Logger("audit"))->pushHandler(new AuditTrailHandler($GLOBALS["RELATIVE_PATH"] . "log/audit.log")), // For audit trail
    "app.logger" => fn(ContainerInterface $c) => (new Logger("log"))->pushHandler(new RotatingFileHandler($GLOBALS["RELATIVE_PATH"] . "log/log.log")),
    "sql.logger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debug.stack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "app.csrf" => fn(ContainerInterface $c) => new Guard($GLOBALS["ResponseFactory"], Config("CSRF_PREFIX")),
    "html.purifier.config" => fn(ContainerInterface $c) => HTMLPurifier_Config::createDefault(),
    "html.purifier" => fn(ContainerInterface $c) => new HTMLPurifier($c->get("html.purifier.config")),
    "debug.stack" => \DI\create(DebugStack::class),
    "debug.sql.logger" => \DI\create(DebugSqlLogger::class),
    "debug.timer" => \DI\create(Timer::class),
    "app.security" => \DI\create(AdvancedSecurity::class),
    "user.profile" => \DI\create(UserProfile::class),
    "app.session" => \DI\create(HttpSession::class),
    "mime.types" => \DI\create(MimeTypes::class),
    "app.language" => \DI\create(Language::class),
    PermissionMiddleware::class => \DI\create(PermissionMiddleware::class),
    ApiPermissionMiddleware::class => \DI\create(ApiPermissionMiddleware::class),
    JwtMiddleware::class => \DI\create(JwtMiddleware::class),
    Std::class => \DI\create(Std::class),
    Encrypter::class => fn(ContainerInterface $c) => new Encrypter(AesEncryptionKey(base64_decode(Config("AES_ENCRYPTION_KEY"))), Config("AES_ENCRYPTION_CIPHER")),

    // Tables
    "cars" => \DI\create(Cars::class),
    "categories" => \DI\create(Categories::class),
    "customers" => \DI\create(Customers::class),
    "employees" => \DI\create(Employees::class),
    "orderdetails" => \DI\create(Orderdetails::class),
    "orders" => \DI\create(Orders::class),
    "products" => \DI\create(Products::class),
    "shippers" => \DI\create(Shippers::class),
    "suppliers" => \DI\create(Suppliers::class),
    "models" => \DI\create(Models::class),
    "trademarks" => \DI\create(Trademarks::class),
    "userlevelpermissions" => \DI\create(Userlevelpermissions::class),
    "userlevels" => \DI\create(Userlevels::class),
    "order_details_extended" => \DI\create(OrderDetailsExtended::class),
    "orders2" => \DI\create(Orders2::class),
    "cars2" => \DI\create(Cars2::class),
    "home" => \DI\create(Home::class),
    "news" => \DI\create(News::class),
    "order_details_extended_2" => \DI\create(OrderDetailsExtended2::class),
    "sales_by_category_for_20142" => \DI\create(SalesByCategoryFor20142::class),
    "Quarterly_Orders_By_Product" => \DI\create(QuarterlyOrdersByProduct::class),
    "quarterly_orders_by_product" => \DI\create(QuarterlyOrdersByProduct::class),
    "Sales_By_Customer" => \DI\create(SalesByCustomer::class),
    "sales_by_customer" => \DI\create(SalesByCustomer::class),
    "Sales_By_Customer_Compact" => \DI\create(SalesByCustomerCompact::class),
    "sales_by_customer_compact" => \DI\create(SalesByCustomerCompact::class),
    "Alphabetical_List_of_Products" => \DI\create(AlphabeticalListOfProducts::class),
    "alphabetical_list_of_products" => \DI\create(AlphabeticalListOfProducts::class),
    "Products_By_Category" => \DI\create(ProductsByCategory::class),
    "products_by_category" => \DI\create(ProductsByCategory::class),
    "Sales_by_Category_for_2014" => \DI\create(SalesByCategoryFor2014::class),
    "sales_by_category_for_2014" => \DI\create(SalesByCategoryFor2014::class),
    "Sales_By_Year" => \DI\create(SalesByYear::class),
    "sales_by_year" => \DI\create(SalesByYear::class),
    "Sales_By_Order" => \DI\create(SalesByOrder::class),
    "sales_by_order" => \DI\create(SalesByOrder::class),
    "Sales_By_Customer_2" => \DI\create(SalesByCustomer2::class),
    "sales_by_customer_2" => \DI\create(SalesByCustomer2::class),
    "Dashboard1" => \DI\create(Dashboard1::class),
    "dashboard1" => \DI\create(Dashboard1::class),
    "orders_by_product2" => \DI\create(OrdersByProduct2::class),
    "Gantt" => \DI\create(Gantt::class),
    "gantt" => \DI\create(Gantt::class),
    "calendar" => \DI\create(Calendar::class),
    "Calendar1" => \DI\create(Calendar1::class),
    "calendar1" => \DI\create(Calendar1::class),
    "audittrail" => \DI\create(Audittrail::class),

    // User table
    "usertable" => \DI\get("employees"),
] + $definitions;
