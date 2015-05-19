<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/16/2015
 * Time: 9:07 PM
 */

namespace JeffVandenberg\Room;


use JeffVandenberg\Chat;
use JeffVandenberg\Connection\ConnectionInformation;
use JeffVandenberg\Utility\Config;
use Ratchet\Wamp\Exception;

/**
 * Class RoomManager
 * @package JeffVandenberg\Room
 */
class RoomManager
{
    /**
     * @var Chat
     */
    private $chat;
    /**
     * @var Room[]
     */
    private $rooms;

    /**
     * @param Chat $chat
     */
    function __construct(Chat $chat)
    {
        $this->chat = $chat;
        $this->rooms = $this->loadRoomList();
    }

    /**
     * @return Room[]
     */
    public function getRoomList()
    {
        return $this->rooms;
    }

    /**
     * @return Room[]
     */
    private function loadRoomList()
    {
        $list = array();
        $list[1] = $this->makeRoom('Welcome Room', 1);
        $list[2] = $this->makeRoom('Out of Character', 2);
        return $list;
    }

    /**
     * @param $name
     * @param $id
     * @return Room
     */
    private function makeRoom($name, $id)
    {
        return new Room($name, $id, $this->chat);
    }

    /**
     * @param $connInfo
     */
    public function addConnectionToRoom(ConnectionInformation $connInfo)
    {
        $this->rooms[$connInfo->getRoomId()]->addConnection($connInfo);
    }

    /**
     * @param $roomId
     * @return Room
     * @throws Exception
     */
    public function getRoom($roomId)
    {
        if (isset($this->rooms[$roomId])) {
            return $this->rooms[$roomId];
        }
        else {
            throw new Exception('Unable to find room: ' . $roomId);
        }
    }

    public function sendMessageToRoom(ConnectionInformation $connInfo, $message)
    {
        $room = $this->getRoom($connInfo->getRoomId());
        if (!empty($room)) {
            $room->sendMessage($connInfo, $message);
        }
    }

    public function switchRoomForConnection(ConnectionInformation $connInfo, $roomId)
    {
        $oldRoom = $this->getRoom($connInfo->getRoomId());
        $newRoom = $this->getRoom($roomId);

        $oldRoom->removeConnection($connInfo);
        $oldRoom->sendMessage($connInfo, 'User has left the room');
        $oldRoom->sendUserListUpdate('remove', $connInfo);

        $connInfo->setRoomId($roomId);
        $newRoom->addConnection($connInfo);
        $this->chat->getMessageManager()->sendUserList($connInfo);
        $newRoom->sendMessage($connInfo, 'User has joined the room');
    }

    public function sendUserListUpdate($action, ConnectionInformation $connInfo)
    {
        $room = $this->getRoom($connInfo->getRoomId());
        $room->sendUserListUpdate($action, $connInfo);
    }

}