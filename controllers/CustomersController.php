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

class CustomersController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/customerslist[/{CustomerID:.*}]", [PermissionMiddleware::class], "list.customers")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CustomersList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/customersadd[/{CustomerID:.*}]", [PermissionMiddleware::class], "add.customers")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CustomersAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/customersview[/{CustomerID:.*}]", [PermissionMiddleware::class], "view.customers")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CustomersView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/customersedit[/{CustomerID:.*}]", [PermissionMiddleware::class], "edit.customers")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CustomersEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/customersdelete[/{CustomerID:.*}]", [PermissionMiddleware::class], "delete.customers")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CustomersDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/customerssearch", [PermissionMiddleware::class], "search.customers")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CustomersSearch");
    }
}
