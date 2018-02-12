<?php

class User {
	
	public function validateLogIn($db, $username, $password) {
		$db->startConnection();

		$sql = "SELECT * FROM users WHERE username = '$username'";
		$result = $db->runQuery($sql);
		if ($result == "Error") {
			$status = "Username error";
		} else {
			$userSalt = $result["salt"];
			$userPassword = $result["password"];
			if ($userPassword == md5($password.''.$userSalt)) {
				$_SESSION['first_name'] = $result["first_name"];
				$_SESSION['last_name'] = $result["last_name"];
				$_SESSION['username'] = $result["username"];
				$status = $result["first_name"];
			} else {
				$status = "Password error";
			}
		}

		$db->endConnection();
		return $status;
	}

	public function checkUserName($db, $username) {
		$db->startConnection();
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$result = $db->runQuery($sql);
		$db->endConnection();
		return $result;
	}

	public function registerNewUser($db, $firstName, $lastName, $username, $password) {
		$db->startConnection();

		// Create salt and encrypted password
		$salt = bin2hex(openssl_random_pseudo_bytes(22));
		$encrypted_password = md5($password.''.$salt);

		$sql = "INSERT INTO `users` (`first_name`, `last_name`, `username`, `password`, `salt`, `created_at`) VALUES ('$firstName', '$lastName', '$username', '$encrypted_password', '$salt', NOW());";
		
		$result = $db->runQuery($sql);
		if ($result === true) {
    		$status = "New user created successfully";
		} else {
    		$status = "Error: " . $sql . "<br>" . $connection->error;
		}

		// End connection
		$db->endConnection();
		return $status;
	}

}


?>