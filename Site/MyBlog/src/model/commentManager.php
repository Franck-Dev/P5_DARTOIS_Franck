<?php

namespace App\src\model;

use App\src\entity\Comment;
use App\src\manager\Manager;

class CommentManager extends Manager
{

    private function buildObject($row)
    {
        $comment = new Comment();
        $comment->setId($row['id']);
        $comment->setPseudo($row['pseudo']);
        $comment->setDescription($row['description']);
        $comment->setCreatedAt($row['createdAt']);
        return $comment;
    }

    public function getComments($postId)
    {
        $sql = 'SELECT id, pseudo, description, createdAt FROM comment WHERE post_id = ? ORDER BY createdAt DESC';
        $result=$this->createQuery($sql, [$postId]);
        $comments=[];
        foreach ($result as $row) {
            $commentId=$row['id'];
            $comments[$commentId]=$this->buildObject($row);
        }
        $result->closeCursor();
        return $comments;
    }

    public function addComment($comment)
    {
        $sql = 'INSERT INTO comment (post_id, description, pseudo, createdAt) VALUES (?, ?, ?, NOW())';
        $this->createQuery($sql,[$comment->get('postId'), $comment->get('description'), $comment->get('pseudo')]);
    }
}
