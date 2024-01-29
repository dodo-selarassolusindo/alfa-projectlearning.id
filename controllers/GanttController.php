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
 * Gantt controller
 */
class GanttController extends ControllerBase
{
    // summary
    #[Map(["GET", "POST", "OPTIONS"], "/gantt", [PermissionMiddleware::class], "summary.Gantt")]
    public function summary(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "GanttSummary");
    }
}
