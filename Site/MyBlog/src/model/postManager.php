<?php
namespace App\src\model;

use App\src\entity\Post;
use App\src\manager\Manager;

class PostManager extends Manager
{
    private function buildObject($row)
    {
        $post = new Post();
        $post->setId($row['id']);
        $post->setTitle($row['title']);
        $post->setChapo($row['chapo']);
        $post->setDescription($row['description']);
        $post->setUser($row['username']);
        $post->setCategory($row['namecate']);
        $post->setCreatedAt($row['createdAt']);
        return $post;
    }

    public function getPosts()
    {
        $sql='SELECT p.id, p.title, p.description, 
        p.chapo, u.username, p.createdAt, c.name as namecate
        FROM posts as p 
        LEFT JOIN user as u ON p.user_id = u.id 
        LEFT JOIN category as c ON p.category_id = c.id
        ORDER BY id DESC';
        $result= $this->createQuery($sql);
        foreach ($result as $row) {
            $postId=$row['id'];
            $post[$postId]=$this->buildObject($row);
        }
        $result->closeCursor();
        return $post;
    }

    public function getPost($postId)
    {
        $sql='SELECT p.id, p.title, p.chapo, p.description,
         u.username, p.createdAt, c.name as namecate 
         FROM posts as p
         LEFT JOIN user as u ON p.user_id = u.id 
        LEFT JOIN category as c ON p.category_id = c.id 
        WHERE p.id = ?';
        $result= $this->createQuery($sql, [$postId]);
        $post=$result->fetch();
        $result->closeCursor();
        return $this->buildObject($post);
    }

    public function addPost($post)
    {
        //Permet de récupérer les variables $title, $description et $author
        $sql = 'INSERT INTO posts (title, description, chapo, 
         user_id, category_id, createdAt) VALUES (?, ?, ?, ?, ?, NOW())';
        $this->createQuery($sql, [
            $post->get('title'), $post->get('description'), $post->get('chapo'),
            $post->get('userId'), $post->get('categoryId')]);
    }

    public function editPost($post, $postId)
    {
        //Permet de mettre à jour l'article
        $sql = 'UPDATE posts SET title=:title, chapo=:chapo, description=:description, 
        user_id=:user_id, category_id=:category_id  WHERE id=:postId';
        $this->createQuery($sql, [
            'title' => $post->get('title'),
            'chapo' => $post->get('chapo'),
            'description' => $post->get('description'),
            'user_id' => $post->get('userId'),
            'category_id' => $post->get('categoryId'),
            'postId' =>$postId
        ]);
    }

    public function deletePost($postId)
    {
        //Permet de supprimer un article et ses commentaires associés
        $sql = 'DELETE FROM comment WHERE post_id = ?';
        $this->createQuery($sql, [$postId]);
        $sql = 'DELETE FROM posts WHERE id = ?';
        $this->createQuery($sql, [$postId]);
    }
}
