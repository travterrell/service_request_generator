<?php
session_start();
spl_autoload_register(function ($class) {
    include_once($class.".class.php");
});

// Set DB and User class instances 
$db = new DB;
$user = new User;

// Sanitize post inputs for database search
$username = $db->sanitizeInput($_POST['username']);
$password = $db->sanitizeInput($_POST['password']);

echo $user->validateLogIn($db, $username, $password);

?>