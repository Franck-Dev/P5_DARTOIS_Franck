<?php

namespace App\src\controller;


class FrontController extends Controller
{
    
    private function recupSession($index)
    {
        foreach($index as $key => $value) {
            if (is_int($key) === true) {

            }
            else {
                $this->session->set($key,$value);
            }
        }
    }
    
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
            $login=$this->userManager->login($user);
            if ($login && $login['isPasswordValid'] == true) {
                $this->recupSession($login['result']);
                header('Location: ../public/index.php');
            }
            else{
                $this->session->getFlashBag()->add('connexion',
                 'Mot de passe ou identifiant incorrect');
            }
        }
        else{
            echo $this->twig->render('login.html.twig');
        }
    }

    public function logout()
    {
        $this->session->clear();
        header('Location: ../public/index.php');
    }

    public function register($user)
    {
        if ($user->request->get('submit')) {
            $this->userManager->register($user);
            header('Location: ../public/index.php');
        }
        echo $this->twig->render('form_AddUser.html.twig');
    }

    public function userComments()
    {
        $commentsUser=$this->commentManager->getcommentsUser($this->session->get('id'));
        echo $this->twig->render('comments_User.html.twig', [
            'commentsUser' => $commentsUser
        ]);
    }
}
