<?php
use JeffVandenberg\Connection\ConnectionInformation;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 5/18/15
 * Time: 11:55 AM
 */

namespace JeffVandenberg\Message;


use JeffVandenberg\Chat;
use JeffVandenberg\Connection\ConnectionInformation;

class MessageManager
{
    /**
     * @var Chat
     */
    private $chat;

    /**
     * MessageManager constructor.
     * @param Chat $chat
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function sendFullUserList(array $connections)
    {
        $usersData = array(
            'type' => 'userlist',
            'data' => $this->chat->getUsers()
        );

        foreach($connections as $connection) {
            /* @var ConnectionInformation $connection */
            $connection->getConnection()->send(json_encode($usersData));
        }

    }

    public function sendUserListUpdate($action, ConnectionInformation $connInfo)
    {
        $data = array(
            'type' => 'userlist-update',
            'data' => array(
                'action' => $action,
                'username' => $connInfo->getUsername()
            )
        );

        foreach($this->chat->getConnectionManager()->getConnections() as $connection) {
            if ($connection->getConnection()->resourceId != $connInfo->getConnection()->resourceId) {
                $connection->getConnection()->send(json_encode($data));
            }
        }
    }

    public function sendMessage(ConnectionInformation $connInfo, $message)
    {
        $message = trim($message);

    }
}