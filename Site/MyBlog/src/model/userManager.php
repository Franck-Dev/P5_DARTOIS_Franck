<?php

namespace App\src\model;

use App\src\entity\User;
use App\src\manager\Manager;

class UserManager extends Manager
{

    private function buildObject($row)
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setusername($row['username']);
        $user->setemail($row['email']);
        $user->setpassword($row['password']);
        $user->setcreateTime($row['createTime']);
        $user->setprofilsId($row['profilid']);
        $user->setstatutId($row['statutid']);
        return $user;
    }

    public function checkUser($userId)
    {
        $sql = 'SELECT id, username, email, password, create_time, Profils_id,Statut_id,last_date_connect FROM user WHERE user_id = ?';
        $result= $this->createQuery($sql, [$userId]);
        $user=$result->fetch();
        $result->closeCursor();
        return $this->buildObject($user);
    }
}
