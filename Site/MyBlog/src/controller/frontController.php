<?php
namespace App\src\controller;


class FrontController extends Controller
{
    public function home()
    {
         $posts=$this->postManager->getPosts();
        echo $this->twig->render('home.html.twig', [
            "posts" => $posts
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
        if ($comment->get('submit')) {
            $this->commentManager->addComment($comment);
            header('Location: ../public/index.php');
        }
        //return require '../templates/add_comment.php';
        return $this->twig->render('add_comment.html.twig', [
           'comment' => $comment
        ]);
    }
}
