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

class OrderDetailsExtendedController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/orderdetailsextendedlist[/{OrderID}]", [PermissionMiddleware::class], "list.order_details_extended")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrderDetailsExtendedList");
    }
}
