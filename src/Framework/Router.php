<?php

namespace App\src\Framework;

use Exception;
use App\src\Framework\Route;
use App\src\controller\backController;
use App\src\controller\errorController;
use App\src\controller\frontController;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    private $frontController;
    private $errorController;
    private $backController;

    public function __construct()
    {
        $this->frontController = new frontController();
        $this->errorController = new errorController();
        $this->backController = new backController();
    }
    public function run()
    {
        $request = Request::createFromGlobals();
        $uri=$request->getRequestUri();
        preg_match('/([A-z]+)\/?([A-z]+)*\/?([A-z]+|[0-9]+)*\/?([A-z]+|[0-9]+)*\/?([0-9]+)*/', $uri, $matches);
        $route = new Route($matches);
        $wayOK = $route->matchRoutes($uri, $request);
        $controllerName = $route->module;
        $controller = $this->$controllerName;
        $action = $route->action;
        $params = $route->params;
        try {
            if (method_exists($controller, $action) && $params != null) {
                call_user_func_array([$controller,$action], $params);
            } else {
                $controller->$action();
            }
        } catch (Exception $e) {
            $this->errorController->errorServer();
            var_dump($e);
        }
    }
}
