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

    public function editPost($request, $postId)
    {
        $post=$this->postManager->getPost($postId);
        $categories=$this->categoryManager->getCategories();
        if ($request->get('submit')) {
            $this->postManager->editPost($request, $postId);
            header('Location: ../public/index.php');
        }
        echo $this->twig->render('form_AddPost.html.twig', [
           'post' => $post,
           'categories' => $categories
        ]);
    }
    
    public function deletePost($postId)
    {
            $this->postManager->deletePost($postId);
            header('Location: ../public/index.php');
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

    public function adminUsers()
    {
        $users=$this->userManager->getUsers();
        echo $this->twig->render('user_Admin.html.twig', [
            "users" => $users
          ]);
    }
}
