<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 5/14/15
 * Time: 2:32 PM
 */

use JeffVandenberg\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;

require_once dirname(__DIR__) . '/vendor/autoload.php';


$loop = Factory::create();

$chat = new Chat();

$server = new HttpServer(
    new WsServer(
        $chat
    )
);

$chatServer = IoServer::factory(
    $server,
    8080);

//$loop->addPeriodicTimer(3, function() use ($chat) {
//    $chat->performPulse();
//});
//$loop->run();

$chatServer->run();

