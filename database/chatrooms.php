<?php 

	class chatrooms
	{
		private $id;
		
		private $msg;
		private $status;
		
		protected $dbConn;

		function setMsg($id, $msg, $status) {
			$this->id = $id; 
			$this->msg = $msg; 
			$this->status = $status; 
		}
		function getMsg() { 
			return $this->msg;
		}

		public function __construct() {
			require_once('connection.php');
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function saveChatRoom() {
			$stmt = $this->dbConn->prepare('INSERT INTO chat (chat) VALUES(:msg)');
			$stmt->bindParam(':msg', $this->msg);
			
			if($stmt->execute()) {
				// $last_id = $this->$dbConn->lastInsertId();
				return true;
			} else {
				return false;
			}
		}

		public function getAllChatRooms() {
			$stmt = $this->dbConn->prepare("SELECT c.* FROM chat c ORDER BY c.id DESC");
			$stmt->execute();
			$chatrooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $chatrooms;
		}

		public function getSingleData($id){
			$stmt = $this->dbConn->prepare("SELECT c.* FROM chat c WHERE c.id = {$id} ORDER BY c.id DESC");
			$stmt->execute();
			$chatrooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $chatrooms;
		}

		public function updateRecord(){	
			$id = $this->id;
			$stmt = $this->dbConn->prepare('UPDATE chat SET chat=:chat, status=:status WHERE id=:id');
			$stmt->bindParam(':id', $this->id);
			$stmt->bindParam(':chat', $this->msg);
			$stmt->bindParam(':status', $this->status);

			if($stmt->execute()) {
				return $id; // id of updated record
			} else {
				return false;
			}
		}

		public function deleteRecord($id){	
			$d = 1;
			$stmt = $this->dbConn->prepare('UPDATE chat SET deleted=:del WHERE id=:id');
			$stmt->bindParam(':id', $id);
			$stmt->bindParam(':del', $d);

			if($stmt->execute()) {
				return $id; // id of updated record
			} else {
				return false;
			}
		}

	}
 ?>