<?php
/**
 * @package Model
 */
namespace App\src\model;

use App\src\entity\Comment;
use App\src\Framework\Manager;

/**
 * This file manage SQL requests from comments's database
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
class CommentManager extends Manager
{    
    /**
     * Return object hydrated
     *
     * @param  array $row [Result of database request]
     * @return void
     */
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
    
    /**
     * Return list of comments by post
     *
     * @param  int $postId [Post index]
     * @return void
     */
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
    
    /**
     * Add new comment in database by comment's datas sent
     *
     * @param  array $comment [Comment datas]
     * @param  int $postId
     * @return void
     */
    public function addComment($comment, $postId)
    {
        $sql = 'INSERT INTO comments (posts_id, description,
         user_id, createdAtComments, statut) VALUES (?, ?, ?, NOW(), 0)';
        $this->createQuery($sql, [
            $postId, $comment->request->get('description'), $comment->request->get('pseudo')]);
    }
    
    /**
     * Update the comment by commentId and datas associated
     *
     * @param  array $comment [Comment datas]
     * @param  int $commentId [Comment index]
     * @return void
     */
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
            'commentId' =>$commentId]);
        }
    }
    
    /**
     * Delete the comment by commentId
     *
     * @param  int $commentId [Comment index]
     * @return void
     */
    public function deleteComment($commentId)
    {
        $sql = 'DELETE FROM comments WHERE id=?';
        $this->createQuery($sql, [$commentId]);
    }
    
    /**
     * Return list of comments by statut validated or not
     *
     * @return void
     */
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
            $result1=$this->createQuery($sql1, [$usr['statut']]);
            $comments=[];
            foreach ($result1 as $data) {
                $commentId=$data['id'];
                $comments[$commentId]=$this->buildObject($data);
            }
            $result1->closeCursor();
            $commentsUsers[$usr['statut']]=$comments;
        }
        $result->closeCursor();
        return $commentsUsers;
    }
    
    /**
     * Return comments by users with the posts's filter
     *
     * @param  int $userId [User index]
     * @return void
     */
    public function getcommentsUser($userId)
    {
        //Call-back between posts-id alone
        $sql='SELECT DISTINCT id FROM posts';
        $result=$this->createQuery($sql);
        $commentsUser=[];
        //In chaque posts, list of comments by user and posts-id
        foreach ($result as $post) {
            $sql1 = 'SELECT c.id, u.username, c.description,
            c.createdAtComments, c.statut, p.title, c.posts_id FROM comments as c 
            LEFT JOIN posts as p ON c.posts_id = p.id 
            LEFT JOIN user as u ON c.user_id = u.id  
            WHERE c.posts_id = ? AND c.user_id = ? ';
            $result1=$this->createQuery($sql1, [$post['id'], $userId]);
            $comments=[];
            foreach ($result1 as $data) {
                $commentId=$data['id'];
                $titlePost=$data['title'];
                $comments[$commentId]=$this->buildObject($data);
            }
            $result1->closeCursor();
            if ($comments) {
                $commentsUser[$titlePost]=$comments;
            }
        }
        $result->closeCursor();
        return $commentsUser;
    }
    
    /**
     * Return the numbers of comments by postId
     *
     * @param  int|null $postId [Post index]
     * @return void
     */
    public function getcommentsCount($postId = null)
    {
        if (!$postId) {//If doesn't have got a postId, will find all comments by postId
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
    
    /**
     * Return the numbers of comments by userId
     *
     * @param  int $userId [User index]
     * @return void
     */
    public function getCountComments($userId)
    {
        $sql='SELECT COUNT(id) AS nb FROM comments WHERE user_id=?';
        $result=$this->createQuery($sql, [$userId]);
        foreach ($result as $row) {
            $countComments=$row['nb'];
        }
        $result->closeCursor();
        return $countComments;
    }
}
