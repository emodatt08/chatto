<?php

namespace App\Controllers;

use App\Models\Message;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatController implements MessageComponentInterface{

    protected $clients;

    public function __construct(){
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn){
        $this->clients->attach($conn);
        echo "New Connection ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg_send){
        foreach($this->clients as $client){
                if($client !== $from){
                    $client->send($msg_send);
                }
        }
          Message::create(['text' => $msg_send]);
    }

     public function onClose(ConnectionInterface $conn){
            $this->clients->detach($conn);
            echo "Connection with id ({$conn->resourceId}) has disconnected \n";

    }

     public function onError(ConnectionInterface $conn, \Exception $e){
            echo "A silly error has occured ({$e->getMessage()})";
            $conn->close();
    }

    public function getMessages(){
        return Message::all()->toJson();
    }

}