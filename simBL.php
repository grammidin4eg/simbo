<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


include 'dbconf.php';
include 'simbo-object.php';

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$inParam = json_decode($json);

$dbParam = array('servername' => $servername, 'username' => $username, 'password' => $password, 'dbname' => $dbname);

$fileName = './'.$inParam->OBJECT.'/'.$inParam->METHOD.'.php';
include $fileName;

$testObj = new TodoList($inParam, $dbParam);
$testObj->executeMethod();

echo $testObj->getResult();
//echo json_encode($obj);

?>