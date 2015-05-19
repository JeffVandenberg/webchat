<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/18/2015
 * Time: 8:59 PM
 */

namespace JeffVandenberg\Room;


use JeffVandenberg\Chat;
use JeffVandenberg\Connection\ConnectionInformation;

class Room
{
    /**
     * @var
     */
    private $name;
    /**
     * @var ConnectionInformation[]
     */
    private $connections;
    /**
     * @var Chat
     */
    private $chat;
    /**
     * @var
     */
    private $id;

    /**
     * @param $name
     * @param $id
     * @param Chat $chat
     */
    function __construct($name, $id, Chat $chat)
    {
        $this->chat        = $chat;
        $this->id          = $id;
        $this->name        = $name;
        $this->connections = array();
    }

    /**
     * @param ConnectionInformation $connInfo
     */
    public function addConnection(ConnectionInformation $connInfo)
    {
        $this->connections[$connInfo->getConnection()->resourceId] = $connInfo;
    }

    /**
     * @param ConnectionInformation $connInfo
     */
    public function removeConnection(ConnectionInformation $connInfo)
    {
        if (isset($this->connections[$connInfo->getConnection()->resourceId])) {
            unset($this->connections[$connInfo->getConnection()->resourceId]);
        }
    }

    /**
     * @param ConnectionInformation $fromConnInfo
     * @param $message
     */
    public function sendMessage(ConnectionInformation $fromConnInfo = null, $message = '')
    {
        $message = trim($message);

        foreach ($this->connections as $toConnInfo) {
            $this->chat->getMessageManager()->sendPublicMessage(
                $fromConnInfo, $toConnInfo, $message);
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'   => $this->id,
            'name' => $this->name
        );
    }

    /**
     * @return \JeffVandenberg\Connection\ConnectionInformation[]
     */
    public function getConnections()
    {
        return $this->connections;
    }

    public function sendUserListUpdate($action, ConnectionInformation $connInfo)
    {
        foreach ($this->connections as $toConnInfo) {
            if($toConnInfo->getConnection()->resourceId != $connInfo->getConnection()->resourceId) {
                $this->chat->getMessageManager()->sendUserListUpdate($action, $connInfo, $toConnInfo);
            }
        }
    }
}