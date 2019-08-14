<?php
require('vendor/autoload.php');
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Controllers\ChatController;

/**
 * Serve on port 8080
 */
$server = IoServer::factory(
    (new HttpServer(
        new WsServer(
            (new ChatController())
        )))
    , 8080);
$server->run();

 