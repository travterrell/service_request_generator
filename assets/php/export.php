<?php
session_start();
spl_autoload_register(function ($class) {
    include_once($class.".class.php");
});

// Set DB and User class instances 
$db = new DB;
$request = new Request;

$type = $_REQUEST['type'];
$requestID = (int)$_REQUEST['id'];

if ( isset($_SESSION['username']) ) {
	$request->exportRequest($db, $type, $requestID);
} else {
	echo "No user logged in.  You are not allowed to use this feature";
}

?>