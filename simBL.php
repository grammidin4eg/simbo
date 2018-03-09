<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'dbconf.php';
include 'simbo.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$obj = json_decode($json);
//echo json_encode($obj);
$simbo = new Simbo($servername, $username, $password, $dbname);
if($simbo->isConError()) {
   echo $simbo->getConError();
} else {
   $object = new SimboObject($simbo, $obj->OBJ, '');
   if( $obj->MET == 'Add' ) {
      $params = $obj->PARAMS;
      $res = $object->AddRow(array('field1' => $params[0], 'field2' => $params[1], 'field3' => $params[2]));
   }
   if( $obj->MET == 'List' ) {
      $res = $object->GetList();
   }
   echo $res;
}

?>