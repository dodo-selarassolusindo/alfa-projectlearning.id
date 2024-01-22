<?php

namespace PHPMaker2024\demo2024;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMaker2024\demo2024\Attributes\Delete;
use PHPMaker2024\demo2024\Attributes\Get;
use PHPMaker2024\demo2024\Attributes\Map;
use PHPMaker2024\demo2024\Attributes\Options;
use PHPMaker2024\demo2024\Attributes\Patch;
use PHPMaker2024\demo2024\Attributes\Post;
use PHPMaker2024\demo2024\Attributes\Put;

/**
 * orders_by_product2 controller
 */
class OrdersByProduct2Controller extends ControllerBase
{
    // summary
    #[Map(["GET", "POST", "OPTIONS"], "/ordersbyproduct2", [PermissionMiddleware::class], "summary.orders_by_product2")]
    public function summary(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "OrdersByProduct2Summary");
    }

    // OrderByProducts (chart)
    #[Map(["GET", "POST", "OPTIONS"], "/ordersbyproduct2/OrderByProducts", [PermissionMiddleware::class], "summary.orders_by_product2.OrderByProducts")]
    public function OrderByProducts(Request $request, Response $response, array $args): Response
    {
        return $this->runChart($request, $response, $args, "OrdersByProduct2Summary", "OrderByProducts");
    }
}
