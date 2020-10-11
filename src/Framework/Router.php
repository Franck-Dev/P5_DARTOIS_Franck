<?php
/**
 * @package Framework
 */
namespace App\src\Framework;

use Exception;
use App\src\Framework\Route;
use App\src\controller\backController;
use App\src\controller\errorController;
use App\src\controller\frontController;
use Symfony\Component\HttpFoundation\Request;

/**
 * This file oriented client by the url given:
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
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
    /**
     * Accessing to good method in good controller with params by route
     *
     * @return void
     */
    public function run()
    {
        $request = Request::createFromGlobals();
        $uri=$request->getRequestUri();
        preg_match('/([A-z]+)\/?([A-z]+)*\/?([A-z]+|[0-9]+)*\/?([A-z]+|[0-9]+)*\/?([0-9]+)*/', $uri, $matches);
        $route = new Route($matches);
        $wayOK = $route->matchRoutes($uri, $request);
        $controllerName = $route->module;
        $controller = $this->$controllerName;
        //Control access rules it's ok for this user
        $method = 'adminAccess';
        if (method_exists($controller, $method) && $controller->adminAccess() != true) {
            $controllerName ='errorController';
            $controller = $this->$controllerName;
            $action = 'errorAccess';
            $params = '';
        } else {
            $action = $route->action;
            $params = $route->params;
        }
        try {
            if (method_exists($controller, $action) && $params != null) {
                $controller->$action(...$params);
            } else {
                $controller->$action();
            }
        } catch (Exception $e) {
            $this->errorController->errorServer();
            var_dump($e);
        }
    }
}
