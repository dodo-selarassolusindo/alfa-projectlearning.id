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
 * Sales_By_Customer_2 controller
 */
class SalesByCustomer2Controller extends ControllerBase
{
    // summary
    #[Map(["GET", "POST", "OPTIONS"], "/salesbycustomer2", [PermissionMiddleware::class], "summary.Sales_By_Customer_2")]
    public function summary(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "SalesByCustomer2Summary");
    }
}
