<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'dbconf.php';
include 'simbo.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$obj = json_decode($json);
echo $obj;
/*$simbo = new Simbo($servername, $username, $password);
if($simbo->isConError()) {
   echo $simbo->getConError();
} else {
   $object = new SimboObject($simbo, 'TEST', '');
   $res = $object->AddRow(array('field1' => 'value1', 'field2' => 'value2', 'field3' => 'value3'));
}
echo $res;*/
?>