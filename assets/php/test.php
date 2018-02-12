<?php
spl_autoload_register(function ($class) {
	include_once($class.".class.php");
});

$db = new DB;
$user = new User;

$username = "tterrell";

$db->startConnection();
$sql = "SELECT * FROM users WHERE username = '$username'";
// $sql = "SELECT * FROM users";
$result = $db->runQuery($sql);
echo $result;
?>