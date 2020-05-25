<?php
/**
 * @package Controller
 */
namespace App\src\controller;

use App\src\Framework\Controller;

/**
 * This file manage methods are using for blog administration
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
class BackController extends Controller
{
    /**
    * Send the datas of form towards postManager for a new post
    */
    public function addPost($post)
    {
        if ($post->get('submit')) {
            if ($post->files->get('image')) {
                $path='../public/Images';
                $name=$post->files->get('image')->getClientOriginalName();
                $extValide=['jpg','png','jpeg','gif','ico'];
                $extFile=$post->files->get('image')->getClientOriginalExtension();
                if (in_array($extFile, $extValide)) {
                    $post->files->get('image')->move($path, $name);
                    $this->postManager->addPost($post->request, $name);
                } else {
                    $this->session->getFlashBag()->add('ErUploadFiles', 'Le format du fichier a importer est inconnu');
                }
            }
            header('Location: ../public/index.php?route=AdminPosts');
        }
    }

    /**
    * Send the modifications of form towards postManager for a old post
    */
    public function editPost($request, $postId)
    {
        $post=$this->postManager->getPost($postId);
        $categories=$this->categoryManager->getCategories();
        if ($request->get('submit')) {
            $this->postManager->editPost($request, $postId);
            header('Location: ../public/index.php?route=AdminPosts');
        }
        echo $this->twig->render('forms/form_AddPost.html.twig', [
           'post' => $post,
           'categories' => $categories
        ]);
    }
    
    /**
    * Send the postId towards postManagerfor deleting post in database
    */
    public function deletePost($postId)
    {
            $this->postManager->deletePost($postId);
            header('Location: ../public/index.php?route=AdminPosts');
    }

    /**
    * Send the datas of form towards categoryManager for a new category
    */
    public function addCategory($category)
    {
        if ($category->get('submit')) {
            $this->categoryManager->addCategory($category);
            header('Location: ../public/index.php');
        }
    }

    /**
    * Send the modifications of form towards categoryManager for a old category
    */
    public function editCategory($request, $categoryId)
    {
        $category=$this->categoryManager->getCategory($categoryId);
        if ($request->get('submit')) {
            $this->categoryManager->editCategory($request, $categoryId);
            header('Location: ../public/index.php');
        }
        echo $this->twig->render('forms/form_AddCategory.html.twig', [
           'category' => $category
        ]);
    }

    /**
    * Send the categoryId towards categoryManagerfor deleting category in database
    */
    public function deleteCategory($categoryId)
    {
            $this->categoryManager->deleteCategory($categoryId);
            header('Location: ../public/index.php');
    }

    /**
    * Send the list of posts by one or all category for the adminPost template
    */
    public function adminPosts()
    {
        $posts=$this->postManager->getPosts();
        $categories=$this->categoryManager->getCategories();
        echo $this->twig->render('admin/post_Admin.html.twig', [
            "posts" => $posts,
            "categories" => $categories
          ]);
    }

    /**
    * Send the list of comments validated for the adminComment template
    */
    public function adminComments()
    {
        $comments=$this->commentManager->getCommentsValidate();
        echo $this->twig->render('admin/comment_Admin.html.twig', [
            "comments" => $comments
          ]);
    }

    /**
    * Send the statut of comment at commentManager
    */
    public function statutComment($comment)
    {
        $this->commentManager->editComment($comment, $comment->get('commentId'));
            header('Location: ../public/index.php?route=AdminComments');
    }

    /**
    * Send the list of users for the adminUser template
    */
    public function adminUsers()
    {
        $users=$this->userManager->getUsers();
        echo $this->twig->render('admin/user_Admin.html.twig', [
            "users" => $users
          ]);
    }

    /**
    * Send the statut of user at userManager
    */
    public function statutUser($user)
    {
        $this->userManager->editUser($user, $user->get('userId'));
            header('Location: ../public/index.php?route=AdminUsers');
    }
}
