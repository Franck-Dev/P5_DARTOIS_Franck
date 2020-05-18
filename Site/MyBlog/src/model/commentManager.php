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
        $sql = 'INSERT INTO comments (posts_id, description,
         user_id, createdAtComments, statut) VALUES (?, ?, ?, NOW(), 0)';
        $this->createQuery($sql, [
            $comment->query->get('postId'), $comment->request->get('description'), $comment->request->get('pseudo')]);
    }

    public function editComment($comment, $commentId)
    {
        //Update the comment after modification for author only
        if (!$comment->get('description')) {
            $sql = 'UPDATE comments SET statut=:statut, datemodif=:datemodif  WHERE id=:commentId';
            $this->createQuery($sql, [
            'statut' => $comment->get('Statut'),
            'datemodif' => date("Y-m-d H:i:s"),
            'commentId' =>$commentId]);
        } else {
            $sql = 'UPDATE comments SET description=:description, statut=:statut, 
            datemodif=:datemodif  WHERE id=:commentId';
            $this->createQuery($sql, [
            'description' => $comment->get('description'),
            'statut' => 0,
            'datemodif' => date("Y-m-d H:i:s"),
            'commentId' =>$commentId
        ]);
        }
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
        $commentsUsers=[];
        foreach ($result as $usr) {
            $sql1 = 'SELECT c.id, u.username, c.description,
            c.createdAtComments, c.statut, c.posts_id FROM comments as c 
            LEFT JOIN user as u ON c.user_id = u.id 
            WHERE c.statut = ? ';
            $result1=$this->createQuery($sql1,[$usr['statut']]);
            $comments=[];
            foreach ($result1 as $data ) {
                $commentId=$data['id'];
                $comments[$commentId]=$this->buildObject($data);
            }
            $result1->closeCursor();
            $commentsUsers[$usr['statut']]=$comments;
        }
       $result->closeCursor();
       return $commentsUsers;
    }

    public function getcommentsUser($userId)
    {
        $sql='SELECT DISTINCT id FROM posts';
        $result=$this->createQuery($sql);
        $commentsUser=[];
        foreach ($result as $post) {
            $sql1 = 'SELECT c.id, u.username, c.description,
            c.createdAtComments, c.statut, p.title, c.posts_id FROM comments as c 
            LEFT JOIN posts as p ON c.posts_id = p.id 
            LEFT JOIN user as u ON c.user_id = u.id  
            WHERE c.posts_id = ? AND c.user_id = ? ';
            $result1=$this->createQuery($sql1,[$post['id'],$userId]);
            $comments=[];
            foreach ($result1 as $data ) {
                $commentId=$data['id'];
                $titlePost=$data['title'];
                $comments[$commentId]=$this->buildObject($data);
            }
            $result1->closeCursor();
            $commentsUser[$titlePost]=$comments;
        }
       $result->closeCursor();
       return $commentsUser;
    }

    public function getcommentsCount($postId = null)
    {
        if (!$postId) {
            $sql='SELECT DISTINCT posts_id, COUNT(id) AS nb FROM comments WHERE statut=1 GROUP BY posts_id';
            $result=$this->createQuery($sql);
        } else {
            $sql='SELECT posts_id, COUNT(id) AS nb FROM comments WHERE posts_id=? AND statut=1';
            $result=$this->createQuery($sql, [$postId]);
        }
        $commentsCount=[];
        foreach ($result as $row) {
            if ($postId) {
                if ($row['posts_id'] == $postId) {
                    $commentsCount[$postId]=$row['nb'];
                }
            } else {
                $commentsCount[$row['posts_id']]=$row['nb'];
            }
        }
        $result->closeCursor();
        return $commentsCount;
    }
}
