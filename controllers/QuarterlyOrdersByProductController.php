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
 * Quarterly_Orders_By_Product controller
 */
class QuarterlyOrdersByProductController extends ControllerBase
{
    // crosstab
    #[Map(["GET", "POST", "OPTIONS"], "/quarterlyordersbyproduct", [PermissionMiddleware::class], "crosstab.Quarterly_Orders_By_Product")]
    public function crosstab(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "QuarterlyOrdersByProductCrosstab");
    }

    // OrdersByCategory (chart)
    #[Map(["GET", "POST", "OPTIONS"], "/quarterlyordersbyproduct/OrdersByCategory", [PermissionMiddleware::class], "crosstab.Quarterly_Orders_By_Product.OrdersByCategory")]
    public function OrdersByCategory(Request $request, Response $response, array $args): Response
    {
        return $this->runChart($request, $response, $args, "QuarterlyOrdersByProductCrosstab", "OrdersByCategory");
    }
}
