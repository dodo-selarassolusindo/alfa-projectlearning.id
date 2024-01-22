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
 * Sales_by_Category_for_2014 controller
 */
class SalesByCategoryFor2014Controller extends ControllerBase
{
    // summary
    #[Map(["GET", "POST", "OPTIONS"], "/salesbycategoryfor2014", [PermissionMiddleware::class], "summary.Sales_by_Category_for_2014")]
    public function summary(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SalesByCategoryFor2014Summary");
    }

    // SalesByCategory2014 (chart)
    #[Map(["GET", "POST", "OPTIONS"], "/salesbycategoryfor2014/SalesByCategory2014", [PermissionMiddleware::class], "summary.Sales_by_Category_for_2014.SalesByCategory2014")]
    public function SalesByCategory2014(Request $request, Response $response, array $args): Response
    {
        return $this->runChart($request, $response, $args, "SalesByCategoryFor2014Summary", "SalesByCategory2014");
    }
}
