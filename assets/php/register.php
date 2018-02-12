<?php
spl_autoload_register(function ($class) {
    include_once($class.".class.php");
});

$db = new DB;
$user = new User;

//Santize input data for insertion
$firstName = $db->sanitizeInput($_POST['first_name']);
$lastName = $db->sanitizeInput($_POST['last_name']);
$username = $db->sanitizeInput($_POST['register_username']);
$password = $db->sanitizeInput($_POST['register_password']);

echo $user->registerNewUser($db, $firstName, $lastName, $username, $password);

?>