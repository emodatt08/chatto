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
        $msg = json_decode($msg_send);
        switch($msg->type){
            case "message":
                foreach($this->clients as $client){
                    if($client !== $from){
                        $client->send($msg->text);
                    }
                }
                Message::create(['text' => $msg->text, 'sender' => $msg->sender]);
            break;

            default:
            break;
        }

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