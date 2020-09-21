<?php
/**
 * @package Controller
 */
namespace App\src\controller;

use Swift_Message;
use App\src\Framework\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * This file manage methods are using for blog and homepage
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
class FrontController extends Controller
{
    /**
    * Sent datas in SESSION object
    */
    private function recupSession($index)
    {
        foreach ($index as $key => $value) {
            if (is_int($key) === true) {
                //I don't take integrekeys
            } else {
                $this->session->set($key, $value);
            }
        }
        $countComments=$this->commentManager->getCountComments($index['id']);
        $this->session->set('countComments', $countComments);
    }

    /**
    * Sent lists of posts, comments and category in homepage
    * @Route("/", name="home")
    */
    public function Blog($categoryId = null)
    {
        //var_dump($categoryId);
        $posts = $this->postManager->getPosts($categoryId);
        $commentsCount = $this->commentManager->getcommentsCount();
        $postsCount = $this->postManager->getpostsCount();
        if ($categoryId!=1) {
            $categories = $this->categoryManager->getCategories();
            if (!$categoryId) {
                $categoryName='Toutes';
            } else {
                $categoryName=$categories[$categoryId]->getName();
            }
            echo $this->twig->render('blog.html.twig', [
                "posts" => $posts,
                "categories" => $categories,
                "nbposts" => $postsCount,
                "categoryName" => $categoryName,
                "nbcomments" => $commentsCount
            ]);
        } else {
            echo $this->twig->render('home.html.twig', [
                "posts" => $posts,
                "nbposts" => $postsCount,
                "nbcomments" => $commentsCount
            ]);
        }
    }

    /**
    * Sent data's postId to single template for visualization
    * @Route("/postId/{id}", name="article")
    */
    public function post($postId)
    {   
        $post = $this->postManager->getPost($postId);
        $comments = $this->commentManager->getComments($postId);
        $commentsCount = $this->commentManager->getcommentsCount($postId);
        echo $this->twig->render('single.html.twig', [
            "post" => $post,
            "comments" => $comments,
            "nbcomments" => $commentsCount
        ]);
    }

    /**
    * Sent datas for add a new comment
    */
    public function addComment($comment, $postId)
    {
        if ($comment->request->get('submit')) {
            $this->commentManager->addComment($comment, $postId);
            header('Location: /PyrTeck/Blog/post/' . $postId);
        }
    }

    /**
    * Sent datas for update a old commentby commentId
    */
    public function editComment($comment, $commentId, $postId)
    {
        if ($comment->request->get('submit')) {
            $this->commentManager->editComment($comment, $commentId);
            header('Location: /PyrTeck/Blog/post/' . $postId);
        } else {
            $comment = $this->commentManager->editComment($comment, $commentId);
            echo $this->twig->render('single.html.twig', [
                'message' => $comment
            ]);
        }
    }

    /**
    * Sent commentId for delete comment
    */
    public function deleteComment($commentId, $postId)
    {
        $violations = $this->validator->validate($commentId, [
            new NotBlank(),
        ]);
        if (0 !== count($violations)) {
            foreach ($violations as $violation) {
                echo $violation->getMessage();
            }
        } else {
            $this->commentManager->deleteComment($commentId);
            header('Location: /PyrTeck/Blog/post/' . $postId);
        }
    }

    /**
    * Sent data for identify user
    */
    public function login($user)
    {
        if ($user->request->get('submit')) {
            $login = $this->userManager->login($user);
            if ($login && $login['isPasswordValid'] == true) {
                $this->recupSession($login['result']);
                header('Location: /PyrTeck');
            } else {
                $this->session->getFlashBag()->add('connexion', 'Mot de passe ou identifiant incorrect');
                $message = 'Mot de passe ou identifiant incorrect';
                echo $this->twig->render('login.html.twig', [
                    'message' => $message
                ]);
            }
        } else {
            echo $this->twig->render('login.html.twig');
        }
    }

    /**
    * Sent for stop session user
    */
    public function logout()
    {
        $this->session->clear();
        header('Location: /PyrTeck');
    }

    /**
    * Sent datas for add a new user
    */
    public function register($user)
    {
        if ($user->request->get('submit')) {
            //Check in database if user exist
            $userExist = $this->userManager->checkUser($user->request);
            if (!$userExist) {
                echo $this->twig->render('register.html.twig', [
                    'user' => $user,
                    'message' => 'Utilisateur deja existant'
                ]);
            } else {
                foreach ($this->parameters['admin'] as $status) {
                    if ($status['name'] == $user->request->get('username')) {
                        $statut = 'ADMIN';
                    break;
                    } else {
                        var_dump($user->request->get('username'));
                        $statut = 'USER';
                    }
                }
                $this->userManager->register($user, $statut);
                header('Location: /PyrTeck');
            }
        }
        echo $this->twig->render('register.html.twig');
    }

    /**
    * Sent datas to show the list comments of user connected
    */
    public function userComments()
    {
        $commentsUser = $this->commentManager->getcommentsUser($this->session->get('id'));
        echo $this->twig->render('comments_User.html.twig', [
            'commentsUser' => $commentsUser
        ]);
    }

    /**
    * Sent mail with datas contact
    */
    public function sendMail($mail)
    {
        // Create a message
        $message = (new Swift_Message('Demande de renseignement'))
        ->setFrom([($mail->request->get('email')) => ($mail->request->get('name'))])
        ->setTo([ 'franck.pyren@orange.fr', ($mail->request->get('email')) => ($mail->request->get('name'))])
        ->setBody($mail->request->get('message'));
        // Send the message
        $result = $this->mailer->send($message);
        if ($result === 1) {
            $message='Le message n\'a pu être envoyé, l\'adresse mail est érronée';
        } else {
            $message='Le message a bien été envoyé';
        }
        
        // Callback home with response send mail
        echo $this->twig->render('home.html.twig', [
            "message" => $message,
            "statutmessage" => $result
        ]);
    }
}