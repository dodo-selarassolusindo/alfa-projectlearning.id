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

class OrderdetailsController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/orderdetailslist[/{keys:.*}]", [PermissionMiddleware::class], "list.orderdetails")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "OrderdetailsList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/orderdetailsadd[/{keys:.*}]", [PermissionMiddleware::class], "add.orderdetails")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "OrderdetailsAdd");
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/orderdetailsview[/{keys:.*}]", [PermissionMiddleware::class], "view.orderdetails")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "OrderdetailsView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/orderdetailsedit[/{keys:.*}]", [PermissionMiddleware::class], "edit.orderdetails")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "OrderdetailsEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/orderdetailsdelete[/{keys:.*}]", [PermissionMiddleware::class], "delete.orderdetails")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "OrderdetailsDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/orderdetailssearch", [PermissionMiddleware::class], "search.orderdetails")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $this->getKeyParams($args), "OrderdetailsSearch");
    }

    // Get keys as associative array
    protected function getKeyParams($args)
    {
        global $RouteValues;
        if (array_key_exists("keys", $args)) {
            $sep = Container("orderdetails")->RouteCompositeKeySeparator;
            $keys = explode($sep, $args["keys"]);
            if (count($keys) == 2) {
                $keyArgs = array_combine(["OrderID","ProductID"], $keys);
                $RouteValues = array_merge(Route(), $keyArgs);
                $args = array_merge($args, $keyArgs);
            }
        }
        return $args;
    }
}
