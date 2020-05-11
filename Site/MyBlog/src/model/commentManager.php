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
        $comment->setUser($row['username']);
        $comment->setDescription($row['description']);
        $comment->setCreatedAtComments($row['createdAtComments']);
        return $comment;
    }

    public function getComments($postId)
    {
        $sql = 'SELECT c.id, u.username, c.description,
         c.createdAtComments FROM comments as c 
         LEFT JOIN user as u ON c.user_id = u.id 
         WHERE c.posts_id = ? 
         ORDER BY c.createdAtComments DESC';
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
        //var_dump($comment);
        $sql = 'INSERT INTO comments (posts_id, description,
         user_id, createdAtComments) VALUES (?, ?, ?, NOW())';
        $this->createQuery($sql, [
            $comment->query->get('postId'), $comment->request->get('description'), $comment->request->get('pseudo')]);
    }

    public function deleteComment($commentId)
    {
        $sql = 'DELETE FROM comments WHERE id=?';
        $this->createQuery($sql, [$commentId]);
    }
}
