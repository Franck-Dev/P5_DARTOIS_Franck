<?php

namespace App\src\controller;
use App\src\model\postManager;
use App\src\model\commentManager;

class frontController
{
    private $postManager;
    private $commentManager;

    public function __construct()
    {
        $this->postManager = new postManager();
        $this->commentManager = new commentManager();
    }
    public function home()
    {
         $posts=$this->postManager->getPosts();
        require '../templates/home.php';
    }

    public function post()
    {
        $posts=$this->postManager->getPost($_GET['postId']);
        $comments=$this->commentManager->getCommentsFromArticle($_GET['postId']);
        require '../templates/single.php';
    }
}