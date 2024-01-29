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
 * contact controller
 */
class ContactController extends ControllerBase
{
    // custom
    #[Map(["GET", "POST", "OPTIONS"], "/contact[/{params:.*}]", [PermissionMiddleware::class], "custom.contact")]
    public function custom(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "Contact");
    }
}