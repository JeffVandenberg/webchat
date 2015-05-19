<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 5/18/15
 * Time: 11:50 AM
 */

namespace JeffVandenberg\Connection;


/**
 * Class ConnectionInformation
 * @package JeffVandenberg\Connection
 */
class ConnectionInformation
{
    /**
     * @var
     */
    private $connection;
    /**
     * @var
     */
    private $action;
    /**
     * @var
     */
    private $username;
    /**
     * @var
     */
    private $id;

    /**
     * @return mixed
     */
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * @var
     */
    private $roomId;

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $roomId
     */
    public function setRoomId($roomId)
    {
        $this->roomId = $roomId;
    }
}