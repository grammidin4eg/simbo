<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


include 'simbo-object.php';

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$inParam = json_decode($json);

$fileName = './'.$inParam->OBJECT.'/'.$inParam->METHOD.'.php';
include $fileName;

$testObj = new TodoList($inParam);
$testObj->executeMethod();

echo $testObj->getResult();
//echo json_encode($obj);

?>