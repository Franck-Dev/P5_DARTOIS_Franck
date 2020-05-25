<?php
namespace App\src\manager;

use PDO;
use Exception;

abstract class Manager
{
/**
* Server address for administration base
*/
    const  DB_HOST = 'mysql:host=localhost;dbname=MyBlog;charset=utf8';

/**
* Name of user administration database
*/
    const  DB_USER = 'root';

/**
* Password
*/
    const  DB_PASS = 'root';

/**
* Connection datas
*/
    private $connection;


/**
* Méthode qui va nous permettre de gérer l'accès à la base
*/
    public function getConnect()
    {
    //Tentative de connexion à la base de données
        try {
            $this->connection = new PDO(self::DB_HOST, self::DB_USER, self::DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //On renvoie la connexion
            return $this->connection;
        } //On renvoie une erreur si la connexion échoue
        catch (Exception $errorConnection) {
            die($errorConnection->getMessage());
        }
    }

/**
* Permet de créer les requetes
*/
    protected function createQuery($sql, $parameters = null)
    {
        if ($parameters) {
            $result = $this->checkConnect()->prepare($sql);
            $result->execute($parameters);
            return $result;
        }
        $result = $this->checkConnect()->query($sql);
        return $result;
    }

/**
* Vérifie si une connexion existe ou pas
*/
    private function checkConnect()
    {
    //Vérifie si la connexion est nulle et fait appel à getConnection()
        if ($this->connection === null) {
            return $this->getConnect();
        }
        //Si la connexion existe, elle est renvoyée, inutile de refaire une connexion
        return $this->connection;
    }
}
