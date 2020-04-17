<?php

namespace App\Http\Controllers;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Illuminate\Http\Request;

class WebSocketController implements MessageComponentInterface
{
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "server is now running";
    }
    
    public function onOpen(ConnectionInterface $connection)
    {
        // TODO: Implement onOpen() method.
        $this->clients->attach($connection);
        echo "New connection! ({$connection->resourceId})\n";
    }
    
    public function onClose(ConnectionInterface $connection)
    {
        // TODO: Implement onClose() method.
        $this->clients->detach($connection);
        echo "Connection {$connection->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        // TODO: Implement onError() method.
        echo "An error has occurred: {$e->getMessage()}\n";
        
        //$connection->close();
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        // TODO: Implement onMessage() method.
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($connection !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }
}
