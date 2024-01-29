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

class CalendarController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/calendarlist[/{Id}]", [PermissionMiddleware::class], "list.calendar")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CalendarList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/calendaradd[/{Id}]", [PermissionMiddleware::class], "add.calendar")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CalendarAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/calendarview[/{Id}]", [PermissionMiddleware::class], "view.calendar")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CalendarView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/calendaredit[/{Id}]", [PermissionMiddleware::class], "edit.calendar")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CalendarEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/calendardelete[/{Id}]", [PermissionMiddleware::class], "delete.calendar")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "CalendarDelete");
    }
}
