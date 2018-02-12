<?php
spl_autoload_register(function ($class) {
	include_once($class.".class.php");
});
$db = new DB;
$request = new Request;

if (isset($_REQUEST["service_order_id"])) {
	$requestID = $_REQUEST["service_order_id"];
}
if (isset($_POST["service_order_id"])) {
	$requestID = $_POST["service_order_id"];
}

echo $request->viewRequest($db, $requestID);

?>