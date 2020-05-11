<?php
namespace App\src\controller;

class BackController extends Controller
{
    public function addPost($post)
    {
        if ($post->get('submit')) {
            $this->postManager->addPost($post);
            header('Location: ../public/index.php');
        }
    }

    public function editPost($request,$postId)
    {
        $post=$this->postManager->getPost($postId);
        if ($request->get('submit')) {
            $this->postManager->editPost($request,$postId);
            header('Location: ../public/index.php');
        }
        //var_dump($post);
        echo $this->twig->render('form_AddPost.html.twig', [
           'post' => $post
        ]);
    }
    
    public function deletePost($postId)
    {
            $this->postManager->deletePost($postId);
            header('Location: ../public/index.php');
    }

    public function adminPost()
    {
        $posts=$this->postManager->getPosts();
        echo $this->twig->render('post_Admin.html.twig', [
            "posts" => $posts
          ]);
    }
}
