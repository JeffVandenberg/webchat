<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 5/14/15
 * Time: 2:26 PM
 */

namespace JeffVandenberg;


use Guzzle\Http\QueryString;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
    /**
     * @var \SplObjectStorage
     */
    protected $clients;

    protected $users;

    /**
     * Chat constructor.
     */
    public function __construct()
    {
        $this->clients = array();
        $this->users = array();
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $query = $conn->WebSocket->request->getQuery();
        /* @var QueryString $query */
        $action = $query->get('action');
        $id = $query->get('id');
        $username = $query->get('username');

        $object = new \stdClass();
        $object->connection = $conn;
        $object->action = $action;
        $object->id = $id;
        $object->username = $username;

        $this->users[] = $username;
        sort($this->users);

        $this->clients[$conn->resourceId] = $object;
        echo "New connection! ({$conn->resourceId})\n";

        $usersData = array(
            'type' => 'userlist',
            'data' => $this->users
        );

        $conn->send(json_encode($usersData));

        $this->sendUserListUpdate($conn, 'add', $username);
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
}