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
   $object = new SimboObject($simbo, $obj->OBJ, array('id', 'field1', 'field2', 'field3'));
   $obj->LISTWHERE = '';
   $obj->ADDARRAY = array('field1' => $params[0], 'field2' => $params[1], 'field3' => $params[2]);
   $obj->SETARRAY = array('field1' => $params[0], 'field2' => $params[1]);
   $res = $object->ConfirmStd($obj);

   /*$params = $obj->PARAMS;
   $id = $obj->ID;
   if( $obj->MET == 'Add' ) {
      $res = $object->AddRow(array('field1' => $params[0], 'field2' => $params[1], 'field3' => $params[2]));
   }
   if( $obj->MET == 'List' ) {
      $res = $object->GetListSimple('');
   }
   if( $obj->MET == 'Get' ) {
      $res = $object->GetData($id);
   }
   if( $obj->MET == 'Set' ) {
      $res = $object->SetData($id, array('field1' => $params[0], 'field2' => $params[1]));
   }
   if( $obj->MET == 'Del' ) {
      $res = $object->DelRow($id);
   }*/

   echo $res;
}

?>