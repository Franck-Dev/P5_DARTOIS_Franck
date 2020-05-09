<?php

namespace App\src\controller;


class backController extends controller
{
    public function addPost($post)
    {
        if(isset($post['submit'])) {
            $this->postManager->addPost($post);
            header('Location: ../public/index.php');
        }
        return require '../templates/add_post.php';
        //return $this->twig->render('add_article', [
        //    'post' => $post
        //]);
    }

    public function adminPost()
    {
        $posts=$this->postManager->getPosts();
        echo $this->twig->render('post_Admin.html.twig',[
            "posts" => $posts
          ]);
    }
}