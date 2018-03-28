<?php

class DB {
	public $connection = NULL; 

	public function startConnection() {
		// MySQL connection settings will either be:
		// $this->connection = mysqli_connect('localhost','root','root','service_request_generator');
		// 	    or
		// $this->connection = mysqli_connect('localhost','trav_terrell','Jimmyt16','service_request_generator');
		$this->connection = mysqli_connect('localhost','root','root','service_request_generator');
		if (!$this->connection) {
			echo 'Could not connect: ' . mysqli_error($this->connection);
		    die();
		}
	}

	public function sanitizeInput($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	public function runQuery($sql) {
		if ($result = mysqli_query($this->connection,$sql)) {
			if (mysqli_num_rows($result) > 1) {
				$rows = array();
				while ($row = $result->fetch_assoc()) {
  					$rows[] = $row;
				}
				mysqli_free_result($result);
				return $rows;
			} else if (mysqli_num_rows($result) == 1) {
				$row = mysqli_fetch_assoc($result);
				mysqli_free_result($result);
				return $row;
			} else if ($result === true) {
				return true;
			} else {
				return "Error";
			}
		}
	}

	public function endConnection() {
		mysqli_close($this->connection);
		$this->connection = NULL;
	}

	public function prettyPrint($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	public function formatDate($date) {
		$date = date_create($date);
		$date = date_format($date,"F j, Y");
		return $date;
	}

}

?>