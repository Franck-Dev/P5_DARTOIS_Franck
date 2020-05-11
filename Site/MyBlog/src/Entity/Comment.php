<?php
namespace App\src\entity;

class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $createdAtComments;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getcreatedAtComments()
    {
        return $this->createdAtComments;
    }

    /**
     * @param \DateTime $createdAtComments
     */
    public function setcreatedAtComments($createdAtComments)
    {
        $this->createdAtComments = $createdAtComments;
    }
}
