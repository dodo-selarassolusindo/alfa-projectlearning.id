<?php

namespace PHPMaker2024\prj_alfa;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\prj_alfa\Attributes\Delete;
use PHPMaker2024\prj_alfa\Attributes\Get;
use PHPMaker2024\prj_alfa\Attributes\Map;
use PHPMaker2024\prj_alfa\Attributes\Options;
use PHPMaker2024\prj_alfa\Attributes\Patch;
use PHPMaker2024\prj_alfa\Attributes\Post;
use PHPMaker2024\prj_alfa\Attributes\Put;

/**
 * Calendar1 controller
 */
class Calendar1Controller extends ControllerBase
{
    // calendar
    #[Map(["GET", "POST", "OPTIONS"], "/calendar1", [PermissionMiddleware::class], "calendar.Calendar1")]
    public function calendar(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1Calendar");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/calendar1add[/{Id}]", [PermissionMiddleware::class], "add.Calendar1")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1Add");
    }

    // view
    #[Map(["GET","OPTIONS"], "/calendar1view[/{Id}]", [PermissionMiddleware::class], "view.Calendar1")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1View");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/calendar1edit[/{Id}]", [PermissionMiddleware::class], "edit.Calendar1")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1Edit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/calendar1delete[/{Id}]", [PermissionMiddleware::class], "delete.Calendar1")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Calendar1Delete");
    }
}
