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
        $post->setDerniereMaJ($row['derniereMaJ']);
        return $post;
    }

    public function getPosts($categoryId = null)
    {
        if ($categoryId) {
            $sql='SELECT p.id, p.title, p.description, 
            p.chapo, u.username, p.createdAt, c.name as namecate, 
            p.derniereMaJ 
            FROM posts as p 
            LEFT JOIN user as u ON p.user_id = u.id 
            LEFT JOIN category as c ON p.category_id = c.id
            WHERE p.category_id = ?
            ORDER BY id DESC';
            $result= $this->createQuery($sql, [$categoryId]);
        } else {
            $sql='SELECT p.id, p.title, p.description, 
            p.chapo, u.username, p.createdAt, c.name as namecate, 
            p.derniereMaJ
            FROM posts as p 
            LEFT JOIN user as u ON p.user_id = u.id 
            LEFT JOIN category as c ON p.category_id = c.id
            ORDER BY id DESC';
            $result= $this->createQuery($sql);
        }
        $post=[];
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
         u.username, p.createdAt, c.name as namecate, 
         p.derniereMaJ
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
        user_id=:user_id, category_id=:category_id, DerniereMaJ=:modif_date WHERE id=:postId';
        $this->createQuery($sql, [
            'title' => $post->get('title'),
            'chapo' => $post->get('chapo'),
            'description' => $post->get('description'),
            'user_id' => $post->get('userId'),
            'category_id' => $post->get('categoryId'),
            'postId' =>$postId,
            'modif_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function deletePost($postId)
    {
        //Delete post and comments associate
        $sql = 'DELETE FROM comments WHERE posts_id = ?';
        $this->createQuery($sql, [$postId]);
        $sql = 'DELETE FROM posts WHERE id = ?';
        $this->createQuery($sql, [$postId]);
    }

    public function getpostsCount()
    {
        $sql='SELECT DISTINCT category_id, COUNT(id) AS nb FROM posts GROUP BY category_id';
        $result=$this->createQuery($sql);
        $postNb=[];
        foreach ($result as $row) {
            $postNb[$row['category_id']]=$row['nb'];
        }
        $result->closeCursor();
        return $postNb;
    }
}
