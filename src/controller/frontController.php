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
     *
     * @param  int $index [Datas of user index]
     * @return void
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
     *
     * @param  int|null $categoryId [Choice of catogory for look a list post by herself]
     * @return void
     */
    public function blog($categoryId = null)
    {
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
     *
     * @param  int $postId [Index of post]
     * @return void
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
     *
     * @param  array $comment [Datas of comment]
     * @param  int $postId [Post Index]
     * @return void
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
     *
     * @param  array $comment [Datas comment]
     * @param  int $commentId [Index Comment]
     * @param  int $postId [Index of post for the comment]
     * @return void
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
     *
     * @param  int $commentId [Index of comment]
     * @param  int $postId [Index of post]
     * @return void
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
     *
     * @param  array $user [DAtas of user]
     * @return void
     */
    public function login($user)
    {
        if ($user->request->get('submit')) {
            $login = $this->userManager->login($user);
            if ($login && $login['isPasswordValid'] == true && $login['isUserActive'] == true) {
                $this->recupSession($login['result']);
                header('Location: /PyrTeck');
            } elseif ($login['isUserActive']  == false) {
                $this->session->getFlashBag()->add('connexion', 'Compte désactivé');
                $message = "Votre compte est désactivé, Contactez l'administrateur";
                echo $this->twig->render('login.html.twig', [
                    'message' => $message
                ]);
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
     *
     * @return void
     */
    public function logout()
    {
        $this->session->clear();
        header('Location: /PyrTeck');
    }
    
    /**
     * Sent datas for add a new user
     *
     * @param  array $user [Datas of user]
     * @return void
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
     * Update data was modified by user
     *
     * @param  int $userId
     * @return void
     */
    public function profile($user)
    {
        $userId = $this->userManager->checkUser($user)->getId();
        $this->userManager->editUser($user, $userId);
        header('Location: /PyrTeck');
    }
    
    /**
     * Sent datas for add a new user
     *
     * @return void
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
     *
     * @param  array $mail [Datas mail]
     * @return void
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
    
    /**
     * Read the differents files in the same folder
     *
     * @param  string $file
     * @return void
     */
    public function readFile($file)
    {
        header("Content-type: application/pdf");
        readfile("Files/".$file);
    }
}
