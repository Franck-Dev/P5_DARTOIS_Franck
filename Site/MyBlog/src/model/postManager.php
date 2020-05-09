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
        $post->setDescription($row['description']);
        $post->setAuthor($row['author']);
        $post->setCreatedAt($row['createdAt']);
        return $post;
    }

    public function getPosts()
    {
        $sql='SELECT id, title, description, author, createdAt FROM post ORDER BY id DESC';
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
        $sql='SELECT id, title, description, author, createdAt FROM post WHERE id = ?';
        $result= $this->createQuery($sql, [$postId]);
        $post=$result->fetch();
        $result->closeCursor();
        return $this->buildObject($post);
    }

    public function addPost($post)
    {
        //Permet de récupérer les variables $title, $description et $author
        extract($post);
        $sql = 'INSERT INTO post (title, description, author, createdAt) VALUES (?, ?, ?, NOW())';
        $this->createQuery($sql, [$title, $description, $author]);
    }
}
