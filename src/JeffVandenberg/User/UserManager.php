<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/21/2015
 * Time: 4:21 AM
 */

namespace JeffVandenberg\User;


use JeffVandenberg\Chat;
use JeffVandenberg\Connection\ConnectionInformation;
use JeffVandenberg\Utility\Result;

/**
 * Class UserManager
 * @package JeffVandenberg\User
 */
class UserManager
{
    /**
     * @var Chat
     */
    private $chat;
    /**
     * @var array
     */
    private $users;

    /**
     * @param Chat $chat
     */
    function __construct(Chat $chat)
    {
        $this->chat = $chat;
        $this->users = array();
    }

    /**
     * @param ConnectionInformation $connInfo
     * @return Result
     */
    public function mayUserConnect(ConnectionInformation $connInfo)
    {
        $response = new Result(false, 'Unknown Error');

        if(array_search($connInfo->getUsername(), $this->users)) {
            $response->setMessage('Username already in use');
        }
        else {
            $response->setStatus(true);
            $response->setMessage('');
        }

        return $response;
    }

    public function addUser(ConnectionInformation $connInfo)
    {
        $this->users[] = $connInfo->getUsername();
    }
}