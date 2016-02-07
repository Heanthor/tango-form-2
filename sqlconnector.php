<?php
	require_once("dbLogin.php");
	
	class SQLConnector {
		private $credentials;
		private $connected = false;
		private $db_connection;
		
		function __construct($credentials) {
			$this->credentials = $credentials;
		}
		
		function connect() {
			$this->db_connection = new mysqli($this->credentials->host,
										$this->credentials->user,
										$this->credentials->password,
										$this->credentials->database);
			
			if ($this->db_connection->connect_error) {
				die($this->db_connection->connect_error);
			} else {
				$this->connected = true;
			}
		}
		
		function insert($query) {
			if ($this->connected) {
				$result = $this->db_connection->query($query);
				if (!$result) {
					die("Insertion failed: " . $this->db_connection->error);
				} else {
					return true;
				}
			} else {
				die("Not connected to a server");
			}
		}
		
		function retrieve($query) {
			$result = $this->db_connection->query($query);
			if (!$result) {
				die("Retrieval failed: ". $this->db_connection->error);
			} else {
				$num_rows = $result->num_rows;
				if ($num_rows === 0) {
					return false;
				} else {
					if ($num_rows === 1) {
						$result->data_seek(0);
						$row = $result->fetch_array(MYSQLI_ASSOC);
					} else {
						for ($row_index = 0; $row_index < $num_rows; $row_index++) {
							$result->data_seek($row_index);
							$row[$row_index] = $result->fetch_array(MYSQLI_ASSOC);
						}
					}
					$result->close();
					return $row;
				}
			}
		}
		
		function __destruct() {
			$this->db_connection->close();
		}
		
		function getConnection() {
			return $this->db_connection;
		}
		
		function getError() {
			return $this->db_connection->error;
		}
	}
?>