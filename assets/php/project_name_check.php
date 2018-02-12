<?php
spl_autoload_register(function ($class) {
    include_once($class.".class.php");
});

$db = new DB;
$request = new Request;

$projectName = $db->sanitizeInput($_REQUEST["project_name"]);
$type = $db->sanitizeInput($_REQUEST["type"]);
$id = (int)$db->sanitizeInput($_REQUEST["id"]);

echo $request->checkProjectName($db, $projectName, $id, $type);

?>