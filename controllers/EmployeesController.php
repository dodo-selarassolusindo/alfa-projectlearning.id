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

class EmployeesController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/employeeslist[/{EmployeeID}]", [PermissionMiddleware::class], "list.employees")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EmployeesList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/employeesadd[/{EmployeeID}]", [PermissionMiddleware::class], "add.employees")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EmployeesAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/employeesview[/{EmployeeID}]", [PermissionMiddleware::class], "view.employees")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EmployeesView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/employeesedit[/{EmployeeID}]", [PermissionMiddleware::class], "edit.employees")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EmployeesEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/employeesdelete[/{EmployeeID}]", [PermissionMiddleware::class], "delete.employees")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EmployeesDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/employeessearch", [PermissionMiddleware::class], "search.employees")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "EmployeesSearch");
    }
}
