<?php

namespace App\src\model;

use DateTime;
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
        $user->setlastDateConnect($row['last_date_connect']);
        return $user;
    }

    public function checkUser($user)
    {
        $name=$user->get('username');
        $sql = 'SELECT id, username, email, password,
         CreatedAt, Profil, Statut, last_date_connect FROM user WHERE username = ?';
        $result= $this->createQuery($sql, [$name]);
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

    public function login($user)
    {
        $sql = 'SELECT id, password, username, email, statut, profil, last_date_connect FROM user WHERE username = ?';
        $data = $this->createQuery($sql, [$user->get('username')]);
        $result = $data->fetch();
        $isPasswordValid = password_verify($user->get('password'), $result['password']);
        if ($isPasswordValid === true) 
        {
            $sql='UPDATE user SET last_date_connect=:last_date_connect WHERE id=:userId';
            $this->createQuery($sql, [
                'userId' => $result['id'], 'last_date_connect' => date('Y-m-d H:i:s')
            ]);
        }
        return [
            'result' => $result,
            'isPasswordValid' => $isPasswordValid
        ];
    }

    public function editUser($user, $userId)
    {
        //Update the user after modification for ADMIN only
        if (!$user->get('username')) {
            $sql = 'UPDATE user SET statut=:statut  WHERE id=:userId';
            $this->createQuery($sql, [
            'statut' => $user->get('Statut'),
            'userId' =>$userId]);
        } else {
            $sql = 'UPDATE user SET mail=:mail, password=:password, statut=:statut, 
            WHERE id=:userId';
            $this->createQuery($sql, [
            'mail' => $user->get('mail'),
            'statut' => 0,
            'password' => password_hash($user->get('password'), PASSWORD_BCRYPT),
            'userId' =>$userId
        ]);
        }
    }
}
