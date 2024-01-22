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

class CarsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/carslist[/{ID}]", [PermissionMiddleware::class], "list.cars")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CarsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/carsadd[/{ID}]", [PermissionMiddleware::class], "add.cars")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CarsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/carsview[/{ID}]", [PermissionMiddleware::class], "view.cars")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CarsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/carsedit[/{ID}]", [PermissionMiddleware::class], "edit.cars")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CarsEdit");
    }

    // update
    #[Map(["GET","POST","OPTIONS"], "/carsupdate", [PermissionMiddleware::class], "update.cars")]
    public function update(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CarsUpdate");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/carsdelete[/{ID}]", [PermissionMiddleware::class], "delete.cars")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CarsDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/carssearch", [PermissionMiddleware::class], "search.cars")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CarsSearch");
    }

    // query
    #[Map(["GET","POST","OPTIONS"], "/carsquery", [PermissionMiddleware::class], "query.cars")]
    public function query(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CarsSearch", "CarsQuery");
    }
}
