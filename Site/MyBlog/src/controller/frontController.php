<?php
namespace App\src\controller;

class FrontController extends Controller
{
    public function home()
    {
        $categories=$this->categoryManager->getCategories();
        $posts=$this->postManager->getPosts();
        echo $this->twig->render('home.html.twig', [
            "posts" => $posts,
            "categories" => $categories
          ]);
    }

    public function post($postId)
    {
        $post=$this->postManager->getPost($postId);
        $comments=$this->commentManager->getComments($postId);
        echo $this->twig->render('single.html.twig', [
            "post" => $post,
            "comments" => $comments
          ]);
    }

    public function addComment($comment)
    {
        if ($comment->request->get('submit')) {
            $this->commentManager->addComment($comment);
            header('Location: ../public/index.php');
        }
    }

    public function deleteComment($commentId)
    {
        $this->commentManager->deleteComment($commentId);
        header('Location: ../public/index.php');
    }

    public function login($user)
    {  
        if ($user->request->get('submit')) {
            $this->userManager->checkUser($user);
            header('Location: ../public/index.php');
        }
        else{
            echo $this->twig->render('login.html.twig');
        }
    }

    public function register($user)
    {
        if ($user->request->get('submit')) {
            $this->userManager->register($user);
            header('Location: ../public/index.php');
        }
        echo $this->twig->render('form_AddUser.html.twig');
    }
}
