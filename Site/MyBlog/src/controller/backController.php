<?php
namespace App\src\controller;

class BackController extends Controller
{
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

    public function editPost($request, $postId)
    {
        $post=$this->postManager->getPost($postId);
        $categories=$this->categoryManager->getCategories();
        if ($request->get('submit')) {
            $this->postManager->editPost($request, $postId);
            header('Location: ../public/index.php?route=AdminPosts');
        }
        echo $this->twig->render('form_AddPost.html.twig', [
           'post' => $post,
           'categories' => $categories
        ]);
    }
    
    public function deletePost($postId)
    {
            $this->postManager->deletePost($postId);
            header('Location: ../public/index.php?route=AdminPosts');
    }

    public function addCategory($category)
    {
        if ($category->get('submit')) {
            $this->categoryManager->addCategory($category);
            header('Location: ../public/index.php');
        }
    }

    public function editCategory($request, $categoryId)
    {
        $category=$this->categoryManager->getCategory($categoryId);
        if ($request->get('submit')) {
            $this->categoryManager->editCategory($request, $categoryId);
            header('Location: ../public/index.php');
        }
        echo $this->twig->render('form_AddCategory.html.twig', [
           'category' => $category
        ]);
    }
    
    public function deleteCategory($categoryId)
    {
            $this->categoryManager->deleteCategory($categoryId);
            header('Location: ../public/index.php');
    }

    public function adminPosts()
    {
        $posts=$this->postManager->getPosts();
        $categories=$this->categoryManager->getCategories();
        echo $this->twig->render('post_Admin.html.twig', [
            "posts" => $posts,
            "categories" => $categories
          ]);
    }

    public function adminComments()
    {
        $comments=$this->commentManager->getCommentsValidate();
        echo $this->twig->render('comment_Admin.html.twig', [
            "comments" => $comments
          ]);
    }

    public function statutComment($comment)
    {
        $this->commentManager->editComment($comment, $comment->get('commentId'));
            header('Location: ../public/index.php?route=AdminComments');
    }

    public function adminUsers()
    {
        $users=$this->userManager->getUsers();
        echo $this->twig->render('user_Admin.html.twig', [
            "users" => $users
          ]);
    }

    public function statutUser($user)
    {
        $this->userManager->editUser($user, $user->get('userId'));
            header('Location: ../public/index.php?route=AdminUsers');
    }
}
