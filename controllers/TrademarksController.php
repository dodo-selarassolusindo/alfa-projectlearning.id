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

class TrademarksController extends ControllerBase
{
    // list
    #[Map(["GET","POST","OPTIONS"], "/trademarkslist[/{ID}]", [PermissionMiddleware::class], "list.trademarks")]
    public function list(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TrademarksList");
    }

    // add
    #[Map(["GET","POST","OPTIONS"], "/trademarksadd[/{ID}]", [PermissionMiddleware::class], "add.trademarks")]
    public function add(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TrademarksAdd");
    }

    // addopt
    #[Map(["GET","POST","OPTIONS"], "/trademarksaddopt", [PermissionMiddleware::class], "addopt.trademarks")]
    public function addopt(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TrademarksAddopt", null, false);
    }

    // view
    #[Map(["GET","POST","OPTIONS"], "/trademarksview[/{ID}]", [PermissionMiddleware::class], "view.trademarks")]
    public function view(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TrademarksView");
    }

    // edit
    #[Map(["GET","POST","OPTIONS"], "/trademarksedit[/{ID}]", [PermissionMiddleware::class], "edit.trademarks")]
    public function edit(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TrademarksEdit");
    }

    // delete
    #[Map(["GET","POST","OPTIONS"], "/trademarksdelete[/{ID}]", [PermissionMiddleware::class], "delete.trademarks")]
    public function delete(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TrademarksDelete");
    }

    // search
    #[Map(["GET","POST","OPTIONS"], "/trademarkssearch", [PermissionMiddleware::class], "search.trademarks")]
    public function search(Request $request, Response $response, array $args): Response
    {
        return $this->runPage($request, $response, $args, "TrademarksSearch");
    }
}
