<?php
namespace App\src\entity;

class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \DateTime
     */
    private $createTime;

    /**
     * @var int
     */
    private $profilsId;

    /**
     * @var int
     */
    private $statutId;

    /**
     * @var \DateTime
     */
    private $lastDAteConnect;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return \DateTime
     */
    public function getcreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param \DateTime $createTime
     */
    public function setcreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return int
     */
    public function getprofilsId()
    {
        return $this->profilsId;
    }

    /**
     * @param int $profilsId
     */
    public function setprofilsId($profilsId)
    {
        $this->profilsId = $profilsId;
    }

    /**
     * @return int
     */
    public function getstatutId()
    {
        return $this->statutId;
    }

    /**
     * @param int $statutId
     */
    public function setstatutId($statutId)
    {
        $this->statutId = $statutId;
    }

    /**
     * @return \DateTime
     */
    public function getlastDateConnect()
    {
        return $this->lastDateConnect;
    }

    /**
     * @param \DateTime $lastDateConnect
     */
    public function setlastDateConnect($lastDateConnect)
    {
        $this->lastDateConnect = $lastDateConnect;
    }
}
