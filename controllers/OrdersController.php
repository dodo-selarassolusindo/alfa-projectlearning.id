<?php

namespace PHPMaker2024\prj_alfa;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\prj_alfa\Attributes\Delete;
use PHPMaker2024\prj_alfa\Attributes\Get;
use PHPMaker2024\prj_alfa\Attributes\Map;
use PHPMaker2024\prj_alfa\Attributes\Options;
use PHPMaker2024\prj_alfa\Attributes\Patch;
use PHPMaker2024\prj_alfa\Attributes\Post;
use PHPMaker2024\prj_alfa\Attributes\Put;

class OrdersController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/orderslist[/{OrderID}]", [PermissionMiddleware::class], "list.orders")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/ordersadd[/{OrderID}]", [PermissionMiddleware::class], "add.orders")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/ordersview[/{OrderID}]", [PermissionMiddleware::class], "view.orders")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/ordersedit[/{OrderID}]", [PermissionMiddleware::class], "edit.orders")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/ordersdelete[/{OrderID}]", [PermissionMiddleware::class], "delete.orders")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/orderssearch", [PermissionMiddleware::class], "search.orders")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersSearch");
    }

    // query
    #[Map(["GET","POST","OPTIONS"], "/ordersquery", [PermissionMiddleware::class], "query.orders")]
    public function query(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersSearch", "OrdersQuery");
    }
}
