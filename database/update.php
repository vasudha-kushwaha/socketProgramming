<?php
$conn = mysqli_connect("localhost", "root", "", "websocket");

$input = filter_input_array(INPUT_POST);

if ($input['action'] == 'edit') {
    $update_field='';
    if(isset($input['chat'])) {
        $update_field.= "chat='".$input['chat']."'";
    } 
    if(isset($input['status'])) {
        $update_field.= ", status=".$input['status'];
    } 
    if($update_field && $input['id']) {
        $sql_query = "UPDATE chat SET $update_field WHERE id=" . $input['id'] ;
        mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
    }
}
else if ($input['action'] === 'delete') {
    $sql_query = "UPDATE chat SET deleted=1 WHERE id=" . $input['id'];
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
    
    echo "<pre>";
    print_r($sql_query);
} 
else if ($input['action'] === 'restore') {
    $sql_query = "UPDATE chat SET deleted=0 WHERE id=" . $input['id'];
    mysqli_query($conn, $sql_query) or die("database error:". mysqli_error($conn));
}
?>
