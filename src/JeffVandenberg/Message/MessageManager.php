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
use JeffVandenberg\Room\Room;

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

    public function sendUserListUpdate($action, ConnectionInformation $fromConnInfo, ConnectionInformation $toConnInfo)
    {
        $data = array(
            'type' => 'userlist-update',
            'data' => array(
                'action' => $action,
                'username' => $fromConnInfo->getUsername(),
                'id' => $fromConnInfo->getConnection()->resourceId
            )
        );

        $toConnInfo->getConnection()->send(json_encode($data));
    }

    public function sendMessage(ConnectionInformation $connInfo, $message)
    {
        $message = trim($message);

        $numRecv = count($this->chat->getConnectionManager()->getConnections());

        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $connInfo->getConnection()->resourceId, $message, $numRecv, $numRecv == 1 ? '' : 's');

        $username = '';
        if($connInfo) {
            $username = $connInfo->getUsername();
        }
        $data = array(
            'type' => 'message',
            'username' => $username,
            'timestamp' => date('Y-m-d H:i:s'),
            'message' => $message
        );

        foreach ($this->chat->getConnectionManager()->getConnections() as $client) {
            // The sender is not the receiver, send to each client connected
            $client->getConnection()->send(json_encode($data));
        }
    }

    public function sendPublicMessage(ConnectionInformation $fromConnInfo = null, ConnectionInformation $toConnInfo, $message)
    {
        $message = trim($message);

        $username = '';
        if($fromConnInfo) {
            $username = $fromConnInfo->getUsername();
        }
        $data = array(
            'type' => 'message',
            'username' => $username,
            'timestamp' => date('Y-m-d H:i:s'),
            'message' => $message
        );

        $toConnInfo->getConnection()->send(json_encode($data));
    }

    public function sendRoomList(ConnectionInformation $connInfo, array $roomList)
    {
        $data = array(
            'type' => 'roomlist',
            'data' => array()
        );

        foreach($roomList as $room) {
            /* @var Room $room */
            $data['data'][] = $room->toArray();
        }

        $connInfo->getConnection()->send(json_encode($data));
    }

    public function sendUserList(ConnectionInformation $connInfo)
    {
        $usersData = array(
            'type' => 'userlist',
            'data' => array()
        );

        $room = $this->chat->getRoomManager()->getRoom($connInfo->getRoomId());

        foreach($room->getConnections() as $connection) {
            $usersData['data'][] = array(
                'id' => $connection->getConnection()->resourceId,
                'username' => $connection->getUsername()
            );
        }
        $connInfo->getConnection()->send(json_encode($usersData));
    }
}