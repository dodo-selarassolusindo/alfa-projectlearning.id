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

class MateriController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/materilist[/{id}]", [PermissionMiddleware::class], "list.materi")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MateriList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/materiadd[/{id}]", [PermissionMiddleware::class], "add.materi")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MateriAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/materiview[/{id}]", [PermissionMiddleware::class], "view.materi")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MateriView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/materiedit[/{id}]", [PermissionMiddleware::class], "edit.materi")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MateriEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/materidelete[/{id}]", [PermissionMiddleware::class], "delete.materi")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "MateriDelete");
    }
}
