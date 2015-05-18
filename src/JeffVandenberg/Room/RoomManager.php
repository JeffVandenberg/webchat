<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/16/2015
 * Time: 9:07 PM
 */

namespace JeffVandenberg\Room;


use JeffVandenberg\Utility\Config;

class RoomManager
{
    function __construct()
    {
    }

    public function mustUpdateRooms()
    {
        return Config::read('must_update_rooms');
    }

    public function getRoomList()
    {
        
    }
}