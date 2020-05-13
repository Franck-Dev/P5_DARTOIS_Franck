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
        $user->setCreatedAt($row['CreatedAt']);
        $user->setProfil($row['Profil']);
        $user->setStatut($row['Statut']);
        return $user;
    }

    public function checkUser($user)
    {
        $email=$user->get('username');
        $sql = 'SELECT id, username, email, password,
         CreatedAt, Profil, Statut, last_date_connect FROM user WHERE username = ?';
        $result= $this->createQuery($sql, [$email]);
        $user=$result->fetch();
        $result->closeCursor();
        return $this->buildObject($user);
    }

    public function register($user)
    {
        $sql = 'INSERT INTO user (username, email, password,
         createdAt, Profil, Statut) VALUES (?, ?, ?, NOW(), ?, ?)';
        $this->createQuery($sql, [
            $user->get('username'), $user->get('email'),
             password_hash($user->get('password'), PASSWORD_BCRYPT),
             'USER', 'NOT']);
    }

    public function getUsers()
    {
        $sql = 'SELECT * FROM user';
        $result= $this->createQuery($sql);
        foreach ($result as $row) {
            $userId=$row['id'];
            $user[$userId]=$this->buildObject($row);
        }
        $result->closeCursor();
        return $user;
    }
}
