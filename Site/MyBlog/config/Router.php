<?php

namespace App\config;

use Exception;
use App\src\controller\backController;
use App\src\controller\errorController;
use App\src\controller\frontController;

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
        try{
            if(isset($_GET['route']))
            {
                if($_GET['route'] === 'post'){
                    $this->frontController->post($_GET['postId']);   
                }
                elseif($_GET['route'] === 'addPost'){
                    $this->backController->addPost($_POST);
                }
                elseif($_GET['route'] === 'addComment'){
                    $this->frontController->addComment($_POST);
                }
                else{
                    echo 'page inconnue';
                }
            }
            else{
                $this->frontController->home();
            }
        }
        catch (Exception $e)
        {
            $this->errorController->errorServer();
        }
    }
}