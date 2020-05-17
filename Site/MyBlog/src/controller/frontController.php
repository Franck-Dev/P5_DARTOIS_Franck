<?php

namespace App\src\controller;

use SebastianBergmann\Type\TypeName;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;


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
    
    public function home($categoryId = null)
    {
        $categories=$this->categoryManager->getCategories();
        $posts=$this->postManager->getPosts($categoryId);
        $commentsCount=$this->commentManager->getcommentsCount();
        $postsCount=$this->postManager->getpostsCount();
        echo $this->twig->render('home.html.twig', [
            "posts" => $posts,
            "categories" => $categories,
            "nbposts" => $postsCount,
            "nbcomments" => $commentsCount
          ]);
    }

    public function post($postId)
    {
        $post=$this->postManager->getPost($postId);
        $comments=$this->commentManager->getComments($postId);
        $commentsCount=$this->commentManager->getcommentsCount($postId);
        echo $this->twig->render('single.html.twig', [
            "post" => $post,
            "comments" => $comments,
            "count" => $commentsCount
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
        $violations = $this->validator->validate($commentId, [
            new Type('integer'),
            new NotBlank(),
        ]);
        if (0 !== count($violations)) {
            foreach ($violations as $violation){
                echo $violation->getMessage();
            } 
        } else {
            $this->commentManager->deleteComment($commentId);
            header('Location: ../public/index.php');
        }
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
                $message='Mot de passe ou identifiant incorrect';
                echo $this->twig->render('login.html.twig',[
                    'message' => $message
                ]);
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
            //Check in database if user exist
            $userExist=$this->userManager->checkUser($user->request);
            if (!$userExist) {
                echo $this->twig->render('register.html.twig', [
                    'user' => $user,
                    'message' => 'Utilisateur deja existant'
                ]);
            } else {
                $this->userManager->register($user);
                header('Location: ../public/index.php');
            }            
        }
        echo $this->twig->render('register.html.twig');
    }

    public function userComments()
    {
        $commentsUser=$this->commentManager->getcommentsUser($this->session->get('id'));
        echo $this->twig->render('comments_User.html.twig', [
            'commentsUser' => $commentsUser
        ]);
    }
}
