<?php

namespace App\config;

use Exception;
use App\config\Route;
use App\src\controller\backController;
use App\src\controller\errorController;
use App\src\controller\frontController;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;

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
        //$response = new Response();
        $uri=$request->getRequestUri();
        preg_match('/([A-z]+)\/?([A-z]+)*\/?([A-z]+|[0-9]+)*\/?([A-z]+|[0-9]+)*\/?([0-9]+)*/', $uri, $matches);
        $route = new Route($matches);
        $wayOK = $route->matchRoutes($uri, $request);
        $controllerName = $route->module;
        $controller = $this->$controllerName;
        $action = $route->action;
        $params = $route->params;
        //var_dump($route);
        try {
            if (method_exists($controller, $action) && $params != null) {
                //var_dump($params);
                call_user_func_array([$controller,$action], $params);
                //$controller->$action($params);                
            //     if ($request->query->get('route') === 'postsCategory') {
            //         $this->frontController->home($request->query->get('categoryId'));
            //     } elseif ($url[2] === 'blog') {
            //         $this->frontController->home();
            //     } elseif ($request->query->get('route') === 'post') {
            //         $this->frontController->post($request->query->get('postId'));
            //     } elseif ($request->query->get('route') === 'register') {
            //         $this->frontController->register($request);
            //     } elseif ($request->query->get('route') === 'login') {
            //         $this->frontController->login($request);
            //     } elseif ($request->query->get('route') === 'logout') {
            //         $this->frontController->logout();
            //     } elseif ($request->query->get('route') === 'userComments') {
            //         $this->frontController->userComments();
            //     } elseif ($request->query->get('route') === 'addComment') {
            //         $this->frontController->addComment($request, $request->query->get('postId'));
            //     } elseif ($request->query->get('route') === 'editComment') {
            //         $this->frontController->editComment(
            //             $request,
            //             $request->query->get('commentId'),
            //             $request->query->get('postId')
            //         );
            //     } elseif ($request->query->get('route') === 'deleteComment') {
            //         $this->frontController->deleteComment($request->query->get('commentId'));
            //     } elseif ($request->query->get('route') === 'sendMail') {
            //         $this->frontController->sendMail($request);
            //     } elseif ($request->query->get('route') === 'AdminPosts') {
            //         $this->backController->adminPosts();
            //     } elseif ($request->query->get('route') === 'AdminComments') {
            //         $this->backController->adminComments();
            //     } elseif ($request->query->get('route') === 'StatutComment') {
            //         $this->backController->statutComment($request->query);
            //     } elseif ($request->query->get('route') === 'AdminUsers') {
            //         $this->backController->adminUsers();
            //     } elseif ($request->query->get('route') === 'StatutUser') {
            //         $this->backController->statutUser($request->query);
            //     } elseif ($request->query->get('route') === 'addPost') {
            //         $this->backController->addPost($request);
            //     } elseif ($request->query->get('route') === 'editPost') {
            //         $this->backController->editPost($request, $request->query->get('postId'));
            //     } elseif ($request->query->get('route') === 'deletePost') {
            //         $this->backController->deletePost($request->query->get('postId'));
            //     } elseif ($request->query->get('route') === 'addCategory') {
            //         $this->backController->addCategory($request->request);
            //     } elseif ($request->query->get('route') === 'editCategory') {
            //         $this->backController->editCategory($request, $request->query->get('categoryId'));
            //     } elseif ($request->query->get('route') === 'deleteCategory') {
            //         $this->backController->deleteCategory($request->query->get('categoryId'));
            //     } else {
            //         $response->setContent('Page inconnue');
            //         $response->prepare($request);
            //         $response->send();
            //     }
            // } else {
            //     $this->frontController->home($categoryId='1');
            // }
            } else {
                //$this->frontController->blog($categoryId='1');
                $controller->$action();
            }
        } catch (Exception $e) {
            $this->errorController->errorServer();
            var_dump($e);
        }
    }
}
