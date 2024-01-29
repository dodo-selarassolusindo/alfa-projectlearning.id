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

class SuppliersController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/supplierslist[/{SupplierID}]", [PermissionMiddleware::class], "list.suppliers")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SuppliersList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/suppliersadd[/{SupplierID}]", [PermissionMiddleware::class], "add.suppliers")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SuppliersAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/suppliersview[/{SupplierID}]", [PermissionMiddleware::class], "view.suppliers")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SuppliersView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/suppliersedit[/{SupplierID}]", [PermissionMiddleware::class], "edit.suppliers")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SuppliersEdit");
    }

    // update
    #[Map(["GET","POST","OPTIONS"], "/suppliersupdate", [PermissionMiddleware::class], "update.suppliers")]
    public function update(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SuppliersUpdate");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/suppliersdelete[/{SupplierID}]", [PermissionMiddleware::class], "delete.suppliers")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SuppliersDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/supplierssearch", [PermissionMiddleware::class], "search.suppliers")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SuppliersSearch");
    }
}
