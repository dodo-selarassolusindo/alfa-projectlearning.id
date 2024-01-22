<?php

namespace PHPMaker2024\demo2024;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\demo2024\Attributes\Delete;
use PHPMaker2024\demo2024\Attributes\Get;
use PHPMaker2024\demo2024\Attributes\Map;
use PHPMaker2024\demo2024\Attributes\Options;
use PHPMaker2024\demo2024\Attributes\Patch;
use PHPMaker2024\demo2024\Attributes\Post;
use PHPMaker2024\demo2024\Attributes\Put;

class ProductsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/productslist[/{ProductID}]", [PermissionMiddleware::class], "list.products")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProductsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/productsadd[/{ProductID}]", [PermissionMiddleware::class], "add.products")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProductsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/productsview[/{ProductID}]", [PermissionMiddleware::class], "view.products")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProductsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/productsedit[/{ProductID}]", [PermissionMiddleware::class], "edit.products")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProductsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/productsdelete[/{ProductID}]", [PermissionMiddleware::class], "delete.products")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProductsDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/productssearch", [PermissionMiddleware::class], "search.products")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ProductsSearch");
    }
}
