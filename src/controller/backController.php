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
     *
     * @param  array $post [new post with datas]
     * @return void
     */
    public function addPost($post)
    {
        if ($post->get('submit')) {
            if ($post->files->get('image')) {
                $path='/PyrTeck/public/Images';
                $name=$post->files->get('image')->getClientOriginalName();
                $extValide=['jpg','png','jpeg','gif','ico'];
                $extFile=$post->files->get('image')->getClientOriginalExtension();
                if (in_array($extFile, $extValide)) {
                    //The picture was remove in folder images
                    $post->files->get('image')->move($path, $name);
                    //This image will be resize for equal dimensions themself
                    $image_path=$path . '/' . $name;
                    $this->resizeImg($image_path, '', 1233, 633, 0, 'auto');
                    //Save in database
                    $this->postManager->addPost($post->request, $name);
                } else {
                    $this->session->getFlashBag()->add('ErUploadFiles', 'Le format du fichier a importer est inconnu');
                }
            }
            header('Location: /PyrTeck/Admin/Posts');
        }
    }
    
    /**
     * Send the modifications of form towards postManager for a old post
     *
     * @param  mixed $request [datas of post]
     * @param  int $postId [index of post in the database]
     * @return void
     */
    public function editPost($request, $postId)
    {
        $post=$this->postManager->getPost($postId);
        $categories=$this->categoryManager->getCategories();
        if ($request->get('submit')) {
            $this->postManager->editPost($request, $postId);
            header('Location: /PyrTeck/Admin/Posts');
        }
        echo $this->twig->render('forms/form_AddPost.html.twig', [
           'post' => $post,
           'categories' => $categories
        ]);
    }
        
    /**
     * Send the postId towards postManagerfor deleting post in database
     *
     * @param  int $postId [index of post in the database]
     * @return void
     */
    public function deletePost($postId)
    {
            $this->postManager->deletePost($postId);
            header('Location: /PyrTeck/Admin/Posts');
    }
        
    /**
     * Send the datas of form towards categoryManager for a new category
     *
     * @param  array $category [Datas of category]
     * @return void
     */
    public function addCategory($category)
    {
        if ($category->get('submit')) {
            $this->categoryManager->addCategory($category);
            header('Location: /PyrTeck/Blog');
        }
    }
    
    /**
     * Send the modifications of form towards categoryManager for a old category
     *
     * @param  mixed $request [Datas category]
     * @param  int $categoryId [index of category in the database]
     * @return void
     */
    public function editCategory($request, $categoryId)
    {
        $category=$this->categoryManager->getCategory($categoryId);
        if ($request->get('submit')) {
            $this->categoryManager->editCategory($request, $categoryId);
            header('Location: /PyrTeck/Blog');
        }
        echo $this->twig->render('forms/form_AddCategory.html.twig', [
           'category' => $category
        ]);
    }
    
    /**
     * Send the categoryId towards categoryManagerfor deleting category in database
     *
     * @param  int $categoryId [index of category in the database]
     * @return void
     */
    public function deleteCategory($categoryId)
    {
        $this->categoryManager->deleteCategory($categoryId);
        header('Location: /PyrTeck/Blog');
    }
    
    /**
     * Send the list of posts by one or all category for the adminPost template
     *
     * @return void
     */
    public function categories()
    {
        $categories=$this->categoryManager->getCategories();
        echo $this->twig->render('admin/category_Admin.html.twig', [
            "categories" => $categories
          ]);
    }
    
    /**
     * Send the list of posts by one or all category for the adminPost template
     *
     * @return void
     */
    public function posts()
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
     *
     * @return void
     */
    public function comments()
    {
        $comments=$this->commentManager->getCommentsValidate();
        echo $this->twig->render('admin/comment_Admin.html.twig', [
            "comments" => $comments
          ]);
    }
    
    /**
     * Send the statut of comment at commentManager
     *
     * @param  array $comment [Datas comment]
     * @return void
     */
    public function statutComment($comment)
    {
        $this->commentManager->editComment($comment, $comment->get('commentId'));
            header('Location: /PyrTeck/Admin/AdminComments');
    }
    
    /**
     * Send the list of users for the adminUser template
     *
     * @return void
     */
    public function users()
    {
        $users=$this->userManager->getUsers();
        echo $this->twig->render('admin/user_Admin.html.twig', [
            "users" => $users
          ]);
    }
    
    /**
     * Send the modification of data's user at userManager
     *
     * @param  array $user [Datas user]
     * @return void
     */
    public function statutUser($user)
    {
        $this->userManager->editUser($user, $user->get('userId'));
            header('Location: /PyrTeck/Admin/AdminUsers');
    }
    
    /**
     * Validate or devalidate the user's statut for admin only
     *
     * @param  int $userId
     * @param  string $statut
     * @return void
     */
    public function updateStatutUser($userId, $statut)
    {
        if ($statut === "True") {
            $stat = 1;
        } else {
            $stat = 0;
        }
        $this->userManager->updateStatut($userId, $stat);
            header('Location: /PyrTeck/Admin/AdminUsers');
    }
    
    /**
     * Validate or devalidate the comment's statut for admin only
     *
     * @param  int $commentId
     * @param  string $stat
     * @return void
     */
    public function updateStatutComment($commentId, $statut)
    {
        if ($statut === "True") {
            $stat = 1;
        } else {
            $stat = 0;
        }
        $this->commentManager->updateStatut($commentId, $stat);
            header('Location: /PyrTeck/Admin/AdminComments');
    }
    
    /**
     * Control if this acces is a access admin or not
     *
     * @return boolean
     */
    public function adminAccess()
    {
        if ($this->checkAccess() === 'ADMIN') {
            return true;
        } else {
            return false;
        }
    }
}
