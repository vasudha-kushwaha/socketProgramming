<?php

define('HOST', 'localhost');
define('User', 'root');
define('PASS', '');
define('DB', 'websocket');

function dbQuery($query)
{
    $dbConnection = null;
    if($dbConnection == null){
        $dbConnection = mysqli_connect(HOST, User, PASS, DB);   
    }
    $result = mysqli_query($dbConnection, $query);
    // if(mysqli_query($dbConnection, $query)) {
    //     return true;
    // } else {
    //     return false;
    // }
    return $result;
}

function insertData($sql){
    $dbConnection = null;
    if($dbConnection == null){
        $dbConnection = mysqli_connect(HOST, User, PASS, DB);   
    }
    $result = mysqli_query($dbConnection, $sql);
    $last_id = mysqli_insert_id($dbConnection);
    return $last_id;
}

function getAllChats() {
    $sql = "SELECT c.id, c.chat, c.status FROM chat c WHERE c.deleted = 0 ORDER BY c.id DESC";
    $result = dbQuery($sql);
    return $result;
}

function getSingleRecord($id){
    $sql = "SELECT c.* FROM chat c WHERE c.id = {$id}";
    $result = dbQuery($sql);
    return $result;
}

function updateData($sql){
    $result = dbQuery($sql);
    return $result;
}