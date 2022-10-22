
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   
    <!-- <script src="pushlib/push.js"></script> -->
    <script src="pushlib/push.min.js"></script>
    <script src="pushlib/serviceWorker.min.js"></script>

    <!-- Inline edit library -->
    <script type="text/javascript" src="pushlib/dist/jquery.tabledit.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Chat Room</title>
</head>
<body>
    <div class="container">
        <br><br>
        <!-- <a href="javascript:void(0)" onclick="start()">Click</a> -->
        <br><br>
        <div >
            <?php
                if(isset($_GET['editid'])){
                    $conn = mysqli_connect("localhost", "root", "", "websocket");
                    $eid=$_GET['editid'];
                    $result=mysqli_query($conn,"select * from chat where id={$eid}");
                    if(mysqli_num_rows($result)>0){
                        while ($row=mysqli_fetch_array($result)) {
            ?>
            <h3>Update Record</h3>
            <form action="" method="post">
                <input type="hidden" name="id" id="id" value="<?php  echo $row['id'];?>">
                <input type="text" name="editmsg" id="editmsg" value="<?php  echo $row['chat'];?>">
                <input type="text" name="editstatus" id="editstatus" value="<?php  echo $row['status'];?>">
                <input type="submit" value="update" id="update">
            </form>
            <?php
                        }
                    }
                }
            ?>
        </div>
        <br><br>
        <div>
            <form id="chat-room-frm" method="post" action="">
                <input type="text" name="msg" id="msg">
                <input type="button" value="Send" id="btn">
            </form>
        </div>
        <br><br>
        <table id="chats" class="table table-bordered table-striped">
			<thead>
				<tr>
                    <th>Id</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
				</tr>
			</thead>
			<tbody id="chat_data">
				<?php 
                    require("database/connection.php");
                    require("database/chatrooms.php");
                    require("database/functions.php");


                    // Procedural Method
                    $result = getAllChats();
                    if(mysqli_num_rows($result)>0){
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr id=\"{$row['id']}\">
                                <td>".$row['id']."</td>
                                <td>".$row['chat']."</td>
                                <td>".$row['status']."</td>
                                <td><a href=\"index.php?editid={$row['id']}\">Edit</a>
                                <a><button name=\"delid\" value=\"{$row['id']}\" onclick=\"deleteRecord(this.value)\"> delete </button>
                                </a>
                                </td>
                            </tr>";
                        }
                    }

                    // OOps Method

                    // $objChatroom = new chatrooms;
                    // $chatrooms   = $objChatroom->getAllChatRooms();
					// foreach ($chatrooms as $key => $chatroom) {
					//   	echo '<tr><td>'.$chatroom['chat'].'</td><td>'.$chatroom['status'].'</td></tr>';
                    // }
				?>
			</tbody>
		</table>
    </div> 
</body>
</html>

<script>
    
    function insertPush(){
        // console.log(Push.Permission.has());
        message = getData.chat;
        Push.create(message, {
            // link: "https://www.google.com/",
            // requireInteraction: true,
            tag: message,
            body: "One Row Inserted in your database",
            icon: 'pushlib/pushicon.png',
            timeout: 5000,
            onClick: function () {
                window.focus();
                this.close();
            },
            onClose: function () {
                // window.open("https://www.google.com/");
                //alert("popup closed");
            }
        });
        // Push.close(message);
    }

    function updatePush(){
        message = getData.chat;
        Push.create(message, {
            body: "Record has been updated",
            icon: 'pushlib/pushicon.png',
            timeout: 5000,
            onClick: function () {
                window.focus();
                this.close();
            },
        });
    }

    function deletePush(){
        Push.create("Attention", {
            body: "Record has been deleted",
            icon: 'pushlib/pushicon.png',
            timeout: 5000,
            onClick: function () {
                window.focus();
                this.close();
            },
        });
    }

    var conn = new WebSocket('ws://localhost:8080');
    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        getData = $.parseJSON(e.data);
        // console.log(getData);
        if(getData.edit == 0){
            var row = '<tr id="'+ getData.id +'" name="row"><td>'+getData.id+'</td><td>'+getData.chat+'</td><td>'+getData.status+'</td><td><a href="index.php?editid="'+getData.id+'">Edit</a><a><button name="delid" value="'+ getData.id +'" onclick="deleteRecord(this.value)"> delete </button></a></td></tr>';
		    $('#chats > tbody').prepend(row);
            insertPush();
        }
        if(getData.edit == 1){
            var id = getData.id;
            var updateid = "#"+id;
            var updatedRow = '<tr id="'+ getData.id +'" name="row"><td>'+getData.id+'</td><td>'+getData.chat+'</td><td>'+getData.status+'</td><td><a href="index.php?editid="'+getData.id+'">Edit</a><a><button name="delid" value="'+ getData.id +'" onclick="deleteRecord(this.value)"> delete </button></a></td></tr>';
            $(updateid).replaceWith(updatedRow);
            updatePush();
        }
        if(getData.delete == 1){
            var id = "#"+ getData.id;
	        $(id).remove();
            deletePush();
        }
    };

    $("#btn").on("click", function(){
        var msg = $("#msg").val();
        var content = {
            msg : msg
        }
        conn.send(JSON.stringify(content));
        $("#msg").val("");
    });


    // inline edit
    $(document).ready(function(){
        // $('#chats').Tabledit({ 
        //     url: 'database/update.php', 		
        //     columns: {
        //         identifier: [0, 'id'],                    
        //         editable: [[1, 'chat'], [2, 'status', '{"0": "0", "1": "1"}']]
        //     }
        // });
    });

    $("#update").on("click", function(){
        var id = $("#id").val();
        var msg = $("#editmsg").val();
        var status = $("#editstatus").val();
        var content = {
            id : id,
            msg : msg,
            status : status
        }
        conn.send(JSON.stringify(content));
    });
    
    function deleteRecord(id){
        var del = "delete";
        var content = {
            id : id,
            del : del,
        }
        conn.send(JSON.stringify(content));
        console.log(id);
    }

    
    
</script>
