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

class ShippersController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/shipperslist[/{ShipperID}]", [PermissionMiddleware::class], "list.shippers")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ShippersList");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/shipperssearch", [PermissionMiddleware::class], "search.shippers")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "ShippersSearch");
    }
}
