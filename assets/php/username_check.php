<?php
spl_autoload_register(function ($class) {
    include_once($class.".class.php");
});

$db = new DB;
$user = new User;

$username = $db->sanitizeInput($_REQUEST["username"]);

echo $user->checkUserName($db, $username);

?>