<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require "./database/chatrooms.php";
require "./database/functions.php";

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }
    
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
        //get all data
        // $chatrooms = getAllChats();
        // if(mysqli_num_rows($chatrooms)){
        //     while ($row = mysqli_fetch_assoc($chatrooms)) {
        //         $val = json_encode($row);
        //         echo $val;
        //         echo "<br>";
        //         foreach ($this->clients as $client) {
        //                 $client->send($val);
        //         }
        //     }
        // }
        //get all data
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        $data = json_decode($msg, true);
        print_r($data);
        $chatId = $data['id'];
        
        if($chatId > 0){
            $delId = ($data['del']) ? ($data['id']) : 0;
            if($delId > 0){
                echo "Id to be deleted is : " . $delId;
                $objChatroom = new \chatrooms;
                $deletedId = $objChatroom->deleteRecord($delId);
                $del = array("id" => $deletedId, "delete" => "1");
                //print_r($del);
                $jsonData = json_encode($del);
                // echo $jsonData;
                foreach ($this->clients as $client) {
                    if ($from !== $client)
                        $client->send($jsonData);
                    else
                        $client->send($jsonData);
                }
            }
            else{
                $objChatroom = new \chatrooms;
                $objChatroom->setMsg($chatId, $data['msg'], $data['status']);
                $updatedId = $objChatroom->updateRecord();
                // echo $updatedId;
                // $sql = "UPDATE chat SET chat = '{$data['msg']}', status = {$data['status']} WHERE id = {$chatId};
                // echo $sql;
                // $result = updateData($sql);
                if($updatedId){
                    $chatrooms = getSingleRecord($chatId);
                    $chat = mysqli_fetch_assoc($chatrooms);
                    $edit = array("edit" => "1");
                    $data = array_merge($chat, $edit);
                    // print_r($data);
                    $jsonData = json_encode($data);
                    // echo $jsonData;
                    foreach ($this->clients as $client) {
                        if ($from !== $client)
                            $client->send($jsonData);
                        else
                            $client->send($jsonData);
                    }
                }
            }
        }
        else{
            $sql = "INSERT INTO chat(chat, status) VALUES ('{$data['msg']}', 0)";
            $last_id = insertData($sql);
            if($last_id > 0) {
                $chatrooms = getSingleRecord($last_id);
                $chat = mysqli_fetch_assoc($chatrooms);
                $edit = array("edit" => "0");
                $data = array_merge($chat, $edit);
                // print_r($data);
                $jsonData = json_encode($data);
                // echo $jsonData;
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        // The sender is not the receiver, send to each client connected
                        $client->send($jsonData);
                    }
                    else
                        $client->send($jsonData);
                }
            }
        } 
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}