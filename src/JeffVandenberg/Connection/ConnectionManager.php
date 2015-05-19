<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 5/18/15
 * Time: 11:45 AM
 */

namespace JeffVandenberg\Connection;

use Guzzle\Http\QueryString;
use JeffVandenberg\Chat;
use Ratchet\ConnectionInterface;

/**
 * Class ConnectionManager
 * @package JeffVandenberg\Connection
 */
class ConnectionManager
{
    /**
     * @var ConnectionInformation[]
     */
    private $clients = array();
    /**
     * @var Chat
     */
    private $chat;

    /**
     *
     */
    function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * @param ConnectionInterface $conn
     * @return ConnectionInformation
     */
    public function loadNewConnection(ConnectionInterface $conn)
    {
        $query = $conn->WebSocket->request->getQuery();
        /* @var QueryString $query */

        $action = $query->get('action');
        $id = $query->get('id');
        $username = $query->get('username');

        $object = new ConnectionInformation();
        $object->setConnection($conn);
        $object->setAction($action);
        $object->setId($id);
        $object->setUsername($username);

        $this->clients[$object->getConnection()->resourceId] = $object;
        return $object;
    }

    /**
     * @return ConnectionInformation[]
     */
    public function getConnections()
    {
        return $this->clients;
    }


}