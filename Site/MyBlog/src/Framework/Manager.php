<?php
/**
 * @package Framework
 */
namespace App\src\Framework;

use PDO;
use Exception;

/**
 * This file manage
 *
 * @author Franck D <franck.pyren@gmail.com>
 */
abstract class Manager
{
    const  DB_HOST = '';
    const  DB_USER = '';
    const  DB_PASS = '';
    private $connection;

/**
     * Manage the connection of database entry files and this.
     *
     * The file creating the connection with database by methods:
     *
     *   * getConnect,
     *   * createQuery,
     *   * checkConnect.
     *
     * Parameters helping at the connection to create.
     *
     * @param string      DB_HOST     Server address for administration base
     * @param string      DB_USER     Name of user administration database
     * @param string|null DB_PASS     Password
     * @param string      $connection Connection datas
     *
     */

/**
* Methods use for creating database's access
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
* Use for creating request before executing for database
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
* Check-up if the connection was etablish before a new connection
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
