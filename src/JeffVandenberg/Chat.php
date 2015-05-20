<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 5/14/15
 * Time: 2:26 PM
 */

namespace JeffVandenberg;


use JeffVandenberg\Connection\ConnectionManager;
use JeffVandenberg\Message\MessageManager;
use JeffVandenberg\Room\RoomManager;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
    /**
     * @var array
     */
    protected $users;

    /**
     * @var MessageManager
     */
    private $MessageManager;
    /**
     * @var ConnectionManager
     */
    private $ConnectionManager;

    /**
     * Chat constructor.
     */
    public function __construct()
    {
        $this->users = array();
        $this->ConnectionManager = new ConnectionManager($this);
        $this->MessageManager = new MessageManager($this);
        $this->RoomManager = new RoomManager($this);
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        echo "New connection! ({$conn->resourceId})\n";

        $connInfo = $this->ConnectionManager->loadNewConnection($conn);

        $this->users[] = $connInfo->getUsername();
        sort($this->users);

        $this->RoomManager->addConnectionToRoom($connInfo);
        $this->MessageManager->sendUserList($connInfo);
        $this->MessageManager->sendRoomList($connInfo, $this->RoomManager->getRoomList());
        $this->RoomManager->sendUserListUpdate('add', $connInfo);
        $this->RoomManager->sendMessageToRoom($connInfo, "New User Connected!");
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        echo "Connection {$conn->resourceId} has disconnected\n";
        // The connection is closed, remove it, as we can no longer send it messages
        $connInfo = $this->ConnectionManager->getConnectionInfoForConnection($conn);

        $key = array_search($connInfo->getUsername(), $this->users);
        if ($key !== false) {
            unset($this->users[$key]);
        }

        $this->ConnectionManager->unsetConnection($connInfo);
        $room = $this->RoomManager->getRoom($connInfo->getRoomId());
        $room->removeConnection($connInfo);
        $room->sendUserListUpdate('remove', $connInfo);
        $room->sendMessage($connInfo, $connInfo->getUsername() . ' Disconnected!');
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        $connInfo = $this->ConnectionManager->getConnectionInfoForConnection($from);

        $command = json_decode($msg, true);
        switch (strtolower($command['action'])) {
            case 'room-message':
                $this->RoomManager->sendMessageToRoom($connInfo, $command['data']['message']);
                break;
            case 'change-room':
                $this->RoomManager->switchRoomForConnection($connInfo, $command['data']['roomId']);
                break;
        }
    }

    /**
     * @return MessageManager
     */
    public function getMessageManager()
    {
        return $this->MessageManager;
    }

    /**
     * @return RoomManager
     */
    public function getRoomManager()
    {
        return $this->RoomManager;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return ConnectionManager
     */
    public function getConnectionManager()
    {
        return $this->ConnectionManager;
    }

    public function performPulse()
    {
        foreach ($this->RoomManager->getRooms() as $room) {
            $room->sendMessage(null, 'This is a pulse');
        }
    }
}