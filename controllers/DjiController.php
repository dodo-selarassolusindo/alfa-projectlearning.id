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

class DjiController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/djilist[/{ID}]", [PermissionMiddleware::class], "list.dji")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DjiList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/djiadd[/{ID}]", [PermissionMiddleware::class], "add.dji")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DjiAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/djiview[/{ID}]", [PermissionMiddleware::class], "view.dji")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DjiView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/djiedit[/{ID}]", [PermissionMiddleware::class], "edit.dji")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DjiEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/djidelete[/{ID}]", [PermissionMiddleware::class], "delete.dji")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "DjiDelete");
    }
}
