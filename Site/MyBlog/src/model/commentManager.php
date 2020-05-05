<?php

namespace App\src\model;

use App\src\manager\Manager;

class commentManager extends Manager{

    public function getCommentsFromArticle($postId)
    {
        $sql = 'SELECT id, pseudo, content, createdAt FROM comment WHERE post_id = ? ORDER BY createdAt DESC';
        return $this->createQuery($sql, [$postId]);
    }

}