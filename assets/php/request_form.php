<?php
spl_autoload_register(function ($class) {
    include_once($class.".class.php");
});
session_start();

if (!isset($_SESSION["username"])) {
	echo "No user logged in.  Access not allowed.";
} else {

	$db = new DB;
	$request = new Request;

	echo $request->submitRequest($db, $_POST);

}

?>