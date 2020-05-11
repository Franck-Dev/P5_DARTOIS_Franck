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
        $sql = 'INSERT INTO post (title, description, author, createdAt) VALUES (?, ?, ?, NOW())';
        $this->createQuery($sql, [$post->get('title'), $post->get('description'), $post->get('author')]);
    }

    public function editPost($post,$postId)
    {
        //Permet de mettre à jour l'article
        $sql = 'UPDATE post SET title=:title, description=:description, author=:author WHERE id=:postId';
        $this->createQuery($sql, [
            'title' => $post->get('title'),
            'description' => $post->get('description'),
            'author' => $post->get('author'),
            'postId' =>$postId
        ]);
    }

    public function deletePost($postId)
    {
        //Permet de supprimer un article et ses commentaires associés
        $sql = 'DELETE FROM comment WHERE post_id = ?';
        $this->createQuery($sql, [$postId]);
        $sql = 'DELETE FROM post WHERE id = ?';
        $this->createQuery($sql, [$postId]);
    }
}
