<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 5/14/15
 * Time: 2:26 PM
 */

namespace JeffVandenberg;


use Guzzle\Http\QueryString;
use JeffVandenberg\Connection\ConnectionManager;
use JeffVandenberg\Message\MessageManager;
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
        $this->clients = array();
        $this->users = array();
        $this->ConnectionManager = new ConnectionManager($this);
        $this->MessageManager = new MessageManager($this);
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $connInfo = $this->ConnectionManager->loadNewConnection($conn);

        $this->users[] = $connInfo->getUsername();
        sort($this->users);

        echo "New connection! ({$conn->resourceId})\n";

        $this->MessageManager->sendFullUserList(array($connInfo));
        $this->MessageManager->sendUserListUpdate('add', $connInfo);
        $this->MessageManager->sendMessage($connInfo, "New User Connected!");
        $this->onMessage($conn, "New User Connected!");
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $data = clone $this->clients[$conn->resourceId];
        $key = array_search($data->username, $this->users);
        if($key !== false) {
            unset($this->users[$key]);
        }
        unset($this->clients[$conn->resourceId]);

        echo "Connection {$conn->resourceId} has disconnected\n";
        $this->sendUserListUpdate($conn, 'remove', $data->username);
        $this->onMessage($conn, $data->username . " Disconnected!");
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
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = $this->clients[$from->resourceId];

        $username = '';
        if($data) {
            $username = $data->username;
        }
        $data = array(
            'type' => 'message',
            'username' => $username,
            'timestamp' => date('Y-m-d H:i:s'),
            'message' => $msg
        );

        foreach ($this->clients as $client) {
            if (true) { //$from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->connection->send(json_encode($data));
            }
        }
    }

    /**
     * @param $conn
     * @param $action
     * @param $username
     */
    private function sendUserListUpdate($conn, $action, $username)
    {
        $data = array(
            'type' => 'userlist-update',
            'data' => array(
                'action' => $action,
                'username' => $username
            )
        );

        foreach($this->clients as $client) {
            if($client->connection->resourceId != $conn->resourceId) {
                $client->connection->send(json_encode($data));
            }
        }
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
}