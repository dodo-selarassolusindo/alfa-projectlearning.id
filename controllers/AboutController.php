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
 * about controller
 */
class AboutController extends ControllerBase
{
    // custom
    #[Map(["GET", "POST", "OPTIONS"], "/about[/{params:.*}]", [PermissionMiddleware::class], "custom.about")]
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "About");
    }
}