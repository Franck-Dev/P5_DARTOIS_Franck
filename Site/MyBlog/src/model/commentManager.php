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
        $comment->setStatut($row['statut']);
        $comment->setPostId($row['posts_id']);
        return $comment;
    }

    public function getComments($postId)
    {
        $sql = 'SELECT c.id, u.username, c.description,
         c.createdAtComments, c.statut, c.posts_id FROM comments as c 
         LEFT JOIN user as u ON c.user_id = u.id 
         WHERE c.posts_id = ? AND c.statut = ?
         ORDER BY c.createdAtComments DESC';
        $result=$this->createQuery($sql, [$postId,'1']);
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
         user_id, createdAtComments, statut) VALUES (?, ?, ?, NOW(), 0)';
        $this->createQuery($sql, [
            $comment->query->get('postId'), $comment->request->get('description'), $comment->request->get('pseudo')]);
    }

    public function deleteComment($commentId)
    {
        $sql = 'DELETE FROM comments WHERE id=?';
        $this->createQuery($sql, [$commentId]);
    }

    public function getCommentsValidate()
    {
        $sql='SELECT DISTINCT statut FROM comments';
        $result=$this->createQuery($sql);
        $commentsUser=[];
        foreach ($result as $usr) {
            $sql1 = 'SELECT c.id, u.username, c.description,
            c.createdAtComments, c.statut, c.posts_id FROM comments as c 
            LEFT JOIN user as u ON c.user_id = u.id 
            WHERE c.statut = ? ';
            $result1=$this->createQuery($sql1, [$usr['statut']]);
            $comments=[];
            foreach ($result1 as $data) {
                $commentId=$data['id'];
                $comments[$commentId]=$this->buildObject($data);
            }
            $result1->closeCursor();
            $commentsUser[$usr['statut']]=$comments;
        }
        $result->closeCursor();
        return $commentsUser;
    }
}
