<?php

namespace App\src\controller;

class frontController extends controller
{

    public function home()
    {
         $posts=$this->postManager->getPosts();
        require '../templates/home.php';
        //echo $this->twig->render('home.php',["posts" => $posts]);
        //echo $this->twig->render('home.html.twig',[
        //    "posts" => $posts[1]
        //  ]);
    }

    public function post()
    {
        $post=$this->postManager->getPost($_GET['postId']);
        $comments=$this->commentManager->getComments($_GET['postId']);
        require '../templates/single.php';
    }

    public function addComment($comment)
    {
        if(isset($comment['submit'])) {
            $this->commentManager->addComment($comment);
            header('Location: ../public/index.php');
        }
        return require '../templates/add_comment.php';
        //return $this->twig->render('add_article', [
        //    'post' => $post
        //]);
    }
}