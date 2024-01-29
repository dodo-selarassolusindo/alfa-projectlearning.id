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

class ModelsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/modelslist[/{ID}]", [PermissionMiddleware::class], "list.models")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ModelsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/modelsadd[/{ID}]", [PermissionMiddleware::class], "add.models")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ModelsAdd");
    }

    // addopt
    #[Map(["GET","POST","OPTIONS"], "/modelsaddopt", [PermissionMiddleware::class], "addopt.models")]
    public function addopt(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ModelsAddopt", null, false);
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/modelsview[/{ID}]", [PermissionMiddleware::class], "view.models")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ModelsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/modelsedit[/{ID}]", [PermissionMiddleware::class], "edit.models")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ModelsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/modelsdelete[/{ID}]", [PermissionMiddleware::class], "delete.models")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ModelsDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/modelssearch", [PermissionMiddleware::class], "search.models")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ModelsSearch");
    }
}
