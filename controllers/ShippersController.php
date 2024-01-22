<?php

namespace PHPMaker2024\demo2024;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\demo2024\Attributes\Delete;
use PHPMaker2024\demo2024\Attributes\Get;
use PHPMaker2024\demo2024\Attributes\Map;
use PHPMaker2024\demo2024\Attributes\Options;
use PHPMaker2024\demo2024\Attributes\Patch;
use PHPMaker2024\demo2024\Attributes\Post;
use PHPMaker2024\demo2024\Attributes\Put;

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
