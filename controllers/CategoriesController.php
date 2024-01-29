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

class CategoriesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/categorieslist[/{CategoryID}]", [PermissionMiddleware::class], "list.categories")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CategoriesList");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/categoriesview[/{CategoryID}]", [PermissionMiddleware::class], "view.categories")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CategoriesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/categoriesedit[/{CategoryID}]", [PermissionMiddleware::class], "edit.categories")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CategoriesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/categoriesdelete[/{CategoryID}]", [PermissionMiddleware::class], "delete.categories")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CategoriesDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/categoriessearch", [PermissionMiddleware::class], "search.categories")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CategoriesSearch");
    }
}
