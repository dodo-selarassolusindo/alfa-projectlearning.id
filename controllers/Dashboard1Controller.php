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
 * Dashboard1 controller
 */
class Dashboard1Controller extends ControllerBase
{
    // dashboard
    #[Map(["GET", "POST", "OPTIONS"], "/dashboard1", [PermissionMiddleware::class], "dashboard.Dashboard1")]
    public function dashboard(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Dashboard1");
    }
}
