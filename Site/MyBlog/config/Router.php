<?php

namespace App\config;

use Exception;
use App\src\controller\backController;
use App\src\controller\errorController;
use App\src\controller\frontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $response = new Response();
        try {
            if ($request->query->get('route')) {
                if ($request->query->get('route') === 'post') {
                    $this->frontController->post($request->query->get('postId'));
                } elseif ($request->query->get('route') === 'register') {
                    $this->frontController->register($request);
                } elseif ($request->query->get('route') === 'login') {
                    $this->frontController->login($request);
                } elseif ($request->query->get('route') === 'logout') {
                    $this->frontController->logout();
                } elseif ($request->query->get('route') === 'userComments') {
                    $this->frontController->userComments();
                } elseif ($request->query->get('route') === 'addComment') {
                    $this->frontController->addComment($request);
                } elseif ($request->query->get('route') === 'deleteComment') {
                    $this->frontController->deleteComment($request->query->get('commentId'));
                } elseif ($request->query->get('route') === 'AdminPosts') {
                    $this->backController->adminPosts();
                } elseif ($request->query->get('route') === 'AdminComments') {
                    $this->backController->adminComments();
                } elseif ($request->query->get('route') === 'AdminUsers') {
                    $this->backController->adminUsers();
                } elseif ($request->query->get('route') === 'addPost') {
                    $this->backController->addPost($request->request);
                } elseif ($request->query->get('route') === 'editPost') {
                    $this->backController->editPost($request, $request->query->get('postId'));
                } elseif ($request->query->get('route') === 'deletePost') {
                    $this->backController->deletePost($request->query->get('postId'));
                } elseif ($request->query->get('route') === 'addCategory') {
                    $this->backController->addCategory($request->request);
                } elseif ($request->query->get('route') === 'editCategory') {
                    $this->backController->editCategory($request, $request->query->get('categoryId'));
                } elseif ($request->query->get('route') === 'deleteCategory') {
                    $this->backController->deleteCategory($request->query->get('categoryId'));
                } else {
                    $response->setContent('Page inconnue');
                    $response->prepare($request);
                    $response->send();
                }
            } else {
                $this->frontController->home();
            }
        }
        catch (Exception $e) {
            var_dump($e);
        }
    }
}
