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

require_once dirname(__DIR__) . '/vendor/autoload.php';


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080);

$server->run();