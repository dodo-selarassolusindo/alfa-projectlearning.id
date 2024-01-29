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

class Orders2Controller extends ControllerBase
{
    // FreightByEmployees (chart)
    #[Map(["GET", "POST", "OPTIONS"], "/orders2list/FreightByEmployees", [PermissionMiddleware::class], "list.orders2.FreightByEmployees")]
    public function FreightByEmployees(Request $request, Response $response, array $args): Response
    {
        return $this->runChart($request, $response, $args, "Orders2List", "FreightByEmployees");
    }

    // list
    #[Map(["GET","POST","OPTIONS"], "/orders2list[/{OrderID}]", [PermissionMiddleware::class], "list.orders2")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Orders2List");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/orders2add[/{OrderID}]", [PermissionMiddleware::class], "add.orders2")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Orders2Add");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/orders2view[/{OrderID}]", [PermissionMiddleware::class], "view.orders2")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Orders2View");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/orders2edit[/{OrderID}]", [PermissionMiddleware::class], "edit.orders2")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Orders2Edit");
    }

    // update
    #[Map(["GET","POST","OPTIONS"], "/orders2update", [PermissionMiddleware::class], "update.orders2")]
    public function update(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Orders2Update");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/orders2delete[/{OrderID}]", [PermissionMiddleware::class], "delete.orders2")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Orders2Delete");
    }
}
